import React from 'react';
import { Link } from 'react-router-dom';
import '../css/Footer.css';

export function Footer() {
    return (
        <footer className="py-5 mt-auto dark-bg footer-top">
            <div className="container">
                <div className="row text-left">
                    {/* About */}
                    <div className="col-lg-3 col-md-6 mb-4 mb-lg-0">
                        <h6 className="accent-primary font-weight-bold mb-3 footer-section-title">Về chúng tôi</h6>
                        <p className="text-muted accent-primary">Đây là cửa hàng cho bài thi cuối kỳ môn Thiết kế web nâng cao. Truy cập trang <Link to="/about" className="text-primary text-decoration-none accent-primary">Về chúng tôi</Link> để biết thêm.</p>
                        <div className="footer-icons">
                            <i className="bi bi-facebook accent-primary fs-4 footer-icon" aria-hidden="true"></i>
                            <i className="bi bi-twitter accent-primary fs-4 footer-icon" aria-hidden="true"></i>
                            <i className="bi bi-instagram accent-primary fs-4 footer-icon" aria-hidden="true"></i>
                            <i className="bi bi-youtube accent-primary fs-4 footer-icon" aria-hidden="true"></i>
                        </div>
                    </div>

                    {/* Quick Links */}
                    <div className="col-lg-3 col-md-6 mb-4 mb-lg-0 pl-lg-5">
                        <h6 className="footer-section-title">Liên kết nhanh</h6>
                        <ul className="list-unstyled mb-0 footer-list">
                            <li className="mb-2"><Link to="/" className="text-muted text-decoration-none accent-primary">Trang chủ</Link></li>
                            <li className="mb-2"><Link to="/shop" className="text-muted text-decoration-none accent-primary">Cửa hàng</Link></li>
                            <li className="mb-2"><Link to="/about" className="text-muted text-decoration-none accent-primary">Về chúng tôi</Link></li>
                            <li className="mb-2"><Link to="/cart" className="text-muted text-decoration-none accent-primary">Quản lý giỏ hàng</Link></li>
                        </ul>
                    </div>

                    {/* Services */}
                    <div className="col-lg-3 col-md-6 mb-4 mb-lg-0">
                        <h6 className="footer-section-title">Dịch vụ</h6>
                        <ul className="list-unstyled mb-0 footer-services-list">
                            <li className="mb-2"><Link to="/products" className="text-muted text-decoration-none accent-primary">Xem sản phẩm</Link></li>
                            <li className="mb-2"><Link to="/cart" className="text-muted text-decoration-none accent-primary">Xem giỏ hàng</Link></li>
                            <li className="mb-2"><Link to="/checkout" className="text-muted text-decoration-none accent-primary">Mua sản phẩm</Link></li>
                        </ul>
                    </div>

                    {/* Contact */}
                    <div className="col-lg-3 col-md-6 mb-4 mb-lg-0">
                        <h6 className="footer-section-title">Liên hệ</h6>
                        <ul className="list-unstyled mb-0 footer-contact-list">
                            <li className="mb-2 d-flex"><i className="bi bi-geo-alt-fill text-success me-2 mt-1"></i><span className="text-muted">Số 123, Đường ABC, Quận Sơn Trà, TP. Đà Nẵng</span></li>
                            <li className="mb-2 d-flex"><i className="bi bi-telephone-fill text-success me-2 mt-1"></i><span className="text-muted">(+84) 373 532 152</span></li>
                            <li className="mb-2 d-flex"><i className="bi bi-envelope-fill text-success me-2 mt-1"></i><span className="text-muted">End123@finaltest.PNV.com</span></li>
                            <li className="mb-2 d-flex"><i className="bi bi-clock-fill text-success me-2 mt-1"></i><span className="text-muted">Thứ Hai - Thứ Bảy: 8:00 - 17:00</span></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div className="text-center mt-5 pt-3 bg-white footer-bottom-border">
                <p className="text-muted font-weight-bold mb-0 footer-note">© 2026 VanTiet. Bản quyền.</p>
                <p className="text-muted font-weight-bold mb-3 footer-note">Cửa hàng được phát triển bởi Văn Tiết cho bài thi cuối kỳ REACTJS.</p>
            </div>
        </footer>
    );
}