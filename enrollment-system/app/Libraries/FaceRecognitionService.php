<?php

namespace App\Libraries;

class FaceRecognitionService
{
    private $faceApiKey;
    private $faceApiEndpoint;
    
    public function __construct()
    {
        // You can configure these in your .env file
        $this->faceApiKey = env('FACE_API_KEY', '');
        $this->faceApiEndpoint = env('FACE_API_ENDPOINT', 'https://api.face-api.com');
    }

    /**
     * Detect faces in an image and return face encodings
     */
    public function detectFaces($imagePath)
    {
        try {
            // For now, this is a placeholder implementation
            // You'll need to integrate with a face recognition API like:
            // - Face++ API
            // - Azure Face API
            // - AWS Rekognition
            // - Or use local libraries like dlib/face_recognition
            
            if (!$this->faceApiKey) {
                throw new \Exception('Face API key not configured');
            }

            // Example implementation with cURL to external API
            $imageData = base64_encode(file_get_contents($imagePath));
            
            $data = [
                'image' => $imageData,
                'return_face_attributes' => 'age,gender,headPose,smile,facialHair,glasses,emotion,hair,makeup,occlusion,accessories,blur,exposure,noise'
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->faceApiEndpoint . '/detect');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Ocp-Apim-Subscription-Key: ' . $this->faceApiKey
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                throw new \Exception('Face API request failed: ' . $httpCode);
            }

            $faces = json_decode($response, true);
            
            if (empty($faces)) {
                throw new \Exception('No faces detected in the image');
            }

            // Return the first face encoding
            return $faces[0]['faceId'] ?? null;

        } catch (\Exception $e) {
            log_message('error', 'Face detection failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Compare two face encodings and return similarity score
     */
    public function compareFaces($encoding1, $encoding2)
    {
        try {
            if (!$this->faceApiKey) {
                throw new \Exception('Face API key not configured');
            }

            $data = [
                'faceId1' => $encoding1,
                'faceId2' => $encoding2
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->faceApiEndpoint . '/verify');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Ocp-Apim-Subscription-Key: ' . $this->faceApiKey
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                throw new \Exception('Face comparison API request failed: ' . $httpCode);
            }

            $result = json_decode($response, true);
            
            // Return confidence score (0.0 to 1.0)
            return $result['confidence'] ?? 0.0;

        } catch (\Exception $e) {
            log_message('error', 'Face comparison failed: ' . $e->getMessage());
            return 0.0;
        }
    }

    /**
     * Validate if an image contains a face
     */
    public function validateFaceImage($imagePath)
    {
        $faceId = $this->detectFaces($imagePath);
        return $faceId !== null;
    }

    /**
     * Get face attributes from an image
     */
    public function getFaceAttributes($imagePath)
    {
        try {
            $faceId = $this->detectFaces($imagePath);
            if (!$faceId) {
                return null;
            }

            // You can implement additional logic to get face attributes
            // like age, gender, etc. from your face API
            
            return [
                'faceId' => $faceId,
                'detected' => true
            ];

        } catch (\Exception $e) {
            log_message('error', 'Face attributes extraction failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Alternative: Simple face detection using OpenCV (if available)
     */
    public function detectFacesOpenCV($imagePath)
    {
        // This is an alternative implementation using OpenCV
        // You'll need to install OpenCV PHP extension
        
        if (!extension_loaded('opencv')) {
            return null;
        }

        try {
            $image = cv\imread($imagePath);
            $faceCascade = cv\CascadeClassifier('haarcascade_frontalface_default.xml');
            
            $faces = $faceCascade->detectMultiScale($image, 1.1, 4);
            
            if (count($faces) > 0) {
                // Return basic face detection result
                return [
                    'faces_detected' => count($faces),
                    'face_locations' => $faces
                ];
            }
            
            return null;
            
        } catch (\Exception $e) {
            log_message('error', 'OpenCV face detection failed: ' . $e->getMessage());
            return null;
        }
    }
}

