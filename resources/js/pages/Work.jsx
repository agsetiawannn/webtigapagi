import React from 'react';

function Work() {
  const stats = [
    { number: '345+', label: 'Project Finished' },
    { number: '100+', label: 'Clients' },
    { number: '4+', label: 'Years Experienced' },
  ];

  const works = [
    {
      number: '01',
      title: 'Pertamina',
      description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
    },
    {
      number: '02',
      title: 'Yamaha Bali',
      description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
    },
    {
      number: '03',
      title: 'Elementis',
      description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
    },
    {
      number: '04',
      title: 'The Smoke House',
      description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
    },
    {
      number: '05',
      title: 'Balisabi',
      description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
    },
    {
      number: '06',
      title: 'Mallali',
      description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
    },
    {
      number: '07',
      title: 'Summerhouse',
      description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
    },
  ];

  return (
    <div>
      {/* Hero Stats Section */}
      <section className="relative min-h-[60vh] flex items-center justify-center overflow-hidden">
        {/* Background gradients */}
        <div className="absolute inset-0 bg-gradient-to-b from-green-900/30 via-transparent to-transparent pointer-events-none" />
        <div className="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent pointer-events-none" />
        
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
          {/* Header */}
          <div className="text-center mb-16">
            <h2 className="text-4xl md:text-6xl font-light text-white">
              Selected
            </h2>
            <h2 className="text-4xl md:text-6xl font-light italic text-green-400">
              Works
            </h2>
          </div>

          {/* Works List */}
          <div className="space-y-16">
            {works.map((work, index) => (
              <div key={index} className="border-t border-white/10 pt-8">
                <div className="flex flex-col lg:flex-row gap-8">
                  {/* Info */}
                  <div className="lg:w-1/3">
                    <span className="text-green-400 text-sm font-mono">
                      {work.number}
                    </span>
                    <h3 className="text-2xl md:text-3xl font-semibold text-white mt-2 mb-4">
                      {work.title}
                    </h3>
                    <p className="text-white/60 text-sm leading-relaxed">
                      {work.description}
                    </p>
                  </div>

                  {/* Gallery */}
                  <div className="lg:w-2/3 grid grid-cols-3 gap-4">
                    <div className="col-span-1">
                      <img
                        src="/img/work/cbt1.svg"
                        alt={`${work.title} Work 1`}
                        className="w-full h-48 object-cover rounded-xl bg-white/5"
                      />
                    </div>
                    <div className="col-span-1">
                      <img
                        src="/img/work/cbt1.svg"
                        alt={`${work.title} Work 2`}
                        className="w-full h-48 object-cover rounded-xl bg-white/5"
                      />
                    </div>
                    <div className="col-span-1 grid grid-rows-2 gap-4">
                      <img
                        src="/img/work/cbt2.svg"
                        alt={`${work.title} Work 3`}
                        className="w-full h-full object-cover rounded-xl bg-white/5"
                      />
                      <img
                        src="/img/work/cbt2.svg"
                        alt={`${work.title} Work 4`}
                        className="w-full h-full object-cover rounded-xl bg-white/5"
                      />
                    </div>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>
    </div>
  );
}

export default Work;
