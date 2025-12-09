import cv2
import face_recognition
import pickle
import base64
import requests
from db import get_db_connection

# === CONFIG ===
API_ATTENDANCE = "http://localhost:8080/api/attendance/record"

def load_known_faces_for_subject(subject_id: int):
    """Load encodings only for students enrolled in sections that have this subject assigned."""
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)
    query = (
        "SELECT s.id, s.full_name AS name, s.face_encoding "
        "FROM students s "
        "WHERE s.face_encoding IS NOT NULL "
        "AND s.section_id IN ("
        "  SELECT DISTINCT tsa.section_id FROM teacher_subject_assignments tsa "
        "  WHERE tsa.subject_id = %s AND tsa.is_active = 1"
        ")"
    )
    cursor.execute(query, (subject_id,))
    students = cursor.fetchall()
    conn.close()

    known_encodings, known_ids, known_names = [], [], []
    for s in students:
        try:
            encoding = pickle.loads(base64.b64decode(s["face_encoding"].encode('utf-8')))
            known_encodings.append(encoding)
            known_ids.append(s["id"])
            known_names.append(s["name"])
        except Exception as e:
            print(f"⚠️ Error decoding face for {s['id']}: {e}")
    return known_encodings, known_ids, known_names


def get_subject_id_by_code(subject_code: str):
    conn = get_db_connection()
    cursor = conn.cursor()
    cursor.execute("SELECT id FROM subjects WHERE code=%s", (subject_code,))
    row = cursor.fetchone()
    conn.close()
    return int(row[0]) if row else None


seen_today = set()

def record_attendance(student_id, subject_id):
    try:
        key = (student_id, subject_id)
        if key in seen_today:
            print("[SKIP] Already recorded this student today (cached).")
            return
        resp = requests.post(API_ATTENDANCE, json={"student_id": student_id, "subject_id": subject_id})
        if resp.status_code == 200:
            data = resp.json()
            if data.get('status') == 'exists':
                print("[INFO] Already recorded today (server).")
            else:
                seen_today.add(key)
                print(f"[API ✅] {data}")
        else:
            print(f"[API ❌] Status {resp.status_code}: {resp.text}")
    except Exception as e:
        print(f"[API Error] {e}")


def main():
    # Ask for subject code (e.g., ENG7-01)
    subject_code = input("Enter Subject Code (e.g., ENG7-01): ")
    subject_id = get_subject_id_by_code(subject_code)
    if not subject_id:
        print("❌ Subject code not found.")
        return

    known_encodings, known_ids, known_names = load_known_faces_for_subject(subject_id)
    if not known_encodings:
        print("❌ No face data found for students in this subject's sections. Run capture_faces.py first.")
        return

    cap = cv2.VideoCapture(0)
    print(f"📷 Starting camera... (Subject {subject_code}) Press 'q' to quit.")

    while True:
        ret, frame = cap.read()
        if not ret:
            break

        rgb_frame = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
        face_locations = face_recognition.face_locations(rgb_frame)
        face_encodings = face_recognition.face_encodings(rgb_frame, face_locations)

        for (top, right, bottom, left), face_encoding in zip(face_locations, face_encodings):
            matches = face_recognition.compare_faces(known_encodings, face_encoding, tolerance=0.5)
            name = "Unknown"

            if True in matches:
                match_index = matches.index(True)
                name = known_names[match_index]
                student_id = known_ids[match_index]

                cv2.rectangle(frame, (left, top), (right, bottom), (0, 255, 0), 2)
                cv2.putText(frame, name, (left, top - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.8, (0, 255, 0), 2)

                print(f"✅ Recognized: {name} (ID: {student_id})")
                record_attendance(student_id, subject_id)
            else:
                cv2.rectangle(frame, (left, top), (right, bottom), (0, 0, 255), 2)
                cv2.putText(frame, name, (left, top - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.8, (0, 0, 255), 2)

        cv2.imshow("Face Recognition", frame)
        if cv2.waitKey(1) & 0xFF == ord('q'):
            break

    cap.release()
    cv2.destroyAllWindows()


if __name__ == "__main__":
    main()
