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

        /* === FIXED HEADER === */
        .header-bar {
            position: fixed;
            top: 0;
            right: 0;
            left: 250px;
            height: 70px;
            background: #002b7f !important;
            color: white;
            display: flex;
            align-items: center;
            padding: 0 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            z-index: 1000;
        }

        .header-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: white;
        }

        .header-title i {
            color: white;
        }

        /* === SIDEBAR OFFSET + FIXED HEADER AVOID OVERLAP === */
        .main-content {
            margin-left: 250px;
            padding: 20px 35px 35px 35px;
            margin-top: 70px;
            transition: all 0.2s;
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
            min-width: 0;
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
            flex-shrink: 0;
        }

        .stat-content {
            min-width: 0;
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

        .btn-edit-profile {
            background: #002b7f;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 8px 14px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .btn-edit-profile:hover {
            background: #001f5c;
            color: white;
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
            .header-bar {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
                padding: 15px;
                margin-top: 70px;
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
                white-space: normal;
            }
            
            .stat-content p {
                font-size: 0.85rem;
                white-space: normal;
            }
            
            .grid-2col {
                grid-template-columns: 1fr;
            }
            
            .avatar-large {
                flex-direction: column;
                text-align: center;
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
    <?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

    <!-- SINGLE FIXED HEADER -->
    <div class="header-bar">
        <div class="header-title">
            <i class="fa-solid fa-id-card me-2"></i>
            My National ID
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

            <div class="d-flex gap-2 align-items-center flex-wrap">
                <button type="button" class="btn btn-edit-profile" onclick="openEditProfileModal()">
                    <i class="fa-solid fa-pen-to-square me-1"></i> Edit Profile
                </button>

                <div class="badge-verified">
                    <i class="fa-solid fa-check-circle" style="color:#1e7e34;"></i> 
                    verified ARTA account
                </div>
            </div>
        </div>

        <!-- stat cards - all fields start empty -->
        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-id-card"></i></div>
                <div class="stat-content">
                    <h4 id="stat_national_id"></h4>
                    <p>National ID (last digits)</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-briefcase"></i></div>
                <div class="stat-content">
                    <h4 id="stat_designation"></h4>
                    <p>Designation</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-calendar-check"></i></div>
                <div class="stat-content">
                    <h4 id="stat_date_joined"></h4>
                    <p>Date created</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-building"></i></div>
                <div class="stat-content">
                    <h4 id="stat_office"></h4>
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
                            <h3 id="fullNameDisplay"></h3>
                            <p><i class="fa-solid fa-location-dot" style="color:#004e9e;"></i> <span id="officeLocation"></span></p>
                        </div>
                    </div>
                    <!-- quick id row -->
                    <div class="quick-id-row">
                        <span><i class="fa-regular fa-id-card"></i> Portal ID: <strong id="portal_id_mini"></strong></span>
                        <span class="text-muted">|</span>
                        <span><i class="fa-regular fa-id-card"></i> National ID: <strong id="national_id_mini"></strong></span>
                    </div>
                </div>

                <!-- WORK ASSIGNMENT card -->
                <div class="info-card">
                    <div class="card-title"><i class="fa-solid fa-sitemap"></i> Work assignment</div>
                    <div class="assignment-block">
                        <div class="assignment-item">
                            <span class="assignment-label">Office</span>
                            <span class="assignment-value" id="office_display"></span>
                        </div>
                        <div class="assignment-item">
                            <span class="assignment-label">Division</span>
                            <span class="assignment-value" id="division_display"></span>
                        </div>
                        <div class="assignment-item">
                            <span class="assignment-label">Unit / section</span>
                            <span class="assignment-value" id="unit_display"></span>
                        </div>
                        <div class="assignment-item">
                            <span class="assignment-label">Designation</span>
                            <span class="assignment-value" id="designation_display"></span>
                        </div>
                        <div class="assignment-item">
                            <span class="assignment-label">Role in system</span>
                            <span class="assignment-value" id="role_display"></span>
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
                            <span class="detail-value" id="lastNameDisplay"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">First name</span>
                            <span class="detail-value" id="firstNameDisplay"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Middle name</span>
                            <span class="detail-value" id="middleNameDisplay"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Birthdate</span>
                            <span class="detail-value" id="dobDisplay"></span>
                        </div>
                        <div class="detail-item" style="grid-column: span 2;">
                            <span class="detail-label">Complete address</span>
                            <span class="detail-value" id="addressDisplay"></span>
                        </div>
                    </div>
                </div>

                <!-- CONTACT & SYSTEM INFO -->
                <div class="info-card">
                    <div class="card-title"><i class="fa-solid fa-address-book"></i> Contact & system</div>
                    <div class="grid-1col">
                        <div class="contact-chip">
                            <i class="fa-solid fa-envelope"></i> <span id="emailDisplay"></span>
                        </div>
                        <div class="contact-chip">
                            <i class="fa-solid fa-phone"></i> <span id="contactDisplay"></span>
                        </div>
                        <div class="grid-2col" style="margin-top: 6px;">
                            <div class="detail-item">
                                <span class="detail-label">Portal UUID</span>
                                <span class="detail-value" id="portal_id_display"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">National ID (full)</span>
                                <span class="detail-value" id="national_id_display"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Date created</span>
                                <span class="detail-value" id="date_created_display"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Last update</span>
                                <span class="detail-value" id="lastUpdate"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- extra note -->
                <div class="footer-note">
                    <span><i class="fa-regular fa-clock"></i> employee since <span id="employeeSince"></span></span>
                    <span><i class="fa-solid fa-lock"></i> secured</span>
                </div>
            </div>
        </div>

        <!-- small footer outside columns -->
        <div style="text-align: right; margin-top: 20px; color:#4d6f8f; font-size:0.85rem;">
            <i class="fa-solid fa-circle-check" style="color:#2b6c2b;"></i> Verified · <span id="footerDate"></span>
        </div>
    </div>

    <div class="modal fade" id="editProfileModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title">
                        <i class="fa-solid fa-user-pen me-2"></i> Edit Profile
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-info small">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        You can now update your name, National ID, and work assignment details here.
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small">First Name</label>
                            <input type="text" id="editFirstName" class="form-control" placeholder="First name">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small">Last Name</label>
                            <input type="text" id="editLastName" class="form-control" placeholder="Last name">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small">Middle Name</label>
                            <input type="text" id="editMiddleName" class="form-control" placeholder="Middle name">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small">National ID</label>
                            <input type="text" id="editNationalId" class="form-control" placeholder="National ID digits">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small">Email</label>
                        <input type="email" id="editEmail" class="form-control" placeholder="name@arta.gov.ph">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small">Contact Number</label>
                        <input type="text" id="editContact" class="form-control" placeholder="+63...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small">Complete Address</label>
                        <textarea id="editAddress" class="form-control" rows="3" placeholder="Complete address"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small">Office Location</label>
                        <input type="text" id="editOfficeLocation" class="form-control" placeholder="Office location">
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small">Office</label>
                            <select id="editOffice" class="form-select">
                                <option value="">Select office</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small">Division</label>
                            <select id="editDivision" class="form-select">
                                <option value="">Select division</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small">Unit</label>
                            <select id="editUnit" class="form-select">
                                <option value="">Select unit</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small">Designation</label>
                            <select id="editDesignation" class="form-select">
                                <option value="">Select designation</option>
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-warning small mt-3 mb-0">
                        <i class="fa-solid fa-lock-open me-1"></i>
                        Portal ID and Role remain read-only.
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-sm btn-primary" onclick="saveProfile()">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js" crossorigin="anonymous"></script>

    <script>
        let currentProfileUser = {};
        let csrfTokenCache = null;

        function setText(id, value, fallback = '-') {
            const el = document.getElementById(id);
            if (el) {
                el.textContent = value || fallback;
            }
        }

        function safeString(value, fallback = '') {
            return String(value ?? fallback);
        }

        async function fetchLookupData(url, key) {
            const res = await fetch(url, {
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await res.json();

            if (!res.ok || data.status !== 200 || !data.data || !Array.isArray(data.data[key])) {
                throw new Error(data.message || 'Unable to load profile options.');
            }

            return data.data[key];
        }

        function populateSelectOptions(selectId, items, valueKey, labelKey, selectedValue, placeholder) {
            const select = document.getElementById(selectId);

            if (!select) {
                return;
            }

            const normalizedSelectedValue = selectedValue == null || selectedValue === ''
                ? ''
                : String(selectedValue);

            select.innerHTML = `<option value="">${placeholder}</option>`;

            items.forEach(item => {
                const option = document.createElement('option');
                option.value = String(item[valueKey] ?? '');
                option.textContent = item[labelKey] ?? '-';

                if (option.value === normalizedSelectedValue) {
                    option.selected = true;
                }

                select.appendChild(option);
            });
        }

        async function loadOfficeOptions(selectedValue = '') {
            const offices = await fetchLookupData('../api/offices/get_offices.php?limit=1000&page=1', 'offices');
            populateSelectOptions('editOffice', offices, 'id', 'office_name', selectedValue, 'Select office');
        }

        async function loadDivisionOptions(officeId = '', selectedValue = '') {
            const query = officeId ? `?office_id=${encodeURIComponent(officeId)}&limit=1000&page=1` : '?limit=1000&page=1';
            const divisions = await fetchLookupData('../api/divisions/get_divisions.php' + query, 'divisions');
            populateSelectOptions('editDivision', divisions, 'id', 'division_name', selectedValue, 'Select division');
        }

        async function loadUnitOptions(divisionId = '', selectedValue = '') {
            const query = divisionId ? `?division_id=${encodeURIComponent(divisionId)}&limit=1000&page=1` : '?limit=1000&page=1';
            const units = await fetchLookupData('../api/units/get_units.php' + query, 'units');
            populateSelectOptions('editUnit', units, 'id', 'unit_name', selectedValue, 'Select unit');
        }

        async function loadDesignationOptions(selectedValue = '') {
            const designations = await fetchLookupData('../api/designations/get_designations.php?limit=1000&page=1', 'designations');
            populateSelectOptions('editDesignation', designations, 'id', 'designation_name', selectedValue, 'Select designation');
        }

        async function prepareProfileFormOptions() {
            const officeId = currentProfileUser.office_id || '';
            const divisionId = currentProfileUser.division_id || '';
            const unitId = currentProfileUser.unit_id || '';
            const designationId = currentProfileUser.designation_id || '';

            await loadOfficeOptions(officeId);
            await loadDivisionOptions(officeId, divisionId);
            await loadUnitOptions(divisionId, unitId);
            await loadDesignationOptions(designationId);
        }

        function getYearFromDate(value) {
            const str = safeString(value, '');
            if (!str) return '-';

            const match = str.match(/\b(19|20)\d{2}\b/);
            if (match) return match[0];

            return str.split(' ').pop() || '-';
        }

        async function getCsrfToken(forceRefresh = false) {
            if (!forceRefresh && csrfTokenCache) {
                return csrfTokenCache;
            }

            const res = await fetch('../api/auth/get_csrf.php', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            const data = await res.json();

            if (!res.ok || !data.token) {
                throw new Error(data.message || 'Unable to get CSRF token.');
            }

            csrfTokenCache = data.token;
            return csrfTokenCache;
        }

        async function openEditProfileModal() {
            document.getElementById('editFirstName').value = currentProfileUser.firstname || '';
            document.getElementById('editLastName').value = currentProfileUser.lastname || '';
            document.getElementById('editMiddleName').value = currentProfileUser.middlename || '';
            document.getElementById('editNationalId').value = currentProfileUser.national_id || '';
            document.getElementById('editEmail').value = currentProfileUser.email || '';
            document.getElementById('editContact').value = currentProfileUser.contact || currentProfileUser.contact_number || currentProfileUser.mobile_number || '';
            document.getElementById('editAddress').value = currentProfileUser.address || currentProfileUser.complete_address || '';
            document.getElementById('editOfficeLocation').value = currentProfileUser.office_location || currentProfileUser.location || '';

            try {
                await prepareProfileFormOptions();
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Profile options unavailable',
                    text: error.message || 'Unable to load assignment options.',
                    confirmButtonColor: '#002b7f'
                });
                return;
            }

            const modalEl = document.getElementById('editProfileModal');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }

        async function saveProfile() {
            const payload = {
                firstname: document.getElementById('editFirstName').value.trim(),
                lastname: document.getElementById('editLastName').value.trim(),
                middlename: document.getElementById('editMiddleName').value.trim(),
                national_id: document.getElementById('editNationalId').value.trim(),
                email: document.getElementById('editEmail').value.trim(),
                contact: document.getElementById('editContact').value.trim(),
                address: document.getElementById('editAddress').value.trim(),
                office_location: document.getElementById('editOfficeLocation').value.trim(),
                office_id: document.getElementById('editOffice').value,
                division_id: document.getElementById('editDivision').value,
                unit_id: document.getElementById('editUnit').value,
                designation_id: document.getElementById('editDesignation').value
            };

            try {
                const makeRequest = async token => fetch('../api/users/update_profile.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(payload),
                    credentials: 'same-origin'
                });

                let res = await makeRequest(await getCsrfToken());

                if (res.status === 419) {
                    csrfTokenCache = null;
                    res = await makeRequest(await getCsrfToken(true));
                }

                const rawText = await res.text();
                let result;

                try {
                    result = JSON.parse(rawText);
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Server Response Error',
                        html: `<pre style="text-align:left;white-space:pre-wrap;">${rawText || 'Empty response from server.'}</pre>`,
                        width: 700,
                        confirmButtonColor: '#002b7f'
                    });
                    return;
                }

                if (result.status === 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated',
                        text: result.message || 'Profile updated successfully.',
                        confirmButtonColor: '#002b7f'
                    });

                    if (document.activeElement) {
                        document.activeElement.blur();
                    }

                    const modal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
                    if (modal) {
                        modal.hide();
                    }

                    await loadUserProfile();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: result.title || 'Error',
                        text: result.message || 'Unable to update profile.',
                        confirmButtonColor: '#002b7f'
                    });
                }
            } catch (err) {
                console.error(err);

                Swal.fire({
                    icon: 'error',
                    title: 'Request Failed',
                    text: err.message || 'Failed to update profile.',
                    confirmButtonColor: '#002b7f'
                });
            }
        }

        async function loadUserProfile() {
            try {
                const res = await fetch("../api/users/get_profile.php", { credentials: 'same-origin' });
                const text = await res.text();
                let data = null;

                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error('Invalid get_profile.php response:', text);
                }

                let user = {};

                if (data && data.status === "success" && data.user) {
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
                        role: "ADMIN",
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

                currentProfileUser = user;

                const lastname = user.lastname || '-';
                const firstname = user.firstname || '-';
                const middlename = user.middlename || '';
                const middleInitial = middlename ? middlename.charAt(0) + '.' : '';

                setText("lastNameDisplay", lastname);
                setText("firstNameDisplay", firstname);
                setText("middleNameDisplay", middlename || '-');
                setText("dobDisplay", user.birthdate || '-');
                setText("addressDisplay", safeString(user.address || user.complete_address || '-').toUpperCase());

                const fullName = `${firstname} ${middleInitial} ${lastname}`.replace(/\s+/g, ' ').trim();
                setText("fullNameDisplay", fullName);

                const natId = safeString(user.national_id || '-');
                const natIdNoSpaces = natId.replace(/\s/g, '');
                setText("national_id_display", natId);
                setText("stat_national_id", natIdNoSpaces.length > 6 ? natIdNoSpaces.slice(-6) : natIdNoSpaces);
                setText("national_id_mini", natIdNoSpaces.length > 7 ? natIdNoSpaces.slice(0, 4) + '...' + natIdNoSpaces.slice(-3) : natIdNoSpaces);

                setText("stat_designation", user.designation_name || '-');
                setText("stat_date_joined", getYearFromDate(user.date_created));
                setText("stat_office", user.office_name || '-');

                const portal = safeString(user.portal_id || '-');
                setText("portal_id_display", portal.length > 12 ? portal.substring(0, 8) + '...' + portal.slice(-3) : portal);
                setText("portal_id_mini", portal.length > 8 ? portal.substring(0, 4) + '...' + portal.slice(-3) : portal);

                setText("office_display", user.office_name || '-');
                setText("division_display", user.division_name || '-');
                setText("unit_display", user.unit_name || '-');
                setText("designation_display", user.designation_name || '-');
                setText("role_display", user.role || '-');

                setText("emailDisplay", user.email || '-');
                setText("contactDisplay", user.contact || user.contact_number || user.mobile_number || '-');
                setText("date_created_display", user.date_created || '-');
                setText("officeLocation", user.office_location || user.location || '-');
                setText("lastUpdate", user.last_update || user.updated_at || user.date_updated || '-');
                setText("employeeSince", getYearFromDate(user.date_created));
                setText("footerDate", new Date().getFullYear());
            } catch (err) {
                console.error("Profile Load Error:", err);

                Swal.fire({
                    icon: 'error',
                    title: 'Profile Error',
                    text: 'Unable to load profile information.',
                    confirmButtonColor: '#002b7f'
                });
            }
        }

        document.getElementById('editProfileModal').addEventListener('hide.bs.modal', function() {
            if (this.contains(document.activeElement)) {
                document.activeElement.blur();
            }
        });

        document.getElementById('editOffice').addEventListener('change', async function() {
            const officeId = this.value;
            await loadDivisionOptions(officeId, '');
            populateSelectOptions('editUnit', [], 'id', 'unit_name', '', 'Select unit');
        });

        document.getElementById('editDivision').addEventListener('change', async function() {
            await loadUnitOptions(this.value, '');
        });

        window.addEventListener("DOMContentLoaded", loadUserProfile);
    </script>

    <?php include_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>