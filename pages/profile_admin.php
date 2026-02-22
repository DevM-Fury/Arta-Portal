<!DOCTYPE html>
<html lang="en">
<?php include_once __DIR__ . '/../includes/head.php'; ?>

<head>
    <style>
        /* === GLOBAL === */
        body {
            background-color: #f3f6fa;
            font-family: 'Segoe UI', Roboto, Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* === MAIN CONTENT WRAPPER === */
        .main-content {
            margin-left: 250px;
            padding-top: 50px;   /* fixed header height */
            padding-right: 30px;
        }

        /* === TWO-COLUMN LAYOUT – may top margin para hindi sumasagad === */
        .profile-wrapper {
            display: flex;
            gap: 30px;
            align-items: stretch;
            max-width: 1100px;
            margin: 150px auto 20px auto;   /* 👈 Ibinaba – may 30px top margin */
        }

        /* === LEFT: NATIONAL ID CARD (FIXED SIZE) === */
        .card-fixed-wrapper {
            flex: 0 0 600px;
            height: 360px;
            position: relative;
        }

        .profile-card {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .card-inner {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            border-radius: 20px;
            background: white;
        }

        .card-front {
            width: 100%;
            height: 130%;
            background: white;
            display: flex;
            flex-direction: column;
            padding: 20px 25px;
            border: 1px solid #d0d9e5;
            border-radius: 20px;
            position: relative;
            box-sizing: border-box;
        }

        .flag-ribbon {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(90deg, #0038a8 33%, #bf0a30 33%, #bf0a30 66%, #fcdc42 66%);
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }

        .philid-header-horizontal {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #ffcd00;
        }

        .republic-text {
            font-family: 'Times New Roman', serif;
        }

        .republic-big {
            font-size: 16px;
            font-weight: 800;
            color: #0038a8;
            letter-spacing: 1px;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .republic-small {
            font-size: 10px;
            color: #5a5a5a;
            text-transform: uppercase;
        }

        .philid-title {
            font-size: 14px;
            font-weight: 700;
            color: #1a1a1a;
            text-align: right;
            line-height: 1.3;
            text-transform: uppercase;
        }

        .id-header-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .card-number {
            font-family: 'Courier New', monospace;
            font-size: 24px;
            font-weight: 700;
            color: #0d2d4b;
            background: #eef3f9;
            padding: 10px 20px;
            border-radius: 40px;
            letter-spacing: 3px;
            border: 1px solid #b0c4ce;
            flex: 1;
            margin-right: 15px;
            text-align: center;
        }

        .photo-placeholder {
            width: 90px;
            height: 110px;
            background: #f2f2f2;
            border-radius: 10px;
            border: 2px solid #ffcd00;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: #0038a8;
            background: linear-gradient(145deg, #ffffff, #e6ecf5);
            box-shadow: inset 0 0 5px rgba(0,0,0,0.05);
        }

        .id-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px 20px;
            margin-top: 5px;
            flex: 1;
        }

        .id-item {
            display: flex;
            flex-direction: column;
            border-bottom: 1px solid #eaeef2;
            padding-bottom: 4px;
        }

        .id-label {
            font-size: 9px;
            font-weight: 700;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .id-value {
            font-size: 14px;
            font-weight: 600;
            color: #212529;
            word-break: break-word;
            text-transform: uppercase;
        }

        .phl-badge-horizontal {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            border-top: 2px solid #ffcd00;
            padding-top: 12px;
        }

        .phl-flag-icon {
            font-size: 26px;
        }

        .phl-text {
            background: #0038a8;
            color: white;
            padding: 4px 20px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 16px;
            letter-spacing: 2px;
        }

        /* === RIGHT: USER DETAILS PANEL === */
        .details-panel {
            flex: 1;
            background: white;
            border-radius: 20px;
            padding: 30px 35px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.08);
            border: 1px solid #e0e0e0;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .details-panel h4 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 25px;
            border-left: 6px solid #ffcd00;
            padding-left: 16px;
            letter-spacing: 1px;
            color: #0038a8;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px 30px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 8px;
        }

        .detail-label {
            font-size: 11px;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .detail-value {
            font-size: 16px;
            font-weight: 500;
            color: #212529;
            word-break: break-word;
        }

        .footer-note {
            margin-top: 25px;
            font-size: 11px;
            color: #6c757d;
            text-align: center;
            border-top: 1px solid #e9ecef;
            padding-top: 15px;
        }

        /* === RESPONSIVE === */
        @media (max-width: 1000px) {
            .profile-wrapper {
                flex-direction: column;
                align-items: center;
            }
            .card-fixed-wrapper {
                flex: 0 0 auto;
                margin-bottom: 20px;
            }
            .details-panel {
                width: 100%;
                max-width: 600px;
            }
        }
    </style>
</head>

<body>
    <?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

    <!-- Header -->
    <div class="header-bar shadow-sm" style="background:#002b7f; color:white; padding:15px 30px;">
        <div class="header-title d-flex align-items-center">
            <i class="fa-solid fa-id-card me-2"></i>
            <h5 class="mb-0 fw-semibold">My National ID</h5>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="profile-wrapper">
            <!-- LEFT: NATIONAL ID CARD -->
            <div class="card-fixed-wrapper">
                <div class="profile-card">
                    <div class="card-inner">
                        <div class="card-front">
                            <div class="flag-ribbon"></div>
                            <div class="philid-header-horizontal">
                                <div class="republic-text">
                                    <div class="republic-big">ANTI-RED TAPE AURTHORITY </div>
                                    <div class="republic-small">Office Of The President</div>
                                </div>
                                <div class="philid-title">
                                    NFA Building 5th Floor  Vasra  Visayas Avenue Quezon City<br>
                                    <span style="font-size:12px;">Portal Identification Card</span>
                                </div>
                            </div>
                            <div class="id-header-details">
                                <div class="card-number" id="cardNumberDisplay">8987 6543 526</div>
                                <div class="photo-placeholder">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                            </div>
                            <div class="id-details-grid">
                                <div class="id-item">
                                    <span class="id-label">Apelyido / Last Name</span>
                                    <span class="id-value" id="lastNameDisplay">LUI</span>
                                </div>
                                <div class="id-item">
                                    <span class="id-label">Mga Pangalan / Given Names</span>
                                    <span class="id-value" id="givenNamesDisplay">PATRICIA</span>
                                </div>
                                <div class="id-item">
                                    <span class="id-label">Gitnang Apelyido / Middle Name</span>
                                    <span class="id-value" id="middleNameDisplay">MARTINEZ</span>
                                </div>
                                <div class="id-item">
                                    <span class="id-label">Petsa ng Kapanganakan / Date of Birth</span>
                                    <span class="id-value" id="dobDisplay">JAN 01, 1990</span>
                                </div>
                                <div class="id-item" style="grid-column: span 2;">
                                    <span class="id-label">Assignment</span>
                                    <span class="id-value" id="addressDisplay">OODDGAF, ICT, DEV</span>
                                </div>
                            </div>
                            <div class="phl-badge-horizontal">
                                <span class="phl-flag-icon">🇵🇭</span>
                                <span class="phl-text">PHL</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: USER DETAILS PANEL -->
            <div class="details-panel">
                <h4><i class="fa-solid fa-id-card me-2"></i>USER DETAILS</h4>
                <div class="details-grid">
                    <div class="detail-item">
                        <span class="detail-label">Portal ID</span>
                        <span class="detail-value" id="portal_id">9199a873-28d6-4840-9272-24b7725ee118</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">National ID</span>
                        <span class="detail-value" id="national_id">89876543526</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Role</span>
                        <span class="detail-value" id="role">USER</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Office</span>
                        <span class="detail-value" id="office">OODDGAF</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Division</span>
                        <span class="detail-value" id="division">ICT</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Unit</span>
                        <span class="detail-value" id="unit">Dev</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Designation</span>
                        <span class="detail-value" id="designation">ISA I</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Date Created</span>
                        <span class="detail-value" id="date_created">October 20, 2025</span>
                    </div>
                </div>
                <div class="footer-note">
                    <i class="fa-solid fa-shield"></i> Official Government Identification
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <small class="text-muted">Philippine Identification Card – secure, valid, at pambansa.</small>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js" crossorigin="anonymous"></script>

    <script>
        async function loadUserProfile() {
            try {
                const res = await fetch("../api/users/get_profile.php", {
                    credentials: 'same-origin'
                });
                const text = await res.text();
                let data;

                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.log('Using static mock data');
                    return;
                }

                if (data.status === "success") {
                    const user = data.user;
                    // FRONT
                    document.getElementById("lastNameDisplay").textContent = (user.lastname || "LUI").toUpperCase();
                    document.getElementById("givenNamesDisplay").textContent = (user.firstname || "PATRICIA").toUpperCase();
                    document.getElementById("middleNameDisplay").textContent = (user.middlename || "MARTINEZ").toUpperCase() || "N/A";
                    let dob = user.birthdate || "JAN 01, 1990";
                    document.getElementById("dobDisplay").textContent = dob.toUpperCase();
                    let address = user.address || "OODDGAF, ICT, DEV";
                    document.getElementById("addressDisplay").textContent = address.toUpperCase();
                    document.getElementById("cardNumberDisplay").textContent = user.national_id || "8987 6543 526";
                    
                    // RIGHT PANEL
                    document.getElementById("portal_id").textContent = user.portal_id || "9199a873-28d6-4840-9272-24b7725ee118";
                    document.getElementById("national_id").textContent = user.national_id || "89876543526";
                    document.getElementById("role").textContent = user.role || "USER";
                    document.getElementById("office").textContent = user.office_name || "OODDGAF";
                    document.getElementById("division").textContent = user.division_name || "ICT";
                    document.getElementById("unit").textContent = user.unit_name || "Dev";
                    document.getElementById("designation").textContent = user.designation_name || "ISA I";
                    document.getElementById("date_created").textContent = user.date_created || "October 20, 2025";
                }
            } catch (err) {
                console.error("Profile Load Error:", err);
            }
        }

        window.addEventListener("DOMContentLoaded", loadUserProfile);
    </script>

    <?php include_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>