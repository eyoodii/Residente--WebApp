#!/usr/bin/env python3
"""
ID Document Scanner using EasyOCR
Extracts text and common fields from Philippine ID documents
"""

import sys
import json
import re
import easyocr
from pathlib import Path
from typing import Dict, List, Any

class IDScanner:
    def __init__(self, languages=['en']):
        """Initialize EasyOCR reader"""
        self.reader = easyocr.Reader(languages, gpu=False)
    
    def scan_document(self, image_path: str) -> Dict[str, Any]:
        """
        Scan ID document and extract text
        
        Args:
            image_path: Path to the ID document image
            
        Returns:
            Dictionary containing extracted data
        """
        try:
            # Read text from image
            results = self.reader.readtext(image_path)
            
            # Extract all text
            all_text = ' '.join([text[1] for text in results])
            
            # Parse common ID fields
            extracted_data = {
                'success': True,
                'raw_text': all_text,
                'fields': self._extract_fields(results),
                'confidence': self._calculate_confidence(results)
            }
            
            return extracted_data
            
        except Exception as e:
            return {
                'success': False,
                'error': str(e)
            }
    
    def _extract_fields(self, results: List) -> Dict[str, str]:
        """Extract common ID fields from OCR results"""
        fields = {}
        text_lines = [text[1] for text in results]
        all_text = ' '.join(text_lines)
        
        # Extract name patterns
        name_pattern = r'(?:NAME|PANGALAN)[:\s]*([A-Z\s]+)'
        name_match = re.search(name_pattern, all_text, re.IGNORECASE)
        if name_match:
            fields['name'] = name_match.group(1).strip()
        
        # Extract date of birth
        dob_pattern = r'(?:BIRTH|KAPANGANAKAN)[:\s]*(\d{1,2}[-/]\d{1,2}[-/]\d{2,4})'
        dob_match = re.search(dob_pattern, all_text, re.IGNORECASE)
        if dob_match:
            fields['date_of_birth'] = dob_match.group(1)
        
        # Extract ID number
        id_pattern = r'(?:ID|NO)[:\s]*(\d{4}[-\s]?\d{4}[-\s]?\d{4})'
        id_match = re.search(id_pattern, all_text)
        if id_match:
            fields['id_number'] = id_match.group(1).replace(' ', '-')
        
        # Extract address
        address_keywords = ['ADDRESS', 'TIRAHAN', 'BARANGAY']
        for i, line in enumerate(text_lines):
            if any(keyword in line.upper() for keyword in address_keywords):
                if i + 1 < len(text_lines):
                    fields['address'] = text_lines[i + 1].strip()
                    break
        
        # Extract sex/gender
        sex_pattern = r'(?:SEX|KASARIAN)[:\s]*(M|F|MALE|FEMALE|LALAKI|BABAE)'
        sex_match = re.search(sex_pattern, all_text, re.IGNORECASE)
        if sex_match:
            sex_value = sex_match.group(1).upper()
            fields['sex'] = 'M' if sex_value in ['M', 'MALE', 'LALAKI'] else 'F'
        
        return fields
    
    def _calculate_confidence(self, results: List) -> float:
        """Calculate average confidence score from OCR results"""
        if not results:
            return 0.0
        
        confidences = [text[2] for text in results]
        return round(sum(confidences) / len(confidences), 2)

def main():
    """Main entry point for CLI usage"""
    if len(sys.argv) < 2:
        print(json.dumps({
            'success': False,
            'error': 'Usage: python id_scanner.py <image_path>'
        }))
        sys.exit(1)
    
    image_path = sys.argv[1]
    
    # Validate file exists
    if not Path(image_path).is_file():
        print(json.dumps({
            'success': False,
            'error': f'File not found: {image_path}'
        }))
        sys.exit(1)
    
    # Scan document
    scanner = IDScanner()
    result = scanner.scan_document(image_path)
    
    # Output JSON result
    print(json.dumps(result, ensure_ascii=False, indent=2))

if __name__ == '__main__':
    main()
