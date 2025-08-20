import cv2
import face_recognition
import numpy as np
import pickle
import base64
from db import get_db_connection

# STEP 1: Ask for student ID
student_id = input("Enter Student ID: ")

# STEP 2: Open camera
cap = cv2.VideoCapture(0)

print("Press 'q' to capture and save face...")

while True:
    ret, frame = cap.read()
    cv2.imshow("Capture Face", frame)

    if cv2.waitKey(1) & 0xFF == ord('q'):
        # Detect face
        rgb_frame = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
        face_locations = face_recognition.face_locations(rgb_frame)

        if face_locations:
            # Encode face
            face_encoding = face_recognition.face_encodings(rgb_frame, face_locations)[0]

            # Serialize encoding
            encoding_blob = base64.b64encode(pickle.dumps(face_encoding)).decode('utf-8')

            # Save to DB
            conn = get_db_connection()
            cursor = conn.cursor()
            cursor.execute("UPDATE students SET face_encoding=%s WHERE id=%s", (encoding_blob, student_id))
            conn.commit()
            conn.close()

            print("✅ Face stored for Student ID:", student_id)
            break
        else:
            print("❌ No face detected, try again.")

cap.release()
cv2.destroyAllWindows()
