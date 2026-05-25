import React, { useState, useEffect } from 'react';
import { useSearchParams, useNavigate } from 'react-router-dom';
import '../css/ShopPage.css';

export function ShopPage() {
    const [searchParams] = useSearchParams();
    const query = searchParams.get('query') || '';
    const page = searchParams.get('page') || '1';
    const navigate = useNavigate();

    const [products, setProducts] = useState([]);
    const [pagination, setPagination] = useState({ current_page: 1, last_page: 1, total: 0 });
    const [loading, setLoading] = useState(true);
    const [aiExplanation, setAiExplanation] = useState(null);
    const [isAi, setIsAi] = useState(false);
    const [searchText, setSearchText] = useState(query);

    useEffect(() => {
        setLoading(true);
        fetch(`${import.meta.env.VITE_API_URL}/products?query=${encodeURIComponent(query)}&page=${page}`)
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    setProducts(data.products);
                    setPagination(data.pagination || { current_page: 1, last_page: 1, total: data.products.length });
                    setAiExplanation(data.explanation);
                    setIsAi(data.is_ai);
                }
                setLoading(false);
            })
            .catch((err) => {
                console.error('Error fetching products:', err);
                setLoading(false);
            });
    }, [query, page]);

    const handlePageChange = (newPage) => {
        if (newPage >= 1 && newPage <= pagination.last_page) {
            navigate(`/shop?query=${encodeURIComponent(query)}&page=${newPage}`);
        }
    };

    const handleSearchSubmit = (e) => {
        e.preventDefault();
        if (searchText.trim()) {
            navigate(`/shop?query=${encodeURIComponent(searchText.trim())}`);
        } else {
            navigate('/shop');
        }
    };

    return (
        <>
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
                    
                    {/* Search Form */}
                    <div className="d-flex justify-content-center">
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
                </div>
            </section>

            <section className="product-shop py-5 shop-section">
                <div className="container">
                <div className="row mb-4">
                    <div className="col-lg-12">
                        {aiExplanation && (
                                <div className="alert alert-primary border-primary shadow-sm rounded-lg d-flex align-items-center p-4" role="alert">
                                    <div className="mr-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center ai-alert-icon">
                                        <i className={isAi ? "bi bi-robot" : "bi bi-search"} />
                                    </div>
                                    <div className="ms-3">
                                        <h5 className="alert-heading font-weight-bold mb-1 alert-heading-custom">
                                            {isAi ? "Trợ lý AI Tìm kiếm" : "Kết quả tìm kiếm"}
                                        </h5>
                                        <p className="mb-0 text-dark alert-text">{aiExplanation}</p>
                                    </div>
                                </div>
                        )}
                    </div>
                </div>

                <div className="row mb-4">
                    <div className="col-12 text-center">
                        <h2 className="text-dark font-weight-bold">Tất cả sản phẩm</h2>
                    </div>
                </div>

                <div className="row">
                    {loading ? (
                        <div className="text-center w-100 py-5">
                            <div className="spinner-border accent-primary spinner-lg" role="status"></div>
                            <p className="mt-3 text-muted font-weight-bold">
                                Đang tải danh sách sản phẩm...
                            </p>
                        </div>
                    ) : products.length > 0 ? (
                        <>
                            {products.map((product) => (
                                <div key={product.id} className="col-lg-3 col-md-4 col-sm-6 mb-4">
                                    <div
                                          className="card h-100 shadow-sm border-0 product-card"
                                          onClick={() => navigate(`/product/${product.id}`)}
                                      >
                                        <div className="position-relative">
                                            <span className="badge accent-primary-bg text-white position-absolute product-badge">{product.category}</span>
                                            <img src={`${import.meta.env.VITE_BASE_URL}/${(product.image || '').startsWith('/') ? product.image.slice(1) : (product.image || '')}`} className="card-img-top product-image" alt={product.name} />
                                        </div>
                                        <div className="card-body d-flex flex-column">
                                            <h6 className="card-title text-dark font-weight-bold mb-2">{product.name}</h6>
                                            <div className="mt-auto d-flex justify-content-between align-items-center">
                                                <span className="accent-primary font-weight-bold price-text">{new Intl.NumberFormat('vi-VN').format(parseFloat(product.price))} VNĐ</span>
                                                <button className="btn btn-outline-primary btn-sm rounded-circle" onClick={(e) => { e.stopPropagation(); }}><i className="bi bi-cart-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ))}

                            {pagination.last_page > 1 && (
                                <div className="col-12 d-flex justify-content-center mt-4">
                                    <nav aria-label="Page navigation">
                                        <ul className="pagination">
                                            <li className={`page-item ${pagination.current_page === 1 ? 'disabled' : ''}`}>
                                                <button className="page-link accent-primary" onClick={() => handlePageChange(pagination.current_page - 1)}>
                                                    Trước
                                                </button>
                                            </li>

                                            {[...Array(pagination.last_page)].map((_, i) => (
                                                <li key={i + 1} className={`page-item ${pagination.current_page === i + 1 ? 'active' : ''}`}>
                                                    <button
                                                        className={`page-link ${pagination.current_page === i + 1 ? 'accent-primary-bg border-0 text-white' : 'accent-primary'}`}
                                                        onClick={() => handlePageChange(i + 1)}
                                                    >
                                                        {i + 1}
                                                    </button>
                                                </li>
                                            ))}

                                            <li className={`page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}`}>
                                                <button className="page-link accent-primary" onClick={() => handlePageChange(pagination.current_page + 1)}>
                                                    Sau
                                                </button>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            )}
                        </>
                    ) : (
                        <div className="text-center py-5 px-3 border rounded bg-white w-100 my-4 no-results">
                            <i className="bi bi-search text-muted mb-3 no-results-icon" />
                            <h4 className="text-secondary font-weight-bold mb-2">Không tìm thấy sản phẩm nào</h4>
                            <p className="text-muted mb-0">Hãy thử tìm kiếm với các từ khóa khác hoặc đưa ra mô tả chi tiết hơn.</p>
                        </div>
                    )}
                </div>
            </div>
        </section>
        </>
    );
}