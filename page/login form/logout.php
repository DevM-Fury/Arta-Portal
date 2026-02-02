<?php
session_start();

// Destroy session and redirect with SweetAlert confirmation
if (isset($_SESSION['company'])) {
    session_destroy();
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Logged Out',
            text: 'You have been successfully logged out.',
            confirmButtonColor: '#66bb6a',
            allowOutsideClick: false
        }).then(() => {
            window.location.href = '../../index.php';
        });
    </script>";
    exit();
} else {
    header("Location: ../../index.php");
    exit();
}
?>