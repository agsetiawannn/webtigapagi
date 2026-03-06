<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Work - Tigapagi</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/work/work.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index/index.css') }}">
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
        <a href="{{ url('/') }}">Home</a>
        <a href="{{ url('/work') }}">Work</a>
        <a href="{{ url('/team') }}">Team</a>
        <a href="{{ url('/tracking/login.php') }}">Tracking</a>
    </div>

    <button aria-label="Menu" onclick="toggleMenu()" class="btn--icon" id="menuBtn">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
    </button>

    <a href="https://api.whatsapp.com/send/?phone=6289638893601&text&type=phone_number&app_absent=0" class="btn" title="Contact" target="_blank" rel="noopener noreferrer">
        <img src="{{ asset('img/wa.png') }}" alt="WhatsApp" style="width:18px;height:18px;border-radius:50%;display:inline-block;object-fit:cover;" />
        Contact
    </a>
</div>

<!-- Hero Stats Section with Background -->
<section class="hero work-hero">
    <div class="hero-gradient-bottom" aria-hidden="true"></div>
    <div class="hero-bg hero-bg-bottom" aria-hidden="true"></div>
    <div class="hero-gradient-top" aria-hidden="true"></div>
    <div class="hero-bg hero-bg-top" aria-hidden="true"></div>
    
    <!-- Stats Section -->
    <div class="container stats-container">
        <div class="section-stats">
            <div class="stat-item">
                <div class="stat-number">345+</div>
                <div class="stat-label">Project Finished</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">100+</div>
                <div class="stat-label">Clients</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">4+</div>
                <div class="stat-label">Years Experienced</div>
            </div>
        </div>
    </div>
</section>

<!-- Content with Black Background -->
<main class="work-content">
    <!-- Selected Works Section -->
    <section class="section-selected-works">
    <div class="selected-works-header">
        <h2>Selected</h2>
        <h2><em>Works</em></h2>
    </div>
    
    <div class="works-list">
        <div class="work-item">
            <span class="work-number">01</span>
            <h3 class="work-title">Pertamina</h3>
            <p class="work-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            <div class="work-gallery">
                <div class="gallery-left">
                    <img src="{{ asset('img/work/cbt1.svg') }}" alt="Pertamina Work 1">
                </div>
                <div class="gallery-middle">
                    <img src="{{ asset('img/work/cbt1.svg') }}" alt="Pertamina Work 2">
                </div>
                <div class="gallery-right">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Pertamina Work 3">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Pertamina Work 4">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Pertamina Work 3">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Pertamina Work 4">
                </div>
            </div>
        </div>
        <div class="work-item">
            <span class="work-number">02</span>
            <h3 class="work-title">Yamaha Bali</h3>
            <p class="work-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            <div class="work-gallery">
                <div class="gallery-left">
                    <img src="{{ asset('img/work/cbt1.svg') }}" alt="Yamaha Work 1">
                </div>
                <div class="gallery-middle">
                    <img src="{{ asset('img/work/cbt1.svg') }}" alt="Yamaha Work 2">
                </div>
                <div class="gallery-right">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Yamaha Work 3">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Yamaha Work 4">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Yamaha Work 3">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Yamaha Work 4">
                </div>
            </div>
        </div>
        <div class="work-item">
            <span class="work-number">03</span>
            <h3 class="work-title">Elementis</h3>
            <p class="work-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            <div class="work-gallery">
                <div class="gallery-left">
                    <img src="{{ asset('img/work/cbt1.svg') }}" alt="Elementis Work 1">
                </div>
                <div class="gallery-middle">
                    <img src="{{ asset('img/work/cbt1.svg') }}" alt="Elementis Work 2">
                </div>
                <div class="gallery-right">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Elementis Work 3">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Elementis Work 4">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Elementis Work 3">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Elementis Work 4">
                </div>
            </div>
        </div>
        <div class="work-item">
            <span class="work-number">04</span>
            <h3 class="work-title">The Smoke House</h3>
            <p class="work-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            <div class="work-gallery">
                <div class="gallery-left">
                    <img src="{{ asset('img/work/cbt1.svg') }}" alt="Smoke House Work 1">
                </div>
                <div class="gallery-middle">
                    <img src="{{ asset('img/work/cbt1.svg') }}" alt="Smoke House Work 2">
                </div>
                <div class="gallery-right">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Smoke House Work 3">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Smoke House Work 4">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Smoke House Work 3">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Smoke House Work 4">
                </div>
            </div>
        </div>
        <div class="work-item">
            <span class="work-number">05</span>
            <h3 class="work-title">Balisabi</h3>
            <p class="work-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            <div class="work-gallery">
                <div class="gallery-left">
                    <img src="{{ asset('img/work/cbt1.svg') }}" alt="Balisabi Work 1">
                </div>
                <div class="gallery-middle">
                    <img src="{{ asset('img/work/cbt1.svg') }}" alt="Balisabi Work 2">
                </div>
                <div class="gallery-right">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Balisabi Work 3">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Balisabi Work 4">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Balisabi Work 3">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Balisabi Work 4">
                </div>
            </div>
        </div>
        <div class="work-item">
            <span class="work-number">06</span>
            <h3 class="work-title">Mallali</h3>
            <p class="work-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            <div class="work-gallery">
                <div class="gallery-left">
                    <img src="{{ asset('img/work/cbt1.svg') }}" alt="Mallali Work 1">
                </div>
                <div class="gallery-middle">
                    <img src="{{ asset('img/work/cbt1.svg') }}" alt="Mallali Work 2">
                </div>
                <div class="gallery-right">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Mallali Work 3">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Mallali Work 4">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Mallali Work 3">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Mallali Work 4">
                </div>
            </div>
        </div>
        <div class="work-item">
            <span class="work-number">07</span>
            <h3 class="work-title">Summerhouse</h3>
            <p class="work-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            <div class="work-gallery">
                <div class="gallery-left">
                    <img src="{{ asset('img/work/cbt1.svg') }}" alt="Summerhouse Work 1">
                </div>
                <div class="gallery-middle">
                    <img src="{{ asset('img/work/cbt1.svg') }}" alt="Summerhouse Work 2">
                </div>
                <div class="gallery-right">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Summerhouse Work 3">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Summerhouse Work 4">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Summerhouse Work 3">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Summerhouse Work 4">
                </div>
            </div>
        </div>
        <div class="work-item">
            <span class="work-number">08</span>
            <h3 class="work-title">Samaya</h3>
            <p class="work-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            <div class="work-gallery">
                <div class="gallery-left">
                    <img src="{{ asset('img/work/cbt1.svg') }}" alt="Samaya Work 1">
                </div>
                <div class="gallery-middle">
                    <img src="{{ asset('img/work/cbt1.svg') }}" alt="Samaya Work 2">
                </div>
                <div class="gallery-right">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Samaya Work 3">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Samaya Work 4">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Samaya Work 3">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Samaya Work 4">
                </div>
            </div>
        </div>
        <div class="work-item">
            <span class="work-number">09</span>
            <h3 class="work-title">Roku Grill</h3>
            <p class="work-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            <div class="work-gallery">
                <div class="gallery-left">
                    <img src="{{ asset('img/work/cbt1.svg') }}" alt="Roku Grill Work 1">
                </div>
                <div class="gallery-middle">
                    <img src="{{ asset('img/work/cbt1.svg') }}" alt="Roku Grill Work 2">
                </div>
                <div class="gallery-right">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Roku Grill Work 3">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Roku Grill Work 4">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Roku Grill Work 3">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Roku Grill Work 4">
                </div>
            </div>
        </div>
        <div class="work-item">
            <span class="work-number">10</span>
            <h3 class="work-title">Sera Face</h3>
            <p class="work-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            <div class="work-gallery">
                <div class="gallery-left">
                    <img src="{{ asset('img/work/cbt1.svg') }}" alt="Sera Face Work 1">
                </div>
                <div class="gallery-middle">
                    <img src="{{ asset('img/work/cbt1.svg') }}" alt="Sera Face Work 2">
                </div>
                <div class="gallery-right">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Sera Face Work 3">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Sera Face Work 4">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Sera Face Work 3">
                    <img src="{{ asset('img/work/cbt2.svg') }}" alt="Sera Face Work 4">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- And Manny More Section -->
<section class="section-clients-more">
    <h2 class="clients-more-title">And Manny More</h2>
    <div class="clients-logos-grid">
        <img src="{{ asset('img/ClientWrap.png') }}" alt="Our Clients" class="clients-all-image">
    </div>
</section>

<!-- Contact Section -->
<section class="section-contact-work">
    <div class="contact-work-inner">
        <div class="contact-form-side">
            <form id="contactForm" method="POST" action="{{ route('contact.store') }}">
                @csrf
                <input type="text" class="form-input" placeholder="Name" name="name" required>
                <input type="email" class="form-input" placeholder="Email" name="email" required>
                <input type="tel" class="form-input" placeholder="Phone number" name="phone" required>
                <button type="submit" class="btn-submit">Submit</button>
            </form>
        </div>
        <div class="contact-text-side">
            <h3>Be a part of us</h3>
            <p><strong>Studio Tigapagi</strong> is a leading digital creative agency and dedicated creative makerspace located in Sanur, Denpasar, Bali.</p>
        </div>
    </div>
</section>
</main>

<!-- Footer -->
<footer class="section-footer" aria-labelledby="footerTitle">
    <div class="footer-bg-bottom" aria-hidden="true"></div>
    <div class="footer-bg-top" aria-hidden="true"></div>
    <div class="footer-gradient-top" aria-hidden="true"></div>
    
    <div class="footer-wrapper">
        <div class="footer-content">
            <!-- Left Side -->
            <div class="footer-left">
                <div class="footer-divider"></div>
                <div class="footer-section">
                    <h4 class="footer-section-title">Our Social Media</h4>
                    <div class="footer-socials">
                        <div class="social-item">
                            <img src="{{ asset('img/IG.png') }}" alt="Instagram">
                            <span>@studio.tigapagi</span>
                        </div>
                        <div class="social-item">
                            <img src="{{ asset('img/TT.png') }}" alt="TikTok">
                            <span>@studio.tigapagi</span>
                        </div>
                        <div class="social-item">
                            <img src="{{ asset('img/T.png') }}" alt="Threads">
                            <span>@studio.tigapagi</span>
                        </div>
                    </div>
                </div>

                <div class="footer-divider"></div>

                <!-- Contact Info Row Below Social Media -->
                <div class="footer-contact-row">
                    <div class="footer-contact-left">
                        <div class="contact-item">
                            <h4>EMAIL</h4>
                            <p>produksitigapagi@gmail.com</p>
                        </div>
                        <div class="contact-item">
                            <h4>CONTACT</h4>
                            <p>0896-3889-3601 - Felix</p>
                        </div>
                    </div>
                    <div class="footer-contact-right">
                        <h4 class="footer-section-title">HEAD OFFICE</h4>
                        <p class="footer-location-text">Jl. Danau Tamblingan No.226, Sanur, Denpasar Selatan, Kota Denpasar, Bali</p>
                    </div>
                </div>

                <div class="footer-divider"></div>
            </div>

            <!-- Middle: Empty for spacing -->
            <div class="footer-middle">
            </div>

            <!-- Right Side: Logo -->
            <div class="footer-right">
                <div class="footer-logos-container">
                    <div class="footer-logo">
                        <img src="{{ asset('img/TP.png') }}" alt="Studio Tigapagi Logo">
                    </div>
                    <div class="footer-tagline">
                        <img src="{{ asset('img/worth.svg') }}" alt="Make it Worth" class="tagline-img">
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <a href="#" class="footer-link">Privacy Policy</a>
                <span class="footer-link-text">Daze</span>
                <span class="footer-link-text">Setiawan</span>
            </div>
            <div class="footer-copyright-container">
                <span class="footer-copyright">©Studio Tigapagi 2025</span>
            </div>
        </div>
    </div>
</footer>

<script>
function toggleMenu() {
    const menu = document.getElementById('navMenu');
    const btn = document.getElementById('menuBtn');
    menu.classList.toggle('active');
    btn.classList.toggle('active');
}

// Page Transition Animation
document.addEventListener('DOMContentLoaded', function() {
    if ('scrollRestoration' in history) {
        history.scrollRestoration = 'manual';
    }
    
    const links = document.querySelectorAll('a[href^="{{ url('/') }}"], a[href^="/"]');
    
    links.forEach(link => {
        if (link.hostname !== window.location.hostname || 
            link.getAttribute('target') === '_blank' ||
            link.getAttribute('href').startsWith('#')) {
            return;
        }
        
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            if (href && !href.startsWith('#') && !href.startsWith('mailto:') && !href.startsWith('tel:')) {
                e.preventDefault();
                
                const navMenu = document.getElementById('navMenu');
                const menuBtn = document.getElementById('menuBtn');
                if (navMenu && navMenu.classList.contains('active')) {
                    navMenu.classList.remove('active');
                    menuBtn.classList.remove('active');
                }
                
                document.body.classList.add('page-exit');
                
                setTimeout(() => {
                    window.location.href = href;
                }, 300);
            }
        });
    });
});
</script>

</body>
</html>
