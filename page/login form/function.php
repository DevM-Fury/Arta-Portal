<?php 
// Call the Database Connection
include '../../connection/db.php';?>



<?php
// Registration Function
function register() {
    global $conn;

    // Sanitize and retrieve inputs
    $company_id = $_POST['company_id'];
    $company_name = trim($_POST['company_name']);
    $company_email = filter_var($_POST['company_email'], FILTER_SANITIZE_EMAIL);
    $company_number = preg_replace('/[^0-9]/', '', $_POST['company_number']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $region = $_POST['region'];

    // Validate required fields
    $required_fields = [
        'company_id' => $company_id,
        'company_name' => $company_name,
        'company_email' => $company_email,
        'company_number' => $company_number,
        'password' => $password,
        'confirm_password' => $confirm_password,
        'region' => $region
    ];

    foreach ($required_fields as $field => $value) {
        if (empty($value)) {
            $field_name = ucwords(str_replace('_', ' ', $field));
            return "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Field',
                    text: " . json_encode("$field_name is required!") . ",
                    showConfirmButton: true
                });
            </script>";
        }
    }

    // Password match check
    if ($password !== $confirm_password) {
        return "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Password and Confirm Password do not match!',
                showConfirmButton: true
            });
        </script>";
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // File upload processing
    $logo_name = null;
    if (isset($_FILES['company_logo']) && $_FILES['company_logo']['error'] === 0) {
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        $file_extension = strtolower(pathinfo($_FILES['company_logo']['name'], PATHINFO_EXTENSION));

        if (!in_array($file_extension, $allowed_extensions)) {
            return "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File',
                    text: 'Only JPG, JPEG, and PNG files are allowed.',
                    showConfirmButton: true
                });
            </script>";
        }

        if ($_FILES['company_logo']['size'] > 2 * 1024 * 1024) {
            return "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'The file size exceeds the 2MB limit.',
                    showConfirmButton: true
                });
            </script>";
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['company_logo']['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, ['image/jpeg', 'image/png'])) {
            return "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    text: 'Uploaded file is not a valid image.',
                    showConfirmButton: true
                });
            </script>";
        }

        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $safe_filename = uniqid() . "_" . preg_replace("/[^a-zA-Z0-9_-]/", "", pathinfo($_FILES['company_logo']['name'], PATHINFO_FILENAME)) . "." . $file_extension;
        $target_file = $target_dir . $safe_filename;

        if (!move_uploaded_file($_FILES['company_logo']['tmp_name'], $target_file)) {
            return "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Failed',
                    text: 'Failed to upload the company logo.',
                    showConfirmButton: true
                });
            </script>";
        }

        $logo_name = $safe_filename;
    }

    $date_created = date('Y-m-d H:i:s');

    // Database operations inside try-catch
    try {
        // Check if Company ID already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM user_tbl WHERE company_id = ?");
        $stmt->execute([$company_id]);
        if ($stmt->fetchColumn() > 0) {
            return "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Duplicate Company ID',
                    text: 'The Company ID already exists. Please refresh and try again.',
                    showConfirmButton: true
                });
            </script>";
        }

        // Check if Email already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM user_tbl WHERE company_email = ?");
        $stmt->execute([$company_email]);
        if ($stmt->fetchColumn() > 0) {
            return "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Duplicate Email',
                    text: 'This email is already registered.',
                    showConfirmButton: true
                });
            </script>";
        }

        // Check if Contact Number already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM user_tbl WHERE company_number = ?");
        $stmt->execute([$company_number]);
        if ($stmt->fetchColumn() > 0) {
            return "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Duplicate Contact Number',
                    text: 'This contact number is already in use.',
                    showConfirmButton: true
                });
            </script>";
        }

        // Insert new user
        $stmt = $conn->prepare("INSERT INTO user_tbl (company_id, company_name, company_email, company_number, password, region, logo, date_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $company_id,
            $company_name,
            $company_email,
            $company_number,
            $hashed_password,
            $region,
            $logo_name,
            $date_created
        ]);

        return "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Registration Successful!',
                    html: 'Your Company ID is: <strong>$company_id</strong><br>Save this for future login.',
                    confirmButtonText: 'Go to Login'
                }).then(() => {
                    window.location.href = 'login.php';
                });
                 </script>";


    } catch (PDOException $e) {
        return "<script>
            Swal.fire({
                icon: 'error',
                title: 'Database Error',
                html: " . json_encode('An error occurred:<br><small>' . $e->getMessage() . '</small>') . ",
                showConfirmButton: true
            });
        </script>";
    }
}
?>
<!-- End Of Registration Function -->

<!-- Login Function -->
<?php

function login() {
    global $conn;
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $company_id = $_POST["company_id"];
        $password = $_POST["password"];

        try {
            // Query user from DB, include region
            $stmt = $conn->prepare("SELECT company_id, password, role, region FROM user_tbl WHERE company_id = ?");
            $stmt->execute([$company_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user["password"])) {
                // Save to session
                $_SESSION["company_id"] = $user["company_id"];
                $_SESSION["role"] = $user["role"];
                $_SESSION["region"] = $user["region"]; // ✅ Store region

                // Determine redirect URL
                switch ($user["role"]) {
                    case null:
                        $redirect_url = "../applicant/applicant_dashboard.php";
                        break;
                    case "ars":
                        $redirect_url = "../ars/ars_dashboard.php";
                        break;
                    case "ard":
                        $redirect_url = "../verifier/ard_dashboard.php";
                        break;
                    case "chief":
                        $redirect_url = "../chief/chief_dashboard.php";
                        break;
                    case "accounting":
                        $redirect_url = "../accounting/accounting_dashboard.php";
                        break;
                    default:
                        return "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Login Failed',
                                text: 'Invalid user role. Contact the administrator.',
                                confirmButtonColor: '#d33'
                            });
                        </script>";
                }

                // Return success SweetAlert and redirect
                return "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful',
                        text: 'Redirecting...',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '$redirect_url';
                    });
                </script>";
            } else {
                return "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: 'Invalid Company ID or Password.',
                        confirmButtonColor: '#d33'
                    });
                </script>";
            }

        } catch (PDOException $e) {
            return "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Database Error',
                    text: '" . addslashes($e->getMessage()) . "',
                    confirmButtonColor: '#d33'
                });
            </script>";
        }
    }

    return ''; // Return nothing if not a POST request
}

?>
<!-- End of Login Function -->


<!-- Login Header -->
<?php
function login_header() {
    echo '
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login - NARIS</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <style>
        body {
            background-color: #e9f5e9;
        }

        .btn-custom {
            background-color: #28a745;
            color: white;
        }

        .btn-custom:hover {
            background-color: #218838;
        }

        .card {
            border-color: #28a745;
        }
        </style>
    </head>
    ';
}
?>

<!-- End Login Header -->

<<!-- Function Reset Password -->
	<?php
function reset_password() {
    global $conn;

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        return null; // Prevents running on GET requests
    }

    $company_id = trim($_POST['company_id'] ?? '');

    if (empty($company_id)) {
        return [
            'status' => 'error',
            'title' => 'Missing Input!',
            'message' => 'Please enter a valid Company ID.'
        ];
    }

    try {
        // Check if the company ID exists
        $stmt = $conn->prepare("SELECT company_id FROM user_tbl WHERE company_id = ?");
        $stmt->execute([$company_id]);

        if ($stmt->rowCount() === 0) {
            return [
                'status' => 'error',
                'title' => 'Not Found',
                'message' => 'Company ID does not exist.'
            ];
        }

        // Hash the new password (set to company_id)
        $hashed_password = password_hash($company_id, PASSWORD_DEFAULT);

        // Update password
        $update = $conn->prepare("UPDATE user_tbl SET password = ? WHERE company_id = ?");
        $update->execute([$hashed_password, $company_id]);

        return [
            'status' => 'success',
            'title' => 'Password Reset!',
            'message' => 'Your password has been reset to your Company ID.',
            'redirect' => 'login.php'
        ];
    } catch (PDOException $e) {
        return [
            'status' => 'error',
            'title' => 'Database Error',
            'message' => 'An error occurred while resetting the password.'
        ];
    }
}
?>
	<!-- End Function Reset Password -->


	<!-- Reset Form Header -->
	<?php
function reset_header() {
    echo '
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Reset Password - NARIS</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <style>
                body {
                    background-color: #e9f5e9;
                }

                .btn-custom {
                    background-color: #28a745;
                    color: white;
                }

                .btn-custom:hover {
                    background-color: #218838;
                }

                .card {
                    border-color: #28a745;
                }
            </style>
        </head>
    ';
}
?>
	<!-- End Reset Form Header -->