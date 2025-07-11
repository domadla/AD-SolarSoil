<?php
// Page variables
$page_title = 'SolarSoil - User Dashboard';
$page_description = 'Welcome to your personal interstellar agriculture command center.';
$body_class = 'home-page';

// Capture page content
ob_start();
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
                                galaxy, manage resources, and expand your cosmic farming empire.
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
                        <div class="stat-number">12</div>
                        <p>Active farming colonies across multiple star systems</p>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: 85%"></div>
                        </div>
                        <small class="text-muted">85% operational efficiency</small>
                    </div>
                </div>
            </div>

            <!-- Resource Management -->
            <div class="col-lg-4 col-md-6">
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-chart-line card-icon"></i>
                        <h3>Resource Monitor</h3>
                    </div>
                    <div class="card-body">
                        <div class="stat-number">2,847</div>
                        <p>Galactic Credits earned this cycle</p>
                        <div class="progress">
                            <div class="progress-bar bg-info" style="width: 65%"></div>
                        </div>
                        <small class="text-muted">+12% from last period</small>
                    </div>
                </div>
            </div>

            <!-- Fleet Status -->
            <div class="col-lg-4 col-md-6">
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-rocket card-icon"></i>
                        <h3>Transport Fleet</h3>
                    </div>
                    <div class="card-body">
                        <div class="stat-number">8</div>
                        <p>Active cargo ships in transit</p>
                        <div class="progress">
                            <div class="progress-bar bg-warning" style="width: 40%"></div>
                        </div>
                        <small class="text-muted">3 scheduled arrivals today</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Feed -->
        <div class="row">
            <div class="col-12">
                <div class="activity-feed">
                    <div class="feed-header">
                        <h3>
                            <i class="fas fa-history me-3"></i>
                            Recent System Activity
                        </h3>
                    </div>
                    <div class="feed-content">
                        <div class="activity-item">
                            <div class="activity-icon bg-success">
                                <i class="fas fa-seedling"></i>
                            </div>
                            <div class="activity-details">
                                <h4>Harvest Complete</h4>
                                <p>Kepler-442b Farm: 500 units of Stellar Moss harvested</p>
                                <span class="activity-time">2 hours ago</span>
                            </div>
                        </div>

                        <div class="activity-item">
                            <div class="activity-icon bg-info">
                                <i class="fas fa-rocket"></i>
                            </div>
                            <div class="activity-details">
                                <h4>Supply Drop Incoming</h4>
                                <p>Transport ship arriving at Proxima Colony with nutrients</p>
                                <span class="activity-time">4 hours ago</span>
                            </div>
                        </div>

                        <div class="activity-item">
                            <div class="activity-icon bg-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="activity-details">
                                <h4>System Alert</h4>
                                <p>Solar storm detected near Titan Base - shields activated</p>
                                <span class="activity-time">6 hours ago</span>
                            </div>
                        </div>

                        <div class="activity-item">
                            <div class="activity-icon bg-primary">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="activity-details">
                                <h4>Performance Report</h4>
                                <p>Weekly productivity increased by 15% across all colonies</p>
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
$content = ob_get_clean();

// Include the single layout
include '../../layouts/page-layout.php';
?>