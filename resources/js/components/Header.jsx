import React, { useState } from 'react';
import { Link, useLocation } from 'react-router-dom';

function Header() {
const [menuOpen, setMenuOpen] = useState(false);
const location = useLocation();

const toggleMenu = () => {
    setMenuOpen(!menuOpen);
};

const navLinks = [
    { path: '/', label: 'Home' },
    { path: '/work', label: 'Work' },
    { path: '/team', label: 'Team' },
    { path: '/tracking/login.php', label: 'Tracking', external: true },
];

return (
    <>
      {/* Logo */}
    <div className="fixed top-4 left-4 md:top-6 md:left-6 z-50">
        <Link to="/">
        <img 
            src="/img/tb.png" 
            alt="Tigapagi Logo" 
            className="h-12 object-contain"
        />
        </Link>
    </div>

      {/* Controls */}
    <div className="fixed top-4 right-4 md:top-6 md:right-6 z-50 flex items-center gap-3 overflow-visible">
        {/* Navigation Menu - Slides out from left of hamburger */}
        <div className="overflow-hidden">
        <nav 
            className={`
            flex items-center gap-6
            border border-white/20
            rounded-2xl
            px-6 py-3
            transition-transform duration-300 ease-out
            ${menuOpen 
                ? 'translate-x-0' 
                : 'translate-x-[110%]'
            }
            `}
            style={{ backgroundColor: 'transparent' }}
        >
            {navLinks.map((link) => (
            link.external ? (
                <a
                key={link.path}
                href={link.path}
                className="text-white text-base font-light hover:text-green-400 transition-colors whitespace-nowrap"
                onClick={() => setMenuOpen(false)}
                >
                {link.label}
                </a>
            ) : (
                <Link
                key={link.path}
                to={link.path}
                className={`
                    text-base font-light whitespace-nowrap
                    hover:text-green-400 transition-colors
                    ${location.pathname === link.path ? 'text-green-400' : 'text-white'}
                `}
                onClick={() => setMenuOpen(false)}
                >
                {link.label}
                </Link>
            )
            ))}
        </nav>
        </div>

        {/* Hamburger Menu Button */}
        <button
        aria-label="Menu"
        onClick={toggleMenu}
        className="
            w-12 h-12
            flex flex-col items-center justify-center gap-1.5
            border border-white/30
            rounded-xl
            hover:border-white/50
            transition-colors
        "
        style={{ backgroundColor: 'transparent' }}
        >
        <span className={`
            block w-5 h-0.5 bg-white transition-all duration-300
            ${menuOpen ? 'rotate-45 translate-y-2' : ''}
        `}></span>
        <span className={`
            block w-5 h-0.5 bg-white transition-all duration-300
            ${menuOpen ? 'opacity-0' : ''}
        `}></span>
        <span className={`
            block w-5 h-0.5 bg-white transition-all duration-300
            ${menuOpen ? '-rotate-45 -translate-y-2' : ''}
        `}></span>
        </button>

        {/* Contact Button */}
        <a
        href="https://api.whatsapp.com/send/?phone=6289638893601&text&type=phone_number&app_absent=0"
        className="
            h-12
            flex items-center justify-center gap-2
            bg-transparent
            border border-white/30
            px-4
            rounded-xl
            text-sm font-medium text-white
            hover:border-white/50
            transition-colors
        "
        target="_blank"
        rel="noopener noreferrer"
        >
        <img 
            src="/img/wa.png" 
            alt="WhatsApp" 
            className="w-5 h-5 rounded-full object-cover"
        />
        Contact
        </a>
    </div>

      {/* Overlay when menu is open */}
    <div 
        className={`
        fixed inset-0 bg-black/30 z-40
        transition-opacity duration-300
        ${menuOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'}
        `}
        onClick={() => setMenuOpen(false)}
    />
    </>
);
}

export default Header;
