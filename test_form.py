#!/usr/bin/env python3
import requests
import re
from urllib.parse import urljoin
import json

BASE_URL = "https://vldn.in/portalv2"

# Session to maintain cookies
session = requests.Session()
session.verify = False  # Ignore SSL warnings

print("Step 1: Fetching form...")
form_response = session.get(f"{BASE_URL}/simple-students/create")
print(f"Status: {form_response.status_code}")

# Extract CSRF token
csrf_match = re.search(r'csrf_token" value="([^"]+)"', form_response.text)
if csrf_match:
    csrf_token = csrf_match.group(1)
    print(f"✓ CSRF Token: {csrf_token[:30]}...")
else:
    print("❌ CSRF token not found!")
    exit(1)

print("\nStep 2: Submitting form...")
form_data = {
    'csrf_token': csrf_token,
    'tc_no': '33333333333',
    'first_name': 'Test',
    'last_name': 'Kullanıcı',
    'birth_date': '2008-07-22',
    'class': '10-B',
    'father_name': 'Baba Test',
    'father_phone': '05551234567',
    'mother_name': 'Anne Test',
    'mother_phone': '05559876543',
    'teacher_name': 'Öğretmen Test',
    'teacher_phone': '05554443322',
    'notes': 'Test notları'
}

print("Form data being sent:")
print(json.dumps(form_data, indent=2, ensure_ascii=False))

# Submit form
response = session.post(
    f"{BASE_URL}/simple-students",
    data=form_data,
    allow_redirects=False
)

print(f"\n✓ Status: {response.status_code}")
print(f"✓ Headers: {dict(response.headers)}")

if 'Location' in response.headers:
    print(f"✓ Redirect to: {response.headers['Location']}")
    
    # Follow redirect
    redirect_response = session.get(response.headers['Location'])
    print(f"✓ Final status: {redirect_response.status_code}")
    
    # Check for flash message
    if 'başarıyla' in redirect_response.text.lower():
        print("✓ Success message found!")
    elif 'hatası' in redirect_response.text.lower():
        print("❌ Error message found!")
    else:
        print("⚠️  No flash message visible")
        print(f"Response (first 500 chars): {redirect_response.text[:500]}")
else:
    print("❌ No redirect header found!")
    print(f"Response: {response.text[:500]}")

print("\nDone!")
