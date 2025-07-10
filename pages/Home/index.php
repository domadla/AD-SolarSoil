<?php
// Page variables
$page_title = 'SolarSoil - User Dashboard';
$page_description = 'Welcome to your personal interstellar agriculture command center.';
$body_class = 'home-page';

// Include header component
include TEMPLATES_PATH . '/header.component.php';
?>

<!-- Main Dashboard Content -->
<div class="dashboard-container">
    <div class="container py-5">
        <!-- Welcome Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="welcome-card">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="welcome-title">
                                <i class="fas fa-rocket me-3"></i>
                                Welcome to your Personal Space Farm
                            </h1>
                            <p class="welcome-subtitle">
                                Your personal interstellar agriculture operations await. Monitor your crops across the
                                galaxy,
                                manage resources, and expand your cosmic farming empire.
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="cosmic-status">
                                <i class="fas fa-satellite-dish cosmic-icon"></i>
                                <span class="status-text">Systems Online</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Cards -->
        <div class="row g-4 mb-5">
            <!-- Planetary Farms -->
            <div class="col-lg-4 col-md-6">
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-globe-americas card-icon"></i>
                        <h3>Planetary Farms</h3>
                    </div>
                    <div class="card-body">
                        <div class="metric-value">12</div>
                        <div class="metric-label">Active Worlds</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 75%;"></div>
                        </div>
                        <p class="card-description">
                            Manage agricultural operations across multiple planets in the Andromeda sector.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quantum Crops -->
            <div class="col-lg-4 col-md-6">
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-seedling card-icon"></i>
                        <h3>Quantum Crops</h3>
                    </div>
                    <div class="card-body">
                        <div class="metric-value">847K</div>
                        <div class="metric-label">Plants Growing</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 92%;"></div>
                        </div>
                        <p class="card-description">
                            Monitor bio-enhanced crops with quantum acceleration technology.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Energy Harvest -->
            <div class="col-lg-4 col-md-6">
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-solar-panel card-icon"></i>
                        <h3>Solar Collection</h3>
                    </div>
                    <div class="card-body">
                        <div class="metric-value">2.4M</div>
                        <div class="metric-label">Energy Units</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 88%;"></div>
                        </div>
                        <p class="card-description">
                            Harvest energy from multiple star systems to power operations.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="actions-card">
                    <h2 class="actions-title">
                        <i class="fas fa-rocket me-2"></i>Quick Actions
                    </h2>
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <button class="action-btn" onclick="location.href='../Shop/index.php'">
                                <i class="fas fa-shopping-bag"></i>
                                <span>Buy Plants</span>
                            </button>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <button class="action-btn" onclick="location.href='../Cart/index.php'">
                                <i class="fas fa-shopping-cart"></i>
                                <span>Show Carts</span>
                            </button>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <button class="action-btn" onclick="location.href='../Order/index.php'">
                                <i class="fas fa-receipt"></i>
                                <span>View Orders</span>
                            </button>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <button class="action-btn" onclick="location.href='../Profile/index.php'">
                                <i class="fas fa-cog"></i>
                                <span>Settings</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row">
            <div class="col-12">
                <div class="activity-card">
                    <h2 class="activity-title">
                        <i class="fas fa-history me-2"></i>Recent Cosmic Activity
                    </h2>
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-seedling"></i>
                            </div>
                            <div class="activity-content">
                                <h4>New Crop Strain Discovered</h4>
                                <p>Quantum-enhanced tomatoes showing 340% growth rate on Kepler-442b</p>
                                <span class="activity-time">2 hours ago</span>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-satellite"></i>
                            </div>
                            <div class="activity-content">
                                <h4>System Scan Complete</h4>
                                <p>Proxima Centauri system shows optimal conditions for agriculture expansion</p>
                                <span class="activity-time">6 hours ago</span>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-rocket"></i>
                            </div>
                            <div class="activity-content">
                                <h4>Supply Ship Deployed</h4>
                                <p>Resource transport successfully launched to Mars Colony Delta-7</p>
                                <span class="activity-time">1 day ago</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer component
include TEMPLATES_PATH . '/footer.component.php';
?>