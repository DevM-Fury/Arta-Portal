<?php
// registration.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARTA Portal Registration - Secure Access Request</title>

    <!-- ✅ Bootstrap & SweetAlert -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- ✅ Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- ✅ Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    :root {
        --primary-blue: #003366;
        --cyber-blue: #0066cc;
        --dark-bg: #0a1929;
        --card-bg: rgba(255, 255, 255, 0.05);
        --border-glow: rgba(0, 102, 204, 0.3);
        --text-light: #f0f4f8;
        --text-muted: #a0b4c8;
        --success-green: #28a745;
        --error-red: #dc3545;
    }
    
    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--dark-bg);
        background-image: 
            radial-gradient(circle at 15% 50%, rgba(0, 102, 204, 0.1) 0%, transparent 20%),
            radial-gradient(circle at 85% 30%, rgba(0, 102, 204, 0.1) 0%, transparent 20%),
            radial-gradient(circle at 50% 80%, rgba(0, 102, 204, 0.1) 0%, transparent 20%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        margin: 0;
        color: var(--text-light);
    }

    .container {
        width: 100%;
        max-width: 1400px;
        display: flex;
        align-items: stretch;
        gap: 60px;
    }

    .hero-section {
        flex: 1;
        padding-right: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .hero-title {
        font-size: 2.8rem;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 20px;
        background: linear-gradient(135deg, #ffffff, var(--cyber-blue));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-subtitle {
        font-size: 1.2rem;
        color: var(--text-muted);
        margin-bottom: 30px;
        line-height: 1.6;
    }

    .security-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: rgba(0, 102, 204, 0.1);
        border: 1px solid var(--border-glow);
        border-radius: 20px;
        padding: 8px 20px;
        margin-bottom: 30px;
        font-size: 0.9rem;
        color: var(--cyber-blue);
    }

    .security-badge i {
        font-size: 1.2rem;
    }

    .features {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-top: 40px;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .feature-icon {
        width: 40px;
        height: 40px;
        background: rgba(0, 102, 204, 0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--cyber-blue);
        font-size: 1.2rem;
    }

    .feature-text {
        font-size: 0.95rem;
        color: var(--text-muted);
    }

    .registration-card {
        background: rgba(16, 30, 50, 0.8);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid var(--border-glow);
        box-shadow: 
            0 8px 32px rgba(0, 0, 0, 0.3),
            0 0 0 1px rgba(0, 102, 204, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
        width: 100%;
        max-width: 800px;
        min-height: 550px;
        padding: 35px 40px 30px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .registration-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--cyber-blue), #00ccff);
        border-radius: 20px 20px 0 0;
    }

    .registration-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .logo-container {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--cyber-blue), #00ccff);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        box-shadow: 0 0 30px rgba(0, 102, 204, 0.4);
    }

    .logo-container img {
        width: 50px;
        height: 50px;
        object-fit: contain;
        filter: brightness(0) invert(1);
    }

    .registration-header h3 {
        font-weight: 700;
        color: var(--text-light);
        letter-spacing: 0.5px;
        font-size: 1.5rem;
        margin-bottom: 6px;
    }

    .registration-header p {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-light);
        margin-bottom: 8px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        letter-spacing: 0.5px;
    }

    .form-label i {
        color: var(--cyber-blue);
        width: 20px;
    }

    .input-group {
        position: relative;
        margin-bottom: 1.2rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    /* PAREHAS NA HEIGHT PARA SA LAHAT NG FIELDS */
    .form-control, .form-select {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        padding: 14px 16px 14px 45px;
        font-size: 14px;
        transition: all 0.3s ease;
        color: var(--text-light);
        width: 100%;
        box-sizing: border-box;
        min-height: 48px;
        height: 48px;
    }

    .form-control:focus, .form-select:focus {
        background: rgba(255, 255, 255, 0.08);
        border-color: var(--cyber-blue);
        box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.2);
        color: var(--text-light);
        outline: none;
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.4);
    }

    .input-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 48px;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--cyber-blue), #0052a3);
        border: none;
        border-radius: 10px;
        padding: 14px;
        font-weight: 600;
        font-size: 15px;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        width: 100%;
        color: white;
        position: relative;
        overflow: hidden;
        margin-top: 8px;
        margin-bottom: 10px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #0052a3, var(--cyber-blue));
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 102, 204, 0.4);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    .login-link {
        text-align: center;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .login-link p {
        color: var(--text-muted);
        margin-bottom: 0;
        font-size: 14px;
        line-height: 1.4;
    }

    .login-link a {
        color: var(--cyber-blue);
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
        margin-top: 2px;
    }

    .login-link a:hover {
        color: #00ccff;
    }

    /* Password toggle */
    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        z-index: 2;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    .password-toggle:hover {
        color: var(--cyber-blue);
    }

    /* Animated background elements */
    .cyber-grid {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: 
            linear-gradient(rgba(0, 102, 204, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 102, 204, 0.05) 1px, transparent 1px);
        background-size: 50px 50px;
        z-index: -1;
        animation: gridMove 20s linear infinite;
    }

    @keyframes gridMove {
        0% { transform: translateY(0); }
        100% { transform: translateY(50px); }
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .container {
            max-width: 1000px;
        }
        
        .registration-card {
            max-width: 700px;
        }
    }

    @media (max-width: 992px) {
        .container {
            flex-direction: column;
            gap: 40px;
            align-items: center;
        }
        
        .hero-section {
            padding-right: 0;
            text-align: center;
            justify-content: flex-start;
        }
        
        .hero-title {
            font-size: 2.2rem;
        }
        
        .features {
            grid-template-columns: 1fr;
            max-width: 400px;
            margin: 30px auto 0;
        }
        
        .registration-card {
            height: auto;
            min-height: auto;
        }
    }

    @media (max-width: 768px) {
        .registration-card {
            padding: 25px 20px;
        }
        
        .hero-title {
            font-size: 1.8rem;
        }
        
        .logo-container {
            width: 70px;
            height: 70px;
            margin-bottom: 12px;
        }
        
        .logo-container img {
            width: 45px;
            height: 45px;
        }
        
        .registration-header h3 {
            font-size: 1.3rem;
        }
        
        .form-control, .form-select {
            padding: 12px 16px 12px 40px;
            min-height: 44px;
            height: 44px;
        }
        
        .input-icon {
            height: 44px;
        }
        
        .btn-primary {
            height: 44px;
        }
        
        .form-row {
            grid-template-columns: 1fr;
            gap: 15px;
        }
    }

    /* Loading animation */
    .loading {
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(16, 30, 50, 0.9);
        align-items: center;
        justify-content: center;
        border-radius: 20px;
        z-index: 1000;
    }

    .spinner-border {
        width: 3rem;
        height: 3rem;
    }

    /* Form grid layout */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .form-full-width {
        grid-column: 1 / -1;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 15px;
    }
    
    .form-col {
        display: flex;
        flex-direction: column;
    }

    /* Validation styles */
    .validation-message {
        font-size: 0.85rem;
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 5px;
        min-height: 20px;
    }
    
    .validation-message.valid {
        color: var(--success-green);
    }
    
    .validation-message.invalid {
        color: var(--error-red);
    }
    
    .validation-message.info {
        color: var(--text-muted);
    }
    
    .form-control.valid {
        border-color: var(--success-green);
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.2);
    }
    
    .form-control.invalid {
        border-color: var(--error-red);
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.2);
    }
    
    .form-select.valid {
        border-color: var(--success-green);
    }
    
    .form-select.invalid {
        border-color: var(--error-red);
    }
    
    .icon-valid {
        color: var(--success-green);
    }
    
    .icon-invalid {
        color: var(--error-red);
    }
    
    .icon-info {
        color: var(--text-muted);
    }
    
    /* Two column layout adjustments */
    .two-column-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        align-items: start;
    }
    
    @media (max-width: 768px) {
        .two-column-container {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }
    
    /* Dropdown Styling */
    .form-select {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        padding: 14px 16px 14px 45px;
        font-size: 14px;
        transition: all 0.3s ease;
        color: var(--text-light);
        width: 100%;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23a0b4c8' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        background-size: 16px;
        min-height: 48px;
        height: 48px;
    }

    .form-select:focus {
        background: rgba(255, 255, 255, 0.08);
        border-color: var(--cyber-blue);
        box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.2);
        color: var(--text-light);
        outline: none;
    }

    .form-select:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .form-select.valid {
        border-color: var(--success-green);
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.2);
    }

    .form-select.invalid {
        border-color: var(--error-red);
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.2);
    }
    
    /* PANTY-PANTY ANG LAHAT NG FIELDS */
    #email,
    #password,
    #confirm_password,
    #officeSelect,
    #divisionSelect,
    #unitSelect,
    #designationSelect,
    #firstname,
    #lastname,
    #national_id,
    #contact {
        min-height: 48px !important;
        height: 48px !important;
        line-height: 20px !important;
    }
    
    /* Ensure consistent spacing */
    .position-relative {
        display: flex;
        flex-direction: column;
    }
    
    /* Uniform field group height */
    .input-group {
        min-height: 95px;
        display: flex;
        flex-direction: column;
    }
    
    /* Align all fields vertically */
    .form-control, .form-select {
        display: flex;
        align-items: center;
    }
    </style>
</head>

<body>
    <div class="cyber-grid"></div>
    
    <div class="container">
        <!-- Hero Section (Left) -->
        <div class="hero-section">
            <div class="security-badge">
                <i class="fas fa-shield-alt"></i>
                <span>SECURE REGISTRATION</span>
            </div>
            
            <h1 class="hero-title">NEW GOVERNMENT PERSONNEL ACCESS REQUEST</h1>
            
            <p class="hero-subtitle">
                Complete the registration form to request access to the ARTA System Portal. 
                All information will be verified by the system administrator before access is granted.
            </p>
            
            <div class="features">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="feature-text">Manual verification process</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="feature-text">24-48 hour approval time</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="feature-text">Complete documentation</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="feature-text">Email notification</div>
                </div>
            </div>
        </div>

        <!-- Registration Card (Right) - WIDER -->
        <div class="registration-card">
            <div class="loading" id="loading">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <div class="registration-header">
                <div class="logo-container">
                    <i class="fas fa-shield-alt" style="color: white; font-size: 2rem;"></i>
                </div>
                <h3>ACCESS REQUEST FORM</h3>
                <p>New Government Personnel Registration</p>
            </div>

            <form id="registrationForm">
                <!-- TWO COLUMN LAYOUT -->
                <div class="two-column-container">
                    <!-- LEFT COLUMN -->
                    <div class="form-col">
                        <!-- Personal Information -->
                        <div class="input-group">
                            <label class="form-label">
                                <i class="fas fa-user"></i> First Name
                            </label>
                            <div class="position-relative">
                                <input type="text" name="firstname" id="firstname" class="form-control" placeholder="Juan" required>
                                <div class="input-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div id="firstnameFeedback" class="validation-message info">
                                <i class="fas fa-info-circle icon-info"></i>
                                <span>Enter your first name</span>
                            </div>
                        </div>

                        <div class="input-group">
                            <label class="form-label">
                                <i class="fas fa-user"></i> Last Name
                            </label>
                            <div class="position-relative">
                                <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Dela Cruz" required>
                                <div class="input-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div id="lastnameFeedback" class="validation-message info">
                                <i class="fas fa-info-circle icon-info"></i>
                                <span>Enter your last name</span>
                            </div>
                        </div>

                        <div class="input-group">
                            <label class="form-label">
                                <i class="fas fa-id-card"></i> National ID
                            </label>
                            <div class="position-relative">
                                <input type="text" name="national_id" id="national_id" class="form-control" placeholder="123456789012" required>
                                <div class="input-icon">
                                    <i class="fas fa-id-card"></i>
                                </div>
                            </div>
                            <div id="national_idFeedback" class="validation-message info">
                                <i class="fas fa-info-circle icon-info"></i>
                                <span>12-digit PhilSys Number</span>
                            </div>
                        </div>

                        <div class="input-group">
                            <label class="form-label">
                                <i class="fas fa-phone"></i> Contact Number
                            </label>
                            <div class="position-relative">
                                <input type="text" name="contact" id="contact" class="form-control" placeholder="09123456789" required>
                                <div class="input-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                            </div>
                            <div id="contactFeedback" class="validation-message info">
                                <i class="fas fa-info-circle icon-info"></i>
                                <span>11 digits starting with 9</span>
                            </div>
                        </div>

                        <!-- Password Fields -->
                        <div class="input-group">
                            <label class="form-label">
                                <i class="fas fa-key"></i> Password
                            </label>
                            <div class="position-relative">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Create password" required>
                                <div class="input-icon">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <button type="button" class="password-toggle" id="passwordToggle">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div id="passwordFeedback" class="validation-message info">
                                <i class="fas fa-info-circle icon-info"></i>
                                <span>Min. 6 alphanumeric characters</span>
                            </div>
                        </div>

                        <div class="input-group">
                            <label class="form-label">
                                <i class="fas fa-key"></i> Confirm Password
                            </label>
                            <div class="position-relative">
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm password" required>
                                <div class="input-icon">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <button type="button" class="password-toggle" id="confirmPasswordToggle">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div id="confirm_passwordFeedback" class="validation-message info">
                                <i class="fas fa-info-circle icon-info"></i>
                                <span>Re-enter your password</span>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN -->
                    <div class="form-col">
                        <div class="input-group">
                            <label class="form-label">
                                <i class="fas fa-envelope"></i> Government Email
                            </label>
                            <div class="position-relative">
                                <input type="email" name="email" id="email" class="form-control" placeholder="juan.delacruz@arta.gov.ph" required>
                                <div class="input-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                            </div>
                            <div id="emailFeedback" class="validation-message info">
                                <i class="fas fa-info-circle icon-info"></i>
                                <span>Only @arta.gov.ph domain</span>
                            </div>
                        </div>

                        <!-- Organizational Fields -->
                        <div class="input-group">
                            <label class="form-label">
                                <i class="fas fa-building"></i> Office
                            </label>
                            <div class="position-relative">
                                <select class="form-select" id="officeSelect" name="office_id" required>
                                    <option value="">Select Office</option>
                                    <!-- Options loaded via JavaScript -->
                                </select>
                                <div class="input-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                            </div>
                            <div id="officeFeedback" class="validation-message info">
                                <i class="fas fa-info-circle icon-info"></i>
                                <span>Select your office</span>
                            </div>
                        </div>

                        <div class="input-group">
                            <label class="form-label">
                                <i class="fas fa-sitemap"></i> Division
                            </label>
                            <div class="position-relative">
                                <select class="form-select" id="divisionSelect" name="division_id" required>
                                    <option value="">Select Division</option>
                                    <!-- Options loaded via JavaScript -->
                                </select>
                                <div class="input-icon">
                                    <i class="fas fa-sitemap"></i>
                                </div>
                            </div>
                            <div id="divisionFeedback" class="validation-message info">
                                <i class="fas fa-info-circle icon-info"></i>
                                <span>Select your division</span>
                            </div>
                        </div>

                        <div class="input-group">
                            <label class="form-label">
                                <i class="fas fa-cubes"></i> Unit
                            </label>
                            <div class="position-relative">
                                <select class="form-select" id="unitSelect" name="unit_id" required>
                                    <option value="">Select Unit</option>
                                    <!-- Options loaded via JavaScript -->
                                </select>
                                <div class="input-icon">
                                    <i class="fas fa-cubes"></i>
                                </div>
                            </div>
                            <div id="unitFeedback" class="validation-message info">
                                <i class="fas fa-info-circle icon-info"></i>
                                <span>Select your unit</span>
                            </div>
                        </div>

                        <div class="input-group">
                            <label class="form-label">
                                <i class="fas fa-user-tag"></i> Designation
                            </label>
                            <div class="position-relative">
                                <select class="form-select" id="designationSelect" name="designation_id" required>
                                    <option value="">Select Designation</option>
                                    <!-- Options loaded via JavaScript -->
                                </select>
                                <div class="input-icon">
                                    <i class="fas fa-user-tag"></i>
                                </div>
                            </div>
                            <div id="designationFeedback" class="validation-message info">
                                <i class="fas fa-info-circle icon-info"></i>
                                <span>Select your designation</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terms & Submit -->
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="termsCheck" required>
                    <label class="form-check-label" for="termsCheck" style="color: var(--text-muted); font-size: 0.9rem;">
                        I agree to the <a href="#" style="color: var(--cyber-blue);">Terms of Service</a> and confirm that all information provided is accurate.
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                    <i class="fas fa-user-plus"></i> Submit Access Request
                </button>

                <div class="login-link">
                    <p class="mb-0">Already have an account?<br>
                        <a href="index.php"><i class="fas fa-sign-in-alt"></i> Login here</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome for icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    <script>
        // Initialize variables
        let dropdownsLoaded = false;
        const loading = document.getElementById('loading');
        const submitBtn = document.getElementById('submitBtn');

        // 1. Password toggle functionality
        document.getElementById('passwordToggle').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });

        document.getElementById('confirmPasswordToggle').addEventListener('click', function() {
            const confirmInput = document.getElementById('confirm_password');
            const type = confirmInput.type === 'password' ? 'text' : 'password';
            confirmInput.type = type;
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });

        // 2. Real-time validation functions - FIXED VERSION
        function updateValidation(elementId, isValid, message) {
            const element = document.getElementById(elementId);
            const feedback = document.getElementById(elementId + 'Feedback');
            
            if (!element || !feedback) {
                console.log(`Element or feedback not found: ${elementId}`);
                return;
            }
            
            element.classList.remove('valid', 'invalid');
            feedback.classList.remove('valid', 'invalid', 'info');
            
            if (isValid === true) {
                element.classList.add('valid');
                feedback.classList.add('valid');
                feedback.innerHTML = `<i class="fas fa-check-circle icon-valid"></i> ${message || 'Valid'}`;
            } else if (isValid === false) {
                element.classList.add('invalid');
                feedback.classList.add('invalid');
                feedback.innerHTML = `<i class="fas fa-times-circle icon-invalid"></i> ${message}`;
            } else {
                feedback.classList.add('info');
                feedback.innerHTML = `<i class="fas fa-info-circle icon-info"></i> ${message}`;
            }
        }

        // Separate function for dropdown validation (since they have different IDs)
        function updateDropdownValidation(elementId, value, message) {
            const element = document.getElementById(elementId);
            // Get the base name without 'Select' for feedback ID
            const baseName = elementId.replace('Select', '');
            const feedback = document.getElementById(baseName + 'Feedback');
            
            if (!element || !feedback) {
                console.log(`Element or feedback not found: ${elementId} or ${baseName}Feedback`);
                return;
            }
            
            element.classList.remove('valid', 'invalid');
            feedback.classList.remove('valid', 'invalid', 'info');
            
            if (value) {
                element.classList.add('valid');
                feedback.classList.add('valid');
                feedback.innerHTML = `<i class="fas fa-check-circle icon-valid"></i> ${message}`;
            } else {
                feedback.classList.add('info');
                feedback.innerHTML = `<i class="fas fa-info-circle icon-info"></i> ${message}`;
            }
        }

        // Email validation
        document.getElementById('email').addEventListener('input', function() {
            const email = this.value;
            const isValid = email.endsWith('@arta.gov.ph');
            updateValidation('email', isValid, isValid ? 'Valid government email' : 'Only @arta.gov.ph emails allowed');
        });

        // Password validation
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const hasMinLength = password.length >= 6;
            const isAlphanumeric = /^[a-zA-Z0-9]+$/.test(password);
            const isValid = hasMinLength && isAlphanumeric;
            
            updateValidation('password', isValid, 
                isValid ? 'Strong password' : 
                'Password must be at least 6 alphanumeric characters');
            
            // Also check password match
            checkPasswordMatch();
        });

        // Confirm password validation
        document.getElementById('confirm_password').addEventListener('input', checkPasswordMatch);

        function checkPasswordMatch() {
            const password = document.getElementById('password');
            const confirm = document.getElementById('confirm_password');
            
            if (!password || !confirm) return;
            
            const confirmValue = confirm.value;
            
            if (!confirmValue) {
                updateValidation('confirm_password', null, 'Re-enter your password');
                return;
            }
            
            const isValid = password.value === confirmValue;
            updateValidation('confirm_password', isValid, 
                isValid ? 'Passwords match' : 'Passwords do not match');
        }

        // Contact validation
        document.getElementById('contact').addEventListener('input', function() {
            let contact = this.value.replace(/\D/g, '');
            this.value = contact;
            
            const isValid = /^9\d{10}$/.test(contact);
            updateValidation('contact', isValid, 
                isValid ? 'Valid Philippine number' : 
                'Must be 11 digits starting with 9');
        });

        // National ID validation - FIXED: using 'national_id' instead of 'nationalId'
        document.getElementById('national_id').addEventListener('input', function() {
            let nationalId = this.value.replace(/\D/g, '');
            if (nationalId.length > 12) nationalId = nationalId.substring(0, 12);
            this.value = nationalId;
            
            const isValid = /^\d{12}$/.test(nationalId);
            updateValidation('national_id', isValid, 
                isValid ? 'Valid PhilSys number' : 
                'Must be 12 digits');
        });

        // Name validation
        document.getElementById('firstname').addEventListener('input', function() {
            const name = this.value.trim();
            const isValid = name.length >= 2;
            updateValidation('firstname', isValid, 
                isValid ? 'Valid first name' : 'Minimum 2 characters');
        });

        document.getElementById('lastname').addEventListener('input', function() {
            const name = this.value.trim();
            const isValid = name.length >= 2;
            updateValidation('lastname', isValid, 
                isValid ? 'Valid last name' : 'Minimum 2 characters');
        });

        // ========== DROPDOWN LOADING FUNCTIONS ==========
        async function loadDropdownData() {
            try {
                console.log('Loading dropdown data...');
                
                const apiPaths = [
                    '../api/',
                    '../../api/',
                    '/arta-portal/api/',
                    '/api/',
                    './api/',
                    '../../arta-portal/api/',
                    '../arta-portal/api/'
                ];
                
                let apiBase = '';
                let foundPath = '';
                
                // Test each path
                for (const path of apiPaths) {
                    try {
                        console.log(`Testing path: ${path}offices/get_offices.php?limit=1`);
                        const testRes = await fetch(`${path}offices/get_offices.php?limit=1`, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json'
                            }
                        });
                        
                        console.log(`Response for ${path}:`, testRes.status, testRes.statusText);
                        
                        if (testRes.ok) {
                            apiBase = path;
                            foundPath = path;
                            console.log('✅ Found API at:', apiBase);
                            break;
                        } else {
                            console.log(`❌ Path ${path} failed with status:`, testRes.status);
                        }
                    } catch (e) {
                        console.log(`❌ Network error for ${path}:`, e.message);
                        continue;
                    }
                }
                
                if (!apiBase) {
                    const currentUrl = window.location.origin;
                    const testPaths = [
                        `${currentUrl}/arta-portal/api/`,
                        `${currentUrl}/api/`,
                        `${currentUrl}/`
                    ];
                    
                    for (const path of testPaths) {
                        try {
                            console.log(`Testing full URL: ${path}offices/get_offices.php?limit=1`);
                            const testRes = await fetch(`${path}offices/get_offices.php?limit=1`);
                            if (testRes.ok) {
                                apiBase = path;
                                foundPath = path;
                                console.log('✅ Found API at full URL:', apiBase);
                                break;
                            }
                        } catch (e) {
                            continue;
                        }
                    }
                }
                
                if (!apiBase) {
                    throw new Error('Could not find API endpoints. Check if API server is running and paths are correct.');
                }
                
                console.log('✅ Using API base:', foundPath);
                
                const apiEndpoints = {
                    offices: `${apiBase}offices/get_offices.php?limit=1000`,
                    divisions: `${apiBase}divisions/get_divisions.php?limit=1000`,
                    units: `${apiBase}units/get_units.php?limit=1000`,
                    designations: `${apiBase}designations/get_designations.php?limit=1000`
                };
                
                console.log('API Endpoints:', apiEndpoints);
                
                const [officesRes, divisionsRes, unitsRes, designationsRes] = await Promise.all([
                    fetch(apiEndpoints.offices),
                    fetch(apiEndpoints.divisions),
                    fetch(apiEndpoints.units),
                    fetch(apiEndpoints.designations)
                ]);
                
                if (!officesRes.ok) throw new Error(`Offices API failed: ${officesRes.status} ${officesRes.statusText}`);
                if (!divisionsRes.ok) throw new Error(`Divisions API failed: ${divisionsRes.status} ${divisionsRes.statusText}`);
                if (!unitsRes.ok) throw new Error(`Units API failed: ${unitsRes.status} ${unitsRes.statusText}`);
                if (!designationsRes.ok) throw new Error(`Designations API failed: ${designationsRes.status} ${designationsRes.statusText}`);
                
                const officesData = await officesRes.json();
                const divisionsData = await divisionsRes.json();
                const unitsData = await unitsRes.json();
                const designationsData = await designationsRes.json();
                
                console.log('API Data loaded:', {
                    offices: officesData,
                    divisions: divisionsData,
                    units: unitsData,
                    designations: designationsData
                });
                
                // Populate offices dropdown
                const officeSelect = document.getElementById('officeSelect');
                officeSelect.innerHTML = '<option value="">Select Office</option>';
                
                let offices = [];
                if (officesData.data && officesData.data.offices) {
                    offices = officesData.data.offices;
                } else if (officesData.offices) {
                    offices = officesData.offices;
                } else if (Array.isArray(officesData)) {
                    offices = officesData;
                } else if (officesData.data && Array.isArray(officesData.data)) {
                    offices = officesData.data;
                }
                
                console.log('Offices to populate:', offices);
                
                offices.forEach(office => {
                    if (office && office.id) {
                        const option = document.createElement('option');
                        option.value = office.id;
                        option.textContent = office.office_name || office.name || `Office ${office.id}`;
                        officeSelect.appendChild(option);
                    }
                });
                
                // Populate divisions dropdown
                const divisionSelect = document.getElementById('divisionSelect');
                divisionSelect.innerHTML = '<option value="">Select Division</option>';
                
                let divisions = [];
                if (divisionsData.data && divisionsData.data.divisions) {
                    divisions = divisionsData.data.divisions;
                } else if (divisionsData.divisions) {
                    divisions = divisionsData.divisions;
                } else if (Array.isArray(divisionsData)) {
                    divisions = divisionsData;
                } else if (divisionsData.data && Array.isArray(divisionsData.data)) {
                    divisions = divisionsData.data;
                }
                
                console.log('Divisions to populate:', divisions);
                
                divisions.forEach(division => {
                    if (division && division.id) {
                        const option = document.createElement('option');
                        option.value = division.id;
                        option.textContent = division.division_name || division.name || `Division ${division.id}`;
                        option.dataset.officeId = division.office_id || '';
                        divisionSelect.appendChild(option);
                    }
                });
                
                // Populate units dropdown
                const unitSelect = document.getElementById('unitSelect');
                unitSelect.innerHTML = '<option value="">Select Unit</option>';
                
                let units = [];
                if (unitsData.data && unitsData.data.units) {
                    units = unitsData.data.units;
                } else if (unitsData.units) {
                    units = unitsData.units;
                } else if (Array.isArray(unitsData)) {
                    units = unitsData;
                } else if (unitsData.data && Array.isArray(unitsData.data)) {
                    units = unitsData.data;
                }
                
                console.log('Units to populate:', units);
                
                units.forEach(unit => {
                    if (unit && unit.id) {
                        const option = document.createElement('option');
                        option.value = unit.id;
                        option.textContent = unit.unit_name || unit.name || `Unit ${unit.id}`;
                        option.dataset.divisionId = unit.division_id || '';
                        unitSelect.appendChild(option);
                    }
                });
                
                // Populate designations dropdown
                const designationSelect = document.getElementById('designationSelect');
                designationSelect.innerHTML = '<option value="">Select Designation</option>';
                
                let designations = [];
                if (designationsData.data && designationsData.data.designations) {
                    designations = designationsData.data.designations;
                } else if (designationsData.designations) {
                    designations = designationsData.designations;
                } else if (Array.isArray(designationsData)) {
                    designations = designationsData;
                } else if (designationsData.data && Array.isArray(designationsData.data)) {
                    designations = designationsData.data;
                }
                
                console.log('Designations to populate:', designations);
                
                designations.forEach(designation => {
                    if (designation && designation.id) {
                        const option = document.createElement('option');
                        option.value = designation.id;
                        option.textContent = designation.designation_name || designation.name || `Designation ${designation.id}`;
                        designationSelect.appendChild(option);
                    }
                });
                
                // Setup filtering
                setupDropdownFiltering();
                
                dropdownsLoaded = true;
                console.log('✅ All dropdowns loaded successfully!');
                
                // Show success message
                Swal.fire({
                    title: "Form Ready",
                    text: "All dropdown data has been loaded successfully.",
                    icon: "success",
                    timer: 2000,
                    showConfirmButton: false,
                    background: 'rgba(16, 30, 50, 0.95)',
                    color: '#f0f4f8'
                });

            } catch (error) {
                console.error('❌ Error loading dropdown data:', error);
                Swal.fire({
                    title: "Data Loading Error",
                    html: `
                        <div style="text-align: left; color: var(--text-muted);">
                            <p>Failed to load form data. Please check:</p>
                            <ul style="margin-top: 10px; padding-left: 20px;">
                                <li>API server is running at: <code>${window.location.origin}/arta-portal/api/</code></li>
                                <li>API files exist: get_offices.php, get_divisions.php, etc.</li>
                                <li>Database connection is working</li>
                                <li>CORS headers are properly set</li>
                            </ul>
                            <p style="margin-top: 15px; color: var(--error-red);">
                                <strong>Error:</strong> ${error.message}
                            </p>
                            <p style="margin-top: 10px; font-size: 0.9rem;">
                                Current URL: ${window.location.href}<br>
                                Try accessing: <a href="${window.location.origin}/arta-portal/api/offices/get_offices.php?limit=1" target="_blank">${window.location.origin}/arta-portal/api/offices/get_offices.php?limit=1</a>
                            </p>
                        </div>
                    `,
                    icon: "error",
                    background: 'rgba(16, 30, 50, 0.95)',
                    color: '#f0f4f8',
                    confirmButtonText: 'OK',
                    width: '600px'
                });
            }
        }

        function setupDropdownFiltering() {
            const officeSelect = document.getElementById('officeSelect');
            const divisionSelect = document.getElementById('divisionSelect');
            const unitSelect = document.getElementById('unitSelect');
            
            officeSelect.addEventListener('change', function() {
                const officeId = this.value;
                const divisionOptions = divisionSelect.querySelectorAll('option:not([value=""])');
                
                let hasVisibleOptions = false;
                
                divisionOptions.forEach(option => {
                    if (officeId && option.dataset.officeId !== officeId) {
                        option.style.display = 'none';
                        option.disabled = true;
                    } else {
                        option.style.display = 'block';
                        option.disabled = false;
                        hasVisibleOptions = true;
                    }
                });
                
                divisionSelect.value = '';
                unitSelect.value = '';
                divisionSelect.disabled = !officeId || !hasVisibleOptions;
                updateDropdownValidation('officeSelect', officeId, 'Office selected');
            });
            
            divisionSelect.addEventListener('change', function() {
                const divisionId = this.value;
                const unitOptions = unitSelect.querySelectorAll('option:not([value=""])');
                
                let hasVisibleOptions = false;
                
                unitOptions.forEach(option => {
                    if (divisionId && option.dataset.divisionId !== divisionId) {
                        option.style.display = 'none';
                        option.disabled = true;
                    } else {
                        option.style.display = 'block';
                        option.disabled = false;
                        hasVisibleOptions = true;
                    }
                });
                
                unitSelect.value = '';
                unitSelect.disabled = !divisionId || !hasVisibleOptions;
                updateDropdownValidation('divisionSelect', divisionId, 'Division selected');
            });
            
            unitSelect.addEventListener('change', function() {
                updateDropdownValidation('unitSelect', this.value, 'Unit selected');
            });
            
            designationSelect.addEventListener('change', function() {
                updateDropdownValidation('designationSelect', this.value, 'Designation selected');
            });
            
            updateDropdownValidation('officeSelect', null, 'Select your office');
            updateDropdownValidation('divisionSelect', null, 'Select your division');
            updateDropdownValidation('unitSelect', null, 'Select your unit');
            updateDropdownValidation('designationSelect', null, 'Select your designation');
        }

        // Load dropdowns on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, starting dropdown load...');
            loadDropdownData();
        });

        // 4. Form submission - WITH BETTER ERROR HANDLING
        document.getElementById('registrationForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            if (!dropdownsLoaded) {
                Swal.fire({
                    title: "Form Not Ready",
                    text: "Please wait for the form data to load completely.",
                    icon: "warning",
                    background: 'rgba(16, 30, 50, 0.95)',
                    color: '#f0f4f8'
                });
                return;
            }

            // Validate all required fields
            const requiredFields = [
                'firstname', 'lastname', 'national_id', 'contact', 
                'email', 'password', 'confirm_password',
                'office_id', 'division_id', 'unit_id', 'designation_id'
            ];
            
            let isValid = true;
            requiredFields.forEach(field => {
                const element = document.getElementById(field) || 
                               document.querySelector(`[name="${field}"]`);
                if (element && !element.value.trim()) {
                    element.classList.add('invalid');
                    isValid = false;
                }
            });

            if (!isValid) {
                Swal.fire({
                    title: "Missing Information",
                    text: "Please fill in all required fields.",
                    icon: "warning",
                    background: 'rgba(16, 30, 50, 0.95)',
                    color: '#f0f4f8'
                });
                return;
            }

            // Check password match
            if (document.getElementById('password').value !== 
                document.getElementById('confirm_password').value) {
                Swal.fire({
                    title: "Passwords Don't Match",
                    text: "Please make sure passwords match.",
                    icon: "warning",
                    background: 'rgba(16, 30, 50, 0.95)',
                    color: '#f0f4f8'
                });
                return;
            }

            // Check terms
            if (!document.getElementById('termsCheck').checked) {
                Swal.fire({
                    title: "Terms Agreement Required",
                    text: "Please agree to the Terms of Service to continue.",
                    icon: "warning",
                    background: 'rgba(16, 30, 50, 0.95)',
                    color: '#f0f4f8'
                });
                return;
            }

            // Show loading
            loading.style.display = 'flex';
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            submitBtn.disabled = true;

            try {
                // Collect form data
                const formData = {
                    national_id: document.getElementById('national_id').value,
                    firstname: document.getElementById('firstname').value.trim(),
                    lastname: document.getElementById('lastname').value.trim(),
                    contact: document.getElementById('contact').value,
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value,
                    office_id: document.getElementById('officeSelect').value,
                    division_id: document.getElementById('divisionSelect').value,
                    unit_id: document.getElementById('unitSelect').value,
                    designation_id: document.getElementById('designationSelect').value
                };

                console.log('Submitting form data:', formData);

                // Try to find the registration API endpoint
                let registerApi = '../api/auth/register.php';
                
                console.log('Sending to:', registerApi);
                const res = await fetch(registerApi, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify(formData)
                });
                
                // First, get the response as text to see what's actually returned
                const responseText = await res.text();
                console.log('Raw response:', responseText);
                
                let result;
                try {
                    result = JSON.parse(responseText);
                } catch (jsonError) {
                    console.error('Failed to parse JSON:', jsonError);
                    console.error('Response was:', responseText);
                    
                    // Check if it's an HTML error page
                    if (responseText.includes('<br />') || responseText.includes('<b>')) {
                        throw new Error('Backend returned HTML error instead of JSON. Check PHP errors.');
                    }
                    throw new Error('Invalid JSON response from server');
                }
                
                console.log('Registration response:', result);
                
                loading.style.display = 'none';
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                
                if (result.status === 201 || result.status === 200) {
                    Swal.fire({
                        title: "Registration Successful!",
                        html: `
                            <div style="text-align: center;">
                                <p>${result.message || "Your account has been created successfully."}</p>
                                ${result.data && result.data.portal_id ? 
                                    `<p><strong>Portal ID:</strong> ${result.data.portal_id}</p>` : ''}
                                ${result.data && result.data.email ? 
                                    `<p><strong>Email:</strong> ${result.data.email}</p>` : ''}
                                ${result.user_id ? 
                                    `<p><strong>User ID:</strong> ${result.user_id}</p>` : ''}
                                <p class="text-muted" style="font-size: 0.9rem; margin-top: 10px;">
                                    You can now login with your credentials.
                                </p>
                            </div>
                        `,
                        icon: "success",
                        background: 'rgba(16, 30, 50, 0.95)',
                        color: '#f0f4f8',
                        confirmButtonText: 'Go to Login'
                    }).then(() => {
                        window.location.href = "index.php";
                    });
                } else {
                    Swal.fire({
                        title: "Registration Failed",
                        html: `
                            <div style="text-align: left; color: var(--text-muted);">
                                <p><strong>${result.message || "Unable to process registration"}</strong></p>
                                ${result.error_info ? 
                                    `<p><strong>Error Details:</strong><br>
                                     Code: ${result.error_info[0] || 'N/A'}<br>
                                     SQL State: ${result.error_info[1] || 'N/A'}<br>
                                     Message: ${result.error_info[2] || 'N/A'}</p>` : ''}
                            </div>
                        `,
                        icon: "error",
                        background: 'rgba(16, 30, 50, 0.95)',
                        color: '#f0f4f8'
                    });
                }
            } catch (err) {
                loading.style.display = 'none';
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                
                console.error('Registration error:', err);
                Swal.fire({
                    title: "Registration Error",
                    html: `
                        <div style="text-align: left; color: var(--text-muted);">
                            <p><strong>Error:</strong> ${err.message}</p>
                            <p>Please check:</p>
                            <ul style="margin-top: 10px; padding-left: 20px;">
                                <li>Backend API file exists: <code>../api/auth/register.php</code></li>
                                <li>API returns valid JSON</li>
                                <li>No PHP syntax errors in backend</li>
                            </ul>
                            <p style="margin-top: 15px; font-size: 0.9rem;">
                                <a href="#" onclick="testBackend()">Test Backend Connection</a>
                            </p>
                        </div>
                    `,
                    icon: "error",
                    background: 'rgba(16, 30, 50, 0.95)',
                    color: '#f0f4f8'
                });
            }
        });

        // Function to test backend connection
        window.testBackend = async function() {
            try {
                loading.style.display = 'flex';
                
                // Test 1: Check if file exists
                const fileCheck = await fetch('../api/auth/register.php', { method: 'HEAD' });
                
                // Test 2: Test with simple data
                const testData = {
                    national_id: '123456789012',
                    firstname: 'Test',
                    lastname: 'User',
                    email: 'test@arta.gov.ph',
                    password: 'test123',
                    contact: '09123456789',
                    office_id: 1,
                    division_id: 1,
                    unit_id: 1,
                    designation_id: 1
                };
                
                const testRes = await fetch('../api/auth/register.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(testData)
                });
                
                const testText = await testRes.text();
                
                loading.style.display = 'none';
                
                Swal.fire({
                    title: "Backend Test Result",
                    html: `
                        <div style="text-align: left; color: var(--text-muted); font-family: monospace; font-size: 12px;">
                            <p><strong>File Exists:</strong> ${fileCheck.ok ? '✅ Yes' : '❌ No'}</p>
                            <p><strong>Response Status:</strong> ${testRes.status}</p>
                            <p><strong>Response:</strong></p>
                            <div style="background: rgba(0,0,0,0.3); padding: 10px; border-radius: 5px; max-height: 300px; overflow: auto;">
                                ${testText.replace(/</g, '&lt;').replace(/>/g, '&gt;')}
                            </div>
                        </div>
                    `,
                    background: 'rgba(16, 30, 50, 0.95)',
                    color: '#f0f4f8',
                    width: '700px'
                });
            } catch (e) {
                loading.style.display = 'none';
                Swal.fire({
                    title: "Test Failed",
                    text: `Error: ${e.message}`,
                    icon: "error",
                    background: 'rgba(16, 30, 50, 0.95)',
                    color: '#f0f4f8'
                });
            }
        };
    </script>
</body>
</html>