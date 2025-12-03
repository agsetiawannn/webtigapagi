<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Our Clients - Tigapagi</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Security: Content Security Policy -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:;">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta name="referrer" content="strict-origin-when-cross-origin">
</head>
<body>

<div class="logo-top-left" >
    <img class="logo-img" src="{{ asset('img/tb.png') }}">
</div>

<div class="controls-top-right">

    <div class="nav-menu-container" id="navMenu">
        <a href="/">Home</a>
        <a href="#Work">Work</a>
        <a href="#Clients">Clients</a>
    </div>

    <button aria-label="Menu" onclick="toggleMenu()" class="btn--icon" id="menuBtn">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
    </button>

    <a href="https://wa.me/089638893601" class="btn" title="Contact" target="_blank" rel="noopener noreferrer">
        <img src="{{ asset('img/wa.png') }}" alt="WhatsApp" style="width:18px;height:18px;border-radius:50%;display:inline-block;object-fit:cover;" />
        Contact
    </a>
</div>

<!-- Clients Hero Section -->
<section class="hero clients-hero" role="banner">
    <div class="hero-gradient-bottom" aria-hidden="true"></div>
    <div class="hero-bg hero-bg-bottom" aria-hidden="true"></div>
    <div class="hero-gradient-top" aria-hidden="true"></div>
    <div class="hero-bg hero-bg-top" aria-hidden="true"></div>
    <div class="container hero-clients-wrap">
        <div class="clients-hero-content">
            <img src="{{ asset('img/ClientWrap.png') }}" alt="Clients" class="hero-clients-image">
            <p class="clients-description">
                We've had the privilege of working with some amazing brands and companies. 
                From startups to established businesses, our clients trust us to deliver exceptional results.
            </p>
        </div>
    </div>
</section>

<!-- All Clients Section -->
<section class="section-all-clients" aria-labelledby="allClientsTitle">
    <div class="clients-container">
    </div>
</section>

<script>
// Disable right-click context menu
document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
    return false;
});

// Disable F12, Ctrl+Shift+I, Ctrl+Shift+C, Ctrl+Shift+J
document.addEventListener('keydown', function(e) {
    if (
        e.key === 'F12' || 
        (e.ctrlKey && e.shiftKey && e.key === 'I') || 
        (e.ctrlKey && e.shiftKey && e.key === 'C') || 
        (e.ctrlKey && e.shiftKey && e.key === 'J')
    ) {
        e.preventDefault();
        return false;
    }
});

// Detect Developer Tools
let devtools = { open: false };
const threshold = 160;

setInterval(function() {
    if (window.outerHeight - window.innerHeight > threshold || window.outerWidth - window.innerWidth > threshold) {
        if (!devtools.open) {
            devtools.open = true;
            console.clear();
            console.log('%cSTOP!', 'font-size: 50px; color: red; font-weight: bold;');
        }
    } else {
        devtools.open = false;
    }
}, 500);

// Hide console
console.log = function() {};
console.warn = function() {};
console.error = function() {};
</script>

<script>
function toggleMenu(){
    const menuBtn = document.getElementById('menuBtn');
    const navMenu = document.getElementById('navMenu');

    menuBtn.classList.toggle('active');
    navMenu.classList.toggle('active');

    const expanded = navMenu.classList.contains('active');
    menuBtn.setAttribute('aria-expanded', expanded);
}
</script>

</body>
</html>
