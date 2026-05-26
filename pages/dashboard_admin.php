<?php
// ✅ Load your database connection first
require_once __DIR__ . '/../includes/configs.php';
global $conn; // Make sure $conn is accessible here

// ✅ Fetch dashboard data safely
$totalUsers = $conn->query("SELECT COUNT(*) FROM public.user_tbl WHERE role='user'")->fetchColumn();
$totalActiveUsers = $conn->query("SELECT COUNT(*) FROM public.user_tbl WHERE role ='admin'")->fetchColumn();
$totalSystems = $conn->query("SELECT COUNT(*) FROM public.systems WHERE status='Active'")->fetchColumn();
$totalOffices = $conn->query("SELECT COUNT(*) FROM public.offices WHERE status='Active'")->fetchColumn();
$totalDivisions = $conn->query("SELECT COUNT(*) FROM public.divisions WHERE division_status='Active'")->fetchColumn();
$totalUnits = $conn->query("SELECT COUNT(*) FROM public.units WHERE unit_status='Active'")->fetchColumn();
$artaManDescription = 'ARTA MAN stands for Administrative Response, Tracking, and Analytics Management Assistant Nexus. He is the command-side intelligence companion of your portal, translating live APIs into fast answers, surfacing operational counts, and guiding admins to the right module without leaving the dashboard.';
?>

<!DOCTYPE html>
<html lang="en">

<!-- ✅ Head include -->
<?php include_once __DIR__ . '/../includes/head.php'; ?>

<style>
    /* Dashboard Styles */
    .header-bar {
        background: linear-gradient(135deg, #040927ff 0%, #080220ff 100%);
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    
    .card-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        padding: 1rem;
    }
    
    .card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }
    
    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }
    
    .card:hover::before {
        transform: scaleX(1);
    }
    
    .card-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        display: inline-block;
        padding: 1rem;
        border-radius: 12px;
        background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
        color: white;
        transition: all 0.3s ease;
    }
    
    .card:hover .card-icon {
        transform: scale(1.1) rotate(5deg);
    }
    
    .card-title {
        color: #333;
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1rem;
        transition: color 0.3s ease;
    }
    
    .card:hover .card-title {
        color: #041048ff;
    }
    
    .display-6 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2d3748;
        margin: 0;
        transition: all 0.3s ease;
    }
    
    .card:hover .display-6 {
        color: #070b3fff;
        transform: scale(1.05);
    }
    
    /* Hover effect for user dropdown */
    .header-user .fa-user {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .header-user .fa-user:hover {
        transform: scale(1.1);
        color: #f6d365;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-container {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .display-6 {
            font-size: 2rem;
        }
        
        .card-icon {
            font-size: 2rem;
            padding: 0.75rem;
        }
    }
    
    /* Loading animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .card {
        animation: fadeInUp 0.5s ease forwards;
        opacity: 0;
    }
    
    .card:nth-child(1) { animation-delay: 0.1s; }
    .card:nth-child(2) { animation-delay: 0.2s; }
    .card:nth-child(3) { animation-delay: 0.3s; }
    .card:nth-child(4) { animation-delay: 0.4s; }
    .card:nth-child(5) { animation-delay: 0.5s; }
    .card:nth-child(6) { animation-delay: 0.6s; }
    
    /* Different colors for each card icon */
    .card:nth-child(1) .card-icon {
        background: linear-gradient(135deg, #050b28ff 0%, #050b28ff 100%);
    }
    .card:nth-child(2) .card-icon {
        background: linear-gradient(135deg, #050b28ff 0%, #050b28ff 100%);
    }
    .card:nth-child(3) .card-icon {
        background: linear-gradient(135deg, #050b28ff 0%, #050b28ff 100%);
    }
    .card:nth-child(4) .card-icon {
        background: linear-gradient(135deg, #050b28ff 0%, #050b28ff 100%);
    }
    .card:nth-child(5) .card-icon {
        background: linear-gradient(135deg, #050b28ff 0%, #050b28ff 100%);
    }
    .card:nth-child(6) .card-icon {
        background: linear-gradient(135deg, #050b28ff 0%, #050b28ff 100%);
        color: #f7f4f4ff;
    }

    .arta-hub {
        position: relative;
        margin: 0 1rem 2rem;
        padding: 1.5rem;
        border-radius: 28px;
        background:
            radial-gradient(circle at top left, rgba(96, 165, 250, 0.22), transparent 28%),
            radial-gradient(circle at bottom right, rgba(8, 145, 178, 0.18), transparent 25%),
            linear-gradient(135deg, #041124 0%, #071b35 45%, #0b2545 100%);
        box-shadow: 0 18px 50px rgba(4, 17, 36, 0.25);
        overflow: hidden;
    }

    .arta-hub::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(120deg, rgba(255,255,255,0.05), transparent 35%, rgba(96,165,250,0.08) 65%, transparent);
        pointer-events: none;
    }

    .arta-grid {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: minmax(300px, 420px) minmax(320px, 1fr);
        gap: 1.5rem;
        align-items: stretch;
    }

    .arta-stage,
    .arta-chat {
        border: 1px solid rgba(148, 163, 184, 0.18);
        border-radius: 24px;
        backdrop-filter: blur(14px);
        background: rgba(255, 255, 255, 0.06);
    }

    .arta-stage {
        padding: 1.75rem;
        color: #e2e8f0;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 540px;
    }

    .arta-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        width: fit-content;
        padding: 0.45rem 0.85rem;
        border-radius: 999px;
        background: rgba(14, 165, 233, 0.14);
        color: #7dd3fc;
        font-size: 0.78rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .arta-title {
        margin: 1rem 0 0.4rem;
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: 0.04em;
        color: #f8fafc;
    }

    .arta-copy {
        color: rgba(226, 232, 240, 0.88);
        line-height: 1.7;
        margin-bottom: 1.25rem;
    }

    .arta-scope {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .arta-scope-item {
        padding: 0.9rem 1rem;
        border-radius: 18px;
        background: rgba(15, 23, 42, 0.55);
        color: #cbd5e1;
        font-size: 0.92rem;
        border: 1px solid rgba(125, 211, 252, 0.12);
    }

    .arta-scope-item strong {
        display: block;
        color: #f8fafc;
        margin-bottom: 0.2rem;
    }

    .arta-scene {
        position: relative;
        height: 260px;
        display: flex;
        align-items: center;
        justify-content: center;
        perspective: 1000px;
        margin: 1rem 0 1.5rem;
    }

    .arta-platform {
        position: absolute;
        bottom: 8px;
        width: 180px;
        height: 30px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(34, 211, 238, 0.55), rgba(8, 47, 73, 0.1) 70%);
        filter: blur(3px);
    }

    .arta-character {
        position: relative;
        width: 170px;
        height: 210px;
        transform-style: preserve-3d;
        animation: arta-float 4.2s ease-in-out infinite;
    }

    .arta-core,
    .arta-head,
    .arta-arm,
    .arta-leg,
    .arta-shadow-ring {
        position: absolute;
        transform-style: preserve-3d;
    }

    .arta-head {
        width: 82px;
        height: 82px;
        top: 4px;
        left: 44px;
        border-radius: 24px;
        background: linear-gradient(145deg, #dbeafe, #60a5fa 65%, #1d4ed8);
        box-shadow: inset -8px -8px 16px rgba(30, 64, 175, 0.55), 0 20px 25px rgba(2, 6, 23, 0.25);
    }

    .arta-head::before {
        content: '';
        position: absolute;
        inset: 18px 10px 22px;
        border-radius: 18px;
        background: linear-gradient(90deg, rgba(14,165,233,0.95), rgba(56,189,248,0.55));
        box-shadow: 0 0 18px rgba(34, 211, 238, 0.65);
    }

    .arta-head::after {
        content: '';
        position: absolute;
        width: 28px;
        height: 10px;
        left: 27px;
        bottom: 12px;
        border-radius: 999px;
        background: rgba(248, 250, 252, 0.8);
    }

    .arta-core {
        width: 108px;
        height: 100px;
        top: 78px;
        left: 31px;
        border-radius: 30px;
        background: linear-gradient(150deg, #93c5fd, #2563eb 70%, #0f172a);
        box-shadow: inset -10px -10px 18px rgba(15, 23, 42, 0.4), 0 18px 24px rgba(2, 6, 23, 0.24);
    }

    .arta-core::before {
        content: 'A';
        position: absolute;
        top: 28px;
        left: 37px;
        width: 34px;
        height: 34px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        background: linear-gradient(180deg, #f8fafc, #bae6fd);
        color: #0f172a;
        font-weight: 900;
        box-shadow: 0 0 18px rgba(186, 230, 253, 0.6);
    }

    .arta-arm {
        width: 28px;
        height: 92px;
        top: 84px;
        border-radius: 999px;
        background: linear-gradient(180deg, #bfdbfe, #2563eb);
        box-shadow: inset -6px -8px 10px rgba(15, 23, 42, 0.3);
    }

    .arta-arm.left {
        left: 8px;
        transform: rotate(16deg);
    }

    .arta-arm.right {
        right: 8px;
        transform: rotate(-16deg);
    }

    .arta-leg {
        width: 30px;
        height: 85px;
        top: 150px;
        border-radius: 999px;
        background: linear-gradient(180deg, #dbeafe, #1d4ed8);
        box-shadow: inset -6px -8px 10px rgba(15, 23, 42, 0.35);
    }

    .arta-leg.left {
        left: 52px;
        transform: rotate(4deg);
    }

    .arta-leg.right {
        right: 52px;
        transform: rotate(-4deg);
    }

    .arta-orb {
        position: absolute;
        width: 54px;
        height: 54px;
        top: 40px;
        right: 22px;
        border-radius: 50%;
        background: radial-gradient(circle at 30% 30%, #f8fafc 0%, #67e8f9 35%, #0ea5e9 70%, transparent 72%);
        box-shadow: 0 0 26px rgba(103, 232, 249, 0.9);
        animation: arta-orbit 3s linear infinite;
    }

    .arta-shadow-ring {
        width: 120px;
        height: 120px;
        top: 52px;
        left: 25px;
        border-radius: 50%;
        border: 1px solid rgba(125, 211, 252, 0.22);
        transform: rotateX(70deg);
    }

    .arta-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.65rem;
        margin-top: 1.25rem;
    }

    .arta-badge {
        padding: 0.55rem 0.9rem;
        border-radius: 999px;
        background: rgba(148, 163, 184, 0.12);
        color: #bfdbfe;
        border: 1px solid rgba(191, 219, 254, 0.18);
        font-size: 0.82rem;
    }

    .arta-chat {
        display: flex;
        flex-direction: column;
        min-height: 540px;
    }

    .arta-chat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        padding: 1.35rem 1.4rem 1rem;
        border-bottom: 1px solid rgba(148, 163, 184, 0.15);
        color: #e2e8f0;
    }

    .arta-chat-header h4 {
        margin: 0;
        font-weight: 700;
        color: #f8fafc;
    }

    .arta-chat-header p {
        margin: 0.35rem 0 0;
        color: rgba(226, 232, 240, 0.78);
    }

    .arta-status {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        color: #86efac;
        font-size: 0.82rem;
        white-space: nowrap;
    }

    .arta-status::before {
        content: '';
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: #22c55e;
        box-shadow: 0 0 12px rgba(34, 197, 94, 0.7);
    }

    .arta-messages {
        flex: 1;
        padding: 1.2rem 1.4rem;
        overflow-y: auto;
        max-height: 380px;
    }

    .arta-message {
        display: flex;
        gap: 0.8rem;
        margin-bottom: 1rem;
        align-items: flex-start;
    }

    .arta-message.user {
        flex-direction: row-reverse;
    }

    .arta-avatar {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        font-weight: 800;
    }

    .arta-message.bot .arta-avatar {
        background: linear-gradient(145deg, #38bdf8, #1d4ed8);
        color: #eff6ff;
        box-shadow: 0 12px 24px rgba(29, 78, 216, 0.25);
    }

    .arta-message.user .arta-avatar {
        background: linear-gradient(145deg, #0f172a, #1e293b);
        color: #f8fafc;
    }

    .arta-bubble {
        max-width: 80%;
        padding: 0.95rem 1rem;
        border-radius: 18px;
        line-height: 1.55;
        white-space: pre-line;
        font-size: 0.95rem;
    }

    .arta-message.bot .arta-bubble {
        background: rgba(15, 23, 42, 0.8);
        color: #e2e8f0;
        border: 1px solid rgba(96, 165, 250, 0.18);
    }

    .arta-message.user .arta-bubble {
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        color: #0f172a;
    }

    .arta-quick-actions {
        padding: 0 1.4rem 1rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.65rem;
    }

    .arta-chip {
        border: 1px solid rgba(125, 211, 252, 0.2);
        background: rgba(15, 23, 42, 0.62);
        color: #e2e8f0;
        padding: 0.55rem 0.85rem;
        border-radius: 999px;
        font-size: 0.84rem;
        transition: all 0.2s ease;
    }

    .arta-chip:hover {
        background: rgba(14, 165, 233, 0.18);
        color: #f8fafc;
        transform: translateY(-1px);
    }

    .arta-chat-form {
        padding: 1rem 1.4rem 1.4rem;
        border-top: 1px solid rgba(148, 163, 184, 0.15);
    }

    .arta-input-wrap {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        padding: 0.6rem;
        border-radius: 18px;
        background: rgba(15, 23, 42, 0.78);
        border: 1px solid rgba(125, 211, 252, 0.18);
    }

    .arta-input-wrap input {
        flex: 1;
        border: none;
        outline: none;
        background: transparent;
        color: #f8fafc;
        padding: 0.4rem 0.5rem;
    }

    .arta-input-wrap input::placeholder {
        color: rgba(226, 232, 240, 0.52);
    }

    .arta-send {
        border: none;
        border-radius: 14px;
        width: 46px;
        height: 46px;
        background: linear-gradient(135deg, #38bdf8, #1d4ed8);
        color: #eff6ff;
        display: grid;
        place-items: center;
        box-shadow: 0 10px 20px rgba(29, 78, 216, 0.3);
    }

    .arta-helper-text {
        margin-top: 0.75rem;
        color: rgba(226, 232, 240, 0.7);
        font-size: 0.82rem;
    }

    @keyframes arta-float {
        0%, 100% { transform: translateY(0) rotateY(-6deg); }
        50% { transform: translateY(-10px) rotateY(6deg); }
    }

    @keyframes arta-orbit {
        from { transform: rotate(0deg) translateX(10px) rotate(0deg); }
        to { transform: rotate(360deg) translateX(10px) rotate(-360deg); }
    }

    @media (max-width: 991px) {
        .arta-grid {
            grid-template-columns: 1fr;
        }

        .arta-stage,
        .arta-chat {
            min-height: auto;
        }
    }

    @media (max-width: 768px) {
        .arta-hub {
            margin: 0 0 2rem;
            padding: 1rem;
        }

        .arta-stage,
        .arta-chat {
            border-radius: 20px;
        }

        .arta-scope {
            grid-template-columns: 1fr;
        }

        .arta-bubble {
            max-width: 100%;
        }
    }
</style>

<body>
    <!-- ✅ Sidebar include -->
    <?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

    <!-- HEADER -->
    <div class="header-bar">
        <div class="header-title">
            <i class="fa-solid fa-building-columns"></i> User Dashboard
            <small class="d-block text-white-50 mt-1" style="font-size: 0.8rem;">
                <i class="fa-solid fa-calendar"></i> <?= date('F d, Y - l') ?>
            </small>
        </div>

        <!-- USER ICON + DROPDOWN -->
        <div class="header-user dropdown">
            <div class="d-flex align-items-center">
                <div class="me-3 text-white">
                    <i class="fa-solid fa-bell"></i>
                    <span class="badge bg-danger">3</span>
                </div>
                <i class="fa-solid fa-user dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown"
                    aria-expanded="false"></i>
            </div>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="#"><i class="fa-solid fa-user me-2"></i>Profile Settings</a></li>
                <li><a class="dropdown-item" href="#"><i class="fa-solid fa-gear me-2"></i>Account Settings</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item text-danger" href="#"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a></li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <div class="container-fluid">
            <!-- Dashboard Title and Refresh Button -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Dashboard Overview</h3>
                        <button class="btn btn-outline-primary btn-sm" id="refreshBtn">
                            <i class="fa-solid fa-rotate"></i> Refresh Data
                        </button>
                    </div>
                    <p class="text-muted">Real-time statistics and analytics</p>
                </div>
            </div>
            
            <!-- Top Cards -->
            <div class="card-container mb-5">
                <div class="card text-center" onclick="window.location.href='#view-users'">
                    <div class="card-icon"><i class="fa-solid fa-users"></i></div>
                    <h5 class="card-title">Total Registered Users</h5>
                    <p class="display-6 count-up"><?= htmlspecialchars($totalUsers) ?></p>
                    <small class="text-muted click-hint"><i class="fa-solid fa-hand-pointer me-1"></i>Click to view</small>
                </div>

                <div class="card text-center" onclick="window.location.href='#manage-admins'">
                    <div class="card-icon"><i class="fa-solid fa-user-check"></i></div>
                    <h5 class="card-title">Active Admin</h5>
                    <p class="display-6 count-up"><?= htmlspecialchars($totalActiveUsers) ?></p>
                    <small class="text-muted click-hint"><i class="fa-solid fa-hand-pointer me-1"></i>Click to view</small>
                </div>

                <div class="card text-center" onclick="window.location.href='#view-systems'">
                    <div class="card-icon"><i class="fa-solid fa-gears"></i></div>
                    <h5 class="card-title"> Active Systems</h5>
                    <p class="display-6 count-up"><?= htmlspecialchars($totalSystems) ?></p>
                    <small class="text-muted click-hint"><i class="fa-solid fa-hand-pointer me-1"></i>Click to view</small>
                </div>

                <div class="card text-center" onclick="window.location.href='#office-list'">
                    <div class="card-icon"><i class="fa-solid fa-building"></i></div>
                    <h5 class="card-title">Office</h5>
                    <p class="display-6 count-up"><?= htmlspecialchars($totalOffices) ?></p>
                    <small class="text-muted click-hint"><i class="fa-solid fa-hand-pointer me-1"></i>Click to view</small>
                </div>

                <div class="card text-center" onclick="window.location.href='#division-list'">
                    <div class="card-icon"><i class="fa-solid fa-sitemap"></i></div>
                    <h5 class="card-title">Division</h5>
                    <p class="display-6 count-up"><?= htmlspecialchars($totalDivisions) ?></p>
                    <small class="text-muted click-hint"><i class="fa-solid fa-hand-pointer me-1"></i>Click to view</small>
                </div>

                <div class="card text-center" onclick="window.location.href='#unit-list'">
                    <div class="card-icon"><i class="fa-solid fa-folder-tree"></i></div>
                    <h5 class="card-title">Unit</h5>
                    <p class="display-6 count-up"><?= htmlspecialchars($totalUnits) ?></p>
                    <small class="text-muted click-hint"><i class="fa-solid fa-hand-pointer me-1"></i>Click to view</small>
                </div>
            </div>

            <section class="arta-hub">
                <div class="arta-grid">
                    <div class="arta-stage">
                        <div>
                            <span class="arta-eyebrow"><i class="fa-solid fa-robot"></i> Portal Intelligence Unit</span>
                            <h2 class="arta-title">Meet ARTA MAN</h2>
                            <p class="arta-copy"><?= htmlspecialchars($artaManDescription) ?></p>

                            <div class="arta-scene" aria-hidden="true">
                                <div class="arta-platform"></div>
                                <div class="arta-character">
                                    <div class="arta-shadow-ring"></div>
                                    <div class="arta-orb"></div>
                                    <div class="arta-head"></div>
                                    <div class="arta-arm left"></div>
                                    <div class="arta-arm right"></div>
                                    <div class="arta-core"></div>
                                    <div class="arta-leg left"></div>
                                    <div class="arta-leg right"></div>
                                </div>
                            </div>

                            <div class="arta-scope">
                                <div class="arta-scope-item">
                                    <strong>Live API Scope</strong>
                                    Users, systems, offices, divisions, and units.
                                </div>
                                <div class="arta-scope-item">
                                    <strong>Admin Guidance</strong>
                                    Answers dashboard questions and points to the right module.
                                </div>
                                <div class="arta-scope-item">
                                    <strong>Fast Summary</strong>
                                    Pulls counts and sample records from your existing endpoints.
                                </div>
                                <div class="arta-scope-item">
                                    <strong>ARTA MAN Persona</strong>
                                    Management Assistant Nexus: strategic, alert, and built to keep the portal mission-ready.
                                </div>
                            </div>
                        </div>

                        <div class="arta-badges">
                            <span class="arta-badge">Admin-ready intelligence</span>
                            <span class="arta-badge">API-connected assistant</span>
                            <span class="arta-badge">3D dashboard companion</span>
                        </div>
                    </div>

                    <div class="arta-chat">
                        <div class="arta-chat-header">
                            <div>
                                <h4>ARTA MAN Console</h4>
                                <p>Ask for totals, latest records, or module guidance based on the APIs already inside this system.</p>
                            </div>
                            <span class="arta-status">Online</span>
                        </div>

                        <div class="arta-messages" id="artaMessages"></div>

                        <div class="arta-quick-actions">
                            <button type="button" class="arta-chip" data-arta-prompt="Bigyan mo ako ng dashboard summary">Dashboard summary</button>
                            <button type="button" class="arta-chip" data-arta-prompt="Ilan ang users ngayon?">Users now</button>
                            <button type="button" class="arta-chip" data-arta-prompt="Anong latest systems ang nasa portal?">Latest systems</button>
                            <button type="button" class="arta-chip" data-arta-prompt="Ilan ang offices, divisions at units?">Org structure</button>
                            <button type="button" class="arta-chip" data-arta-prompt="Saan pupunta para mag-manage ng systems?">Module guide</button>
                        </div>

                        <form class="arta-chat-form" id="artaChatForm">
                            <div class="arta-input-wrap">
                                <input type="text" id="artaInput" placeholder="Type a question for ARTA MAN..." autocomplete="off">
                                <button type="submit" class="arta-send" aria-label="Send message">
                                    <i class="fa-solid fa-paper-plane"></i>
                                </button>
                            </div>
                            <div class="arta-helper-text">Examples: “Ilan ang active systems?”, “show latest users”, “where do I manage units?”</div>
                        </form>
                    </div>
                </div>
            </section>
       
        </div>
    </div>


    <!-- ✅ Footer include -->
    <?php include_once __DIR__ . '/../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dashboardSeed = {
                users: <?= json_encode((int) $totalUsers) ?>,
                admins: <?= json_encode((int) $totalActiveUsers) ?>,
                systems: <?= json_encode((int) $totalSystems) ?>,
                offices: <?= json_encode((int) $totalOffices) ?>,
                divisions: <?= json_encode((int) $totalDivisions) ?>,
                units: <?= json_encode((int) $totalUnits) ?>,
                description: <?= json_encode($artaManDescription) ?>
            };

            // Number counting animation
            const countElements = document.querySelectorAll('.count-up');
            
            countElements.forEach(element => {
                const target = parseInt(element.textContent);
                const duration = 1500;
                const step = target / (duration / 16);
                let current = 0;
                
                // Reset to 0 for animation
                element.textContent = "0";
                
                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) {
                        element.textContent = target.toLocaleString();
                        clearInterval(timer);
                    } else {
                        element.textContent = Math.floor(current).toLocaleString();
                    }
                }, 16);
            });
            
            // Refresh button functionality
            document.getElementById('refreshBtn').addEventListener('click', function() {
                const btn = this;
                const icon = btn.querySelector('i');
                
                // Add spinning animation
                btn.disabled = true;
                icon.classList.add('fa-spin');
                
                // In real application, you would make an AJAX call here
                // For now, just simulate refresh
                setTimeout(() => {
                    btn.disabled = false;
                    icon.classList.remove('fa-spin');
                    
                    // Show success message
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
                    alert.style.zIndex = '9999';
                    alert.innerHTML = `
                        <i class="fa-solid fa-check-circle me-2"></i>
                        Data refreshed successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.body.appendChild(alert);
                    
                    // Auto remove after 3 seconds
                    setTimeout(() => {
                        alert.remove();
                    }, 3000);
                }, 1000);
            });
            
            // Card click effect
            const cards = document.querySelectorAll('.card');
            cards.forEach(card => {
                card.addEventListener('click', function() {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
            });

            const artaMessages = document.getElementById('artaMessages');
            const artaForm = document.getElementById('artaChatForm');
            const artaInput = document.getElementById('artaInput');
            const artaQuickActions = document.querySelectorAll('[data-arta-prompt]');

            const apiCatalog = {
                users: '../api/users/get_users.php?limit=3&page=1',
                systems: '../api/systems/get_systems.php?limit=3&page=1',
                offices: '../api/offices/get_offices.php?limit=3&page=1',
                divisions: '../api/divisions/get_divisions.php?limit=3&page=1',
                units: '../api/units/get_units.php?limit=3&page=1'
            };

            const moduleLinks = {
                systems: 'pages/system.php',
                users: 'pages/users.php',
                offices: 'pages/office.php',
                divisions: 'pages/divisions.php',
                units: 'pages/units.php'
            };

            function appendMessage(role, text) {
                const row = document.createElement('div');
                row.className = `arta-message ${role}`;

                const avatar = document.createElement('div');
                avatar.className = 'arta-avatar';
                avatar.textContent = role === 'bot' ? 'AM' : 'AD';

                const bubble = document.createElement('div');
                bubble.className = 'arta-bubble';
                bubble.textContent = text;

                row.appendChild(avatar);
                row.appendChild(bubble);
                artaMessages.appendChild(row);
                artaMessages.scrollTop = artaMessages.scrollHeight;
            }

            async function fetchJson(url) {
                const response = await fetch(url, {
                    headers: { 'Accept': 'application/json' }
                });

                const contentType = response.headers.get('content-type') || '';
                const rawText = await response.text();

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${rawText.slice(0, 160)}`);
                }

                if (!contentType.includes('application/json')) {
                    throw new Error('Expected JSON response from ' + url);
                }

                return JSON.parse(rawText);
            }

            function extractList(data, key) {
                if (Array.isArray(data?.data?.[key])) {
                    return data.data[key];
                }
                if (Array.isArray(data?.[key])) {
                    return data[key];
                }
                return [];
            }

            function extractTotal(data, key, fallback) {
                if (typeof data?.data?.total === 'number') {
                    return data.data.total;
                }
                if (typeof data?.totalRecords === 'number') {
                    return data.totalRecords;
                }
                if (Array.isArray(data?.data?.[key])) {
                    return data.data[key].length;
                }
                if (Array.isArray(data?.[key])) {
                    return data[key].length;
                }
                return fallback;
            }

            function formatNames(items, fields) {
                return items
                    .map(item => fields.map(field => item[field]).filter(Boolean).join(' '))
                    .filter(Boolean)
                    .slice(0, 3)
                    .join(', ');
            }

            function getModuleGuide(topic) {
                if (topic.includes('system')) {
                    return 'Para sa systems management, pumunta sa ' + moduleLinks.systems + '. Doon mo makikita ang system records at maintenance actions.';
                }
                if (topic.includes('user')) {
                    return 'Para sa user administration, pumunta sa ' + moduleLinks.users + '. Doon naka-centralize ang user records at account actions.';
                }
                if (topic.includes('office')) {
                    return 'Para sa office records, pumunta sa ' + moduleLinks.offices + '.';
                }
                if (topic.includes('division')) {
                    return 'Para sa divisions, pumunta sa ' + moduleLinks.divisions + '.';
                }
                if (topic.includes('unit')) {
                    return 'Para sa units, pumunta sa ' + moduleLinks.units + '.';
                }
                return 'Pwede kitang tulungan sa users, systems, offices, divisions, at units. Sabihin mo lang kung aling module ang gusto mong buksan.';
            }

            async function buildSummaryReply() {
                const [users, systems, offices, divisions, units] = await Promise.all([
                    fetchJson(apiCatalog.users),
                    fetchJson(apiCatalog.systems),
                    fetchJson(apiCatalog.offices),
                    fetchJson(apiCatalog.divisions),
                    fetchJson(apiCatalog.units)
                ]);

                return [
                    'ARTA MAN dashboard summary:',
                    `Users: ${extractTotal(users, 'users', dashboardSeed.users)}`,
                    `Admins: ${dashboardSeed.admins}`,
                    `Systems: ${extractTotal(systems, 'systems', dashboardSeed.systems)}`,
                    `Offices: ${extractTotal(offices, 'offices', dashboardSeed.offices)}`,
                    `Divisions: ${extractTotal(divisions, 'divisions', dashboardSeed.divisions)}`,
                    `Units: ${extractTotal(units, 'units', dashboardSeed.units)}`
                ].join('\n');
            }

            async function generateReply(message) {
                const normalized = message.toLowerCase();

                if (normalized.includes('summary') || normalized.includes('overview') || normalized.includes('dashboard')) {
                    return buildSummaryReply();
                }

                if (normalized.includes('latest') && normalized.includes('user')) {
                    const usersData = await fetchJson(apiCatalog.users);
                    const latestUsers = formatNames(extractList(usersData, 'users'), ['firstname', 'lastname']);
                    return latestUsers
                        ? `Narito ang latest users mula sa API: ${latestUsers}. Total users right now: ${extractTotal(usersData, 'users', dashboardSeed.users)}.`
                        : 'Wala akong nakuha na latest users mula sa API.';
                }

                if (normalized.includes('user')) {
                    const usersData = await fetchJson(apiCatalog.users);
                    return `May ${extractTotal(usersData, 'users', dashboardSeed.users)} user records sa portal. Current admin count on this dashboard is ${dashboardSeed.admins}.`;
                }

                if (normalized.includes('system')) {
                    if (normalized.includes('where') || normalized.includes('saan') || normalized.includes('manage')) {
                        return getModuleGuide('system');
                    }

                    const systemsData = await fetchJson(apiCatalog.systems);
                    const systems = extractList(systemsData, 'systems');
                    const sampleNames = systems.map(item => item.system_name).filter(Boolean).slice(0, 3).join(', ');
                    return sampleNames
                        ? `May ${extractTotal(systemsData, 'systems', dashboardSeed.systems)} systems. Latest visible systems: ${sampleNames}.`
                        : `May ${extractTotal(systemsData, 'systems', dashboardSeed.systems)} systems sa portal.`;
                }

                if (normalized.includes('office') || normalized.includes('division') || normalized.includes('unit') || normalized.includes('org')) {
                    if (normalized.includes('where') || normalized.includes('saan') || normalized.includes('manage')) {
                        return getModuleGuide(normalized);
                    }

                    const [officesData, divisionsData, unitsData] = await Promise.all([
                        fetchJson(apiCatalog.offices),
                        fetchJson(apiCatalog.divisions),
                        fetchJson(apiCatalog.units)
                    ]);

                    return [
                        'Ito ang organizational structure snapshot:',
                        `Offices: ${extractTotal(officesData, 'offices', dashboardSeed.offices)}`,
                        `Divisions: ${extractTotal(divisionsData, 'divisions', dashboardSeed.divisions)}`,
                        `Units: ${extractTotal(unitsData, 'units', dashboardSeed.units)}`
                    ].join('\n');
                }

                if (normalized.includes('arta man') || normalized.includes('who are you') || normalized.includes('sino ka')) {
                    return dashboardSeed.description;
                }

                if (normalized.includes('help') || normalized.includes('guide') || normalized.includes('module')) {
                    return 'Pwede mong itanong ang: dashboard summary, total users, latest systems, org structure, o module guide para sa users/systems/offices/divisions/units.';
                }

                return 'Naiintindihan ko ang portal questions tungkol sa users, systems, offices, divisions, units, at dashboard summary. Subukan mo: “Ilan ang users ngayon?” o “Bigyan mo ako ng dashboard summary”.';
            }

            async function handlePrompt(message) {
                const trimmed = message.trim();
                if (!trimmed) {
                    return;
                }

                appendMessage('user', trimmed);
                artaInput.value = '';
                appendMessage('bot', 'Processing live portal data...');

                const loadingMessage = artaMessages.lastElementChild;

                try {
                    const reply = await generateReply(trimmed);
                    loadingMessage.querySelector('.arta-bubble').textContent = reply;
                } catch (error) {
                    console.error('ARTA MAN error:', error);
                    loadingMessage.querySelector('.arta-bubble').textContent = 'May problema habang kumukuha ako ng data mula sa portal APIs. Paki-check ang endpoint response o subukan ulit.';
                }
            }

            appendMessage('bot', 'Ako si ARTA MAN. Naka-link ako sa portal APIs para sagutin ang admin questions mo tungkol sa users, systems, offices, divisions, at units.');
            appendMessage('bot', 'Subukan mo ang quick actions sa ibaba o magtanong nang direkta para sa live dashboard summary.');

            artaForm.addEventListener('submit', function(event) {
                event.preventDefault();
                handlePrompt(artaInput.value);
            });

            artaQuickActions.forEach(button => {
                button.addEventListener('click', function() {
                    handlePrompt(this.dataset.artaPrompt || 'Dashboard summary');
                });
            });
        });
    </script>
</body>

</html>