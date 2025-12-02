<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Tigapagi</title>
<style>
:root{ --bg:#000; --text:#fff; --muted:#bdbdbd; --primary:#fff; }
html,body{height:100%;margin:0;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,"Helvetica Neue",Arial, sans-serif;background:var(--bg);color:var(--text)}

/* ... (Kode CSS HERO, Backgrounds, Logo, Controls-Top-Right sama) ... */

/* HERO section styling remains the same */
.hero{
    position:relative;
    min-height:100vh;
    display:flex;
    align-items:center;
    text-align: center;
    padding:72px 48px;
    box-sizing:border-box;
    overflow:hidden;
}

/* Backgrounds remain the same */
.hero-bg-bottom{
    z-index:0;
    background-image: url('{{ asset("img/BG.png") }}');
    opacity:0.95;
    mix-blend-mode: normal;
    -webkit-filter: blur(50px);
    filter: blur(50px);
    position:absolute;
    inset:0;
    background-size: 200% auto;
    background-position: 0% center;
    background-repeat: repeat-x;
    animation: pan 6s linear infinite;
}
.hero-bg-top{
    z-index:1;
    background-image: url('{{ asset("img/BG2.png") }}');
    opacity:35%;
    mix-blend-mode: normal;
    position:absolute;
    inset:0;
    background-size:cover;
    background-position:center;
    background-repeat:no-repeat;
}
.hero > .container{ position:relative; z-index:2; }
.container{ max-width:1100px;margin:0 auto }

/* Logo top-left remains the same */
.logo-top-left{
    position:fixed;top:29px;left:26px;right:auto;
    z-index:60;
    display:flex;
    align-items:center;
    gap:12px;
    padding-right:6px
}
.logo-img{
    width:190px;
    height:48px;
}

/* controls on top-right (Container Utama) */
.controls-top-right{
    position:fixed;top:29px;right:26px;
    z-index:70;
    display:flex;
    align-items:center;
    gap:12px
}

/* Button Styling (Contact Button) */
.btn{
    background:transparent;
    border:1px solid rgba(255,255,255,0.08);
    padding:15px 17px;
    border-radius:10px;color:var(--text);
    display:inline-flex;
    align-items:center;
    gap:8px;
    text-decoration:none;
    font-size:14px
}

/* --- PERBAIKAN 1: Tombol Hamburger Tanpa Border --- */
.btn--icon{
    padding:10px;
    width:48px;
    height:48px;
    cursor:pointer;
    border: 1px solid rgba(255,255,255,0.08); /* Tambahkan border yang sama dengan Contact */
    background: transparent;
    border-radius:10px; /* Tambahkan radius agar seperti Contact */
    display: flex; /* Tambahkan flex untuk align tengah isian bar */
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.btn--icon .bar{ display:block; width:20px; height:2px; background:var(--text); margin:3px 0; transition: none }


/* --- NAVIGATION MENU CONTAINER (YANG MEMANJANG) --- */
.nav-menu-container{
    position:relative;
    display:flex;
    align-items:center;
    justify-content:center;
    flex-direction: row;
    width:0;
    height:48px; /* match hamburger button height */
    box-sizing:border-box;
    opacity:0;
    pointer-events:none;
    overflow:hidden;
    transition: width 0.3s ease-out, opacity 0.3s ease-out, height 0.12s ease;

    /* Styling Pill Navigasi (kotak berisi Home, Work, Team) */
    background: transparent;
    border: 1px solid rgba(255,255,255,0.12);
    border-radius:10px;
    backdrop-filter: blur(6px);
}

.nav-menu-container.active {
    width: 250px;
    height:48px; /* keep same height when expanded */
    opacity: 1;
    pointer-events: auto;
    padding: 0 10px; 
}

/* --- PERBAIKAN 2: Aligment Tautan Menu Rata Tengah Vertikal --- */
/* Styling Tautan di dalamnya (Home, Work, Team) */
.nav-menu-container a{
    white-space:nowrap;
    font-size:18px;
    text-decoration:none;
    color:var(--primary);
    display:flex;
    align-items:center;
    justify-content:center;
    height:48px;
    padding:0 12px;
    border-radius:8px;
    transition: background-color 0.1s ease;
}

.nav-menu-container a:hover {
    transform: scale(1.06);
}


/* ... (Kode CSS lainnya sama) ... */
.nav-pill{ display:none!important; }

@media(max-width:700px){ .controls-top-right{gap:8px} }

.hero h1{font-size:64px;line-height:0.95;margin:0 0 36px;font-weight:800;letter-spacing:-1px}
.lead{max-width:900px;color:var(--muted);font-size:15px}
@media(max-width:900px){.hero{padding:40px 20px}.hero h1{font-size:36px}}

@keyframes pan {
    from { background-position: 0% 50%; }
    to   { background-position: 100% 50%; }
}
</style>
</head>
<body>

<div class="logo-top-left" title="Ganti dengan foto/logo kamu nanti">
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
    <div class="hero-bg hero-bg-bottom" aria-hidden="true"></div>
    <div class="hero-bg hero-bg-top" aria-hidden="true"></div>
    <div class="container">
        <h1>A creative makerspace that consist of passionate nocturnal folks</h1>
    </div>
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