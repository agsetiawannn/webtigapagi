<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Team - Tigapagi</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <style>
        body {
            background-image: url('{{ asset('img/COver 1.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .coming-soon-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
            padding: 20px;
        }
        .coming-soon-content {
            max-width: 600px;
        }
        .coming-soon-content p {
            font-size: clamp(1rem, 2vw, 1.5rem);
            color: rgba(255, 255, 255, 0.9);
            margin-top: 1rem;
        }
    </style>
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

<div class="coming-soon-container">
    <div class="coming-soon-content">
        <p>We will update soon</p>
    </div>
</div>

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
</script>

</body>
</html>
