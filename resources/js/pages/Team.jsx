import React from 'react';

function Team() {
  return (
    <div 
      className="min-h-screen flex items-center justify-center"
      style={{
        backgroundImage: "url('/img/COver 1.png')",
        backgroundSize: 'cover',
        backgroundPosition: 'center',
        backgroundRepeat: 'no-repeat',
        backgroundAttachment: 'fixed',
      }}
    >
      <div className="text-center px-6">
        <p className="text-xl md:text-2xl text-white/90">
          We will update soon
        </p>
      </div>
    </div>
  );
}

export default Team;
