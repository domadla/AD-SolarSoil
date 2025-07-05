<?php
session_start();

require_once '../../bootstrap.php';
require VENDOR_PATH . 'autoload.php';
require_once UTILS_PATH . 'session.util.php';
require_once COMPONENTS_PATH . 'templates/header.component.php';
?>

<head>
    <link rel="stylesheet" href="/pages/about/assets/css/style.css">
    <script src="/pages/about/assets/js/script.js"></script>
</head>

<section class="about-hero container" aria-label="About SolarSoil" id="about">
    <h1 class="hero-title">About SolarSoil</h1>
    <p class="hero-subtitle">Pioneering the future of interstellar agriculture</p>
</section>

<section class="about-content container">
    <div class="about-grid">
        <div class="about-text">
            <h2 class="section-title">Our Mission</h2>
            <p class="about-description">
                At SolarSoil, we're dedicated to revolutionizing agriculture beyond Earth's boundaries.
                Our innovative approach combines cutting-edge technology with nature's resilience to
                create sustainable growing solutions for space exploration and extraterrestrial colonization.
            </p>
            <p class="about-description">
                We believe that the future of humanity lies among the stars, and with it, the need for
                reliable food production systems that can thrive in the harshest environments. Our team
                of scientists, botanists, and engineers work tirelessly to develop plants that can
                flourish in low gravity, controlled atmospheres, and artificial light conditions.
            </p>
        </div>
        <div class="about-image">
            <img src="/pages/about/assets/img/mission.png" alt="SolarSoil Mission" class="mission-image">
        </div>
    </div>
</section>

<section class="team-section container">
    <h2 class="section-title">Meet Our Team</h2>
    <p class="team-description">
        Our dedicated team of experts brings together diverse backgrounds in space agriculture,
        biotechnology, and sustainable farming to make interstellar cultivation a reality.
    </p>

    <div class="team-grid">
        <div class="team-member">
            <div class="member-image">
                <img src="/pages/about/assets/img/team/markgamboa.jpg" alt="Mark Gamboa" class="member-photo">
            </div>
            <div class="member-info">
                <h3 class="member-name">Mark Gamboa</h3>
                <p class="member-role">Frontend Developer</p>
                <p class="member-description">Creates intuitive and responsive user interfaces, bringing the SolarSoil
                    vision to life through elegant web design and seamless user experiences.</p>
            </div>
        </div>

        <div class="team-member">
            <div class="member-image">
                <img src="/pages/about/assets/img/team/member2.png" alt="Marlo Veluz" class="member-photo">
            </div>
            <div class="member-info">
                <h3 class="member-name">Marlo Veluz</h3>
                <p class="member-role">Backend Developer</p>
                <p class="member-description">Develops robust server-side systems and APIs that power SolarSoil's
                    platform, ensuring scalable and secure backend architecture.</p>
            </div>
        </div>

        <div class="team-member">
            <div class="member-image">
                <img src="/pages/about/assets/img/team/member3.png" alt="Ronn Rosarito" class="member-photo">
            </div>
            <div class="member-info">
                <h3 class="member-name">Ronn Rosarito</h3>
                <p class="member-role">Backend Developer</p>
                <p class="member-description">Specializes in building efficient backend solutions and integration
                    systems that support SolarSoil's complex agricultural data processing needs.</p>
            </div>
        </div>

        <div class="team-member">
            <div class="member-image">
                <img src="/pages/about/assets/img/team/member4.png" alt="Carl Marino" class="member-photo">
            </div>
            <div class="member-info">
                <h3 class="member-name">Carl Marino</h3>
                <p class="member-role">Database Administrator</p>
                <p class="member-description">Manages and optimizes SolarSoil's database systems, ensuring reliable data
                    storage and efficient retrieval for all agricultural and user data.</p>
            </div>
        </div>

        <div class="team-member">
            <div class="member-image">
                <img src="/pages/about/assets/img/team/member5.png" alt="Dominic Mandlangbayan" class="member-photo">
            </div>
            <div class="member-info">
                <h3 class="member-name">Dominic Mandlangbayan</h3>
                <p class="member-role">Quality Assurance</p>
                <p class="member-description">Ensures the highest standards of quality and reliability across all
                    SolarSoil systems through comprehensive testing and quality control processes.</p>
            </div>
        </div>
    </div>
</section>

<section class="values-section container">
    <h2 class="section-title">Our Values</h2>
    <div class="values-grid">
        <div class="value-card">
            <div class="value-icon">🌱</div>
            <h3 class="value-title">Innovation</h3>
            <p class="value-description">Pushing the boundaries of what's possible in space agriculture through
                cutting-edge research and technology.</p>
        </div>
        <div class="value-card">
            <div class="value-icon">🌍</div>
            <h3 class="value-title">Sustainability</h3>
            <p class="value-description">Creating solutions that are environmentally responsible and resource-efficient
                for long-term space missions.</p>
        </div>
        <div class="value-card">
            <div class="value-icon">🚀</div>
            <h3 class="value-title">Exploration</h3>
            <p class="value-description">Supporting humanity's journey to the stars with reliable food production
                systems.</p>
        </div>
    </div>
</section>

<?php require_once COMPONENTS_PATH . 'templates/footer.component.php'; ?>