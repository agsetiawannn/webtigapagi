import React from 'react';

function Clients() {
  return (
    <div>
      {/* Hero Section */}
      <section className="relative min-h-[70vh] flex items-center justify-center overflow-hidden">
        {/* Background gradients */}
        <div className="absolute inset-0 bg-gradient-to-b from-green-900/30 via-transparent to-transparent pointer-events-none" />
        <div className="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent pointer-events-none" />
        
        <div className="relative z-10 max-w-6xl mx-auto px-6 text-center">
          <img
            src="/img/ClientWrap.png"
            alt="Clients"
            className="w-full max-w-4xl mx-auto mb-8"
          />
          <p className="text-lg md:text-xl text-white/80 max-w-2xl mx-auto leading-relaxed">
            We've had the privilege of working with some amazing brands and companies. 
            From startups to established businesses, our clients trust us to deliver exceptional results.
          </p>
        </div>
      </section>

      {/* All Clients Section */}
      <section className="py-20 bg-black">
        <div className="max-w-6xl mx-auto px-6">
          {/* Client logos would go here */}
          <div className="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-8">
            {/* Placeholder for client logos */}
          </div>
        </div>
      </section>
    </div>
  );
}

export default Clients;
