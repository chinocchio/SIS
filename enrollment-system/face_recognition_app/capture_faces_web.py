#!/usr/bin/env python3
"""
Web-based Face Capture Script
This script processes images sent from the web interface and captures face encodings.
"""

import cv2
import face_recognition
import pickle
import base64
import json
import sys
import os
from db import get_db_connection

def capture_face_from_image(image_path, lrn):
    """Capture face encoding from an image and save to database."""
    try:
        # Load the image
        image = cv2.imread(image_path)
        if image is None:
            return {
                'success': False,
                'error': 'Could not load image'
            }
        
        # Convert BGR to RGB
        rgb_image = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)
        
        # Find face locations
        face_locations = face_recognition.face_locations(rgb_image)
        
        if not face_locations:
            return {
                'success': False,
                'error': 'No face detected in image'
            }
        
        if len(face_locations) > 1:
            return {
                'success': False,
                'error': 'Multiple faces detected. Please ensure only one face is visible.'
            }
        
        # Get face encoding
        face_encodings = face_recognition.face_encodings(rgb_image, face_locations)
        
        if not face_encodings:
            return {
                'success': False,
                'error': 'Could not generate face encoding'
            }
        
        face_encoding = face_encodings[0]
        
        # Serialize encoding
        encoding_blob = base64.b64encode(pickle.dumps(face_encoding)).decode('utf-8')
        
        # Save to database
        conn = get_db_connection()
        cursor = conn.cursor()
        
        # Check if student exists
        cursor.execute("SELECT id, full_name FROM students WHERE lrn = %s", (lrn,))
        student = cursor.fetchone()
        
        if not student:
            conn.close()
            return {
                'success': False,
                'error': f'Student with LRN {lrn} not found'
            }
        
        # Update face encoding
        cursor.execute(
            "UPDATE students SET face_encoding = %s WHERE lrn = %s", 
            (encoding_blob, lrn)
        )
        
        conn.commit()
        conn.close()
        
        return {
            'success': True,
            'message': f'Face captured successfully for {student[1]} (LRN: {lrn})',
            'student_id': student[0],
            'student_name': student[1],
            'lrn': lrn
        }
        
    except Exception as e:
        return {
            'success': False,
            'error': f'Face capture failed: {str(e)}'
        }

def main():
    """Main function to handle command line arguments."""
    if len(sys.argv) != 3:
        print(json.dumps({
            'success': False,
            'error': 'Usage: python capture_faces_web.py <image_path> <lrn>'
        }))
        sys.exit(1)
    
    image_path = sys.argv[1]
    lrn = sys.argv[2]
    
    # Check if image file exists
    if not os.path.exists(image_path):
        print(json.dumps({
            'success': False,
            'error': f'Image file not found: {image_path}'
        }))
        sys.exit(1)
    
    # Process the face capture
    result = capture_face_from_image(image_path, lrn)
    
    # Output JSON result
    print(json.dumps(result, indent=2))

if __name__ == "__main__":
    main()
