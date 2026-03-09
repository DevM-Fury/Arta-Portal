<!DOCTYPE html>
<html lang="en">
<?php include_once __DIR__ . '/../includes/head.php'; ?>

<head>
    <style>
        /* === RESET / GLOBAL === */
        body {
            background-color: #f0f4f8;
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* === SIDEBAR OFFSET + FIXED HEADER AVOID OVERLAP === */
        .main-content {
            margin-left: 250px;          /* space for sidebar_user.php */
            padding: 20px 35px 35px 35px;
            margin-top: 70px;             /* creates gap so header doesn't cover content */
            transition: all 0.2s;
        }

        /* === ORIGINAL HEADER === */
        .header-bar {
            background: #002b7f !important;
            color: white;
            padding: 15px 30px;
            width: 100%;
            position: relative;
            z-index: 10;
        }

        /* === PROFILE HEADER inside main === */
        .profile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .profile-header h2 {
            font-weight: 600;
            font-size: 1.8rem;
            color: #0a2540;
            letter-spacing: -0.3px;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .profile-header h2 i {
            color: #2b6c9e;
            background: white;
            padding: 10px;
            border-radius: 16px;
            box-shadow: 0 4px 10px rgba(0,40,80,0.08);
        }
        .badge-verified {
            background: #e3f2fd;
            color: #0b4770;
            padding: 6px 15px;
            border-radius: 40px;
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #bcd9f0;
        }

        /* === TOP STAT CARDS — FULLY RESPONSIVE === */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 24px;
            padding: 18px 18px;
            box-shadow: 0 8px 20px -6px rgba(0, 45, 85, 0.1);
            border: 1px solid #e6edf5;
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0; /* Prevents overflow */
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #eef6ff, #ddeeff);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #003f88;
            flex-shrink: 0; /* Prevents icon from shrinking */
        }

        .stat-content {
            min-width: 0; /* Allows text to truncate if needed */
            flex: 1;
        }

        .stat-content h4 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
            color: #122b44;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .stat-content p {
            margin: 2px 0 0;
            color: #3f5b76;
            font-weight: 500;
            font-size: 0.85rem;
            letter-spacing: 0.2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* === TWO-COLUMN LAYOUT === */
        .profile-dashboard {
            display: flex;
            gap: 24px;
            align-items: stretch;
        }

        /* === LEFT PANEL === */
        .left-panel {
            flex: 1.2;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* === RIGHT PANEL === */
        .right-panel {
            flex: 1.8;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* === universal card style === */
        .info-card {
            background: white;
            border-radius: 24px;
            padding: 22px 24px;
            box-shadow: 0 12px 24px -10px rgba(0,30,60,0.08);
            border: 1px solid #e2eaf2;
        }
        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1a3d62;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 2px solid #ffcd00;
            padding-bottom: 10px;
        }
        .card-title i {
            color: #1a5d9c;
            background: #ecf3fa;
            padding: 6px;
            border-radius: 12px;
            font-size: 1rem;
        }

        /* === DETAIL GRIDS === */
        .grid-2col {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px 24px;
        }
        .grid-1col {
            display: grid;
            gap: 16px;
        }
        .detail-item {
            display: flex;
            flex-direction: column;
            border-bottom: 1px dashed #d0ddee;
            padding-bottom: 8px;
        }
        .detail-label {
            font-size: 0.7rem;
            font-weight: 600;
            color: #4e6b85;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 3px;
        }
        .detail-value {
            font-size: 1rem;
            font-weight: 500;
            color: #0d2135;
            word-break: break-word;
        }

        /* contact chips */
        .contact-chip {
            background: #ecf7ff;
            border-radius: 32px;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.9rem;
            border: 1px solid #bee0ff;
            margin-bottom: 6px;
        }
        .contact-chip i {
            width: 24px;
            color: #004e9e;
            font-size: 1rem;
        }

        /* assignment block */
        .assignment-block {
            background: #f7faff;
            border-radius: 18px;
            padding: 16px 20px;
            border-left: 5px solid #ffcd00;
            margin-top: 4px;
        }
        .assignment-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #dde5ef;
            font-size: 0.9rem;
        }
        .assignment-item:last-child { border-bottom: none; }
        .assignment-label { font-weight: 600; color: #305f82; }
        .assignment-value { font-weight: 500; color: #122b44; }

        /* avatar */
        .avatar-large {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 12px;
        }
        .avatar-circle {
            width: 75px;
            height: 75px;
            background: linear-gradient(145deg, #273c61, #164675);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: white;
            box-shadow: 0 8px 16px rgba(0,35,70,0.15);
            border: 3px solid #ffcd00;
        }
        .avatar-text h3 {
            font-size: 1.6rem;
            font-weight: 600;
            margin: 0 0 4px;
            color: #0f293f;
        }
        .avatar-text p {
            color: #2d577b;
            font-weight: 500;
            margin: 0;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* quick id row */
        .quick-id-row {
            display: flex;
            gap: 12px;
            background: #f0f7fe;
            border-radius: 36px;
            padding: 8px 16px;
            margin-top: 12px;
            font-size: 0.85rem;
        }

        /* footer note */
        .footer-note {
            margin-top: 24px;
            font-size: 0.8rem;
            color: #5a7492;
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #cfddee;
            padding-top: 16px;
        }

        /* === RESPONSIVE BREAKPOINTS === */
        @media screen and (max-width: 1200px) {
            .stat-grid {
                gap: 12px;
            }
            
            .stat-card {
                padding: 15px 12px;
                gap: 10px;
            }
            
            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 20px;
                border-radius: 14px;
            }
            
            .stat-content h4 {
                font-size: 1.3rem;
            }
            
            .stat-content p {
                font-size: 0.75rem;
            }
        }

        @media screen and (max-width: 992px) {
            .stat-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
            
            .profile-dashboard {
                flex-direction: column;
            }
        }

        @media screen and (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
                margin-top: 80px;
            }
            
            .stat-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            
            .stat-card {
                padding: 15px 18px;
            }
            
            .stat-content h4 {
                font-size: 1.4rem;
                white-space: normal; /* Allow wrapping on mobile */
            }
            
            .stat-content p {
                font-size: 0.85rem;
                white-space: normal;
            }
            
            .grid-2col {
                grid-template-columns: 1fr;
            }
        }

        @media screen and (max-width: 480px) {
            .stat-card {
                padding: 12px 15px;
            }
            
            .stat-icon {
                width: 36px;
                height: 36px;
                font-size: 18px;
            }
            
            .stat-content h4 {
                font-size: 1.2rem;
            }
            
            .avatar-large {
                flex-direction: column;
                text-align: center;
            }
            
            .quick-id-row {
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 5px;
            }
        }
    </style>
    <!-- Inter font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
</head>

<body>
    <?php include_once __DIR__ . '/../includes/sidebar_subadmin.php'; ?>

    <!-- ORIGINAL HEADER -->
    <div class="header-bar shadow-sm" style="background:#002b7f; color:white; padding:15px 30px;">
        <div class="header-title d-flex align-items-center">
            <i class="fa-solid fa-id-card me-2"></i>
            <h5 class="mb-0 fw-semibold">My National ID</h5>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- profile header inside main -->
        <div class="profile-header">
            <h2>
                <i class="fa-solid fa-circle-user"></i> 
                User Profile
            </h2>
            <div class="badge-verified">
                <i class="fa-solid fa-check-circle" style="color:#1e7e34;"></i> 
                verified ARTA account
            </div>
        </div>

        <!-- stat cards -->
        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-id-card"></i></div>
                <div class="stat-content">
                    <h4 id="stat_national_id">•••••526</h4>
                    <p>National ID (last digits)</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-briefcase"></i></div>
                <div class="stat-content">
                    <h4 id="stat_designation">ISA I</h4>
                    <p>Designation</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-calendar-check"></i></div>
                <div class="stat-content">
                    <h4 id="stat_date_joined">2025</h4>
                    <p>Date created</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-building"></i></div>
                <div class="stat-content">
                    <h4 id="stat_office">OODDGAF</h4>
                    <p>Office</p>
                </div>
            </div>
        </div>

        <!-- TWO COLUMN DETAILS -->
        <div class="profile-dashboard">
            <!-- LEFT PANEL -->
            <div class="left-panel">
                <!-- avatar / name block -->
                <div class="info-card">
                    <div class="avatar-large">
                        <div class="avatar-circle">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div class="avatar-text">
                            <h3 id="fullNameDisplay">PATRICIA M. LUI</h3>
                            <p><i class="fa-solid fa-location-dot" style="color:#004e9e;"></i> <span id="officeLocation">NFA Bldg, Quezon City</span></p>
                        </div>
                    </div>
                    <!-- quick id row -->
                    <div class="quick-id-row">
                        <span><i class="fa-regular fa-id-card"></i> Portal ID: <strong id="portal_id_mini">9199...118</strong></span>
                        <span class="text-muted">|</span>
                        <span><i class="fa-regular fa-id-card"></i> National ID: <strong id="national_id_mini">8987...526</strong></span>
                    </div>
                </div>

                <!-- WORK ASSIGNMENT card -->
                <div class="info-card">
                    <div class="card-title"><i class="fa-solid fa-sitemap"></i> Work assignment</div>
                    <div class="assignment-block">
                        <div class="assignment-item">
                            <span class="assignment-label">Office</span>
                            <span class="assignment-value" id="office_display">OODDGAF</span>
                        </div>
                        <div class="assignment-item">
                            <span class="assignment-label">Division</span>
                            <span class="assignment-value" id="division_display">ICT</span>
                        </div>
                        <div class="assignment-item">
                            <span class="assignment-label">Unit / section</span>
                            <span class="assignment-value" id="unit_display">Development</span>
                        </div>
                        <div class="assignment-item">
                            <span class="assignment-label">Designation</span>
                            <span class="assignment-value" id="designation_display">ISA I</span>
                        </div>
                        <div class="assignment-item">
                            <span class="assignment-label">Role in system</span>
                            <span class="assignment-value" id="role_display">USER</span>
                        </div>
                    </div>
                </div>

                <!-- security footer -->
                <div class="info-card" style="padding: 18px;">
                    <div style="display: flex; gap: 12px; color: #2d577b; font-size:0.9rem;">
                        <i class="fa-solid fa-shield-halved fa-xl"></i>
                        <span style="font-weight:500;">ARTA – official employee record<br><small style="font-size:0.7rem;">verification: active</small></span>
                    </div>
                </div>
            </div>

            <!-- RIGHT PANEL -->
            <div class="right-panel">
                <!-- PERSONAL INFORMATION -->
                <div class="info-card">
                    <div class="card-title"><i class="fa-solid fa-user-details"></i> Personal information</div>
                    <div class="grid-2col">
                        <div class="detail-item">
                            <span class="detail-label">Last name</span>
                            <span class="detail-value" id="lastNameDisplay">LUI</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">First name</span>
                            <span class="detail-value" id="firstNameDisplay">PATRICIA</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Middle name</span>
                            <span class="detail-value" id="middleNameDisplay">MARTINEZ</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Birthdate</span>
                            <span class="detail-value" id="dobDisplay">JAN 01, 1990</span>
                        </div>
                        <div class="detail-item" style="grid-column: span 2;">
                            <span class="detail-label">Complete address</span>
                            <span class="detail-value" id="addressDisplay">OODDGAF, ICT, DEV · NFA Bldg, Vasra</span>
                        </div>
                    </div>
                </div>

                <!-- CONTACT & SYSTEM INFO -->
                <div class="info-card">
                    <div class="card-title"><i class="fa-solid fa-address-book"></i> Contact & system</div>
                    <div class="grid-1col">
                        <div class="contact-chip">
                            <i class="fa-solid fa-envelope"></i> <span id="emailDisplay">patricia.lui@arta.gov.ph</span>
                        </div>
                        <div class="contact-chip">
                            <i class="fa-solid fa-phone"></i> <span id="contactDisplay">+63 (2) 8790 1234</span>
                        </div>
                        <div class="grid-2col" style="margin-top: 6px;">
                            <div class="detail-item">
                                <span class="detail-label">Portal UUID</span>
                                <span class="detail-value" id="portal_id_display">9199a873...118</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">National ID (full)</span>
                                <span class="detail-value" id="national_id_display">8987 6543 526</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Date created</span>
                                <span class="detail-value" id="date_created_display">Oct 20, 2025</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Last update</span>
                                <span class="detail-value" id="lastUpdate">Mar 10, 2026</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- extra note -->
                <div class="footer-note">
                    <span><i class="fa-regular fa-clock"></i> employee since 2024</span>
                    <span><i class="fa-solid fa-lock"></i> secured</span>
                </div>
            </div>
        </div>

        <!-- small footer outside columns -->
        <div style="text-align: right; margin-top: 20px; color:#4d6f8f; font-size:0.85rem;">
            <i class="fa-solid fa-circle-check" style="color:#2b6c2b;"></i> Verified · <span id="footerDate">2026</span>
        </div>
    </div>

    <!-- scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js" crossorigin="anonymous"></script>

    <script>
        async function loadUserProfile() {
            try {
                const res = await fetch("../api/users/get_profile.php", { credentials: 'same-origin' });
                const text = await res.text();
                let data;
                try { data = JSON.parse(text); } catch (e) { console.log('Using mock data'); }

                let user = {};
                if (data && data.status === "success") {
                    user = data.user;
                } else {
                    user = {
                        lastname: "LUI",
                        firstname: "PATRICIA",
                        middlename: "MARTINEZ",
                        birthdate: "JAN 01, 1990",
                        address: "OODDGAF, ICT, DEV, NFA Bldg Vasra",
                        national_id: "8987 6543 526",
                        portal_id: "9199a873-28d6-4840-9272-24b7725ee118",
                        role: "USER",
                        office_name: "OODDGAF",
                        division_name: "ICT",
                        unit_name: "Dev",
                        designation_name: "ISA I",
                        date_created: "October 20, 2025",
                        email: "patricia.lui@arta.gov.ph",
                        contact: "+63 (2) 8790 1234",
                        office_location: "NFA Bldg, Quezon City",
                        last_update: "March 10, 2026"
                    };
                }

                document.getElementById("lastNameDisplay").textContent = user.lastname || "LUI";
                document.getElementById("firstNameDisplay").textContent = user.firstname || "PATRICIA";
                document.getElementById("middleNameDisplay").textContent = user.middlename || "MARTINEZ";
                document.getElementById("dobDisplay").textContent = user.birthdate || "JAN 01, 1990";
                document.getElementById("addressDisplay").textContent = (user.address || "OODDGAF, ICT, DEV, NFA Bldg Vasra").toUpperCase();

                const fullName = `${user.firstname || 'PATRICIA'} ${(user.middlename ? user.middlename.charAt(0)+'.' : 'M.')} ${user.lastname || 'LUI'}`;
                document.getElementById("fullNameDisplay").textContent = fullName;

                // FIXED: Convert to string to prevent slice errors
                const natId = String(user.national_id || "8987 6543 526");
                document.getElementById("national_id_display").textContent = natId;
                
                // Safer slicing with fallback
                const lastSix = natId.slice(-6) || "••526";
                document.getElementById("stat_national_id").textContent = lastSix;
                
                document.getElementById("stat_designation").textContent = user.designation_name || "ISA I";
                document.getElementById("stat_date_joined").textContent = (user.date_created || "2025").split(' ').pop() || "2025";
                document.getElementById("stat_office").textContent = user.office_name || "OODDGAF";

                // FIXED: Convert portal_id to string
                const portal = String(user.portal_id || "9199a873-28d6-4840-9272-24b7725ee118");
                document.getElementById("portal_id_display").textContent = portal.substring(0,8)+'...'+portal.slice(-3);
                document.getElementById("portal_id_mini").textContent = portal.substring(0,4)+'...'+portal.slice(-3);
                
                // FIXED: Remove spaces for mini display
                const natIdNoSpaces = natId.replace(/\s/g, '');
                document.getElementById("national_id_mini").textContent = natIdNoSpaces.slice(0,4)+'...'+natIdNoSpaces.slice(-3);

                document.getElementById("office_display").textContent = user.office_name || "OODDGAF";
                document.getElementById("division_display").textContent = user.division_name || "ICT";
                document.getElementById("unit_display").textContent = user.unit_name || "Dev";
                document.getElementById("designation_display").textContent = user.designation_name || "ISA I";
                document.getElementById("role_display").textContent = user.role || "USER";

                document.getElementById("emailDisplay").textContent = user.email || "patricia.lui@arta.gov.ph";
                document.getElementById("contactDisplay").textContent = user.contact || "+63 (2) 8790 1234";
                document.getElementById("date_created_display").textContent = user.date_created || "October 20, 2025";
                document.getElementById("officeLocation").textContent = user.office_location || "NFA Bldg, Quezon City";
                document.getElementById("lastUpdate").textContent = user.last_update || "March 10, 2026";
                document.getElementById("footerDate").textContent = new Date().getFullYear();

            } catch (err) {
                console.error("Profile Load Error:", err);
            }
        }

        window.addEventListener("DOMContentLoaded", loadUserProfile);
    </script>

    <?php include_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>