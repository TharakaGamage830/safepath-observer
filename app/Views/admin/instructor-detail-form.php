<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Details Form</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .form-container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            position: relative;
            overflow: hidden;
        }

        .form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .form-title {
            text-align: center;
            color: #2c3e50;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 40px;
            position: relative;
        }

        .form-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 2px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        input, select {
            padding: 15px;
            border: 2px solid #e1e8ed;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        input:hover, select:hover {
            border-color: #667eea;
            background: white;
        }

        select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 40px;
        }

        .file-upload-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin: 40px 0;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 15px;
            border: 2px dashed #e1e8ed;
        }

        .file-upload {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .upload-area {
            width: 120px;
            height: 120px;
            border: 3px dashed #667eea;
            border-radius: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            position: relative;
            overflow: hidden;
        }

        .upload-area:hover {
            border-color: #764ba2;
            background: #f0f4ff;
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.1);
        }

        .upload-area.has-file {
            border-color: #4CAF50;
            background: #f1f8e9;
        }

        .upload-icon {
            font-size: 40px;
            margin-bottom: 10px;
            color: #667eea;
        }

        .upload-text {
            font-size: 12px;
            color: #666;
            font-weight: 600;
            margin-top: 10px;
        }

        .file-input {
            display: none;
        }

        .file-preview {
            margin-top: 15px;
            max-width: 200px;
        }

        .file-name {
            background: #667eea;
            color: white;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin-top: 10px;
            max-width: 180px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .remove-file {
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            cursor: pointer;
            margin-left: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .preview-image {
            max-width: 100%;
            max-height: 100px;
            border-radius: 10px;
            margin-top: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .button-group {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 40px;
        }

        button {
            padding: 15px 40px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }

        .cancel-btn {
            background: #ffc107;
            color: #2c3e50;
        }

        .cancel-btn:hover {
            background: #ffb300;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 193, 7, 0.3);
        }

        .save-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            position: relative;
        }

        .save-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        .save-btn:active {
            transform: translateY(0);
        }

        .success-message {
            display: none;
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
            font-weight: 600;
            animation: slideDown 0.5s ease;
        }

        .error-message {
            display: none;
            background: linear-gradient(135deg, #f44336, #d32f2f);
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
            font-weight: 600;
            animation: slideDown 0.5s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .required {
            color: #e74c3c;
        }

        .invalid {
            border-color: #e74c3c !important;
            background: #fdf2f2 !important;
        }

        .invalid:focus {
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1) !important;
        }

        .data-display {
            display: none;
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            margin-top: 30px;
            border-left: 5px solid #667eea;
        }

        .data-display h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 20px;
        }

        .data-item {
            display: grid;
            grid-template-columns: 150px 1fr;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #e1e8ed;
        }

        .data-label {
            font-weight: 600;
            color: #667eea;
        }

        .data-value {
            color: #2c3e50;
        }

        .file-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .file-icon {
            width: 30px;
            height: 30px;
            background: #667eea;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .file-upload-section {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .form-container {
                padding: 30px 20px;
            }
            
            .button-group {
                flex-direction: column;
            }
            
            button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div id="successMessage" class="success-message"></div>
        <div id="errorMessage" class="error-message"></div>
        
        <h1 class="form-title">Instructor Details Form</h1>
        
        <form id="instructorForm">
            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="name">Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group full-width">
                    <label for="address">Address <span class="required">*</span></label>
                    <input type="text" id="address" name="address" required>
                </div>
                
                <div class="form-group">
                    <label for="birthDate">Birth Date <span class="required">*</span></label>
                    <input type="date" id="birthDate" name="birthDate" required>
                </div>
                
                <div class="form-group">
                    <label for="phoneNumber">Phone Number <span class="required">*</span></label>
                    <input type="tel" id="phoneNumber" name="phoneNumber" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="gender">Gender <span class="required">*</span></label>
                    <select id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                        <option value="prefer-not-to-say">Prefer not to say</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="nationalId">National ID Number <span class="required">*</span></label>
                    <input type="text" id="nationalId" name="nationalId" required>
                </div>
                
                <div class="form-group">
                    <label for="experience">Experience Years <span class="required">*</span></label>
                    <input type="number" id="experience" name="experience" min="0" max="50" required>
                </div>
                
                <div class="form-group">
                    <label for="drivingLicense">Driving License Number <span class="required">*</span></label>
                    <input type="text" id="drivingLicense" name="drivingLicense" required>
                </div>
                
                <div class="form-group">
                    <label for="vehicleType">Vehicle Type <span class="required">*</span></label>
                    <select id="vehicleType" name="vehicleType" required>
                        <option value="">Select Vehicle Type</option>
                        <option value="car">Car</option>
                        <option value="motorcycle">Motorcycle</option>
                        <option value="truck">Truck</option>
                        <option value="bus">Bus</option>
                        <option value="heavy-vehicle">Heavy Vehicle</option>
                        <option value="multiple">Multiple Vehicle Types</option>
                    </select>
                </div>
            </div>
            
            <div class="file-upload-section">
                <div class="file-upload">
                    <div class="upload-area" onclick="document.getElementById('photo').click()">
                        <div class="upload-icon">📷</div>
                        <div class="upload-text">Upload Photo</div>
                    </div>
                    <input type="file" id="photo" name="photo" class="file-input" accept="image/*" required>
                    <div id="photoPreview" class="file-preview"></div>
                </div>
                
                <div class="file-upload">
                    <div class="upload-area" onclick="document.getElementById('policeClearance').click()">
                        <div class="upload-icon">📋</div>
                        <div class="upload-text">Upload Police Clearance Certificate</div>
                    </div>
                    <input type="file" id="policeClearance" name="policeClearance" class="file-input" accept=".pdf,.jpg,.jpeg,.png" required>
                    <div id="policeClearancePreview" class="file-preview"></div>
                </div>
            </div>
            
            <div class="button-group">
                <button type="button" class="cancel-btn" onclick="cancelForm()">Cancel</button>
                <button type="submit" class="save-btn" id="saveBtn">Save</button>
            </div>
        </form>
        
        <div id="dataDisplay" class="data-display">
            <h3>Saved Instructor Data:</h3>
            <div id="savedData"></div>
        </div>
    </div>

    <script>
        // Store all submitted instructors
        let instructors = [];
        
        // File handling
        function handleFileUpload(input, previewContainer, uploadArea) {
            const file = input.files[0];
            
            if (file) {
                uploadArea.classList.add('has-file');
                
                const fileName = file.name.length > 20 ? file.name.substring(0, 20) + '...' : file.name;
                const fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                
                let previewHTML = `
                    <div class="file-name">
                        ${fileName}
                        <button type="button" class="remove-file" onclick="removeFile('${input.id}', '${previewContainer}', this.parentElement.parentElement.parentElement.querySelector('.upload-area'))">×</button>
                    </div>
                    <div style="font-size: 12px; color: #666; margin-top: 5px;">${fileSize}</div>
                `;
                
                // Show image preview for photos
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewHTML = `
                            <img src="${e.target.result}" alt="Preview" class="preview-image">
                            <div class="file-name">
                                ${fileName}
                                <button type="button" class="remove-file" onclick="removeFile('${input.id}', '${previewContainer}', this.parentElement.parentElement.parentElement.querySelector('.upload-area'))">×</button>
                            </div>
                            <div style="font-size: 12px; color: #666; margin-top: 5px;">${fileSize}</div>
                        `;
                        document.getElementById(previewContainer).innerHTML = previewHTML;
                    };
                    reader.readAsDataURL(file);
                } else {
                    document.getElementById(previewContainer).innerHTML = previewHTML;
                }
            }
        }
        
        function removeFile(inputId, previewId, uploadArea) {
            document.getElementById(inputId).value = '';
            document.getElementById(previewId).innerHTML = '';
            uploadArea.classList.remove('has-file');
        }
        
        // File upload event listeners
        document.getElementById('photo').addEventListener('change', function() {
            handleFileUpload(this, 'photoPreview', this.parentElement.querySelector('.upload-area'));
        });
        
        document.getElementById('policeClearance').addEventListener('change', function() {
            handleFileUpload(this, 'policeClearancePreview', this.parentElement.querySelector('.upload-area'));
        });
        
        // Form validation
        function validateForm() {
            const form = document.getElementById('instructorForm');
            const inputs = form.querySelectorAll('input[required], select[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                input.classList.remove('invalid');
                
                if (input.type === 'file') {
                    if (!input.files || !input.files[0]) {
                        input.parentElement.querySelector('.upload-area').style.borderColor = '#e74c3c';
                        isValid = false;
                    } else {
                        input.parentElement.querySelector('.upload-area').style.borderColor = '#4CAF50';
                    }
                } else if (!input.value.trim()) {
                    input.classList.add('invalid');
                    isValid = false;
                }
            });
            
            // Email validation
            const email = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email.value && !emailRegex.test(email.value)) {
                email.classList.add('invalid');
                isValid = false;
            }
            
            // Phone validation
            const phone = document.getElementById('phoneNumber');
            const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
            if (phone.value && !phoneRegex.test(phone.value.replace(/[\s\-\(\)]/g, ''))) {
                phone.classList.add('invalid');
                isValid = false;
            }
            
            // Experience validation
            const experience = document.getElementById('experience');
            if (experience.value && (experience.value < 0 || experience.value > 50)) {
                experience.classList.add('invalid');
                isValid = false;
            }
            
            return isValid;
        }
        
        // Show message
        function showMessage(message, type = 'success') {
            const successMsg = document.getElementById('successMessage');
            const errorMsg = document.getElementById('errorMessage');
            
            if (type === 'success') {
                successMsg.textContent = message;
                successMsg.style.display = 'block';
                errorMsg.style.display = 'none';
                setTimeout(() => {
                    successMsg.style.display = 'none';
                }, 5000);
            } else {
                errorMsg.textContent = message;
                errorMsg.style.display = 'block';
                successMsg.style.display = 'none';
                setTimeout(() => {
                    errorMsg.style.display = 'none';
                }, 5000);
            }
        }
        
        // Display saved data
        function displaySavedData(data) {
            const dataDisplay = document.getElementById('dataDisplay');
            const savedData = document.getElementById('savedData');
            
            const dataHTML = `
                <div class="data-item">
                    <div class="data-label">Name:</div>
                    <div class="data-value">${data.name}</div>
                </div>
                <div class="data-item">
                    <div class="data-label">Address:</div>
                    <div class="data-value">${data.address}</div>
                </div>
                <div class="data-item">
                    <div class="data-label">Birth Date:</div>
                    <div class="data-value">${new Date(data.birthDate).toLocaleDateString()}</div>
                </div>
                <div class="data-item">
                    <div class="data-label">Phone:</div>
                    <div class="data-value">${data.phoneNumber}</div>
                </div>
                <div class="data-item">
                    <div class="data-label">Email:</div>
                    <div class="data-value">${data.email}</div>
                </div>
                <div class="data-item">
                    <div class="data-label">Gender:</div>
                    <div class="data-value">${data.gender}</div>
                </div>
                <div class="data-item">
                    <div class="data-label">National ID:</div>
                    <div class="data-value">${data.nationalId}</div>
                </div>
                <div class="data-item">
                    <div class="data-label">Experience:</div>
                    <div class="data-value">${data.experience} years</div>
                </div>
                <div class="data-item">
                    <div class="data-label">Driving License:</div>
                    <div class="data-value">${data.drivingLicense}</div>
                </div>
                <div class="data-item">
                    <div class="data-label">Vehicle Type:</div>
                    <div class="data-value">${data.vehicleType}</div>
                </div>
                <div class="data-item">
                    <div class="data-label">Photo:</div>
                    <div class="data-value">
                        <div class="file-info">
                            <div class="file-icon">📷</div>
                            <span>${data.photoName} (${data.photoSize})</span>
                        </div>
                    </div>
                </div>
                <div class="data-item">
                    <div class="data-label">Police Clearance:</div>
                    <div class="data-value">
                        <div class="file-info">
                            <div class="file-icon">📋</div>
                            <span>${data.policeClearanceName} (${data.policeClearanceSize})</span>
                        </div>
                    </div>
                </div>
                <div class="data-item">
                    <div class="data-label">Submitted:</div>
                    <div class="data-value">${new Date(data.timestamp).toLocaleString()}</div>
                </div>
            `;
            
            savedData.innerHTML = dataHTML;
            dataDisplay.style.display = 'block';
        }
        
        // Form submission
        document.getElementById('instructorForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!validateForm()) {
                showMessage('Please fill in all required fields correctly and upload all required files.', 'error');
                return;
            }
            
            const saveBtn = document.getElementById('saveBtn');
            const originalText = saveBtn.innerHTML;
            
            // Show loading state
            saveBtn.innerHTML = '<span class="loading"></span>Saving...';
            saveBtn.disabled = true;
            
            // Simulate API call delay
            setTimeout(() => {
                const formData = new FormData(this);
                const data = {};
                
                for (let [key, value] of formData.entries()) {
                    if (key === 'photo' || key === 'policeClearance') {
                        // Handle file data
                        if (value && value.name) {
                            data[key + 'Name'] = value.name;
                            data[key + 'Size'] = (value.size / 1024 / 1024).toFixed(2) + ' MB';
                            data[key + 'Type'] = value.type;
                            // In a real application, you would upload the file to a server
                            // For demo purposes, we'll just store the file info
                        }
                    } else {
                        data[key] = value;
                    }
                }
                
                // Add timestamp and ID
                data.timestamp = new Date().toISOString();
                data.id = Date.now();
                
                // Store data
                instructors.push(data);
                
                // Reset button
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
                
                // Show success message
                showMessage(`Instructor "${data.name}" has been successfully registered!`);
                
                // Display saved data
                displaySavedData(data);
                
                // Log to console for demo
                console.log('Instructor Data Saved:', data);
                console.log('All Instructors:', instructors);
                
                // Scroll to saved data
                document.getElementById('dataDisplay').scrollIntoView({ 
                    behavior: 'smooth' 
                });
                
            }, 2000); // Simulate network delay
        });
        
        // Cancel function
        function cancelForm() {
            if (confirm('Are you sure you want to cancel? All entered data will be lost.')) {
                document.getElementById('instructorForm').reset();
                document.getElementById('dataDisplay').style.display = 'none';
                
                // Clear file previews
                document.getElementById('photoPreview').innerHTML = '';
                document.getElementById('policeClearancePreview').innerHTML = '';
                
                // Reset upload areas
                document.querySelectorAll('.upload-area').forEach(area => {
                    area.classList.remove('has-file');
                    area.style.borderColor = '';
                });
                
                // Remove validation classes
                const inputs = document.querySelectorAll('.invalid');
                inputs.forEach(input => input.classList.remove('invalid'));
                
                showMessage('Form has been reset.', 'error');
            }
        }
        
        // Real-time validation feedback
        document.querySelectorAll('input, select').forEach(input => {
            input.addEventListener('blur', function() {
                if (this.hasAttribute('required') && !this.value.trim()) {
                    this.classList.add('invalid');
                } else {
                    this.classList.remove('invalid');
                }
            });
            
            input.addEventListener('input', function() {
                if (this.classList.contains('invalid') && this.value.trim()) {
                    this.classList.remove('invalid');
                }
            });
        });
        
        // Auto-focus first field
        window.addEventListener('load', function() {
            document.getElementById('name').focus();
        });
    </script>
</body>
</html>