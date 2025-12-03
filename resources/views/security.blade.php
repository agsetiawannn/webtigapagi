<!-- CARA MEMBUAT CODE SECURE / TIDAK BISA DI-INSPECT -->

<!-- 1. DISABLE RIGHT-CLICK & DEVELOPER TOOLS -->
<script>
// Disable right-click
document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
    return false;
});

// Disable developer tools (F12, Ctrl+Shift+I, Ctrl+Shift+C, Ctrl+Shift+J)
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

<!-- 2. DETECT DEVELOPER TOOLS & BLOCK PAGE -->
<script>
let devtools = { open: false, orientation: null };

const threshold = 160;

setInterval(function() {
    if (window.outerHeight - window.innerHeight > threshold || window.outerWidth - window.innerWidth > threshold) {
        if (!devtools.open) {
            devtools.open = true;
            // Bisa juga arahkan ke halaman lain atau tampilkan pesan
            alert('Developer Tools terdeteksi! Akses ditolak.');
            // Atau: window.location.href = 'about:blank';
        }
    } else {
        devtools.open = false;
    }
}, 500);
</script>

<!-- 3. MINIFY & OBFUSCATE CODE (Gunakan Tools) -->
<!-- - UglifyJS
    - Webpack
    - Terser
    - Google Closure Compiler -->

<!-- 4. DISABLE COPY-PASTE -->
<script>
document.addEventListener('copy', function(e) {
    e.preventDefault();
    alert('Copy tidak diizinkan!');
    return false;
});

document.addEventListener('cut', function(e) {
    e.preventDefault();
    return false;
});
</script>

<!-- 5. HIDE CONSOLE LOGS -->
<script>
// Override console methods
const noop = () => {};
console.log = noop;
console.warn = noop;
console.error = noop;
console.debug = noop;
</script>

<!-- 6. DETECT DEBUGGER & BLOCK -->
<script>
setInterval(function() {
    debugger;
}, 1000);
</script>

<!-- 7. ENKRIPSI DATA SENSITIVE -->
<script>
// Jangan simpan data sensitive di client-side
// Gunakan backend untuk proses sensitive

// Jika harus, gunakan enkripsi seperti:
// - crypto-js
// - TweetNaCl.js
// - libsodium.js

// Contoh simple:
function obfuscateEmail(email) {
    return btoa(email); // Base64 encoding
}

function deobfuscateEmail(encoded) {
    return atob(encoded); // Base64 decoding
}
</script>

<!-- 8. GUNAKAN CONTENT SECURITY POLICY (CSP) -->
<!-- Di file header atau .htaccess -->
<!-- Meta tag example: -->
<meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline';">

<!-- 9. DISABLE SOURCE MAPS DI PRODUCTION -->
<!-- Jangan build dengan source maps di production -->

<!-- 10. SERVICE WORKER UNTUK CACHING & PROTECTION -->
<script>
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js')
        .then(registration => console.log('SW registered'))
        .catch(error => console.error('SW registration failed'));
}
</script>

<!--
CATATAN PENTING:
- Client-side protection TIDAK 100% aman
- Hacker expert masih bisa bypass semua ini
- Untuk security maksimal, gunakan server-side protection:
  * Authentication & Authorization
  * HTTPS/SSL
  * Backend validation
  * Secure headers (X-Frame-Options, X-Content-Type-Options, dll)
  * Rate limiting
  * Input sanitization
  * CORS policies
  * Token-based authentication

- Jangan pernah simpan data/logic penting di client-side
- Selalu validate & secure di backend
-->
