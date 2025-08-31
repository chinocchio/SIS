# OCR Setup Instructions

## Prerequisites

1. **Install Tesseract OCR** on your system:
   - **Windows**: Download from https://github.com/UB-Mannheim/tesseract/wiki
   - **Linux**: `sudo apt-get install tesseract-ocr`
   - **macOS**: `brew install tesseract`

2. **Verify Installation**:
   ```bash
   tesseract --version
   ```

## Configuration

Add these lines to your `.env` file:

```env
# OCR Configuration
ocr.enabled = true
ocr.tesseract_path = "C:\Program Files\Tesseract-OCR\tesseract.exe"
```

**Note**: 
- Set `ocr.enabled = false` if you don't want to use OCR
- Update the path to match your Tesseract installation
- Use forward slashes (/) or escaped backslashes (\\) in the path

## How It Works

1. **Admin uploads SF9 (Form 137)** document
2. **System processes OCR** to extract text
3. **Information is extracted**:
   - LRN (Learner Reference Number)
   - Student Name
   - Birth Date
   - Gender
   - Grade Level
   - Previous School
4. **Admin reviews** extracted information
5. **Student account is created** with:
   - LRN as username
   - Generated random password
   - Status set to 'draft'

## Troubleshooting

### OCR Not Working?
1. Check if Tesseract is installed: `tesseract --version`
2. Verify the path in `.env` file
3. Check file permissions
4. Ensure uploaded documents are clear and readable

### Common Issues
- **"Tesseract not found"**: Install Tesseract or check path
- **"No text extracted"**: Document may be unclear or in unsupported format
- **"Command failed"**: Check file permissions and Tesseract installation

## Supported Formats
- **Images**: JPG, PNG, BMP, TIFF
- **Documents**: PDF

## Security Notes
- OCR processing happens server-side
- Uploaded documents are stored securely
- Generated passwords are hashed before storage
- LRN is used as unique identifier
