import React from 'react';
import { Link } from 'react-router-dom';

function Footer() {
  return (
    <footer className="relative bg-black overflow-hidden">
      
      <div className="relative z-10 max-w-7xl mx-auto px-8 py-16">
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
                  <p className="text-white">0896-3889-3601 - Felix</p>
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
            <a href="#" className="underline hover:text-white transition-colors">Privacy Policy</a>
            <span>Daze</span>
            <span>Setiawan</span>
          </div>
          <span className="text-sm text-white/60">©Studio Tigapagi 2025</span>
        </div>
      </div>
    </footer>
  );
}

export default Footer;
