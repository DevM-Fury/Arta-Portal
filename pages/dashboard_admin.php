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
?>

<!DOCTYPE html>
<html lang="en">

<!-- ✅ Head include -->
<?php include_once __DIR__ . '/../includes/head.php'; ?>

<style>
    /* Dashboard Styles */
    .header-bar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .card:nth-child(2) .card-icon {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    .card:nth-child(3) .card-icon {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    .card:nth-child(4) .card-icon {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    .card:nth-child(5) .card-icon {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
    .card:nth-child(6) .card-icon {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        color: #333;
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
            
            <!-- Quick Stats Section -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card p-3">
                        <h5><i class="fa-solid fa-chart-line text-primary me-2"></i>Quick Stats</h5>
                        <div class="row text-center">
                            <div class="col-md-3 col-6 mb-3">
                                <div class="p-2">
                                    <i class="fa-solid fa-user-plus fa-lg text-success mb-2"></i>
                                    <h6>New Today</h6>
                                    <p class="mb-0 fw-bold">+5</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="p-2">
                                    <i class="fa-solid fa-clock fa-lg text-warning mb-2"></i>
                                    <h6>Pending</h6>
                                    <p class="mb-0 fw-bold">12</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="p-2">
                                    <i class="fa-solid fa-check-circle fa-lg text-success mb-2"></i>
                                    <h6>Completed</h6>
                                    <p class="mb-0 fw-bold">48</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="p-2">
                                    <i class="fa-solid fa-chart-bar fa-lg text-info mb-2"></i>
                                    <h6>Growth</h6>
                                    <p class="mb-0 fw-bold">+15%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ✅ Footer include -->
    <?php include_once __DIR__ . '/../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>
</body>

</html>