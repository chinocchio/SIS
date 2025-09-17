#!/usr/bin/env python3
"""
Web-based Face Recognition Service
This script processes images sent from the web interface and returns face recognition results.
"""

import cv2
import face_recognition
import pickle
import base64
import json
import sys
import os
import tempfile
from db import get_db_connection

def load_known_faces_for_subject(subject_id):
    """Load face encodings for students in a specific subject."""
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)
    
    query = """
        SELECT s.id, s.full_name AS name, s.face_encoding 
        FROM students s 
        WHERE s.face_encoding IS NOT NULL 
        AND s.section_id IN (
            SELECT DISTINCT tsa.section_id 
            FROM teacher_subject_assignments tsa 
            WHERE tsa.subject_id = %s AND tsa.is_active = 1
        )
    """
    
    cursor.execute(query, (subject_id,))
    students = cursor.fetchall()
    conn.close()
    
    known_encodings = []
    known_ids = []
    known_names = []
    
    for student in students:
        try:
            if student['face_encoding']:
                # Decode the base64 encoded face encoding
                encoding_data = base64.b64decode(student['face_encoding'].encode('utf-8'))
                face_encoding = pickle.loads(encoding_data)
                known_encodings.append(face_encoding)
                known_ids.append(student['id'])
                known_names.append(student['name'])
        except Exception as e:
            print(f"Error decoding face for student {student['id']}: {e}", file=sys.stderr)
    
    return known_encodings, known_ids, known_names

def process_image_for_faces(image_path, subject_id):
    """Process an image and return recognized faces."""
    try:
        # Load known faces for the subject
        known_encodings, known_ids, known_names = load_known_faces_for_subject(subject_id)
        
        if not known_encodings:
            return {
                'success': False,
                'error': 'No face encodings found for students in this subject',
                'recognized_faces': []
            }
        
        # Load the image
        image = cv2.imread(image_path)
        if image is None:
            return {
                'success': False,
                'error': 'Could not load image',
                'recognized_faces': []
            }
        
        # Convert BGR to RGB
        rgb_image = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)
        
        # Find face locations
        face_locations = face_recognition.face_locations(rgb_image)
        
        if not face_locations:
            return {
                'success': True,
                'message': 'No faces detected in image',
                'recognized_faces': [],
                'total_faces_detected': 0
            }
        
        # Get face encodings
        face_encodings = face_recognition.face_encodings(rgb_image, face_locations)
        
        recognized_faces = []
        
        for i, face_encoding in enumerate(face_encodings):
            # Compare with known faces
            matches = face_recognition.compare_faces(known_encodings, face_encoding, tolerance=0.6)
            
            if True in matches:
                # Find the best match
                match_index = matches.index(True)
                student_id = known_ids[match_index]
                student_name = known_names[match_index]
                
                # Calculate face distance for confidence
                face_distances = face_recognition.face_distance(known_encodings, face_encoding)
                confidence = 1 - face_distances[match_index]
                
                recognized_faces.append({
                    'student_id': student_id,
                    'name': student_name,
                    'confidence': round(confidence, 3),
                    'face_location': face_locations[i],
                    'timestamp': None  # Will be set by PHP
                })
        
        return {
            'success': True,
            'message': f'Processed {len(face_locations)} faces, recognized {len(recognized_faces)} students',
            'recognized_faces': recognized_faces,
            'total_faces_detected': len(face_locations),
            'total_students_known': len(known_encodings)
        }
        
    except Exception as e:
        return {
            'success': False,
            'error': f'Face recognition processing failed: {str(e)}',
            'recognized_faces': []
        }

def main():
    """Main function to handle command line arguments."""
    if len(sys.argv) != 3:
        print(json.dumps({
            'success': False,
            'error': 'Usage: python web_face_recognition.py <image_path> <subject_id>'
        }))
        sys.exit(1)
    
    image_path = sys.argv[1]
    subject_id = int(sys.argv[2])
    
    # Check if image file exists
    if not os.path.exists(image_path):
        print(json.dumps({
            'success': False,
            'error': f'Image file not found: {image_path}'
        }))
        sys.exit(1)
    
    # Process the image
    result = process_image_for_faces(image_path, subject_id)
    
    # Output JSON result
    print(json.dumps(result, indent=2))

if __name__ == "__main__":
    main()
