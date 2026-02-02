<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>About Us - NARIS</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
        <style>
        body {
            background-color: #e9f5e9;
        }

        .about-container {
            max-width: 900px;
            margin: 50px auto;
            padding: 30px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 128, 0, 0.2);
            text-align: center;
        }

        .about-title {
            font-size: 28px;
            font-weight: bold;
            color: #218838;
        }

        .about-text {
            font-size: 16px;
            color: #444;
            line-height: 1.7;
        }

        .manual-section {
            margin-top: 40px;
        }

        .manual-item {
            margin-bottom: 20px;
        }

        .manual-item h5 {
            font-weight: bold;
            color: #218838;
        }

        .manual-item p {
            color: #555;
        }

        .manual-icon {
            font-size: 40px;
            color: #218838;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #218838;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo i {
            font-size: 60px;
            color: white;
        }

        .password-reset {
            background: #d4edda;
            padding: 15px;
            border-radius: 8px;
            margin-top: 30px;
            text-align: left;
        }

        .password-reset h5 {
            font-weight: bold;
            color: #155724;
        }

        .password-reset p {
            color: #333;
        }
        </style>
    </head>

    <body>

        <div class="container about-container">
            <div class="logo-container">
                <div class="logo">
                    <i class="bi bi-shield-check"></i> <!-- NMIS logo substitute -->
                </div>
            </div>
            <h2 class="about-title">NMIS Accreditation and Registration System</h2>
            <p class="about-text">
                The NMIS Accreditation and Registration System (NARIS) is a digital platform
                designed to simplify accreditation and registration processes for businesses
                in the meat industry. It ensures compliance, transparency, and efficiency
                in regulatory procedures.
            </p>

            <div class="manual-section">
                <h3 class="text-center">User Manual</h3>
                <div class="row">
                    <div class="col-md-6 manual-item">
                        <h5><i class="bi bi-person-plus manual-icon"></i> How to Register</h5>
                        <p>Sign up on the NARIS portal, complete the registration form, and verify your email.</p>
                    </div>
                    <div class="col-md-6 manual-item">
                        <h5><i class="bi bi-upload manual-icon"></i> Submitting an Application</h5>
                        <p>Log in to your account, fill in the required details, and upload necessary documents.</p>
                    </div>
                    <div class="col-md-6 manual-item">
                        <h5><i class="bi bi-eye manual-icon"></i> Tracking Your Application</h5>
                        <p>Use the dashboard to monitor your application's status in real-time.</p>
                    </div>
                    <div class="col-md-6 manual-item">
                        <h5><i class="bi bi-lock manual-icon"></i> Security & Privacy</h5>
                        <p>All user data is encrypted and securely stored to ensure privacy.</p>
                    </div>
                </div>
            </div>

            <div class="password-reset">
                <h5><i class="bi bi-key"></i> How to Reset Your Password</h5>
                <p>1. Go to the <a href="forgot_password.php">Forgot Password</a> page.</p>
                <p>2. Enter your registered email address.</p>
                <p>3. Click "Submit" and check your email for a reset link.</p>
                <p>4. Follow the instructions in the email to create a new password.</p>
                <p>5. Log in with your new password.</p>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>