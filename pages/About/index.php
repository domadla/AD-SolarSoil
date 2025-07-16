<?php
require_once BASE_PATH . '/bootstrap.php';
require_once UTILS_PATH . 'auth.util.php';
require_once UTILS_PATH . 'admin.util.php';
require_once UTILS_PATH . 'envSetter.util.php';

Auth::init();

if (!Auth::check()) {
    header('Location: /index.php?error=LoginRequired');
    exit;
}

// Page variables
$page_title = 'SolarSoil - About';
$page_description = 'Learn about SolarSoil, our cosmic mission, vision, and the team making sustainable agriculture possible across the galaxy.';
$body_class = 'about-page';

// Capture page content
ob_start();
?>
<!-- TODO: Complete Images of Developers -->
<!-- About page custom CSS -->
<link rel="stylesheet" href="./assets/css/about.css">

<main class="container py-5 about-page">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <h1 class="display-4 fw-bold mb-4"><i class="fas fa-leaf me-2"></i>About SolarSoil</h1>
            <p class="lead mb-4">
                SolarSoil is on a mission to make the galaxy greener! We blend cosmic technology and sustainable
                agriculture to help planets everywhere grow thriving, eco-friendly gardens. Our team is passionate about
                harnessing the power of the stars and the richness of alien soils for a brighter, sustainable future.
            </p>

            <h2 class="h4 fw-bold mb-3 section-title"><i class="fas fa-seedling me-2"></i>What We Do</h2>
            <p class="about-vision">
                From Mars to moons beyond Saturn, we deploy modular farming kits, teach interplanetary communities how
                to cultivate food, and continuously experiment with hybrid seeds that can thrive in different
                atmospheres. Whether it’s a colony or a research outpost, we make farming in space possible.
            </p>

            <div class="row mb-4 align-items-stretch">
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="p-4 h-100 bg-dark bg-opacity-50 rounded-4 shadow">
                        <h2 class="h4 fw-bold mb-3 section-title"><i class="fas fa-rocket me-2"></i>Our Mission</h2>
                        <p class="about-vision mb-0">
                            To terraform barren planets into self-sustaining biospheres using smart soil technology,
                            solar-powered
                            ecosystems, and a deep respect for planetary environments. We believe in regenerative
                            agriculture that
                            respects both science and nature—across light-years.
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-4 h-100 bg-dark bg-opacity-50 rounded-4 shadow">
                        <h2 class="h4 fw-bold mb-3 section-title"><i class="fas fa-star me-2"></i>Our Vision</h2>
                        <p class="about-vision mb-0">
                            We dream of a universe where every world can grow healthy food, restore its environment, and
                            reach for
                            the stars without harming the cosmos. SolarSoil fuses advanced space-age tech with a love
                            for nature to
                            make this vision a reality.
                        </p>
                    </div>
                </div>
            </div>

            <h2 class="h4 fw-bold mb-4 section-title"><i class="fas fa-users me-2"></i>Meet the Crew</h2>
            <div class="row justify-content-center mb-4">
                <div class="col-6 col-md-2 mb-3 team-card">
                    <img src="./assets/img/Mark.jpg" class="mb-2 border border-light all-img" alt="Mark">
                    <div>Mark Gerard G. Gamboa<br><small>Frontend Developer</small></div>
                </div>
                <div class="col-6 col-md-2 mb-3 team-card">
                    <img src="./assets/img/Dom.jpg" class="mb-2 border border-light all-img" alt="Dom">
                    <div>Edgar Dominic A. Madlangbayan<br><small>Quality Assurance</small></div>
                </div>
                <div class="col-6 col-md-2 mb-3 team-card">
                    <img src="./assets/img/Carl.jpg" class="mb-2 border border-light all-img" alt="Carl">
                    <div>Carl Jerome N. Mariño<br><small>Database</small></div>
                </div>
                <div class="col-6 col-md-2 mb-3 team-card">
                    <img src="./assets/img/Ronn.jpg" class="mb-2 border border-light all-img" alt="Ronn">
                    <div>Ronn Saimund U. Rosarito<br><small>Backend Developer</small></div>
                </div>
                <div class="col-6 col-md-2 mb-3 team-card">
                    <img src="./assets/img/Marlo.jpg" class="mb-2 border border-light all-img" alt="Marlo">
                    <div>Marlo S. Veluz Jr.<br><br><small>Backend Developer</small></div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
$content = ob_get_clean();

include '../../layouts/page-layout.php';
?>