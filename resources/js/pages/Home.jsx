import React, { useState } from 'react';
import { Link } from 'react-router-dom';

function Home() {
const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
});
const [showClientWrap, setShowClientWrap] = useState(false);

const handleSubmit = async (e) => {
    e.preventDefault();
    try {
    const response = await fetch('/api/contact', {
        method: 'POST',
        headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
        },
        body: JSON.stringify(formData),
    });
    if (response.ok) {
        alert('Message sent successfully!');
        setFormData({ name: '', email: '', phone: '' });
    }
    } catch (error) {
    console.error('Error:', error);
    }
};

const services = [
    'Branding',
    'Social Media Management',
    'Photo Production',
    'UGC Video',
    'Campaign',
];

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
        
        <div className="relative z-10 max-w-5xl mx-auto px-6 text-center md:text-center">
        <h1 className="text-3xl md:text-5xl lg:text-6xl font-light leading-tight text-white">
            A creative makerspace that consist of passionate nocturnal folks.
        </h1>
        </div>
    </section>

      {/* Works Section */}
    <section className="relative py-20 bg-black overflow-hidden">
        <div className="relative z-10 max-w-6xl mx-auto px-6">
        <p className="text-base md:text-lg text-white/70 leading-relaxed max-w-4xl mb-10">
            <strong className="text-white">Studio Tigapagi</strong> is a leading digital creative agency and dedicated creative makerspace located in Sanur, Denpasar, Bali. Known for its high-commitment culture, the agency describes its team as "passionate nocturnal folks" who specialize in delivering high-impact branding.
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
        <div className="flex animate-scroll">
        {[...Array(6)].map((_, index) => (
            <img
            key={index}
            src="/img/run.png"
            alt="Running"
            className="h-[60vh] md:h-[70vh] lg:h-[60vh] object-cover"
            />
        ))}
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
            className="bg-transparent text-white border border-white/50 px-10 py-4 rounded-lg font-medium hover:bg-white hover:text-black transition-all duration-300 cursor-pointer"
            >
            {showClientWrap ? 'Show Less' : 'View All Clients'}
            </button>
        </div>
        </div>
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
            <div className="flex items-center">
            <p className="text-lg text-white/80 leading-relaxed">
                <strong className="text-white">Studio Tigapagi</strong> is a leading digital creative agency and dedicated creative makerspace located in Sanur, Denpasar, Bali. Known for its high-commitment culture, the agency describes its team as "passionate nocturnal folks" who specialize in delivering high-impact branding, sophisticated digital content strategy, and exceptional visual production.
            </p>
            </div>
        </div>
        </div>
    </section>
    </div>
);
}

export default Home;
