import cv2
import face_recognition
import pickle
import base64
import requests
from db import get_db_connection

# === CONFIG ===
API_ATTENDANCE = "http://localhost:8080/api/attendance/record"
API_ACTIVE_SESSION = "http://localhost:8080/api/session/active"

def load_known_faces():
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)
    cursor.execute("SELECT id, name, face_encoding FROM students WHERE face_encoding IS NOT NULL")
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


def get_active_subject():
    """Ask CI4 API what subject is active"""
    try:
        resp = requests.get(API_ACTIVE_SESSION)
        print(f"[DEBUG] API response: {resp.text}")  # 👈 log full response
        if resp.status_code == 200:
            data = resp.json()
            return data.get("subject_id")
        else:
            print(f"[API ❌] Active session not found ({resp.status_code})")
            return None
    except Exception as e:
        print(f"[API Error] {e}")
        return None


def record_attendance(student_id, subject_id):
    try:
        resp = requests.post(API_ATTENDANCE, json={"student_id": student_id, "subject_id": subject_id})
        if resp.status_code == 200:
            print(f"[API ✅] {resp.json()}")
        else:
            print(f"[API ❌] Status {resp.status_code}: {resp.text}")
    except Exception as e:
        print(f"[API Error] {e}")


def main():
    known_encodings, known_ids, known_names = load_known_faces()
    if not known_encodings:
        print("❌ No face data found in DB. Run capture_faces.py first.")
        return

    # 🔑 Get subject dynamically
    subject_id = get_active_subject()
    if not subject_id:
        print("⚠️ No active session found. Start one in CI4 first.")
        return

    cap = cv2.VideoCapture(0)
    print(f"📷 Starting camera... (Subject {subject_id}) Press 'q' to quit.")

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
