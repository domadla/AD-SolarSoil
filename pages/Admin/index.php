<?php
// Set page variables
$page_title = 'SolarSoil - Admin Control Center';
$page_description = 'Administrative dashboard for managing the interstellar agriculture platform.';
$body_class = 'admin-page';

// Capture page content
ob_start();
?>

<!-- Admin Dashboard Content -->
<div class="admin-dashboard-container">
    <div class="container py-5">
        <!-- Admin Welcome Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="admin-welcome-card">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="admin-title">
                                <i class="fas fa-crown me-3"></i>
                                Admin Control Center
                            </h1>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="admin-status">
                                <i class="fas fa-shield-alt admin-icon"></i>
                                <span class="status-text">Admin Mode</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Stats Cards -->
        <div class="row g-4 mb-5">
            <!-- Total Users -->
            <div class="col-lg-3 col-md-6">
                <div class="admin-card users-card">
                    <div class="card-header">
                        <i class="fas fa-users card-icon"></i>
                        <h3>Total Users</h3>
                    </div>
                    <div class="card-body">
                        <div class="metric-value">2,847</div>
                        <div class="metric-label">Active Farmers</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 85%;"></div>
                        </div>
                        <p class="card-description">
                            Cosmic agriculture pioneers across the galaxy.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Plant Inventory -->
            <div class="col-lg-3 col-md-6">
                <div class="admin-card plants-card">
                    <div class="card-header">
                        <i class="fas fa-seedling card-icon"></i>
                        <h3>Plant Inventory</h3>
                    </div>
                    <div class="card-body">
                        <div class="metric-value">156</div>
                        <div class="metric-label">Plant Species</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 92%;"></div>
                        </div>
                        <p class="card-description">
                            Quantum-enhanced crops and bio-fusion varieties.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Orders -->
            <div class="col-lg-3 col-md-6">
                <div class="admin-card orders-card">
                    <div class="card-header">
                        <i class="fas fa-shopping-cart card-icon"></i>
                        <h3>Orders</h3>
                    </div>
                    <div class="card-body">
                        <div class="metric-value">4,231</div>
                        <div class="metric-label">Total Orders</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 78%;"></div>
                        </div>
                        <p class="card-description">
                            Galactic commerce transactions processed.
                        </p>
                    </div>
                </div>
            </div>

            <!-- System Health -->
            <div class="col-lg-3 col-md-6">
                <div class="admin-card system-card">
                    <div class="card-header">
                        <i class="fas fa-server card-icon"></i>
                        <h3>System Health</h3>
                    </div>
                    <div class="card-body">
                        <div class="metric-value">98.7%</div>
                        <div class="metric-label">Uptime</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 98.7%;"></div>
                        </div>
                        <p class="card-description">
                            Platform stability across all star systems.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Management Cards -->
        <div class="row g-4 mb-5">
            <!-- User Management -->
            <div class="col-lg-4 col-md-6">
                <div class="management-card">
                    <div class="card-header">
                        <i class="fas fa-user-cog"></i>
                        <h3>User Management</h3>
                    </div>
                    <div class="card-body">
                        <p>Manage user accounts, permissions, and access levels across the platform.</p>
                        <div class="action-buttons">
                            <button class="btn btn-primary btn-sm me-2" onclick="manageUsers()">
                                <i class="fas fa-users"></i> View Users
                            </button>
                            <button class="btn btn-outline-primary btn-sm" onclick="addUser()">
                                <i class="fas fa-user-plus"></i> Add User
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Plant Management -->
            <div class="col-lg-4 col-md-6">
                <div class="management-card">
                    <div class="card-header">
                        <i class="fas fa-leaf"></i>
                        <h3>Plant Inventory</h3>
                    </div>
                    <div class="card-body">
                        <p>Control plant species, inventory levels, and pricing across all galactic markets.</p>
                        <div class="action-buttons">
                            <button class="btn btn-success btn-sm me-2" onclick="managePlants()">
                                <i class="fas fa-seedling"></i> View Plants
                            </button>
                            <button class="btn btn-outline-success btn-sm" onclick="addPlant()">
                                <i class="fas fa-plus"></i> Add Plant
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Management -->
            <div class="col-lg-4 col-md-6">
                <div class="management-card">
                    <div class="card-header">
                        <i class="fas fa-receipt"></i>
                        <h3>Order Management</h3>
                    </div>
                    <div class="card-body">
                        <p>Monitor, process, and manage all customer orders and transactions.</p>
                        <div class="action-buttons">
                            <button class="btn btn-warning btn-sm me-2" onclick="manageOrders()">
                                <i class="fas fa-list"></i> View Orders
                            </button>
                            <button class="btn btn-outline-warning btn-sm" onclick="processOrders()">
                                <i class="fas fa-cog"></i> Process
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Admin Activity -->
        <div class="row">
            <div class="col-12">
                <div class="admin-activity-card">
                    <h2 class="activity-title">
                        <i class="fas fa-history me-2"></i>Recent Administrative Activity
                    </h2>
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="activity-icon user-activity">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="activity-content">
                                <h4>New User Registration</h4>
                                <p>Captain Maria Santos joined the platform from Mars Colony Alpha-9</p>
                                <span class="activity-time">15 minutes ago</span>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon plant-activity">
                                <i class="fas fa-seedling"></i>
                            </div>
                            <div class="activity-content">
                                <h4>Inventory Updated</h4>
                                <p>Added 50 units of Quantum Tomatoes to the interstellar marketplace</p>
                                <span class="activity-time">1 hour ago</span>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon order-activity">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="activity-content">
                                <h4>Large Order Processed</h4>
                                <p>Enterprise order #GX-4471 for 500 Bio-Enhanced Wheat dispatched to Kepler-442b</p>
                                <span class="activity-time">3 hours ago</span>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon system-activity">
                                <i class="fas fa-server"></i>
                            </div>
                            <div class="activity-content">
                                <h4>System Maintenance</h4>
                                <p>Scheduled backup completed for all galactic database nodes</p>
                                <span class="activity-time">6 hours ago</span>
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

// Use the shared page layout
include '../../layouts/page-layout.php';
?>