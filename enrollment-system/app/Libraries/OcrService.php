<?php

namespace App\Libraries;

class OcrService
{
    private $tesseractPath;
    private $isEnabled;
    
    public function __construct()
    {
        $this->isEnabled = env('ocr.enabled', false);
        $this->tesseractPath = env('ocr.tesseract_path', '');
        
        if ($this->isEnabled && empty($this->tesseractPath)) {
            $this->tesseractPath = $this->autoDetectTesseractPath();
        }
    }
    
    public function isEnabled()
    {
        return $this->isEnabled && !empty($this->tesseractPath);
    }
    
    public function extractText($filePath)
    {
        if (!$this->isEnabled()) {
            return [
                'success' => false,
                'error' => 'OCR is not enabled or Tesseract not found'
            ];
        }
        
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'bmp', 'tiff'])) {
            return $this->processImage($filePath);
        } elseif ($extension === 'pdf') {
            return $this->processPdf($filePath);
        } else {
            return [
                'success' => false,
                'error' => 'Unsupported file format. Supported: JPG, PNG, PDF, BMP, TIFF'
            ];
        }
    }
    
    private function processImage($filePath)
    {
        try {
            // Convert Windows path to forward slashes and handle spaces
            $filePath = str_replace('\\', '/', $filePath);
            $tesseractPath = str_replace('\\', '/', $this->tesseractPath);
            
            // Build command based on whether path contains spaces
            if (strpos($tesseractPath, ' ') !== false) {
                $command = '"' . $tesseractPath . '" "' . $filePath . '" stdout 2>&1';
            } else {
                $command = $tesseractPath . ' "' . $filePath . '" stdout 2>&1';
            }
            
            // Log the command for debugging
            log_message('debug', 'Executing OCR command: ' . $command);
            
            $output = [];
            $returnCode = 0;
            exec($command, $output, $returnCode);
            
            if ($returnCode !== 0) {
                return [
                    'success' => false,
                    'error' => 'Tesseract command failed with return code: ' . $returnCode
                ];
            }
            
            $text = implode("\n", $output);
            
            if (empty(trim($text))) {
                return [
                    'success' => false,
                    'error' => 'No text was extracted from the image. Please ensure the image is clear and readable.'
                ];
            }
            
            return [
                'success' => true,
                'text' => $text,
                'confidence' => $this->calculateConfidence($text)
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Image processing error: ' . $e->getMessage()
            ];
        }
    }
    
    private function processPdf($filePath)
    {
        try {
            // Convert Windows path to forward slashes and handle spaces
            $filePath = str_replace('\\', '/', $filePath);
            $tesseractPath = str_replace('\\', '/', $this->tesseractPath);
            
            // Build command based on whether path contains spaces
            if (strpos($tesseractPath, ' ') !== false) {
                $command = '"' . $tesseractPath . '" "' . $filePath . '" stdout 2>&1';
            } else {
                $command = $tesseractPath . ' "' . $filePath . '" stdout 2>&1';
            }
            
            // Log the command for debugging
            log_message('debug', 'Executing PDF OCR command: ' . $command);
            
            $output = [];
            $returnCode = 0;
            exec($command, $output, $returnCode);
            
            if ($returnCode !== 0) {
                return [
                    'success' => false,
                    'error' => 'PDF OCR processing failed with return code: ' . $returnCode
                ];
            }
            
            $text = implode("\n", $output);
            
            if (empty(trim($text))) {
                return [
                    'success' => false,
                    'error' => 'No text was extracted from the PDF. Please ensure the document is clear and readable.'
                ];
            }
            
            return [
                'success' => true,
                'text' => $text,
                'confidence' => $this->calculateConfidence($text)
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'PDF processing error: ' . $e->getMessage()
            ];
        }
    }
    
    private function calculateConfidence($text)
    {
        // Simple confidence calculation based on text length and character variety
        $textLength = strlen($text);
        $uniqueChars = strlen(count_chars($text, 3));
        $wordCount = str_word_count($text);
        
        if ($textLength < 50) return 0.3;
        if ($textLength < 100) return 0.5;
        if ($textLength < 200) return 0.7;
        if ($textLength < 500) return 0.8;
        return 0.9;
    }
    
    private function autoDetectTesseractPath()
    {
        // Common Windows installation paths
        $commonPaths = [
            'C:\Program Files\Tesseract-OCR\tesseract.exe',
            'C:\Program Files (x86)\Tesseract-OCR\tesseract.exe',
            'C:\Tesseract-OCR\tesseract.exe'
        ];
        
        foreach ($commonPaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        
        // Try to find tesseract in PATH
        $output = [];
        exec('where tesseract 2>&1', $output, $returnCode);
        
        if ($returnCode === 0 && !empty($output)) {
            return trim($output[0]);
        }
        
        // Try direct command
        $output = [];
        exec('tesseract --version 2>&1', $output, $returnCode);
        
        if ($returnCode === 0) {
            return 'tesseract';
        }
        
        return '';
    }
    
    public function checkTesseractAvailability()
    {
        if (empty($this->tesseractPath)) {
            return [
                'available' => false,
                'message' => 'Tesseract path not configured'
            ];
        }
        
        if (!file_exists($this->tesseractPath)) {
            return [
                'available' => false,
                'message' => 'Tesseract executable not found at: ' . $this->tesseractPath
            ];
        }
        
        // Test if tesseract works
        $output = [];
        $returnCode = 0;
        exec('"' . $this->tesseractPath . '" --version 2>&1', $output, $returnCode);
        
        if ($returnCode === 0) {
            return [
                'available' => true,
                'message' => 'Tesseract is working correctly',
                'version' => $output[0] ?? 'Unknown'
            ];
        } else {
            return [
                'available' => false,
                'message' => 'Tesseract executable found but failed to run'
            ];
        }
    }
}
