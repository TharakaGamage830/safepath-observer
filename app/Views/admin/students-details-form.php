<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details Form</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background:  #E3E3EA;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .form-container {
            background: #A4B5CB;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
            position: relative;
            overflow: hidden;
        }

        

        .form-title {
            text-align: center;
            color: #2c3e50;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 40px;
            position: relative;
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
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 40px;
        }

        .button-group {
            display: flex;
            gap: 400px;
            justify-content: center;
            margin-top: 40px;
        }

        button {
            padding: 15px 40px;
            border: none;
            border-radius: 20px;
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
            background: #002F6C;
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

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .form-container {
                padding: 30px 20px;
            }
            
            .button-group {
                flex-direction: column;
                gap: 50px;
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
        
        <h1 class="form-title">Student Details Form</h1>
        
        <form id="studentForm">
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
                        
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="nationalId">National ID Number <span class="required">*</span></label>
                    <input type="text" id="nationalId" name="nationalId" required>
                </div>
                
                <div class="form-group">
                    <label for="startDate">Start Date <span class="required">*</span></label>
                    <input type="date" id="startDate" name="startDate" required>
                </div>
                
                <div class="form-group">
                    <label for="course">Course <span class="required">*</span></label>
                    <select id="course" name="course" required>
                        <option value="">Select Course</option>
                        <option value="course 1">Course 1</option>
                        <option value="course 2">Course 1</option>
                        <option value="course 3">Course 1</option>
                       
                        
                    </select>
                </div>
                
               
            </div>
            
            <div class="button-group">
                <button type="button" class="cancel-btn" onclick="cancelForm()">Cancel</button>
                <button type="submit" class="save-btn" id="saveBtn">Save</button>
            </div>
        </form>
        
        <div id="dataDisplay" class="data-display">
            <h3>Saved Student Data:</h3>
            <div id="savedData"></div>
        </div>
    </div>

    <script>
        // Store all submitted students
        let students = [];
        
        // Form validation
        function validateForm() {
            const form = document.getElementById('studentForm');
            const inputs = form.querySelectorAll('input[required], select[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                input.classList.remove('invalid');
                if (!input.value.trim()) {
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
                    <div class="data-label">Start Date:</div>
                    <div class="data-value">${new Date(data.startDate).toLocaleDateString()}</div>
                </div>
                <div class="data-item">
                    <div class="data-label">Course:</div>
                    <div class="data-value">${data.course}</div>
                </div>
                <div class="data-item">
                    <div class="data-label">Vehicle Type:</div>
                    <div class="data-value">${data.vehicleType || 'Not specified'}</div>
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
        document.getElementById('studentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!validateForm()) {
                showMessage('Please fill in all required fields correctly.', 'error');
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
                    data[key] = value;
                }
                
                // Add timestamp
                data.timestamp = new Date().toISOString();
                data.id = Date.now(); // Simple ID generation
                
                // Store data
                students.push(data);
                
                // Reset button
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
                
                // Show success message
                showMessage(`Student "${data.name}" has been successfully registered!`);
                
                // Display saved data
                displaySavedData(data);
                
                // Log to console for demo
                console.log('Student Data Saved:', data);
                console.log('All Students:', students);
                
                // Scroll to saved data
                document.getElementById('dataDisplay').scrollIntoView({ 
                    behavior: 'smooth' 
                });
                
            }, 1500); // Simulate network delay
        });
        
        // Cancel function
        function cancelForm() {
            if (confirm('Are you sure you want to cancel? All entered data will be lost.')) {
                document.getElementById('studentForm').reset();
                document.getElementById('dataDisplay').style.display = 'none';
                
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
