import React from 'react';
import { Routes, Route } from 'react-router-dom';
import { MainLayout } from './layout/MainLayout';
import { ShopPage } from './pages/ShopPage';
import { HomePage } from './pages/HomePage';
import { ProductDetailPage } from './pages/ProductDetailPage';

function App() {
    return (
        <Routes>
            <Route path="/" element={<MainLayout />}>
                <Route index element={<HomePage />} />
                <Route path="shop" element={<ShopPage />} />
                <Route path="product/:id" element={<ProductDetailPage />} />
            </Route>
        </Routes>
    );
}

export default App;