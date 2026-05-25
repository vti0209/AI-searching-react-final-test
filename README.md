# 🛍️ Fashion E-commerce Platform - MTYTSHOP

Một nền tảng thương mại điện tử thời trang được xây dựng bằng **React + Laravel** với tính năng tìm kiếm AI tích hợp Gemini API.

> Bài thi cuối kỳ môn **Thiết kế Web Nâng cao** & **ReactJS**

---

## 📋 Tổng Quan Dự Án

| **Thành phần** | **Công nghệ** | **Trạng thái** |
|---|---|---|
| **Frontend** | React 18 + Vite + Bootstrap 5 | ✅ Hoàn thành |
| **Backend** | Laravel 11 + SQLite | ✅ Hoàn thành |
| **Database** | MySQL (63 sản phẩm) | ✅ Hoàn thành |
| **Search** | Gemini 2.5 Flash API + Keyword Search | ✅ Hoàn thành |
| **UI/UX** | Responsive Design | ✅ Hoàn thành |

---

## 🎯 Các Tính Năng Đã Hoàn Thành

### ✅ Frontend (React)
- 📱 Giao diện responsive với Bootstrap 5
- 🏠 Trang chủ với danh mục nổi bật
- 🛒 Trang sản phẩm với phân trang (63 sản phẩm / 8 trang)
- 🔍 Tìm kiếm thông minh:
  - **Tìm kiếm AI** sử dụng Gemini 2.5 Flash
  - **Tìm kiếm Keyword** fallback (tên, danh mục, giá)
  - **Lọc theo**: Danh mục, khoảng giá
- 📄 Trang chi tiết sản phẩm
- 🎨 Thiết kế hiện đại, dễ sử dụng

### ✅ Backend (Laravel)
- 🔗 RESTful API complete
- 📦 Endpoints chính:
  - `GET /api/products` - Lấy danh sách sản phẩm (có phân trang)
  - `GET /api/products/{id}` - Lấy chi tiết sản phẩm
  - `POST /api/search` - Tìm kiếm AI (Gemini) + Keyword
- 🗄️ Database SQLite với 9 danh mục sản phẩm
- 🔐 CORS được cấu hình

### ✅ Database (MySQL)
```
Database: **MySQL** (reactjs_final)
📦 63 Sản phẩm được phân loại:
  ├─ 10 Áo Khoác (Coat)
  ├─ 8 Áo Thun (Shirt)
  ├─ 7 Quần Jeans (Jeans)
  ├─ 7 Váy Đầm (Dress)
  ├─ 8 Giày (Shoes)
  ├─ 6 Túi Xách (Bag)
  ├─ 5 Mũ Nón (Hat)
  ├─ 6 Khăn (Towel)
  └─ 6 Phụ Kiện (Accessories)
```

---

## 📁 Cấu Trúc Dự Án

```
ReactJs_Final/
├─ backend/                      # Laravel Backend
│  ├─ app/
│  │  ├─ Http/Controllers/       # API Controllers
│  │  │  └─ ProductController.php
│  │  └─ Models/
│  │     └─ Product.php
│  ├─ config/                    # Cấu hình ứng dụng
│  ├─ database/
│  │  ├─ migrations/             # Database migrations
│  │  └─ seeders/
│  │     └─ ProductSeeder.php   # 63 sản phẩm
│  ├─ routes/
│  │  └─ api.php                # API routes
│  ├─ .env.example              # Config template
│  ├─ composer.json             # PHP dependencies
│  └─ artisan                   # Laravel CLI
│
├─ my-app/                       # React Frontend
│  ├─ src/
│  │  ├─ components/            # React components
│  │  ├─ pages/
│  │  │  ├─ HomePage.jsx        # Trang chủ
│  │  │  ├─ ShopPage.jsx        # Trang sản phẩm
│  │  │  └─ ProductDetailPage.jsx # Chi tiết sản phẩm
│  │  ├─ layout/
│  │  │  └─ MainLayout.jsx      # Layout chính
│  │  ├─ css/                   # Styles
│  │  ├─ App.jsx                # Main app
│  │  └─ index.jsx              # Entry point
│  ├─ public/
│  │  ├─ img/products/          # Hình ảnh sản phẩm
│  │  └─ ...
│  ├─ package.json              # JS dependencies
│  └─ vite.config.js            # Vite config
│
└─ README.md                     # Tài liệu này
```

---

## 🚀 Cách Chạy Dự Án

### 1️⃣ Chuẩn Bị Môi Trường

**Backend (Laravel):**
```bash
cd backend

# Cài đặt dependencies PHP
composer install

# Copy .env và cấu hình
cp .env.example .env

# Tạo app key
php artisan key:generate

# Chạy migration và seeding
php artisan migrate:refresh --seed
```

**Frontend (React):**
```bash
cd my-app

# Cài đặt dependencies Node
npm install
```

---

### 2️⃣ Khởi Động Servers

**Terminal 1 - Backend (Port 8000):**
```bash
cd backend
php artisan serve --host=127.0.0.1 --port=8000
```

**Terminal 2 - Frontend (Port 5173):**
```bash
cd my-app
npm run dev
```

✅ Frontend sẽ tự động mở ở: **http://localhost:5173**
✅ Backend API: **http://127.0.0.1:8000/api**

---

## ⚙️ Yêu Cầu Hệ Thống

- **PHP**: >= 8.2
- **Node.js**: >= 16.0
- **MySQL**: >= 5.7
- **npm**: >= 8.0

---

## 🔑 Các API Endpoints

### Lấy Sản Phẩm

```bash
# Lấy danh sách (phân trang)
GET http://127.0.0.1:8000/api/products
# Query params: page=1, limit=8

# Response:
{
  "success": true,
  "products": [...],
  "pagination": {
    "current_page": 1,
    "last_page": 8,
    "total": 63
  }
}
```

```bash
# Lấy chi tiết sản phẩm
GET http://127.0.0.1:8000/api/products/1

# Response:
{
  "success": true,
  "product": {
    "id": 1,
    "name": "Áo Len Vặn Eo Cổ V Màu Vàng",
    "category": "Coat",
    "price": "299000.00",
    "image": "img/products/product-1.jpg",
    "description": "...",
    "created_at": "2026-05-25T07:44:19.000000Z",
    "updated_at": "2026-05-25T07:44:19.000000Z"
  }
}
```

### Tìm Kiếm

```bash
# Tìm kiếm (AI + Keyword)
POST http://127.0.0.1:8000/api/search
Content-Type: application/json

{
  "query": "áo len màu vàng",
  "category": "Coat",        # Optional
  "priceMin": 100000,        # Optional
  "priceMax": 500000,        # Optional
  "limit": 10
}
```

---

## 📱 Các Trang & Routes

| **Route** | **Mô tả** |
|---|---|
| `/` | 🏠 Trang chủ |
| `/shop` | 🛍️ Trang sản phẩm (Phân trang) |
| `/product/:id` | 📄 Chi tiết sản phẩm |
| `/cart` | 🛒 Giỏ hàng (Placeholder) |

---

## 🎨 Danh Mục Sản Phẩm

```
1. Coat       (Áo Khoác & Áo Len)
2. Shirt      (Áo Thun & Sơ Mi)
3. Jeans      (Quần Jeans)
4. Dress      (Váy & Đầm)
5. Shoes      (Giày & Dép)
6. Bag        (Túi Xách & Balo)
7. Hat        (Mũ & Nón)
8. Towel      (Khăn Quàng)
9. Accessories (Phụ Kiện)
```

---

## 🔍 Tính Năng Tìm Kiếm

### 1. **Tìm Kiếm AI (Gemini)**
- Sử dụng Google Gemini 2.5 Flash API
- Hiểu ngữ cảnh tìm kiếm
- Trả lại sản phẩm phù hợp với mô tả
- VD: "Áo ấm cho mùa đông" → Tìm các áo len, áo khoác

### 2. **Tìm Kiếm Keyword (Fallback)**
- Tìm theo tên sản phẩm, danh mục, giá
- Tác dụng khi API Gemini lỗi
- Nhanh và đáng tin cậy

### 3. **Lọc Nâng Cao**
- Lọc theo danh mục
- Lọc theo khoảng giá
- Kết hợp với tìm kiếm

---

## 🛠️ Công Nghệ Sử Dụng

### Frontend
```json
{
  "react": "^18.0",
  "react-router-dom": "^6.0",
  "bootstrap": "^5.0",
  "axios": "^1.6",
  "vite": "^8.0"
}
```

### Backend
```json
{
  "laravel": "^11.0",
  "php": "^8.2",
  "sqlite": "latest",
  "google/generative-ai": "^1.0"
}
```

---

## 📊 Thống Kê

| **Chỉ số** | **Giá trị** |
|---|---|
| 📦 Tổng sản phẩm | 63 |
| 📄 Trang sản phẩm | 8 |
| 🏷️ Danh mục | 9 |
| 📍 API endpoints | 3+ |
| ⚛️ React components | 10+ |
| 🎯 Pages | 3 |

---

## ⚙️ Yêu Cầu Hệ Thống



---

## 📝 Ghi Chú & Lưu Ý

1. **Database**: Sử dụng MySQL (database `reactjs_final`, user: `root`)
2. **API URL**: Frontend hardcoded API tại `http://127.0.0.1:8000/api/` - cần cập nhật `.env` nếu production
3. **Gemini API**: Cần thiết lập `GEMINI_API_KEY` trong `.env.example` nếu muốn dùng AI search
4. **CORS**: Đã cấu hình cho phép tất cả origins (có thể hạn chế trong production)
5. **Images**: Hình ảnh sản phẩm nằm trong `my-app/public/img/products/`

---

## 🚧 Phát Triển Tiếp Theo

- ❌ Hệ thống giỏ hàng (Cart)
- ❌ Checkout & Thanh toán
- ❌ User authentication & Account
- ❌ Order management
- ❌ Admin dashboard
- ❌ Unit tests & E2E tests

---

## 👤 Thông Tin Tác Giả

**Văn Tiết** (VanTiet / End123)

📧 Email: End123@finaltest.PNV.com
📍 Địa chỉ: Số 123, Đường ABC, Quận Sơn Trà, TP. Đà Nẵng
📞 Phone: (+84) 373 532 152

---

## 📄 License

Dự án này là bài thi cuối kỳ. Không được sử dụng thương mại.

---

**Ngày cập nhật**: 25/05/2026  
**Phiên bản**: 1.0.0

✨ *Happy Shopping with AI!* ✨
