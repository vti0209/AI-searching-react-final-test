import React from 'react';
import { Link, useLocation } from 'react-router-dom';
import '../css/header.css';

export function Header() {
  const location = useLocation();

  return (
    <nav className="navbar dark-bg shadow-sm sticky-top py-3">
      <div className="container position-relative d-flex justify-content-between align-items-center">
        <Link to="/" className="navbar-brand font-weight-bold logo-text">
          FASHI<span className="accent-primary">AI</span>
        </Link>

        <div className="nav-center">
          <Link to="/" className={`nav-link ${location.pathname === '/' ? 'active' : ''}`}>Trang chủ</Link>
          <Link to="/shop" className={`nav-link ${location.pathname === '/shop' || location.pathname.startsWith('/product/') ? 'active' : ''}`}>Cửa hàng</Link>
        </div>
      </div>
    </nav>
  );
}