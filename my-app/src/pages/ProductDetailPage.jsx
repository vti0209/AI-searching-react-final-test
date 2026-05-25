import React, { useState, useEffect } from 'react';
import { useParams, useNavigate, Link } from 'react-router-dom';
import '../css/ProductDetailPage.css';

export function ProductDetailPage() {
    const { id } = useParams();
    const navigate = useNavigate();
    const [product, setProduct] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        setLoading(true);
        fetch(`${import.meta.env.VITE_API_URL}/products/${id}`)
            .then(res => res.json())
            .then(data => {
                if (data.success && data.product) {
                    setProduct(data.product);
                } else if (data.id) {
                    setProduct(data);
                }
                setLoading(false);
            })
            .catch(err => {
                console.error('Error fetching product:', err);
                setLoading(false);
            });
    }, [id]);

    if (loading) {
        return (
            <div className="container py-5 text-center loading-container">
                <div className="spinner-border accent-primary" role="status"></div>
                <p className="mt-3">Đang tải thông tin sản phẩm...</p>
            </div>
        );
    }

    if (!product) {
        return (
            <div className="container py-5 text-center loading-container">
                <h3 className="text-secondary">Sản phẩm không tồn tại.</h3>
                <button className="btn btn-outline-primary mt-3" onClick={() => navigate('/shop')}>
                    Quay lại cửa hàng
                </button>
            </div>
        );
    }

    const formattedPrice = new Intl.NumberFormat('vi-VN').format(parseFloat(product.price)) + ' VNĐ';

    return (
        <div className="container py-5 product-detail-container">
            <nav aria-label="breadcrumb" className="breadcrumb-nav">
                <ol className="breadcrumb mb-0">
                    <li className="breadcrumb-item"><Link to="/" className="accent-primary text-decoration-none">Trang chủ</Link></li>
                    <li className="breadcrumb-item"><Link to="/shop" className="accent-primary text-decoration-none">Cửa hàng</Link></li>
                    <li className="breadcrumb-item active text-secondary" aria-current="page">{product.name}</li>
                </ol>
            </nav>

            <div className="row mt-4 bg-white p-4 rounded shadow-sm">
                <div className="col-md-5">
                    <div className="position-relative">
                        {product.category && (
                            <span className="badge accent-primary-bg position-absolute category-badge">
                                {product.category}
                            </span>
                        )}
                        <img
                            src={`${import.meta.env.VITE_BASE_URL}/${(product.image || '').startsWith('/') ? product.image.slice(1) : (product.image || '')}`}
                            alt={product.name}
                            className="img-fluid rounded product-image"
                        />
                    </div>
                </div>
                <div className="col-md-7 mt-4 mt-md-0">
                    <h2 className="font-weight-bold mb-3">{product.name}</h2>
                    <h3 className="accent-primary font-weight-bold mb-4">{formattedPrice}</h3>

                    <div className="mb-4">
                        <h6 className="font-weight-bold">Mô tả sản phẩm:</h6>
                        <p className="text-muted description-text">
                            {product.description || "Chưa có mô tả chi tiết cho sản phẩm này. Sản phẩm đảm bảo chất lượng cao, thiết kế theo xu hướng mới nhất, mang lại sự thoải mái tối đa cho người sử dụng."}
                        </p>
                    </div>

                    <div className="d-flex gap-3 align-items-center mb-4">
                        <div className="input-group quantity-group">
                            <button className="btn btn-outline-secondary quantity-button" type="button"><i className="bi bi-dash"></i></button>
                            <input type="text" className="form-control text-center" defaultValue="1" />
                            <button className="btn btn-outline-secondary quantity-button" type="button"><i className="bi bi-plus"></i></button>
                        </div>
                    </div>

                    <div className="d-flex action-buttons-container">
                        <button className="btn btn-primary add-to-cart-btn">
                            <i className="bi bi-cart-plus me-2"></i> THÊM VÀO GIỎ HÀNG
                        </button>
                        <button className="btn btn-outline-primary buy-now-btn">
                            MUA NGAY
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
}
