<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Tigapagi</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
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
        <a href="#">Team</a>
        <a href="#">Work</a>
        <a href="#">Home</a>
        <a href="{{ asset('login.php') }}">Tracking</a>
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

<section class="hero" role="banner">
    <div class="hero-gradient-bottom" aria-hidden="true"></div>
    <div class="hero-bg hero-bg-bottom" aria-hidden="true"></div>
    <div class="hero-gradient-top" aria-hidden="true"></div>
    <div class="hero-bg hero-bg-top" aria-hidden="true"></div>
    <div class="container">
        <h1>A creative makerspace that consist of passionate nocturnal folks</h1>
    </div>
</section>

<section class="section-works" aria-labelledby="worksTitle">
    <div class="section-bg-bottom" aria-hidden="true"></div>
    <div class="section-bg-top" aria-hidden="true"></div>
    <div class="section-overlay" aria-hidden="true"></div>
    <div class="works-inner">
        <p class="intro"><strong>Studio Tigapagi</strong> is a leading digital creative agency and dedicated creative makerspace located in Sanur, Denpasar, Bali. Known for its high-commitment culture, the agency describes its team as "passionate nocturnal folks" who specialize in delivering high-impact branding.</p>
        <div class="hr-line"></div>
        <h2 id="worksTitle">Unlock Your Brand's <em>Potential</em><br>with our Strategy</h2>
        <div class="works-cards" role="list">
            <div class="card" role="listitem" aria-label="Branding">Branding</div>
            <div class="card" role="listitem" aria-label="Social Media Management">Social Media Management</div>
            <div class="card" role="listitem" aria-label="Photo Production">Photo Production</div>
            <div class="card" role="listitem" aria-label="UGC Video">UGC Video</div>
            <div class="card" role="listitem" aria-label="Campaign">Campaign</div>
        </div>
    </div>
</section>

<section class="section-running" aria-label="Running animation">
    <div class="running-container">
        <img src="{{ asset('img/run.png') }}" alt="Running" class="running-animation">
        <img src="{{ asset('img/run.png') }}" alt="Running" class="running-animation">
        <img src="{{ asset('img/run.png') }}" alt="Running" class="running-animation"> 
    </div>
</section>

<section class="section-clients" aria-labelledby="clientsTitle">
    <div class="clients-inner">
        <h2 id="clientsTitle">Our <strong>clients</strong></h2>
        <div class="clients-content">
            <img src="{{ asset('img/client.png') }}" alt="Our Clients" class="clients-image">
        </div>
        <div class="clients-footer">
            <a href="{{ route('client') }}" class="btn-view-all">View All Clients</a>
        </div>
    </div>
</section>

<section class="section-contact" aria-labelledby="contactTitle">
    <div class="contact-inner">
        <div class="contact-form-wrapper">
            <form class="contact-form" id="contactForm" method="POST" action="{{ route('contact.store') }}">
                @csrf
                <input type="text" class="form-input" placeholder="Name" name="name" required>
                <input type="email" class="form-input" placeholder="Email" name="email" required>
                <input type="tel" class="form-input" placeholder="Phone number" name="phone" required>
                <button type="submit" class="btn-submit">Submit</button>
            </form>
        </div>
        <div class="contact-text-wrapper">
            <p><strong>Studio Tigapagi</strong> is a leading digital creative agency and dedicated creative makerspace located in Sanur, Denpasar, Bali. Known for its high-commitment culture, the agency describes its team as "passionate nocturnal folks" who specialize in delivering high-impact branding, sophisticated digital content strategy, and exceptional visual production.</p>
        </div>
    </div>
</section>

<footer class="section-footer" aria-labelledby="footerTitle">
    <div class="footer-bg-bottom" aria-hidden="true"></div>
    <div class="footer-bg-top" aria-hidden="true"></div>
    <div class="footer-gradient-top" aria-hidden="true"></div>
    
    <div class="footer-wrapper">
        <div class="footer-content">
            <!-- Left Side -->
            <div class="footer-left">
                <div class="footer-section">
                    <h4 class="footer-section-title">Our Social Media</h4>
                    <div class="footer-socials">
                        <div class="social-item">
                            <img src="{{ asset('img/IG.png') }}" alt="Instagram">
                            <span>@studio.tigapagi</span>
                        </div>
                        <div class="social-item">
                            <img src="{{ asset('img/T.png') }}" alt="TikTok">
                            <span>@studio.tigapagi</span>
                        </div>
                        <div class="social-item">
                            <img src="{{ asset('img/TT.png') }}" alt="Twitter">
                            <span>@studio.tigapagi</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Info Row Below Social Media -->
                <div class="footer-contact-row">
                    <div class="footer-contact-left">
                        <div class="contact-item">
                            <h4>Email</h4>
                            <p>produksitigapagi@gmail.com</p>
                        </div>
                        <div class="contact-item">
                            <h4>Contact</h4>
                            <p>0896-3889-3601 - Felix</p>
                        </div>
                    </div>
                    <div class="footer-contact-right">
                        <h4 class="footer-section-title">Bali</h4>
                        <p class="footer-location-text">Jl. Danau Tamblingan No.226, Sanur, Denpasar Selatan, Kota Denpasar, Bali</p>
                    </div>
                </div>
            </div>

            <!-- Middle: Empty for spacing -->
            <div class="footer-middle">
            </div>

            <!-- Right Side: Logo -->
            <div class="footer-right">
                <div class="footer-logo">
                    <img src="{{ asset('img/TP.png') }}" alt="Studio Tigapagi Logo">
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="footer-bottom-left">
                <a href="#" class="footer-link">Privacy Policy</a>
                <span class="footer-link-text">Daze</span>
                <span class="footer-link-text">Setiawan</span>
            </div>
            <div class="footer-bottom-right">
                <span class="footer-copyright">©Studio Tigapagi 2025</span>
            </div>
        </div>
    </div>
</footer>

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

// Detect Developer Tools (Simplistic check)
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

// Suppress console messages
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