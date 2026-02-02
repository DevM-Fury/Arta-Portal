<?php
include('function.php');

$response = '';
$formData = $_POST ?? [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $response = register();
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Company Registration</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
        <!-- SweetAlert2 CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    </head>
    <body class="d-flex justify-content-center align-items-center min-vh-100">

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card shadow-sm p-4">
                        <div class="text-center">
                            <img src="../../img/avatars/nmis logo.png" alt="NMIS Logo" class="img-fluid mb-3"
                                width="120">
                            <h2 class="mb-3 text-success">Company Registration</h2>
                            <p class="text-muted">Register your company with NARIS</p>
                        </div>
                        <form id="companyRegistrationForm" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Upload Company Logo</label>
                                <input type="file" class="form-control" name="company_logo" accept="image/*">
                                <h6 class="text-danger">This Company Logo is optional</h6>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">NARIS-ID</label>
                                <input type="text" class="form-control" id="company_id" name="company_id" readonly>
                                <h6 class="text-danger">IMPORTANT: Save this Company ID for future login! (Auto
                                    generated)</h6>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Company Name</label>
                                <!-- <input type="text" class="form-control" id="company_name" name="company_name" required> -->
                                <input type="text" class="form-control" id="company_name" name="company_name"
                                    value="<?php echo htmlspecialchars($formData['company_name'] ?? ''); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Company Email</label>
                                <!-- <input type="email" class="form-control" name="company_email" required> -->
                                <input type="email" class="form-control" id="company_email" name="company_email"
                                    value="<?php echo htmlspecialchars($formData['company_email'] ?? ''); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Company Number</label>
                                <!-- <input type="tel" class="form-control" name="company_number" required> -->
                                <input type="tel" class="form-control" id="company_number" name="company_number"
                                    value="<?php echo htmlspecialchars($formData['company_number'] ?? ''); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Region</label>
                                <select class="form-select" name="region" id="regionDropdown" required>
                                    <option value="">Select a region</option>
                                    <?php
                                    // After fetching region options (from JS), use JS to re-select previous value
                                    $selectedRegion = $formData['region'] ?? '';
                                    echo "<script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const prevRegion = " . json_encode($selectedRegion) . ";
                                            if (prevRegion) {
                                                document.getElementById('regionDropdown').value = prevRegion;
                                            }
                                        });
                                    </script>";
                                    ?>

                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" name="confirm_password" required>
                                <div id="passwordError" class="mt-2 text-danger small"></div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg">Register</button>
                            </div>

                            <div class="text-center mt-3">
                                <a href="login.php" class="text-success">Already have an account? Sign in</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- SCRIPT SECTION -->
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            const regionDropdown = document.getElementById("regionDropdown");
            const companyNameInput = document.getElementById("company_name");
            const companyIdInput = document.getElementById("company_id");

            // Fetch and populate regions
            fetch("https://psgc.gitlab.io/api/regions/")
                .then(response => response.json())
                .then(data => {
                    regionDropdown.innerHTML = '<option value="">Select a region</option>';
                    data.sort((a, b) => parseInt(a.code) - parseInt(b.code));
                    data.forEach(region => {
                        const shortCode = region.code.substring(0, 2);
                        const option = document.createElement("option");
                        option.value = shortCode;
                        option.textContent = `${region.name} (${shortCode})`;
                        regionDropdown.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error("Error fetching regions:", error);
                    regionDropdown.innerHTML = '<option value="">Failed to load regions</option>';
                });

            // Generate company ID
            function getFirstThreeLetters(str) {
                const lettersOnly = str.replace(/[^a-zA-Z]/g, '').toUpperCase();
                return lettersOnly.substring(0, 3).padEnd(3, 'X');
            }

            function generateCompanyId(name, regionCode) {
                const firstThree = getFirstThreeLetters(name);
                const randomNum = Math.floor(1000 + Math.random() * 9000);
                return `${firstThree}-${regionCode}-${randomNum}`;
            }

            function updateCompanyId() {
                const name = companyNameInput.value;
                const regionCode = regionDropdown.value;
                if (name && regionCode) {
                    companyIdInput.value = generateCompanyId(name, regionCode);
                }
            }

            companyNameInput.addEventListener("input", updateCompanyId);
            regionDropdown.addEventListener("change", updateCompanyId);

            // Password match validation
            const password = document.querySelector('[name="password"]');
            const confirmPassword = document.querySelector('[name="confirm_password"]');
            const passwordError = document.getElementById("passwordError");
            const submitButton = document.querySelector('button[type="submit"]');

            function validatePasswords() {
                if (password.value !== confirmPassword.value) {
                    passwordError.textContent = "Passwords do not match!";
                    submitButton.disabled = true;
                } else {
                    passwordError.textContent = "";
                    submitButton.disabled = false;
                }
            }

            password.addEventListener("input", validatePasswords);
            confirmPassword.addEventListener("input", validatePasswords);
        });
        </script>

        <?php if (!empty($response)) echo $response; ?>

    </body>
</html>