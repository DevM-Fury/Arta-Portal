<!DOCTYPE html>
<html lang="en">

<?php include_once __DIR__ . '/../includes/head.php'; ?>

<body>

    <?php include_once __DIR__ . '/../includes/sidebar_subadmin.php'; ?>

    <!-- Header -->
    <div class="header-bar">
        <div class="header-title">
            <i class="fa-solid fa-network-wired me-2"></i>
            Dashboard
        </div>
    </div>

    <!-- Main Content -->
    <div
        class="main-content"
        style="margin-left: 250px; padding-top: 80px;"
    >
        <div class="container-fluid mt-4">
            <div class="row" id="dashboardCards">
                <div
                    class="col-12 text-center text-muted"
                    id="loadingText"
                >
                    <div
                        class="spinner-border spinner-border-sm me-2"
                        role="status"
                    ></div>

                    Loading systems...
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>

    <script
        src="https://cdn.jsdelivr.net/npm/sweetalert2@11"
    ></script>

    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js"
        crossorigin="anonymous"
    ></script>

    <style>
        body {
            background: #f4f6f9;
        }

        /*
        |--------------------------------------------------------------------------
        | System Card
        |--------------------------------------------------------------------------
        */

        .system-card {
            cursor: pointer;
            height: 240px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            transition:
                transform 0.3s ease,
                box-shadow 0.3s ease,
                opacity 0.3s ease;
            position: relative;
            margin-bottom: 25px;
            padding: 20px;
            background: linear-gradient(
                135deg,
                #ffffff,
                #e9eff6
            );
            overflow: visible;
        }

        .system-card:not(.inactive-system):hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .system-card:not(.inactive-system):focus {
            outline: 3px solid rgba(13, 110, 253, 0.35);
            outline-offset: 3px;
        }

        .system-card.system-loading {
            pointer-events: none;
            opacity: 0.65;
        }

        /*
        |--------------------------------------------------------------------------
        | Logo and Icon
        |--------------------------------------------------------------------------
        */

        .system-logo {
            width: 70px;
            height: 70px;
            object-fit: contain;
            margin-bottom: 15px;
            border-radius: 12px;
        }

        .system-icon {
            font-size: 45px;
            color: #0d6efd;
            margin-bottom: 15px;
        }

        /*
        |--------------------------------------------------------------------------
        | Card Text
        |--------------------------------------------------------------------------
        */

        .card-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #333;
            word-break: break-word;
        }

        .role-badge {
            font-size: 13px;
            color: #6c757d;
            margin-top: 5px;
            font-weight: 500;
            display: inline-block;
            text-transform: uppercase;
        }

        /*
        |--------------------------------------------------------------------------
        | Status Badge
        |--------------------------------------------------------------------------
        */

        .status-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            font-size: 11px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 30px;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }

        .status-active {
            background: linear-gradient(
                45deg,
                #198754,
                #20c997
            );
        }

        .status-inactive {
            background: linear-gradient(
                45deg,
                #dc3545,
                #ff6b6b
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Inactive System
        |--------------------------------------------------------------------------
        */

        .system-card.inactive-system {
            pointer-events: none;
            opacity: 0.7;
            filter: grayscale(0.7);
            background: #f1f3f6;
            cursor: default;
        }

        .system-card.inactive-system:hover::after {
            content: attr(data-description);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform:
                translateX(-50%)
                translateY(-10px);
            background: #212529;
            color: #fff;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 13px;
            max-width: 250px;
            white-space: normal;
            word-wrap: break-word;
            z-index: 9999;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            pointer-events: none;
            line-height: 1.4;
        }

        .system-card.inactive-system:hover::before {
            content: "";
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            border-width: 6px;
            border-style: solid;
            border-color:
                transparent
                transparent
                #212529
                transparent;
            z-index: 9999;
        }

        /*
        |--------------------------------------------------------------------------
        | Responsive
        |--------------------------------------------------------------------------
        */

        @media (max-width: 991px) {
            .system-card {
                height: 220px;
                padding: 18px;
            }

            .system-logo {
                width: 60px;
                height: 60px;
            }

            .system-icon {
                font-size: 40px;
            }

            .card-title {
                font-size: 18px;
            }

            .role-badge {
                font-size: 12px;
            }

            .status-badge {
                font-size: 10px;
                padding: 4px 10px;
                top: 10px;
                left: 10px;
            }
        }

        @media (max-width: 767px) {
            .system-column {
                flex: 0 0 50%;
                max-width: 50%;
            }

            .system-card {
                height: auto;
                min-height: 200px;
                padding: 15px;
                margin-bottom: 20px;
            }

            .system-logo {
                width: 55px;
                height: 55px;
                margin-bottom: 12px;
            }

            .system-icon {
                font-size: 38px;
                margin-bottom: 12px;
            }

            .card-title {
                font-size: 16px;
                margin-bottom: 5px;
            }

            .role-badge {
                font-size: 11px;
            }

            .status-badge {
                font-size: 9px;
                padding: 3px 8px;
                top: 8px;
                left: 8px;
            }

            .main-content {
                margin-left: 0 !important;
                padding-top: 70px !important;
            }
        }

        @media (max-width: 480px) {
            .system-column {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .system-card {
                min-height: 180px;
                padding: 12px;
            }

            .system-logo {
                width: 50px;
                height: 50px;
                margin-bottom: 10px;
            }

            .system-icon {
                font-size: 35px;
                margin-bottom: 10px;
            }
        }

        @media (min-width: 1400px) {
            .system-column {
                flex: 0 0 20%;
                max-width: 20%;
            }
        }
    </style>

    <script>
        /*
        |--------------------------------------------------------------------------
        | Escape Text
        |--------------------------------------------------------------------------
        */

        function escapeHtml(value) {
            const element = document.createElement('div');

            element.textContent =
                value === null || value === undefined
                    ? ''
                    : String(value);

            return element.innerHTML;
        }

        /*
        |--------------------------------------------------------------------------
        | Fallback Icon
        |--------------------------------------------------------------------------
        */

        function getFallbackIcon(systemName) {
            const name = String(
                systemName || ''
            ).toLowerCase();

            if (
                name.includes('subadmin') ||
                name.includes('admin')
            ) {
                return 'fa-shield-halved';
            }

            if (
                name.includes('asset') ||
                name.includes('inventory') ||
                name.includes('stock')
            ) {
                return 'fa-boxes-stacked';
            }

            if (
                name.includes('document') ||
                name.includes('records')
            ) {
                return 'fa-folder-open';
            }

            if (
                name.includes('lto') ||
                name.includes('license')
            ) {
                return 'fa-id-card';
            }

            if (
                name.includes('hr') ||
                name.includes('human')
            ) {
                return 'fa-users';
            }

            if (
                name.includes('finance') ||
                name.includes('payroll')
            ) {
                return 'fa-peso-sign';
            }

            if (
                name.includes('portal') ||
                name.includes('gateway')
            ) {
                return 'fa-door-open';
            }

            return 'fa-gears';
        }

        /*
        |--------------------------------------------------------------------------
        | Parse JSON Response
        |--------------------------------------------------------------------------
        */

        async function parseJsonResponse(response) {
            const responseText = await response.text();

            if (!responseText.trim()) {
                return {
                    data: null,
                    rawText: ''
                };
            }

            try {
                return {
                    data: JSON.parse(responseText),
                    rawText: responseText
                };
            } catch (error) {
                console.error(
                    'Invalid JSON response:',
                    responseText
                );

                return {
                    data: null,
                    rawText: responseText
                };
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Redirect URL
        |--------------------------------------------------------------------------
        */

        function resolveRedirectUrl(redirectValue) {
            if (
                !redirectValue ||
                typeof redirectValue !== 'string'
            ) {
                throw new Error(
                    'The Portal did not return a redirect URL.'
                );
            }

            const redirectUrl = new URL(
                redirectValue.trim(),
                window.location.href
            );

            if (
                redirectUrl.protocol !== 'http:' &&
                redirectUrl.protocol !== 'https:'
            ) {
                throw new Error(
                    'The returned redirect URL is invalid.'
                );
            }

            return redirectUrl.href;
        }

        /*
        |--------------------------------------------------------------------------
        | Open Assigned System through SSO
        |--------------------------------------------------------------------------
        */

        async function goToSystem(
            systemId,
            cardElement = null
        ) {
            const normalizedSystemId = String(
                systemId || ''
            ).trim();

            console.log(
                'Opening system:',
                normalizedSystemId
            );

            if (!normalizedSystemId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Unable to open system',
                    text: 'Missing system ID.'
                });

                return;
            }

            if (cardElement) {
                cardElement.classList.add(
                    'system-loading'
                );
            }

            Swal.fire({
                title: 'Generating SSO token...',
                text: 'Please wait while your access is being prepared.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,

                didOpen: function () {
                    Swal.showLoading();
                }
            });

            try {
                const apiUrl =
                    '../api/sso/generate_token.php'
                    + '?system_id='
                    + encodeURIComponent(
                        normalizedSystemId
                    )
                    + '&_='
                    + Date.now();

                console.log(
                    'SSO API request:',
                    apiUrl
                );

                const response = await fetch(
                    apiUrl,
                    {
                        method: 'GET',
                        credentials: 'same-origin',
                        cache: 'no-store',

                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }
                );

                const parsed = await parseJsonResponse(
                    response
                );

                const data = parsed.data;

                console.log(
                    'SSO HTTP status:',
                    response.status
                );

                console.log(
                    'SSO response:',
                    data
                );

                if (!response.ok) {
                    const errorMessage =
                        data && data.message
                            ? data.message
                            : (
                                parsed.rawText ||
                                'SSO request failed with HTTP '
                                + response.status
                                + '.'
                            );

                    throw new Error(errorMessage);
                }

                if (
                    !data ||
                    data.status !== 'success'
                ) {
                    throw new Error(
                        data && data.message
                            ? data.message
                            : 'Failed to generate SSO token.'
                    );
                }

                const redirectUrl = resolveRedirectUrl(
                    data.redirect
                );

                console.log(
                    'Redirecting directly to AMS:',
                    redirectUrl
                );

                /*
                |--------------------------------------------------------------------------
                | Close SweetAlert before navigation
                |--------------------------------------------------------------------------
                |
                | Do not await another SweetAlert here. Redirect immediately after
                | the successful response to prevent the page from getting stuck.
                |
                */

                Swal.close();

                /*
                |--------------------------------------------------------------------------
                | Direct Navigation
                |--------------------------------------------------------------------------
                */

                window.location.replace(redirectUrl);

                /*
                 * Fallback in case replace() is interrupted by a browser extension
                 * or another script.
                 */
                window.setTimeout(function () {
                    if (
                        window.location.href !== redirectUrl
                    ) {
                        window.location.href = redirectUrl;
                    }
                }, 500);
            } catch (error) {
                console.error(
                    'SSO generation error:',
                    error
                );

                if (cardElement) {
                    cardElement.classList.remove(
                        'system-loading'
                    );
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Unable to open system',
                    text:
                        error.message ||
                        'An unexpected SSO error occurred.'
                });
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Replace Broken Logo
        |--------------------------------------------------------------------------
        */

        function replaceBrokenLogo(
            imageElement,
            systemName
        ) {
            if (!imageElement) {
                return;
            }

            const icon = document.createElement('i');

            icon.className =
                'fa-solid '
                + getFallbackIcon(systemName)
                + ' system-icon';

            imageElement.replaceWith(icon);
        }

        /*
        |--------------------------------------------------------------------------
        | Load Dashboard Systems
        |--------------------------------------------------------------------------
        */

        async function loadDashboardSystems() {
            const container = document.getElementById(
                'dashboardCards'
            );

            try {
                const apiUrl =
                    '../api/assignment/get_dashboard_systems.php'
                    + '?_='
                    + Date.now();

                const response = await fetch(
                    apiUrl,
                    {
                        method: 'GET',
                        credentials: 'same-origin',
                        cache: 'no-store',

                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }
                );

                const parsed = await parseJsonResponse(
                    response
                );

                const data = parsed.data;

                console.log(
                    'Dashboard Systems:',
                    data
                );

                if (!response.ok) {
                    const message =
                        data && data.message
                            ? data.message
                            : (
                                parsed.rawText ||
                                'Failed to load systems. HTTP '
                                + response.status
                                + '.'
                            );

                    throw new Error(message);
                }

                if (!Array.isArray(data)) {
                    throw new Error(
                        data && data.message
                            ? data.message
                            : 'Invalid dashboard systems response.'
                    );
                }

                container.innerHTML = '';

                if (data.length === 0) {
                    container.innerHTML = `
                        <div class="col-12 text-center text-muted py-5">
                            <i class="fa-solid fa-circle-info fa-2x mb-3"></i>
                            <div>No systems assigned.</div>
                        </div>
                    `;

                    return;
                }

                data.forEach(function (system) {
                    const systemId = String(
                        system.system_id || ''
                    ).trim();

                    const systemName = String(
                        system.system_name ||
                        'Unnamed System'
                    ).trim();

                    const role = String(
                        system.role || ''
                    ).trim();

                    const status = String(
                        system.status || ''
                    ).trim();

                    const active =
                        typeof system.is_active === 'boolean'
                            ? system.is_active
                            : status.toLowerCase() === 'active';

                    console.log(
                        'System card:',
                        {
                            system_id: systemId,
                            system_name: systemName,
                            role: role,
                            status: status,
                            is_active: active
                        }
                    );

                    const column = document.createElement(
                        'div'
                    );

                    column.className =
                        'col-md-3 mb-4 system-column';

                    const card = document.createElement(
                        'div'
                    );

                    card.className =
                        'system-card'
                        + (
                            active
                                ? ''
                                : ' inactive-system'
                        );

                    card.dataset.systemId = systemId;

                    if (active) {
                        card.setAttribute(
                            'role',
                            'button'
                        );

                        card.setAttribute(
                            'tabindex',
                            '0'
                        );

                        card.setAttribute(
                            'aria-label',
                            'Open ' + systemName
                        );
                    } else {
                        card.setAttribute(
                            'aria-disabled',
                            'true'
                        );

                        card.dataset.description =
                            String(
                                system.description ||
                                systemName +
                                ' is currently inactive.'
                            );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Status Badge
                    |--------------------------------------------------------------------------
                    */

                    const statusBadge =
                        document.createElement('span');

                    statusBadge.className =
                        'status-badge '
                        + (
                            active
                                ? 'status-active'
                                : 'status-inactive'
                        );

                    statusBadge.textContent =
                        active
                            ? 'Active'
                            : 'Inactive';

                    card.appendChild(statusBadge);

                    /*
                    |--------------------------------------------------------------------------
                    | System Logo
                    |--------------------------------------------------------------------------
                    */

                    const logoPath = String(
                        system.logo_path || ''
                    ).trim();

                    if (
                        logoPath !== '' &&
                        logoPath.toLowerCase() !== 'null'
                    ) {
                        let resolvedLogoPath = logoPath;

                        if (
                            !resolvedLogoPath.startsWith(
                                'http://'
                            ) &&
                            !resolvedLogoPath.startsWith(
                                'https://'
                            ) &&
                            !resolvedLogoPath.startsWith(
                                '../'
                            ) &&
                            !resolvedLogoPath.startsWith(
                                '/'
                            )
                        ) {
                            resolvedLogoPath =
                                '../' + resolvedLogoPath;
                        }

                        const image =
                            document.createElement('img');

                        image.src = encodeURI(
                            resolvedLogoPath
                        );

                        image.alt = systemName;

                        image.className =
                            'system-logo';

                        image.addEventListener(
                            'error',
                            function () {
                                replaceBrokenLogo(
                                    image,
                                    systemName
                                );
                            },
                            {
                                once: true
                            }
                        );

                        card.appendChild(image);
                    } else {
                        const icon =
                            document.createElement('i');

                        icon.className =
                            'fa-solid '
                            + getFallbackIcon(
                                systemName
                            )
                            + ' system-icon';

                        card.appendChild(icon);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | System Name
                    |--------------------------------------------------------------------------
                    */

                    const title =
                        document.createElement('h5');

                    title.className =
                        'card-title';

                    title.textContent =
                        systemName;

                    card.appendChild(title);

                    /*
                    |--------------------------------------------------------------------------
                    | Assigned Role
                    |--------------------------------------------------------------------------
                    */

                    const roleBadge =
                        document.createElement('div');

                    roleBadge.className =
                        'role-badge';

                    roleBadge.textContent =
                        role !== ''
                            ? role
                            : 'Assigned User';

                    card.appendChild(roleBadge);

                    /*
                    |--------------------------------------------------------------------------
                    | Click and Keyboard Events
                    |--------------------------------------------------------------------------
                    */

                    if (active) {
                        card.addEventListener(
                            'click',
                            function () {
                                console.log(
                                    'System card clicked:',
                                    {
                                        system_id:
                                            this.dataset.systemId,

                                        system_name:
                                            systemName,

                                        role:
                                            role
                                    }
                                );

                                goToSystem(
                                    this.dataset.systemId,
                                    this
                                );
                            }
                        );

                        card.addEventListener(
                            'keydown',
                            function (event) {
                                if (
                                    event.key === 'Enter' ||
                                    event.key === ' '
                                ) {
                                    event.preventDefault();

                                    goToSystem(
                                        this.dataset.systemId,
                                        this
                                    );
                                }
                            }
                        );
                    }

                    column.appendChild(card);
                    container.appendChild(column);
                });
            } catch (error) {
                console.error(
                    'Failed to load dashboard systems:',
                    error
                );

                container.innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-danger text-center">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            ${escapeHtml(
                                error.message ||
                                'Failed to load systems. Please try again.'
                            )}
                        </div>
                    </div>
                `;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Initialize Dashboard
        |--------------------------------------------------------------------------
        */

        window.addEventListener(
            'DOMContentLoaded',
            function () {
                console.log(
                    'SubAdmin dashboard initialized.'
                );

                loadDashboardSystems();
            }
        );
    </script>

    <?php include_once __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>