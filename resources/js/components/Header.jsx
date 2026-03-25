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
            <div className="fixed top-3 left-3 md:top-6 md:left-6 z-50">
                <Link to="/">
                    <img
                        src="/img/tb.png"
                        alt="Tigapagi Logo"
                        className="h-9 md:h-12 object-contain"
                    />
                </Link>
            </div>

            {/* Controls */}
            <div className="fixed top-3 right-3 md:top-6 md:right-6 z-50 flex items-center gap-2 md:gap-3 overflow-visible">
                {/* Navigation Menu - Slides out from left of hamburger */}
                <div className="hidden md:block overflow-hidden">
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
                        style={{ backgroundColor: 'transparent', backdropFilter: 'blur(40px)', WebkitBackdropFilter: 'blur(40px)' }}
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

                <div className="relative flex items-center gap-2 md:gap-3">
                    {/* Hamburger Menu Button */}
                    <button
                        aria-label="Menu"
                        onClick={toggleMenu}
                        className={`
            w-9 h-9 md:w-12 md:h-12
            flex flex-col items-center justify-center gap-1.5
            border rounded-lg
            transition-colors
            ${menuOpen ? 'border-blue-400 ring-2 ring-blue-500/60' : 'border-white/30 hover:border-white/50'}
            `}
                        style={{ backgroundColor: 'transparent', backdropFilter: 'blur(40px)', WebkitBackdropFilter: 'blur(40px)' }}
                    >
                        <span className={`
            block w-4 md:w-5 h-0.5 bg-white transition-all duration-300
            ${menuOpen ? 'rotate-45 translate-y-2' : ''}
            `}></span>
                        <span className={`
            block w-4 md:w-5 h-0.5 bg-white transition-all duration-300
            ${menuOpen ? 'opacity-0' : ''}
            `}></span>
                        <span className={`
            block w-4 md:w-5 h-0.5 bg-white transition-all duration-300
            ${menuOpen ? '-rotate-45 -translate-y-2' : ''}
            `}></span>
                    </button>

                    {/* Contact Button */}
                    <a
                        href="https://api.whatsapp.com/send/?phone=6289638893601&text&type=phone_number&app_absent=0"
                        className="
            h-9 md:h-12
            flex items-center justify-center gap-2
            bg-transparent
            border border-white/30
            px-3 md:px-4
            rounded-lg
            text-xs md:text-sm font-medium text-white
            hover:border-white/50
            transition-colors
            "
                        style={{ backdropFilter: 'blur(40px)', WebkitBackdropFilter: 'blur(40px)' }}
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <img
                            src="/img/wa.png"
                            alt="WhatsApp"
                            className="w-4 h-4 md:w-5 md:h-5 rounded-full object-cover"
                        />
                        Contact
                    </a>

                    {/* Mobile menu - dropdown under hamburger + contact */}
                    <div
                        className={`
            absolute top-[calc(100%+8px)] right-0 z-50 md:hidden
            transition-all duration-300 ease-out
            ${menuOpen ? 'opacity-100 translate-y-0' : 'opacity-0 -translate-y-2 pointer-events-none'}
            `}
                    >
                        <nav className="w-35 rounded-2xl border border-white/20 bg-transparent text-center" style={{ backdropFilter: 'blur(40px)', WebkitBackdropFilter: 'blur(40px)' }}>
                            {navLinks.map((link, index) => (
                                link.external ? (
                                    <a
                                        key={link.path}
                                        href={link.path}
                                        onClick={() => setMenuOpen(false)}
                                        className={`
                    block px-5 py-3 text-base font-light text-white/90 hover:text-white
                    ${index !== navLinks.length - 1 ? 'border-b border-white/10' : ''}
                    `}
                                    >
                                        {link.label}
                                    </a>
                                ) : (
                                    <Link
                                        key={link.path}
                                        to={link.path}
                                        onClick={() => setMenuOpen(false)}
                                        className={`
                    block px-5 py-3 text-base font-light
                    ${location.pathname === link.path ? 'text-white' : 'text-white/80'}
                    hover:text-white
                    ${index !== navLinks.length - 1 ? 'border-b border-white/10' : ''}
                    `}
                                    >
                                        {link.label}
                                    </Link>
                                )
                            ))}
                        </nav>
                    </div>
                </div>
            </div>



        </>
    );
}

export default Header;
