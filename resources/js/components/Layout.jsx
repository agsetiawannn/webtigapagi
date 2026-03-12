import React from 'react';
import { Outlet } from 'react-router-dom';
import Header from './Header';
import Footer from './Footer';

function Layout() {
return (
    <div className="min-h-screen bg-black text-white font-sans">
    <Header />
    <main>
        <Outlet />
    </main>
    <Footer />
    </div>
);
}

export default Layout;
