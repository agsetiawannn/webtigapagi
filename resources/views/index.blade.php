<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Tigapagi</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
    </div>

    <button aria-label="Menu" onclick="toggleMenu()" class="btn--icon" id="menuBtn">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
    </button>

    <a href="#" class="btn" title="Contact">
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

<!-- Works / Services section (background image provided by you) -->
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

<!-- Running animation section -->
<section class="section-running" aria-label="Running animation">
    <div class="running-container">
        <img src="{{ asset('img/run.png') }}" alt="Running" class="running-animation">
        <img src="{{ asset('img/run.png') }}" alt="Running" class="running-animation">
        <img src="{{ asset('img/run.png') }}" alt="Running" class="running-animation"> </div>
</section>

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