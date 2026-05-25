import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import '../css/HomePage.css';

export function HomePage() {
    const navigate = useNavigate();
    const [searchText, setSearchText] = useState('');

    const handleSearchSubmit = (e) => {
        e.preventDefault();
        if (searchText.trim()) {
            navigate(`/shop?query=${encodeURIComponent(searchText.trim())}`);
        } else {
            navigate('/shop');
        }
    };

    return (
        <div className="home-page-container">
            {/* Hero Section */}
            <section className="hero-section-custom dark-bg">
                <div className="container text-center py-4">
                    {/* Subtitle Badge */}
                    <div className="mb-3">
                        <span className="badge hero-subtitle-premium">BỘ SƯU TẬP MỚI 2026</span>
                    </div>
                    
                    {/* Title */}
                    <h1 className="font-weight-bold hero-title-premium mb-3">
                        Phong cách thời trang <span className="accent-color">Nổi bật &amp; Đẳng cấp</span>
                    </h1>
                    
                    {/* Description */}
                    <p className="hero-description-premium text-muted mx-auto mb-4">
                        Trải nghiệm mua sắm hoàn hảo với hàng ngàn sản phẩm chất lượng, đón đầu xu hướng thời trang mới nhất dành riêng cho bạn.
                    </p>
                    
                    {/* Search Form */}
                    <div className="d-flex justify-content-center mb-4">
                        <form onSubmit={handleSearchSubmit} className="hero-search-form-premium">
                            <div className="input-group shadow-sm rounded-pill overflow-hidden bg-white border">
                                <input
                                    type="text"
                                    className="form-control border-0 py-3 px-4 search-input"
                                    placeholder="Bạn muốn tìm gì..."
                                    value={searchText}
                                    onChange={(e) => setSearchText(e.target.value)}
                                />
                                <button
                                    type="submit"
                                    className="btn btn-search-premium px-4"
                                >
                                    <i className="bi bi-search" />
                                </button>
                            </div>
                        </form>
                    </div>

                    {/* Action Buttons */}
                    <div className="hero-buttons-premium d-flex justify-content-center align-items-center">
                        <button onClick={() => navigate('/shop')} className="btn btn-buy-premium shadow-sm">
                            MUA NGAY <i className="bi bi-cart3 ms-2" />
                        </button>
                        <button onClick={() => navigate('/shop')} className="btn btn-view-premium shadow-sm">
                            XEM BỘ SƯU TẬP <i className="bi bi-arrow-right ms-2" />
                        </button>
                    </div>
                </div>
            </section>

            {/* Features Section */}
            <section className="features-section">
                <div className="container">
                    <div className="row">
                        <div className="col-md-4">
                            <div className="feature-box">
                                <div className="feature-icon">
                                    <i className="bi bi-truck"></i>
                                </div>
                                <h5>Giao Hàng Miễn Phí</h5>
                                <p>Cho tất cả đơn hàng trên 500,000 VND</p>
                            </div>
                        </div>
                        <div className="col-md-4">
                            <div className="feature-box">
                                <div className="feature-icon">
                                    <i className="bi bi-credit-card"></i>
                                </div>
                                <h5>Thanh Toán An Toàn</h5>
                                <p>Bảo mật thông tin 100%</p>
                            </div>
                        </div>
                        <div className="col-md-4">
                            <div className="feature-box">
                                <div className="feature-icon">
                                    <i className="bi bi-headset"></i>
                                </div>
                                <h5>Hỗ Trợ 24/7</h5>
                                <p>Sẵn sàng giải đáp mọi thắc mắc</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Categories Section */}
            <section className="categories-section-custom">
                <div className="container">
                    <div className="section-title-custom text-center">
                        <h2>Danh mục nổi bật</h2>
                        <div className="line-divider"></div>
                    </div>
                    <div className="row">
                        <div className="col-md-3 col-sm-6 mb-4">
                            <div className="category-card" onClick={() => navigate('/shop?query=Coat')}>
                                <div className="category-img-wrapper">
                                    <img src={`${import.meta.env.VITE_BASE_URL}/img/products/product-8.jpg`} alt="Áo khoác" />
                                </div>
                                <div className="category-info">
                                    <h4>Áo Khoác</h4>
                                    <span>Áo khoác & Áo len</span>
                                </div>
                            </div>
                        </div>
                        <div className="col-md-3 col-sm-6 mb-4">
                            <div className="category-card" onClick={() => navigate('/shop?query=Shirt')}>
                                <div className="category-img-wrapper">
                                    <img src={`${import.meta.env.VITE_BASE_URL}/img/products/tshirt-1.jpg`} alt="Áo thun" />
                                </div>
                                <div className="category-info">
                                    <h4>Áo Thun</h4>
                                    <span>Áo thun, áo polo, sơ mi</span>
                                </div>
                            </div>
                        </div>
                        <div className="col-md-3 col-sm-6 mb-4">
                            <div className="category-card" onClick={() => navigate('/shop?query=Jeans')}>
                                <div className="category-img-wrapper">
                                    <img src={`${import.meta.env.VITE_BASE_URL}/img/products/jeans-1.jpg`} alt="Quần Jeans" />
                                </div>
                                <div className="category-info">
                                    <h4>Quần Jeans</h4>
                                    <span>Skinny, baggy, slim fit</span>
                                </div>
                            </div>
                        </div>
                        <div className="col-md-3 col-sm-6 mb-4">
                            <div className="category-card" onClick={() => navigate('/shop?query=Dress')}>
                                <div className="category-img-wrapper">
                                    <img src={`${import.meta.env.VITE_BASE_URL}/img/products/dress-1.jpg`} alt="Váy Đầm" />
                                </div>
                                <div className="category-info">
                                    <h4>Váy & Đầm</h4>
                                    <span>Maxi, midi, bodycon</span>
                                </div>
                            </div>
                        </div>
                        <div className="col-md-3 col-sm-6 mb-4">
                            <div className="category-card" onClick={() => navigate('/shop?query=Shoes')}>
                                <div className="category-img-wrapper">
                                    <img src={`${import.meta.env.VITE_BASE_URL}/img/products/product-9.jpg`} alt="Giày" />
                                </div>
                                <div className="category-info">
                                    <h4>Giày</h4>
                                    <span>Sneaker, Converse, boot</span>
                                </div>
                            </div>
                        </div>
                        <div className="col-md-3 col-sm-6 mb-4">
                            <div className="category-card" onClick={() => navigate('/shop?query=Bag')}>
                                <div className="category-img-wrapper">
                                    <img src={`${import.meta.env.VITE_BASE_URL}/img/products/bag-3.jpg`} alt="Túi xách" />
                                </div>
                                <div className="category-info">
                                    <h4>Túi Xách</h4>
                                    <span>Balo, túi tote, túi vai</span>
                                </div>
                            </div>
                        </div>
                        <div className="col-md-3 col-sm-6 mb-4">
                            <div className="category-card" onClick={() => navigate('/shop?query=Hat')}>
                                <div className="category-img-wrapper">
                                    <img src={`${import.meta.env.VITE_BASE_URL}/img/products/hat-2.jpg`} alt="Mũ nón" />
                                </div>
                                <div className="category-info">
                                    <h4>Mũ & Nón</h4>
                                    <span>Snapback, bucket, beanie</span>
                                </div>
                            </div>
                        </div>
                        <div className="col-md-3 col-sm-6 mb-4">
                            <div className="category-card" onClick={() => navigate('/shop?query=Accessories')}>
                                <div className="category-img-wrapper">
                                    <img src={`${import.meta.env.VITE_BASE_URL}/img/products/accessory-1.jpg`} alt="Phụ kiện" />
                                </div>
                                <div className="category-info">
                                    <h4>Phụ Kiện</h4>
                                    <span>Kính, thắt lưng, vòng tay</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    );
}
