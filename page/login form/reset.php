<?php
include('function.php');

$response = null; // Ensure it's defined before using it below

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $response = reset_password(); // Call your function to handle reset
}
?>

<!DOCTYPE html>
<html lang="en">
    <?php echo reset_header(); ?>

    <body class="d-flex justify-content-center align-items-center vh-100">

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm p-4">
                        <div class="text-center">
                            <img src="../../img/avatars/nmis logo.png" alt="Logo" class="img-fluid rounded-circle mb-3"
                                width="100">
                            <h2 class="mb-3 text-success">NARIS</h2>
                            <p class="text-muted">Reset your password</p>
                        </div>

                        <form action="reset.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Company ID</label>
                                <input type="text" class="form-control" name="company_id"
                                    placeholder="Enter your Company ID" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-custom">Reset Password</button>
                            </div>
                            <div class="text-center mt-3">
                                <a href="login.php" class="text-success">Back to Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($response)): ?>
        <script>
        Swal.fire({
            icon: '<?= $response['status'] ?>',
            title: '<?= $response['title'] ?>',
            text: '<?= $response['message'] ?>',
            confirmButtonColor: '<?= $response['status'] === 'success' ? "#28a745" : "#dc3545" ?>'
        }).then(() => {
            <?php if (!empty($response['redirect'])): ?>
            window.location.href = '<?= $response['redirect'] ?>';
            <?php endif; ?>
        });
        </script>
        <?php endif; ?>

    </body>
</html>