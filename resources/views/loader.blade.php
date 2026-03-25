<!-- Loading Screen -->
<div class="loader-wrapper" id="loader">
    <div class="liquid-bg">
        <div class="blob blob1"></div>
        <div class="blob blob2"></div>
        <div class="blob blob3"></div>
        <div class="blob blob4"></div>
        <div class="blob blob5"></div>
        <div class="blob blob6"></div>
        <div class="blob blob7"></div>
        <div class="blob blob8"></div>
        <div class="blob blob9"></div>
        <div class="blob blob10"></div>
        <div class="blob blob11"></div>
    </div>

    <div class="logo-container">
        <img src="{{ asset('img/tp.svg') }}" class="logo-circle" alt="Logo">
        <img src="{{ asset('img/tp1.svg') }}" class="brand-text" alt="TIGAPAGI">
    </div>
</div>

<script>
window.addEventListener('load', function() {
    setTimeout(function() {
        const loader = document.getElementById('loader');
        if (loader) {
            loader.classList.add('fade-out');
            setTimeout(function() {
                loader.remove();
            }, 1000);
        }
    }, 4500);
});
</script>
