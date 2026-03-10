import React from 'react';
import { Link } from 'react-router-dom';

function Footer() {
  return (
    <footer className="relative bg-black overflow-hidden">
      {/* Background gradients */}
      <div className="absolute inset-0 bg-gradient-to-t from-green-900/20 to-transparent pointer-events-none" />
      
      <div className="relative z-10 max-w-7xl mx-auto px-6 py-12 md:py-16">
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-12">
          {/* Left Side */}
          <div className="lg:col-span-2 space-y-8">
            {/* Divider */}
            <div className="h-px bg-white/20" />
            
            {/* Social Media */}
            <div>
              <h4 className="text-sm font-semibold text-white/60 uppercase tracking-wider mb-4">
                Our Social Media
              </h4>
              <div className="flex flex-wrap gap-6">
                <a href="#" className="flex items-center gap-2 text-white hover:text-green-400 transition-colors">
                  <img src="/img/IG.png" alt="Instagram" className="w-5 h-5" />
                  <span className="text-sm">@studio.tigapagi</span>
                </a>
                <a href="#" className="flex items-center gap-2 text-white hover:text-green-400 transition-colors">
                  <img src="/img/TT.png" alt="TikTok" className="w-5 h-5" />
                  <span className="text-sm">@studio.tigapagi</span>
                </a>
                <a href="#" className="flex items-center gap-2 text-white hover:text-green-400 transition-colors">
                  <img src="/img/T.png" alt="Threads" className="w-5 h-5" />
                  <span className="text-sm">@studio.tigapagi</span>
                </a>
              </div>
            </div>

            {/* Divider */}
            <div className="h-px bg-white/20" />

            {/* Contact Info */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
              <div className="space-y-4">
                <div>
                  <h4 className="text-xs font-semibold text-white/60 uppercase tracking-wider mb-1">
                    EMAIL
                  </h4>
                  <p className="text-white">produksitigapagi@gmail.com</p>
                </div>
                <div>
                  <h4 className="text-xs font-semibold text-white/60 uppercase tracking-wider mb-1">
                    CONTACT
                  </h4>
                  <p className="text-white">0896-3889-3601 - Felix</p>
                </div>
              </div>
              <div>
                <h4 className="text-xs font-semibold text-white/60 uppercase tracking-wider mb-1">
                  HEAD OFFICE
                </h4>
                <p className="text-white/80 text-sm leading-relaxed">
                  Jl. Danau Tamblingan No.226, Sanur, Denpasar Selatan, Kota Denpasar, Bali
                </p>
              </div>
            </div>

            {/* Divider */}
            <div className="h-px bg-white/20" />
          </div>

          {/* Right Side - Logo */}
          <div className="flex flex-col items-center lg:items-end justify-center">
            <img 
              src="/img/TP.png" 
              alt="Studio Tigapagi Logo" 
              className="w-32 md:w-40 mb-4"
            />
            <img 
              src="/img/worth.svg" 
              alt="Make it Worth" 
              className="h-6 md:h-8"
            />
          </div>
        </div>

        {/* Footer Bottom */}
        <div className="mt-12 pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-4">
          <div className="flex items-center gap-4 text-sm text-white/60">
            <a href="#" className="hover:text-white transition-colors">Privacy Policy</a>
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
