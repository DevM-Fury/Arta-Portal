<?php
// pages/registration.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARTA – Registration</title>

    <!-- Bootstrap + SweetAlert + Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* === CLEAN, PROFESSIONAL, TWO-COLUMN DESIGN === */
        body {
            background: #f8fafc;
            font-family: 'Inter', -apple-system, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
            margin: 0;
        }
        .card {
            max-width: 950px;
            width: 100%;
            background: white;
            border: none;
            border-radius: 24px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.03);
            padding: 35px 35px 30px;
        }
        .logo {
            text-align: center;
            margin-bottom: 25px;
        }
        .logo i {
            width: 70px;
            height: 70px;
            background: linear-gradient(145deg, #1a56db, #2563eb);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            color: white;
            font-size: 28px;
            box-shadow: 0 6px 14px rgba(26,86,219,0.15);
        }
        .logo h3 {
            font-weight: 600;
            color: #111827;
            margin-bottom: 4px;
        }
        .logo p {
            color: #6b7280;
            font-size: 0.9rem;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 15px;
        }
        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-label {
            font-weight: 500;
            color: #111827;
            margin-bottom: 6px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .form-label i {
            color: #1a56db;
            width: 18px;
        }
        .input-wrapper {
            position: relative;
        }
        .form-control, .form-select {
            width: 100%;
            padding: 12px 16px 12px 44px;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            font-size: 15px;
            transition: 0.2s;
            background: white;
            color: #111827;
            height: 48px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #1a56db;
            box-shadow: 0 0 0 4px rgba(26,86,219,0.08);
            outline: none;
        }
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
        }
        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
        }
        /* Green/Red validation */
        .form-control.valid, .form-select.valid {
            border-color: #10b981;
            background-color: #f0fdf4;
        }
        .form-control.invalid, .form-select.invalid {
            border-color: #ef4444;
            background-color: #fef2f2;
        }
        .validation-message {
            font-size: 0.8rem;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
            min-height: 20px;
        }
        .validation-message.valid { color: #10b981; }
        .validation-message.invalid { color: #ef4444; }
        .validation-message.info { color: #6b7280; }
        .btn-submit {
            background: linear-gradient(to right, #1a56db, #2563eb);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            transition: 0.2s;
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            height: 52px;
        }
        .btn-submit:hover {
            background: linear-gradient(to right, #1e40af, #1a56db);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(26,86,219,0.2);
        }
        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        .login-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .login-link a {
            color: #1a56db;
            font-weight: 600;
            text-decoration: none;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #6b7280;
            font-size: 12px;
        }
        .loading-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(2px);
            display: none;
            align-items: center;
            justify-content: center;
            border-radius: 24px;
            z-index: 1000;
        }
    </style>
</head>
<body>
<div class="card position-relative">
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-border text-primary" style="width:3rem;height:3rem;"></div>
    </div>

    <div class="logo">
        <i class="fas fa-shield-alt"></i>
        <h3>CREATE ACCOUNT</h3>
        <p>ARTA Portal – Government Personnel</p>
    </div>

    <form id="registrationForm">
        <!-- TWO COLUMN LAYOUT -->
        <div class="form-grid">
            <!-- LEFT COLUMN: Personal + Password -->
            <div>
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-user"></i> First Name</label>
                    <div class="input-wrapper">
                        <input type="text" id="firstname" name="firstname" class="form-control" placeholder="Juan" required>
                        <i class="fas fa-user input-icon"></i>
                    </div>
                    <div id="firstnameFeedback" class="validation-message info"><i class="fas fa-info-circle"></i> Enter first name</div>
                </div>
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-user"></i> Last Name</label>
                    <div class="input-wrapper">
                        <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Dela Cruz" required>
                        <i class="fas fa-user input-icon"></i>
                    </div>
                    <div id="lastnameFeedback" class="validation-message info"><i class="fas fa-info-circle"></i> Enter last name</div>
                </div>
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-id-card"></i> National ID</label>
                    <div class="input-wrapper">
                        <input type="text" id="national_id" name="national_id" class="form-control" placeholder="123456789012" required>
                        <i class="fas fa-id-card input-icon"></i>
                    </div>
                    <div id="national_idFeedback" class="validation-message info"><i class="fas fa-info-circle"></i> 12 digits</div>
                </div>
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-phone"></i> Contact</label>
                    <div class="input-wrapper">
                        <input type="text" id="contact" name="contact" class="form-control" placeholder="09123456789" required>
                        <i class="fas fa-phone input-icon"></i>
                    </div>
                    <div id="contactFeedback" class="validation-message info"><i class="fas fa-info-circle"></i> 11 digits, start 9</div>
                </div>
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-key"></i> Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Create password" required>
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="password-toggle" id="passwordToggle"><i class="fas fa-eye"></i></button>
                    </div>
                    <div id="passwordFeedback" class="validation-message info"><i class="fas fa-info-circle"></i> Min 6 alphanumeric</div>
                </div>
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-key"></i> Confirm Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm password" required>
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="password-toggle" id="confirmPasswordToggle"><i class="fas fa-eye"></i></button>
                    </div>
                    <div id="confirm_passwordFeedback" class="validation-message info"><i class="fas fa-info-circle"></i> Re-enter password</div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Email + Org -->
            <div>
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-envelope"></i> Government Email</label>
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email" class="form-control" placeholder="juan.delacruz@arta.gov.ph" required>
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                    <div id="emailFeedback" class="validation-message info"><i class="fas fa-info-circle"></i> @arta.gov.ph only</div>
                </div>
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-building"></i> Office</label>
                    <div class="input-wrapper">
                        <select id="officeSelect" name="office_id" class="form-select" required>
                            <option value="">Select Office</option>
                        </select>
                        <i class="fas fa-building input-icon"></i>
                    </div>
                    <div id="officeFeedback" class="validation-message info"><i class="fas fa-info-circle"></i> Select office</div>
                </div>
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-sitemap"></i> Division</label>
                    <div class="input-wrapper">
                        <select id="divisionSelect" name="division_id" class="form-select" required>
                            <option value="">Select Division</option>
                        </select>
                        <i class="fas fa-sitemap input-icon"></i>
                    </div>
                    <div id="divisionFeedback" class="validation-message info"><i class="fas fa-info-circle"></i> Select division</div>
                </div>
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-cubes"></i> Unit</label>
                    <div class="input-wrapper">
                        <select id="unitSelect" name="unit_id" class="form-select" required>
                            <option value="">Select Unit</option>
                        </select>
                        <i class="fas fa-cubes input-icon"></i>
                    </div>
                    <div id="unitFeedback" class="validation-message info"><i class="fas fa-info-circle"></i> Select unit</div>
                </div>
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-user-tag"></i> Designation</label>
                    <div class="input-wrapper">
                        <select id="designationSelect" name="designation_id" class="form-select" required>
                            <option value="">Select Designation</option>
                        </select>
                        <i class="fas fa-user-tag input-icon"></i>
                    </div>
                    <div id="designationFeedback" class="validation-message info"><i class="fas fa-info-circle"></i> Select designation</div>
                </div>
            </div>
        </div>

        <!-- Terms & Submit -->
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="termsCheck" required>
            <label class="form-check-label" for="termsCheck" style="color:#6b7280;">
                I agree to the <a href="#" style="color:#1a56db;">Terms of Service</a>
            </label>
        </div>
        <button type="submit" class="btn-submit" id="submitBtn">
            <i class="fas fa-user-plus"></i> Submit Access Request
        </button>
    </form>

    <div class="login-link">
        Already have an account? <a href="login.php"><i class="fas fa-sign-in-alt me-1"></i>Login</a>
    </div>
    <div class="footer">
        © 2024 Anti-Red Tape Authority
    </div>
</div>

<script>
    // ============================================================
    // 1. Password Toggle
    // ============================================================
    function togglePassword(btnId, inputId) {
        document.getElementById(btnId).addEventListener('click', function() {
            let inp = document.getElementById(inputId);
            let type = inp.type === 'password' ? 'text' : 'password';
            inp.type = type;
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
    }
    togglePassword('passwordToggle', 'password');
    togglePassword('confirmPasswordToggle', 'confirm_password');

    // ============================================================
    // 2. Real-time Validation (Green/Red)
    // ============================================================
    function updateValidation(id, isValid, msg) {
        let el = document.getElementById(id);
        let fb = document.getElementById(id + 'Feedback');
        if (!el || !fb) return;
        el.classList.remove('valid','invalid');
        fb.classList.remove('valid','invalid','info');
        if (isValid === true) {
            el.classList.add('valid');
            fb.classList.add('valid');
            fb.innerHTML = `<i class="fas fa-check-circle"></i> ${msg}`;
        } else if (isValid === false) {
            el.classList.add('invalid');
            fb.classList.add('invalid');
            fb.innerHTML = `<i class="fas fa-times-circle"></i> ${msg}`;
        } else {
            fb.classList.add('info');
            fb.innerHTML = `<i class="fas fa-info-circle"></i> ${msg}`;
        }
    }
    function updateDropdownValidation(selectId, val, msg) {
        let el = document.getElementById(selectId);
        let base = selectId.replace('Select','');
        let fb = document.getElementById(base + 'Feedback');
        if (!el || !fb) return;
        el.classList.remove('valid','invalid');
        fb.classList.remove('valid','invalid','info');
        if (val) {
            el.classList.add('valid');
            fb.classList.add('valid');
            fb.innerHTML = `<i class="fas fa-check-circle"></i> ${msg}`;
        } else {
            fb.classList.add('info');
            fb.innerHTML = `<i class="fas fa-info-circle"></i> ${msg}`;
        }
    }

    // Attach input listeners
    document.getElementById('firstname').addEventListener('input', function() {
        let v = this.value.trim();
        updateValidation('firstname', v.length>=2, v.length>=2 ? 'Valid' : 'Min 2 chars');
    });
    document.getElementById('lastname').addEventListener('input', function() {
        let v = this.value.trim();
        updateValidation('lastname', v.length>=2, v.length>=2 ? 'Valid' : 'Min 2 chars');
    });
    document.getElementById('national_id').addEventListener('input', function() {
        let v = this.value.replace(/\D/g,'');
        if (v.length>12) v = v.slice(0,12);
        this.value = v;
        updateValidation('national_id', /^\d{12}$/.test(v), /^\d{12}$/.test(v) ? 'Valid' : '12 digits');
    });
    document.getElementById('contact').addEventListener('input', function() {
        let v = this.value.replace(/\D/g,'');
        if (v.length>11) v = v.slice(0,11);
        this.value = v;
        updateValidation('contact', /^9\d{10}$/.test(v), /^9\d{10}$/.test(v) ? 'Valid' : '11 digits, start 9');
    });
    document.getElementById('email').addEventListener('input', function() {
        let v = this.value;
        updateValidation('email', v.endsWith('@arta.gov.ph'), v.endsWith('@arta.gov.ph') ? 'Valid' : '@arta.gov.ph only');
    });
    document.getElementById('password').addEventListener('input', function() {
        let v = this.value;
        let ok = v.length>=6 && /^[a-zA-Z0-9]+$/.test(v);
        updateValidation('password', ok, ok ? 'Strong' : 'Min 6 alphanumeric');
        checkPasswordMatch();
    });
    document.getElementById('confirm_password').addEventListener('input', checkPasswordMatch);
    function checkPasswordMatch() {
        let p = document.getElementById('password').value;
        let c = document.getElementById('confirm_password').value;
        if (!c) updateValidation('confirm_password', null, 'Re-enter password');
        else updateValidation('confirm_password', p===c, p===c ? 'Match' : 'Do not match');
    }

    // ============================================================
    // 3. API CONFIGURATION – pure API, no mock data
    // ============================================================
    const API_BASE = '../api/';
    const loadingOverlay = document.getElementById('loadingOverlay');
    const submitBtn = document.getElementById('submitBtn');
    let dropdownsLoaded = false;

    // Helper: extract array from backend JSON response
    function extractArray(data, key) {
        if (data.data && data.data[key]) return data.data[key];
        if (data[key]) return data[key];
        if (Array.isArray(data)) return data;
        if (data.data && Array.isArray(data.data)) return data.data;
        return [];
    }

    // Populate select element and set dataset attributes for filtering
    function populateSelect(selectId, items, textKey, extraAttr = null) {
        let sel = document.getElementById(selectId);
        sel.innerHTML = '<option value="">Select ' + selectId.replace('Select','') + '</option>';
        items.forEach(it => {
            if (it && it.id) {
                let opt = document.createElement('option');
                opt.value = it.id;
                opt.textContent = it[textKey] || it.name || `Item ${it.id}`;
                if (extraAttr && it[extraAttr] !== undefined) {
                    // Convert to camelCase dataset property: office_id -> officeId, division_id -> divisionId
                    let attrName = extraAttr === 'office_id' ? 'officeId' : 
                                  (extraAttr === 'division_id' ? 'divisionId' : extraAttr);
                    opt.dataset[attrName] = it[extraAttr];
                }
                sel.appendChild(opt);
            }
        });
    }

    // ============================================================
    // 4. DEPENDENT FILTERS – FIXED: use dataset.officeId and dataset.divisionId
    // ============================================================
    function setupDependentFilters() {
        let office = document.getElementById('officeSelect');
        let division = document.getElementById('divisionSelect');
        let unit = document.getElementById('unitSelect');

        office.addEventListener('change', function() {
            let offId = this.value;
            let divOpts = division.querySelectorAll('option:not([value=""])');
            let hasVis = false;
            divOpts.forEach(opt => {
                // ✅ TAMA: dataset.officeId (camelCase) – ito ang itinakda sa populateSelect
                let match = offId && opt.dataset.officeId == offId;
                opt.style.display = match ? 'block' : 'none';
                opt.disabled = !match;
                if (match) hasVis = true;
            });
            division.value = '';
            unit.value = '';
            division.disabled = !offId || !hasVis;
            updateDropdownValidation('officeSelect', offId, 'Office selected');
        });

        division.addEventListener('change', function() {
            let divId = this.value;
            let unitOpts = unit.querySelectorAll('option:not([value=""])');
            let hasVis = false;
            unitOpts.forEach(opt => {
                // ✅ TAMA: dataset.divisionId (camelCase)
                let match = divId && opt.dataset.divisionId == divId;
                opt.style.display = match ? 'block' : 'none';
                opt.disabled = !match;
                if (match) hasVis = true;
            });
            unit.value = '';
            unit.disabled = !divId || !hasVis;
            updateDropdownValidation('divisionSelect', divId, 'Division selected');
        });

        unit.addEventListener('change', function() {
            updateDropdownValidation('unitSelect', this.value, 'Unit selected');
        });

        document.getElementById('designationSelect').addEventListener('change', function() {
            updateDropdownValidation('designationSelect', this.value, 'Designation selected');
        });

        // Initial validation messages
        updateDropdownValidation('officeSelect', null, 'Select your office');
        updateDropdownValidation('divisionSelect', null, 'Select your division');
        updateDropdownValidation('unitSelect', null, 'Select your unit');
        updateDropdownValidation('designationSelect', null, 'Select your designation');
    }

    // ============================================================
    // 5. LOAD DROPDOWNS – pure API, no fallback
    // ============================================================
    async function loadDropdowns() {
        loadingOverlay.style.display = 'flex';
        submitBtn.disabled = true;

        try {
            // Test connection
            let test = await fetch(API_BASE + 'offices/get_offices.php?limit=1', { method: 'HEAD' });
            if (!test.ok) throw new Error(`Offices API not reachable (HTTP ${test.status})`);

            // Fetch all dropdown data in parallel
            let [officesRes, divisionsRes, unitsRes, designationsRes] = await Promise.all([
                fetch(API_BASE + 'offices/get_offices.php?limit=1000'),
                fetch(API_BASE + 'divisions/get_divisions.php?limit=1000'),
                fetch(API_BASE + 'units/get_units.php?limit=1000'),
                fetch(API_BASE + 'designations/get_designations.php?limit=1000')
            ]);

            if (!officesRes.ok) throw new Error(`Offices API failed (${officesRes.status})`);
            if (!divisionsRes.ok) throw new Error(`Divisions API failed (${divisionsRes.status})`);
            if (!unitsRes.ok) throw new Error(`Units API failed (${unitsRes.status})`);
            if (!designationsRes.ok) throw new Error(`Designations API failed (${designationsRes.status})`);

            let offices = await officesRes.json();
            let divisions = await divisionsRes.json();
            let units = await unitsRes.json();
            let designations = await designationsRes.json();

            // Extract arrays using correct keys
            let officesArr = extractArray(offices, 'offices');
            let divisionsArr = extractArray(divisions, 'divisions');
            let unitsArr = extractArray(units, 'units');
            let designationsArr = extractArray(designations, 'designations');

            // Populate dropdowns – use exact column names from backend
            populateSelect('officeSelect', officesArr, 'office_name');
            populateSelect('divisionSelect', divisionsArr, 'division_name', 'office_id');
            populateSelect('unitSelect', unitsArr, 'unit_name', 'division_id');
            populateSelect('designationSelect', designationsArr, 'designation_name');

            // Enable dependent filters
            setupDependentFilters();

            dropdownsLoaded = true;
            submitBtn.disabled = false;
            loadingOverlay.style.display = 'none';

        } catch (error) {
            console.error('API Load Error:', error);
            loadingOverlay.style.display = 'none';
            submitBtn.disabled = true;
            dropdownsLoaded = false;

            Swal.fire({
                icon: 'error',
                title: 'Cannot load form data',
                html: `<p>Unable to connect to the server or API error.</p>
                       <p style="font-size:0.9rem; margin-top:10px; color:#6b7280;">
                           <strong>Error:</strong> ${error.message}<br>
                           API Path: <code>${API_BASE}</code><br><br>
                           ✅ Frontend configuration is correct.<br>
                           ⚠️ Please ensure your <strong>backend API</strong> is updated to match the database schema.
                       </p>`,
                confirmButtonText: 'OK',
                confirmButtonColor: '#1a56db',
                background: 'white'
            });
        }
    }

    // Start loading on page load
    document.addEventListener('DOMContentLoaded', loadDropdowns);

    // ============================================================
    // 6. Form Submission – real submit to backend
    // ============================================================
    document.getElementById('registrationForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        if (!dropdownsLoaded) {
            Swal.fire({
                icon: 'warning',
                title: 'Form not ready',
                text: 'Please wait for the form data to finish loading.',
                confirmButtonColor: '#1a56db'
            });
            return;
        }

        // Basic validations
        if (!document.getElementById('email').value.endsWith('@arta.gov.ph')) {
            Swal.fire({ icon: 'error', title: 'Invalid Email', text: 'Only @arta.gov.ph allowed' });
            return;
        }
        if (document.getElementById('password').value !== document.getElementById('confirm_password').value) {
            Swal.fire({ icon: 'error', title: 'Passwords do not match' });
            return;
        }
        if (!document.getElementById('termsCheck').checked) {
            Swal.fire({ icon: 'warning', title: 'Terms required', text: 'Please agree to the Terms of Service' });
            return;
        }

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

        const btn = document.getElementById('submitBtn');
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        btn.disabled = true;
        loadingOverlay.style.display = 'flex';

        try {
            const res = await fetch(API_BASE + 'auth/register.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });
            const text = await res.text();
            let data;
            try { data = JSON.parse(text); } catch { throw new Error('Invalid server response'); }

            loadingOverlay.style.display = 'none';
            btn.innerHTML = original;
            btn.disabled = false;

            if (res.ok && (data.status === 201 || data.status === 200 || data.status === 'success')) {
                Swal.fire({
                    icon: 'success',
                    title: 'Request Submitted',
                    html: `<p>${data.message || 'Your access request has been sent.'}</p>
                           ${data.data?.portal_id ? `<p><strong>Portal ID:</strong> ${data.data.portal_id}</p>` : ''}`,
                    confirmButtonText: 'Go to Login',
                    confirmButtonColor: '#1a56db'
                }).then(() => window.location.href = 'login.php');
            } else {
                Swal.fire({ icon: 'error', title: 'Registration Failed', text: data.message || 'Please try again' });
            }
        } catch (err) {
            loadingOverlay.style.display = 'none';
            btn.innerHTML = original;
            btn.disabled = false;
            Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Please try again.' });
        }
    });
</script>
</body>
</html>