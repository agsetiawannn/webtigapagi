import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';

function Home() {
    const [formData, setFormData] = useState({
        name: '',
        email: '',
        phone: '',
    });
    const [showClientWrap, setShowClientWrap] = useState(false);
    const [popup, setPopup] = useState({ show: false, success: true, message: '' });

    useEffect(() => {
        if (popup.show) {
            const timer = setTimeout(() => setPopup({ ...popup, show: false }), 4000);
            return () => clearTimeout(timer);
        }
    }, [popup.show]);

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const response = await fetch('/api/contact', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
                body: JSON.stringify(formData),
            });
            if (response.ok) {
                setPopup({ show: true, success: true, message: 'Your message has been sent successfully!' });
                setFormData({ name: '', email: '', phone: '' });
            } else {
                const errorData = await response.json().catch(() => null);
                setPopup({ show: true, success: false, message: errorData?.message || 'Failed to send message. Please try again.' });
            }
        } catch (error) {
            console.error('Error:', error);
            setPopup({ show: true, success: false, message: 'Network error. Please try again.' });
        }
    };

    const services = [
        'Branding',
        'Social Media Management',
        'Photo Production',
        'UGC Video',
        'Campaign',
    ];
    const runImages = [...Array(6)];

    return (
        <div>
            {/* Hero Section */}
            <section className="relative min-h-screen flex items-center justify-center overflow-hidden px-6 md:px-12">
                {/* Background Layer 1 - BG.png (bottom layer, with blur and pan animation) */}
                <div className="absolute inset-0 overflow-hidden pointer-events-none" style={{ zIndex: 0 }}>
                    <div
                        className="animate-pan-smooth h-full"
                        style={{
                            width: '300%',
                            backgroundImage: 'url(/img/BG.png)',
                            backgroundSize: '50% auto',
                            backgroundRepeat: 'repeat-x',
                            backgroundPosition: 'center',
                            opacity: 0.95,
                            filter: 'blur(100px)',
                        }}
                    />
                </div>

                {/* Background Layer 2 - BG2.png (top layer, static) */}
                <div
                    className="absolute inset-0 bg-cover bg-center pointer-events-none"
                    style={{
                        backgroundImage: 'url(/img/BG2.png)',
                        opacity: 0.35,
                        zIndex: 2
                    }}
                />

                {/* Gradient Top */}
                <div
                    className="absolute top-0 left-0 right-0 h-[300px] md:h-[300px] pointer-events-none"
                    style={{
                        background: 'linear-gradient(to bottom, rgba(0,0,0,1) 0%, rgba(0,0,0,0.4) 30%, rgba(0,0,0,0) 100%)',
                        zIndex: 5
                    }}
                />

                {/* Gradient Bottom */}
                <div
                    className="absolute bottom-0 left-0 right-0 h-[200px] md:h-[200px] pointer-events-none"
                    style={{
                        background: 'linear-gradient(to top, rgba(0,0,0,1) 0%, rgba(0,0,0,0.3) 50%, rgba(0,0,0,0) 100%)',
                        zIndex: 5
                    }}
                />

                <div className="relative z-10 w-full max-w-5xl mx-0 md:mx-auto px-0 md:px-10 text-left md:text-center">
                    <h1 className="text-5xl md:text-6xl lg:text-7xl font-light leading-tight text-white">
                        <span className="block md:inline">A creative </span>
                        <span className="block md:inline">makerspace </span>
                        <span className="block md:inline">that consist </span>
                        <span className="block md:inline">of passionate </span>
                        <span className="block md:inline">nocturnal </span>
                        <span className="block md:inline">folks.</span>
                    </h1>
                </div>
            </section>

            {/* Works Section */}
            <section className="relative py-20 bg-black overflow-hidden">
                <div className="relative z-10 max-w-6xl mx-auto px-6">
                    <p className="text-base md:text-lg text-white/70 leading-relaxed text-justify mb-10">
                        <strong className="text-white">Studio Tigapagi</strong> is a creative makerspace located in Sanur, Bali. Powered by “Passionate nocturnal folks” with high standarts and high commitment. Helping brands grow through branding, digital content strategy, social media campaigns, and visual production.
                    </p>
                    <p className="text-base md:text-lg text-white/70 leading-relaxed max-w-4xl mb-10">
                        Turn your vision into reality #Makeitworth
                    </p>

                    <div className="h-px bg-white/20 mb-10" />

                    <h2 className="text-3xl md:text-5xl lg:text-6xl text-white mb-12 leading-tight">
                        Unlock Your Brand's <strong className="font-bold">Potential</strong><br />
                        with our Strategy
                    </h2>

                    <div className="flex flex-wrap gap-4 justify-center">
                        {services.map((service, index) => (
                            <div
                                key={index}
                                className="border border-white/30 rounded-xl px-6 py-4 text-center hover:border-white/60 transition-all duration-300"
                            >
                                <span className="text-white font-medium text-sm md:text-base">{service}</span>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Running Animation Section */}
            <section className="bg-black overflow-hidden">
                <div className="flex overflow-hidden">
                    <div className="flex w-max animate-scroll">
                        {runImages.map((_, index) => (
                            <img
                                key={`run-a-${index}`}
                                src="/img/run.png"
                                alt="Running"
                                className="h-[60vh] md:h-[70vh] lg:h-[60vh] w-auto object-cover shrink-0"
                            />
                        ))}
                        {runImages.map((_, index) => (
                            <img
                                key={`run-b-${index}`}
                                src="/img/run.png"
                                alt="Running"
                                className="h-[60vh] md:h-[70vh] lg:h-[60vh] w-auto object-cover shrink-0"
                            />
                        ))}
                    </div>
                </div>
            </section>

            {/* Clients Section */}
            <section className="py-20 bg-black">
                <div className="max-w-6xl mx-auto px-6 text-center">
                    <h2 className="text-2xl md:text-4xl font-light text-white mb-12">
                        Our <strong className="font-semibold">Clients</strong>
                    </h2>

                    {/* Client Images Container */}
                    <div className="relative">
                        {/* Default client.png */}
                        <div
                            className={`transition-all duration-500 ease-in-out ${showClientWrap ? 'opacity-0 scale-95 absolute inset-0 pointer-events-none' : 'opacity-100 scale-100 relative'}`}
                        >
                            <img
                                src="/img/client.png"
                                alt="Our Clients"
                                className="w-full max-w-4xl mx-auto"
                            />
                        </div>

                        {/* Expanded clientwrap.png */}
                        <div
                            className={`transition-all duration-500 ease-in-out ${showClientWrap ? 'opacity-100 scale-100 relative' : 'opacity-0 scale-95 absolute inset-0 pointer-events-none'}`}
                        >
                            <img
                                src="/img/ClientWrap.png"
                                alt="All Clients"
                                className="w-full mx-auto"
                            />
                        </div>
                    </div>

                    <div className="mt-12 flex justify-center">
                        <button
                            onClick={() => setShowClientWrap(!showClientWrap)}
                            className="bg-transparent text-white border border-white/50 px-6 py-2 rounded-lg text-sm font-medium hover:bg-white hover:text-black transition-all duration-300 cursor-pointer"
                        >
                            {showClientWrap ? 'Show Less' : 'View All Clients'}
                        </button>
                    </div>

                </div>
            </section>

            {/* Motion Video Section */}
            <section className="bg-black">
                <video
                    src="/img/MOTION TP.mov"
                    autoPlay
                    muted
                    loop
                    playsInline
                    className="w-full"
                    style={{ display: 'block', objectFit: 'cover' }}
                />
            </section>

            {/* Contact Section */}
            <section className="py-20 bg-black">
                <div className="max-w-6xl mx-auto px-6">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                        {/* Form */}
                        <div>
                            <form onSubmit={handleSubmit} className="space-y-4">
                                <input
                                    type="text"
                                    placeholder="Name"
                                    value={formData.name}
                                    onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                                    className="w-full bg-zinc-900 border border-zinc-700 rounded-lg px-6 py-4 text-white placeholder-zinc-500 focus:outline-none focus:border-green-400 transition-colors"
                                    required
                                />
                                <input
                                    type="email"
                                    placeholder="Email"
                                    value={formData.email}
                                    onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                                    className="w-full bg-zinc-900 border border-zinc-700 rounded-lg px-6 py-4 text-white placeholder-zinc-500 focus:outline-none focus:border-green-400 transition-colors"
                                    required
                                />
                                <input
                                    type="tel"
                                    placeholder="Phone number"
                                    value={formData.phone}
                                    onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                                    className="w-full bg-zinc-900 border border-zinc-700 rounded-lg px-6 py-4 text-white placeholder-zinc-500 focus:outline-none focus:border-green-400 transition-colors"
                                    required
                                />
                                <button
                                    type="submit"
                                    className="bg-green-500 hover:bg-green-400 text-black font-semibold rounded-lg px-8 py-3 transition-colors"
                                >
                                    Submit
                                </button>
                            </form>
                        </div>

                        {/* Text */}
                        <div className="flex items-start">
                            <div className="text-white/80 leading-relaxed">
                                <h3 className="text-2xl md:text-3xl font-semibold text-white mb-4">
                                    We make ordinary brands unforgettable.
                                </h3>
                                <p className="text-lg text-justify mb-4">
                                    Studio Tigapagi is a creative partner for businesses ready to grow with intention — through strategic social media, powerful visuals, and branding that builds long-term trust.
                                </p>
                                <p className="text-lg text-justify">
                                    We're not trend-driven. We're strategy-led. We make it <strong className="text-white">Worth</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Success/Error Popup */}
            {popup.show && (
                <div
                    style={{
                        position: 'fixed',
                        inset: 0,
                        zIndex: 9999,
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        backgroundColor: 'rgba(0, 0, 0, 0.6)',
                        backdropFilter: 'blur(8px)',
                        animation: 'popupFadeIn 0.3s ease',
                    }}
                    onClick={() => setPopup({ ...popup, show: false })}
                >
                    <div
                        style={{
                            background: 'linear-gradient(135deg, rgba(20, 20, 20, 0.95), rgba(30, 30, 30, 0.9))',
                            border: popup.success ? '1px solid rgba(34, 197, 94, 0.3)' : '1px solid rgba(239, 68, 68, 0.3)',
                            borderRadius: '20px',
                            padding: '40px 48px',
                            maxWidth: '420px',
                            width: '90%',
                            textAlign: 'center',
                            boxShadow: popup.success
                                ? '0 0 60px rgba(34, 197, 94, 0.15), 0 25px 50px rgba(0, 0, 0, 0.5)'
                                : '0 0 60px rgba(239, 68, 68, 0.15), 0 25px 50px rgba(0, 0, 0, 0.5)',
                            animation: 'popupScaleIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1)',
                        }}
                        onClick={(e) => e.stopPropagation()}
                    >
                        {/* Icon */}
                        <div
                            style={{
                                width: '64px',
                                height: '64px',
                                borderRadius: '50%',
                                background: popup.success
                                    ? 'linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(34, 197, 94, 0.1))'
                                    : 'linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(239, 68, 68, 0.1))',
                                border: popup.success ? '2px solid rgba(34, 197, 94, 0.4)' : '2px solid rgba(239, 68, 68, 0.4)',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'center',
                                margin: '0 auto 20px',
                                animation: 'popupIconPop 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s both',
                            }}
                        >
                            {popup.success ? (
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#22c55e" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                            ) : (
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ef4444" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18" />
                                    <line x1="6" y1="6" x2="18" y2="18" />
                                </svg>
                            )}
                        </div>

                        {/* Title */}
                        <h3 style={{
                            color: '#ffffff',
                            fontSize: '20px',
                            fontWeight: '600',
                            marginBottom: '8px',
                            letterSpacing: '-0.02em',
                        }}>
                            {popup.success ? 'Thank You!' : 'Oops!'}
                        </h3>

                        {/* Message */}
                        <p style={{
                            color: 'rgba(255, 255, 255, 0.6)',
                            fontSize: '15px',
                            lineHeight: '1.5',
                            marginBottom: '28px',
                        }}>
                            {popup.message}
                        </p>

                        {/* Button */}
                        <button
                            onClick={() => setPopup({ ...popup, show: false })}
                            style={{
                                background: popup.success
                                    ? 'linear-gradient(135deg, #22c55e, #16a34a)'
                                    : 'linear-gradient(135deg, #ef4444, #dc2626)',
                                color: popup.success ? '#000' : '#fff',
                                border: 'none',
                                borderRadius: '12px',
                                padding: '12px 32px',
                                fontSize: '14px',
                                fontWeight: '600',
                                cursor: 'pointer',
                                transition: 'transform 0.2s, box-shadow 0.2s',
                                letterSpacing: '0.02em',
                            }}
                            onMouseOver={(e) => {
                                e.target.style.transform = 'scale(1.05)';
                                e.target.style.boxShadow = popup.success
                                    ? '0 8px 25px rgba(34, 197, 94, 0.4)'
                                    : '0 8px 25px rgba(239, 68, 68, 0.4)';
                            }}
                            onMouseOut={(e) => {
                                e.target.style.transform = 'scale(1)';
                                e.target.style.boxShadow = 'none';
                            }}
                        >
                            Got it
                        </button>
                    </div>
                </div>
            )}

            {/* Popup Animations */}
            <style>{`
                @keyframes popupFadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }
                @keyframes popupScaleIn {
                    from { opacity: 0; transform: scale(0.85) translateY(20px); }
                    to { opacity: 1; transform: scale(1) translateY(0); }
                }
                @keyframes popupIconPop {
                    from { opacity: 0; transform: scale(0) rotate(-45deg); }
                    to { opacity: 1; transform: scale(1) rotate(0deg); }
                }
            `}</style>
        </div>
    );
}

export default Home;
