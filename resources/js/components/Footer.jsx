import React from 'react';
import { Link } from 'react-router-dom';

function Footer() {
  return (
    <footer className="relative bg-black overflow-hidden">

      {/* Animated Blurred Background - Footer.png */}
      <div className="absolute inset-0 overflow-hidden pointer-events-none" style={{ zIndex: 0 }}>
        <div
          className="animate-footer-pan h-full"
          style={{
            width: '200%',
            backgroundImage: 'url(/img/Footer.png)',
            backgroundSize: '150% auto',
            backgroundRepeat: 'repeat-x',
            backgroundPosition: 'center',
            opacity: 0.95,
            filter: 'blur(150px)',
            WebkitFilter: 'blur(150px)',
          }}
        />
      </div>

      {/* Gradient Top - smooth transition from main content */}
      <div
        className="absolute top-0 left-0 right-0 h-[120px] pointer-events-none"
        style={{
          background: 'linear-gradient(to bottom, rgba(0,0,0,1) 0%, rgba(0,0,0,0.6) 40%, rgba(0,0,0,0) 100%)',
          zIndex: 5
        }}
      />

      <div className="relative z-10 max-w-7xl mx-auto px-8 py-16">
        {/* Mobile layout */}
        <div className="md:hidden">
          <div className="h-px bg-white/80" />

          <div className="pt-10">
            <h4 className="text-xl font-semibold text-white mb-6">Our Social Media</h4>
            <div className="flex flex-col gap-6">
              <a href="https://instagram.com/studio.tigapagi" target="_blank" rel="noopener noreferrer" className="flex items-center gap-4 text-white">
                <img src="/img/IG.png" alt="Instagram" className="w-12 h-12" />
                <span className="text-lg font-semibold">@studio.tigapagi</span>
              </a>
              <a href="https://tiktok.com/@studio.tigapagi" target="_blank" rel="noopener noreferrer" className="flex items-center gap-4 text-white">
                <img src="/img/TT.png" alt="TikTok" className="w-12 h-12" />
                <span className="text-lg font-semibold">@studio.tigapagi</span>
              </a>
              <a href="https://threads.net/@studio.tigapagi" target="_blank" rel="noopener noreferrer" className="flex items-center gap-4 text-white">
                <img src="/img/T.png" alt="Threads" className="w-12 h-12" />
                <span className="text-lg font-semibold">@studio.tigapagi</span>
              </a>
            </div>
          </div>

          <div className="my-10 h-px bg-white/80" />

          <div className="space-y-8">
            <div>
              <h4 className="text-sm font-semibold text-white uppercase tracking-wider mb-3">EMAIL</h4>
              <p className="text-white/80 text-lg">produksitigapagi@gmail.com</p>
            </div>
            <div>
              <h4 className="text-sm font-semibold text-white uppercase tracking-wider mb-3">CONTACT</h4>
              <p className="text-white/80 text-lg">0896-3889-3601 - Felix</p>
            </div>
            <div>
              <h4 className="text-sm font-semibold text-white uppercase tracking-wider mb-3">HEAD OFFICE</h4>
              <p className="text-white/80 text-lg leading-relaxed">
                Jl. Danau Tamblingan No.226, Sanur,
                Denpasar Selatan, Kota Denpasar, Bali
              </p>
            </div>
          </div>

          <div className="my-10 h-px bg-white/80" />

          <div className="flex items-center justify-between">
            <img src="/img/TP.png" alt="Studio Tigapagi Logo" className="w-16 h-16" />
            <img src="/img/worth.svg" alt="Make it worth" className="h-10" />
          </div>

          <div className="mt-8 text-center">
            <div className="flex items-center justify-center gap-6 text-base text-white/80">
              <a href="#" className="underline hover:text-white transition-colors">Privacy Policy</a>
              <a href="https://instagram.com/dazee._" target="_blank" rel="noopener noreferrer" className="underline hover:text-white transition-colors">Daze</a>
              <a href="https://instagram.com/ag.setiawannn" target="_blank" rel="noopener noreferrer" className="underline hover:text-white transition-colors">Setiawan</a>
            </div>
            <div className="mt-4 text-base text-white/80">©Studio Tigapagi 2025</div>
          </div>
        </div>

        {/* Desktop layout */}
        <div className="hidden md:block">
          <div className="flex flex-col lg:flex-row justify-between gap-12">
            {/* Left Side - Content */}
            <div className="flex-1 space-y-8">
              {/* Social Media */}
              <div>
                <h4 className="text-base font-medium text-white mb-6">
                  Our Social Media
                </h4>
                <div className="flex flex-wrap gap-8">
                  <a href="https://instagram.com/studio.tigapagi" target="_blank" rel="noopener noreferrer" className="flex items-center gap-3 text-white hover:text-green-400 transition-colors">
                    <img src="/img/IG.png" alt="Instagram" className="w-10 h-10" />
                    <span className="text-sm">@studio.tigapagi</span>
                  </a>
                  <a href="https://tiktok.com/@studio.tigapagi" target="_blank" rel="noopener noreferrer" className="flex items-center gap-3 text-white hover:text-green-400 transition-colors">
                    <img src="/img/TT.png" alt="TikTok" className="w-10 h-10" />
                    <span className="text-sm">@studio.tigapagi</span>
                  </a>
                  <a href="https://threads.net/@studio.tigapagi" target="_blank" rel="noopener noreferrer" className="flex items-center gap-3 text-white hover:text-green-400 transition-colors">
                    <img src="/img/T.png" alt="Threads" className="w-10 h-10" />
                    <span className="text-sm">@studio.tigapagi</span>
                  </a>
                </div>
              </div>

              {/* Contact Info Grid */}
              <div className="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                <div className="space-y-6">
                  <div>
                    <h4 className="text-sm font-semibold text-white uppercase tracking-wider mb-2">
                      EMAIL
                    </h4>
                    <p className="text-white">produksitigapagi@gmail.com</p>
                  </div>
                  <div>
                    <h4 className="text-sm font-semibold text-white uppercase tracking-wider mb-2">
                      CONTACT
                    </h4>
                    <p className="text-white">0896-3889-3601 - Felix Marbun</p>
                  </div>
                </div>
                <div className="md:-ml-27">
                  <h4 className="text-sm font-semibold text-white uppercase tracking-wider mb-2">
                    HEAD OFFICE
                  </h4>
                  <p className="text-white/80 leading-relaxed">
                    Jl. Danau Tamblingan No.226, Sanur, Denpasar Selatan,<br />
                    Kota Denpasar, Bali
                  </p>
                </div>
              </div>
            </div>

            {/* Right Side - Logo */}
            <div className="flex items-center justify-center lg:justify-end">
              <img
                src="/img/TP.png"
                alt="Studio Tigapagi Logo"
                className="w-40 md:w-48"
              />
            </div>
          </div>

          {/* Footer Bottom */}
          <div className="mt-16 flex flex-col md:flex-row justify-between items-center gap-4">
            <div className="flex items-center gap-6 text-sm text-white/80">
              <a href="https://instagram.com/dazee._" target="_blank" rel="noopener noreferrer" className="hover:text-green-400 transition-colors">Daze</a>
              <a href="https://instagram.com/ag.setiawannn" target="_blank" rel="noopener noreferrer" className="hover:text-green-400 transition-colors">Setiawan</a>
            </div>
            <span className="text-sm text-white/60">©Studio Tigapagi 2025</span>
          </div>
        </div>
      </div>
    </footer>
  );
}

export default Footer;
