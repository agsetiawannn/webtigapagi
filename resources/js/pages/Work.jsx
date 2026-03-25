import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { works } from '../data/works';

function Work() {
const [hoveredWork, setHoveredWork] = useState(null);

const stats = [
    { number: '345+', label: 'Project Finished' },
    { number: '100+', label: 'Clients' },
    { number: '4+', label: 'Years Experienced' },
];

return (
    <div>
      {/* Hero Stats Section */}
    <section className="relative min-h-screen flex items-center justify-center overflow-hidden">
        {/* Background Layer 1 - BG.png (blur + pan animation) */}
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

        {/* Background Layer 2 - BG2.png (static overlay) */}
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
            className="absolute top-0 left-0 right-0 h-[300px] pointer-events-none"
            style={{
                background: 'linear-gradient(to bottom, rgba(0,0,0,1) 0%, rgba(0,0,0,0.4) 30%, rgba(0,0,0,0) 100%)',
                zIndex: 5
            }}
        />

        {/* Gradient Bottom */}
        <div
            className="absolute bottom-0 left-0 right-0 h-[200px] pointer-events-none"
            style={{
                background: 'linear-gradient(to top, rgba(0,0,0,1) 0%, rgba(0,0,0,0.3) 50%, rgba(0,0,0,0) 100%)',
                zIndex: 5
            }}
        />

        <div className="relative z-10 max-w-6xl mx-auto px-6 w-full">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            {stats.map((stat, index) => (
            <div key={index} className="py-8">
                <div className="text-5xl md:text-7xl font-bold text-white mb-2">
                {stat.number}
                </div>
                <div className="text-white/60 text-lg">
                {stat.label}
                </div>
            </div>
            ))}
        </div>
        </div>
    </section>

      {/* Selected Works Section */}
    <section className="py-20 bg-black">
        <div className="max-w-6xl mx-auto px-6">
          {/* Header - Selected left, Works right */}
        <div className="flex justify-between items-baseline mb-4">
            <h2 className="text-3xl md:text-5xl font-bold italic text-white">
            Selected
            </h2>
            <h2 className="text-3xl md:text-5xl font-bold italic text-white">
            Works
            </h2>
        </div>

          {/* Works List */}
        <div>
            {works.map((work, index) => (
            <Link
                key={work.slug}
                to={`/work/${work.slug}`}
                className="block border-t border-white/20 py-8 cursor-pointer"
                // onMouseEnter={() => setHoveredWork(index)}
                // onMouseLeave={() => setHoveredWork(null)}
            >
                <div className="grid grid-cols-12 gap-4 items-start">
                  {/* Number */}
                <div className="col-span-2 md:col-span-1">
                    <span className="text-white text-lg md:text-xl font-medium">
                    {work.number}
                    </span>
                </div>
                  {/* Title */}
                <div className="col-span-10 md:col-span-3">
                    <h3 className="text-xl md:text-2xl font-bold text-white">
                    {work.title}
                    </h3>
                </div>
                  {/* Description */}
                <div className="col-span-12 md:col-span-8">
                    <p className="text-white/60 text-sm leading-relaxed">
                    {work.description}
                    </p>
                </div>
                </div>

                {/* Gallery - appears on hover */}
                <div
                className={`hidden md:block overflow-hidden transition-all duration-500 ease-in-out max-h-0 opacity-0 mt-0`}
                >
                <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    {/* Left - 2 large portrait images */}
                    <div className="col-span-1">
                    <img
                        src="/img/work/cbt1.svg"
                        alt={`${work.title} Work 1`}
                        className="object-cover rounded-xl bg-white/5"
                        style={{ width: '339px', height: '603px' }}
                    />
                    </div>
                    <div className="col-span-1">
                    <img
                        src="/img/work/cbt1.svg"
                        alt={`${work.title} Work 2`}
                        className="object-cover rounded-xl bg-white/5"
                        style={{ width: '339px', height: '603px' }}
                    />
                    </div>
                    {/* Right - 3x2 grid of smaller images */}
                    <div className="col-span-2 grid grid-cols-3 grid-rows-2 gap-4">
                    <img src="/img/work/cbt2.svg" alt={`${work.title} Work 3`} className="object-cover rounded-xl bg-white/5" style={{ width: '228px', height: '285px' }} />
                    <img src="/img/work/cbt2.svg" alt={`${work.title} Work 4`} className="object-cover rounded-xl bg-white/5" style={{ width: '228px', height: '285px' }} />
                    <img src="/img/work/cbt2.svg" alt={`${work.title} Work 5`} className="object-cover rounded-xl bg-white/5" style={{ width: '228px', height: '285px' }} />
                    <img src="/img/work/cbt2.svg" alt={`${work.title} Work 6`} className="object-cover rounded-xl bg-white/5" style={{ width: '228px', height: '285px' }} />
                    <img src="/img/work/cbt2.svg" alt={`${work.title} Work 7`} className="object-cover rounded-xl bg-white/5" style={{ width: '228px', height: '285px' }} />
                    <img src="/img/work/cbt2.svg" alt={`${work.title} Work 8`} className="object-cover rounded-xl bg-white/5" style={{ width: '228px', height: '285px' }} />
                    </div>
                </div>
                </div>
            </Link>
            ))}
            {/* Bottom border */}
            <div className="border-t border-white/20" />
        </div>
        </div>
    </section>
    </div>
);
}

export default Work;
