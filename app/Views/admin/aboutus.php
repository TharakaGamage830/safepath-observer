<?php
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafePath Observer - About Us</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: white;
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            text-align: center;
            max-width: 500px;
            padding: 40px 20px;
        }

        .header {
            font-size: 3rem;
            color: #2c3e50;
            margin-bottom: 50px;
            font-weight: 600;
        }

        .scan-text {
            font-size: 1.8rem;
            color: #495057;
            margin-bottom: 40px;
            font-weight: 400;
        }

        .qr-container {
            background: #f8f9fa;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            display: inline-block;
        }

        .qr-code {
            width: 250px;
            height: 250px;
            margin: 0 auto;
            background: white;
            border: 3px solid #dee2e6;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 16px;
            position: relative;
            overflow: hidden;
        }

        .qr-placeholder {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px 15px;
            }
            
            .header {
                font-size: 2.5rem;
            }
            
            .scan-text {
                font-size: 1.5rem;
            }
            
            .qr-container {
                padding: 30px;
            }
            
            .qr-code {
                width: 220px;
                height: 220px;
            }
        }

        /* Company Details Page Styles */
        .details-page {
            display: none;
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
            text-align: center;
        }

        .details-page.active {
            display: block;
        }

        .greeting {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 30px;
            font-weight: 300;
        }

        .app-logo {
            width: 120px;
            height: 120px;
            margin: 0 auto 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .app-name {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 40px;
            font-weight: 600;
        }

        .creators-section {
            margin: 40px 0;
        }

        .creators-title {
            font-size: 1.8rem;
            color: #2c3e50;
            margin-bottom: 25px;
            font-weight: 500;
        }

        .creators-list {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        .creator {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 25px;
            border-radius: 12px;
            font-weight: 500;
            font-size: 1.1rem;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .university {
            font-size: 1.2rem;
            color: #6c757d;
            font-style: italic;
            margin: 20px 0;
        }

        .back-btn {
            background: #2c3e50;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 30px;
            transition: background 0.3s ease;
        }

        .back-btn:hover {
            background: #34495e;
        }

        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 10px;
        }

        /* GitHub QR Code Styles */
        .github-qr-container {
            display: inline-block;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin: 0 auto;
        }

        .github-qr-code {
            width: 120px;
            height: 120px;
            margin: 0 auto;
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 12px;
            position: relative;
            overflow: hidden;
        }

        .github-section {
            border-top: 1px solid #e9ecef;
            padding-top: 30px;
        }
    </style>

</head>
<body>
    <div class="container" id="aboutPage">
        <h1 class="header">About Us</h1>
        <p class="scan-text">Scan Me</p>
        <div class="qr-container">
            <div class="qr-code" id="qrcode">
                <div class="qr-placeholder">
                    QR Code<br>
                    <small>Loading...</small>
                </div>
            </div>
            <div class="error-message" id="errorMessage" style="display: none;"></div>
        </div>
        <!-- GitHub QR Code Section -->
        <div class="github-section" style="margin-top: 40px;">
            <h3 style="color: #2c3e50; margin-bottom: 15px; font-size: 1.2rem;">
                🚀 View Our Source Code
            </h3>
            <div class="github-qr-container">
                <div class="github-qr-code" id="githubQrcode">
                    <div class="qr-placeholder">
                        GitHub QR<br>
                        <small>Loading...</small>
                    </div>
                </div>
            </div>
            <p style="color: #6c757d; font-size: 0.9rem; margin-top: 10px;">
                Scan to visit our GitHub repository
            </p>
        </div>
    </div>

    <div class="details-page" id="detailsPage">
        <div id="greeting" class="greeting"></div>
        
        <div class="app-logo">
            SP
        </div>
        
        <h1 class="app-name">SafePath Observer</h1>
        
        <div class="creators-section">
            <h2 class="creators-title">Created By</h2>
            <div class="creators-list">
                <div class="creator">Tharaka Gamage</div>
                <div class="creator">Lahiru Pehesara</div>
                <div class="creator">Gimhani Ahinsa</div>
            </div>
            <div class="university">
                Computer Science Undergraduates<br>
                University of Ruhuna
            </div>
        </div>
        
        <div style="margin-top: 40px; color: #6c757d;">
            Thank you for using SafePath Observer!<br>
            Building safer paths through technology.
        </div>
        
        <button class="back-btn" onclick="showAboutPage()">Back to About Us</button>
    </div>

    <!-- QRious Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <script>
        // Debug: Check if QRious loaded
        console.log('QRious loaded:', typeof QRious !== 'undefined');
        
        // Check if page was accessed via QR code
        const urlParams = new URLSearchParams(window.location.search);
        const showDetails = urlParams.get('details') === 'true';

        // Time-based greeting function
        function updateGreeting() {
            const now = new Date();
            const hours = now.getHours();
            let greeting;
            
            if (hours >= 4 && hours < 12) {
                greeting = "Good Morning!";
            } else if (hours >= 12 && hours < 16) {
                greeting = "Good Afternoon!";
            } else {
                greeting = "Good Evening!";
            }
            
            document.getElementById('greeting').textContent = greeting;
        }

        // Generate QR code that points to details page
        function generateQRCode() {
            const currentUrl = window.location.href.split('?')[0];
            const qrUrl = currentUrl + '?details=true';
            
            console.log('Generating QR for URL:', qrUrl);
            
            const qrElement = document.getElementById('qrcode');
            const errorElement = document.getElementById('errorMessage');
            
            try {
                // Check if QRious library is loaded
                if (typeof QRious === 'undefined') {
                    throw new Error('QRious library not loaded');
                }
                
                // Clear existing content
                qrElement.innerHTML = '';
                
                // Create canvas element for QR code
                const canvas = document.createElement('canvas');
                qrElement.appendChild(canvas);
                
                const qr = new QRious({
                    element: canvas,
                    value: qrUrl,
                    size: 244, // Slightly smaller to fit within border
                    background: 'white',
                    foreground: '#2c3e50',
                    level: 'M'
                });
                
                console.log('QR code generated successfully');
                errorElement.style.display = 'none';
                
            } catch (error) {
                console.error('QR code generation failed:', error);
                
                // Show fallback content
                qrElement.innerHTML = `
                    <div class="qr-placeholder">
                        <div style="font-size: 24px; margin-bottom: 10px;">📱</div>
                        <div>QR Code</div>
                        <small>Click to view details</small>
                    </div>
                `;
                
                // Make it clickable as fallback
                qrElement.style.cursor = 'pointer';
                qrElement.onclick = showDetailsPage;
                
                // Show error message
                errorElement.textContent = 'QR library failed to load. Click the box above to view details.';
                errorElement.style.display = 'block';
            }
        }

        // Generate GitHub QR code
        function generateGitHubQRCode() {
            const githubUrl = 'https://github.com/TharakaGamage830/safepath-observer';
            const qrElement = document.getElementById('githubQrcode');
            
            try {
                // Check if QRious library is loaded
                if (typeof QRious === 'undefined') {
                    throw new Error('QRious library not loaded');
                }
                
                // Clear existing content
                qrElement.innerHTML = '';
                
                // Create canvas element for QR code
                const canvas = document.createElement('canvas');
                qrElement.appendChild(canvas);
                
                const qr = new QRious({
                    element: canvas,
                    value: githubUrl,
                    size: 116, // Smaller size for GitHub QR
                    background: 'white',
                    foreground: '#2c3e50',
                    level: 'M'
                });
                
                console.log('GitHub QR code generated successfully');
                
            } catch (error) {
                console.error('GitHub QR code generation failed:', error);
                
                // Show fallback content
                qrElement.innerHTML = `
                    <div class="qr-placeholder">
                        <div style="font-size: 16px; margin-bottom: 5px;">🔗</div>
                        <div style="font-size: 10px;">GitHub</div>
                        <small style="font-size: 8px;">Click to visit</small>
                    </div>
                `;
                
                // Make it clickable as fallback
                qrElement.style.cursor = 'pointer';
                qrElement.onclick = function() {
                    window.open(githubUrl, '_blank');
                };
            }
        }

        // Show details page
        function showDetailsPage() {
            document.getElementById('aboutPage').style.display = 'none';
            document.getElementById('detailsPage').classList.add('active');
            updateGreeting();
        }

        // Show about page
        function showAboutPage() {
            document.getElementById('aboutPage').style.display = 'flex';
            document.getElementById('detailsPage').classList.remove('active');
            // Update URL without details parameter
            window.history.pushState({}, '', window.location.pathname);
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, showDetails:', showDetails);
            
            if (showDetails) {
                showDetailsPage();
            } else {
                // Small delay to ensure everything is loaded
                setTimeout(() => {
                    generateQRCode();
                    generateGitHubQRCode(); // THIS WAS MISSING!
                }, 100);
            }
        });

        // Handle script loading errors
        window.addEventListener('error', function(e) {
            console.error('Script error:', e);
            if (e.filename && e.filename.includes('qrious')) {
                console.log('QR library failed to load');
                document.getElementById('errorMessage').textContent = 'QR library failed to load from: ' + e.filename;
                document.getElementById('errorMessage').style.display = 'block';
            }
        });
    </script>
</body>
</html>
<?php
$content = ob_get_clean(); // Get all buffered HTML
include '../components/layout.php'; // Insert layout and pass $content to it
?>