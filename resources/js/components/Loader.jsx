import React, { useState, useEffect } from 'react';
import './Loader.css';

function Loader() {
    const [visible, setVisible] = useState(true);
    const [fadeOut, setFadeOut] = useState(false);

    useEffect(() => {
        const fadeTimer = setTimeout(() => {
            setFadeOut(true);
        }, 4500);

        const removeTimer = setTimeout(() => {
            setVisible(false);
        }, 5500);

        return () => {
            clearTimeout(fadeTimer);
            clearTimeout(removeTimer);
        };
    }, []);

    if (!visible) return null;

    return (
        <div className={`loader-wrapper${fadeOut ? ' fade-out' : ''}`}>
            <div className="liquid-bg">
                <div className="blob blob1"></div>
                <div className="blob blob2"></div>
                <div className="blob blob3"></div>
                <div className="blob blob4"></div>
                <div className="blob blob5"></div>
                <div className="blob blob6"></div>
                <div className="blob blob7"></div>
                <div className="blob blob8"></div>
                <div className="blob blob9"></div>
                <div className="blob blob10"></div>
                <div className="blob blob11"></div>
            </div>

            <div className="logo-container">
                <img src="/img/tp.svg" className="logo-circle" alt="Logo" />
                <img src="/img/tp1.svg" className="brand-text" alt="TIGAPAGI" />
            </div>
        </div>
    );
}

export default Loader;
