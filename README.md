#  Fashion E-commerce Platform - MTYTSHOP

Một nền tảng thương mại điện tử thời trang được xây dựng bằng **React + Laravel** với tính năng tìm kiếm AI tích hợp Groq API.

> Bài thi cuối kỳ môn **Thiết kế Web Nâng cao** & **ReactJS**

---

##  Tổng Quan Dự Án

| **Thành phần** | **Công nghệ** | **Trạng thái** |
|---|---|---|
| **Frontend** | React 18 + Vite + Bootstrap 5 |  Hoàn thành |
| **Backend** | Laravel 11 + SQLite |  Hoàn thành |
| **Database** | MySQL (63 sản phẩm) |  Hoàn thành |
| **Search** | Groq API + Keyword Search |  Hoàn thành |
| **UI/UX** | Responsive Design |  Hoàn thành |

---

##  Các Tính Năng Đã Hoàn Thành

###  Frontend (React)
-  Giao diện responsive với Bootstrap 5
-  Trang chủ với danh mục nổi bật
-  Trang sản phẩm với phân trang (63 sản phẩm / 8 trang)
-  Tìm kiếm thông minh:
  - **Tìm kiếm AI** sử dụng Gemini 2.5 Flash
  - **Tìm kiếm Keyword** fallback (tên, danh mục, giá)
  - **Lọc theo**: Danh mục, khoảng giá
-  Trang chi tiết sản phẩm
-  Thiết kế hiện đại, dễ sử dụng

###  Backend (Laravel)
-  RESTful API complete
-  Endpoints chính:
  - `GET /api/products` - Lấy danh sách sản phẩm (có phân trang)
  - `GET /api/products/{id}` - Lấy chi tiết sản phẩm
  - `GET /api/products` - Lấy danh sách sản phẩm (hỗ trợ query `?query=...` để tìm kiếm AI hoặc tìm kiếm từ khóa)
-  Database SQLite với 9 danh mục sản phẩm
-  CORS được cấu hình

###  Database (MySQL)
```
Database: **MySQL** (reactjs_final)
 63 Sản phẩm được phân loại:
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

##  Cấu Trúc Dự Án

```
ReactJs_Final/
├─ baocao.md                     # Báo cáo số 1 - Tổng quan dự án
├─ BAOCAO2.md                    # Báo cáo số 2 - Chi tiết tính năng tìm kiếm AI
├─ README.md                     # Tài liệu này
│
├─ backend/                      # 🔧 Laravel Backend (API Server)
│  ├─ app/
│  │  ├─ Http/
│  │  │  └─ Controllers/
│  │  │     └─ ProductController.php      # Xử lý /api/products
│  │  ├─ Models/
│  │  │  └─ Product.php                   # Database model
│  │  ├─ Services/
│  │  │  └─ ProductSearchService.php      # Groq AI integration
│  │  └─ Providers/
│  │     └─ AppServiceProvider.php
│  ├─ bootstrap/
│  │  ├─ app.php
│  │  ├─ providers.php
│  │  └─ cache/
│  ├─ config/                    # Cấu hình ứng dụng
│  │  ├─ app.php
│  │  ├─ auth.php
│  │  ├─ cache.php
│  │  ├─ cors.php
│  │  ├─ database.php
│  │  └─ ...
│  ├─ database/
│  │  ├─ database.sql            # Backup database
│  │  ├─ factories/
│  │  │  └─ UserFactory.php
│  │  ├─ migrations/
│  │  │  └─ create_products_table.php     # Tạo bảng products
│  │  └─ seeders/
│  │     ├─ DatabaseSeeder.php
│  │     └─ ProductSeeder.php            # 63 sản phẩm mẫu
│  ├─ public/
│  │  ├─ index.php               # Laravel entry point
│  │  ├─ robots.txt
│  │  └─ storage/
│  ├─ resources/
│  │  ├─ css/
│  │  │  └─ app.css
│  │  ├─ js/
│  │  │  ├─ app.js
│  │  │  └─ bootstrap.js
│  │  └─ views/
│  │     └─ welcome.blade.php
│  ├─ routes/
│  │  ├─ api.php                # ⭐ API routes (routes chính)
│  │  ├─ web.php
│  │  └─ console.php
│  ├─ storage/
│  │  ├─ app/
│  │  ├─ framework/
│  │  └─ logs/
│  ├─ vendor/                    # PHP dependencies (composer packages)
│  ├─ .env.example               # Config template
│  ├─ .env                       # Config thực tế (GROQ_API_KEY, DB...)
│  ├─ composer.json              # PHP dependencies
│  ├─ package.json               # NPM dependencies (Vite)
│  ├─ vite.config.js             # Vite config
│  ├─ artisan                    # Laravel CLI
│  └─ README.md
│
├─ my-app/                       # 🎨 React Frontend (UI)
│  ├─ src/
│  │  ├─ components/             # React components tái sử dụng
│  │  ├─ pages/                  # Các trang chính
│  │  │  ├─ HomePage.jsx
│  │  │  ├─ ShopPage.jsx         # ⭐ Trang tìm kiếm sản phẩm
│  │  │  └─ ProductDetailPage.jsx
│  │  ├─ layout/
│  │  │  └─ MainLayout.jsx
│  │  ├─ css/                    # Stylesheets
│  │  ├─ utils/                  # Hàm tiện ích
│  │  ├─ App.jsx                 # Root component
│  │  ├─ App.css
│  │  └─ index.jsx               # Entry point
│  ├─ public/
│  │  ├─ img/                    # Hình ảnh
│  │  │  ├─ products/            # Ảnh sản phẩm
│  │  │  └─ ...
│  │  ├─ css/
│  │  ├─ js/
│  │  ├─ fonts/
│  │  ├─ Template/
│  │  ├─ manifest.json
│  │  └─ robots.txt
│  ├─ index.html
│  ├─ package.json               # JS dependencies
│  ├─ vite.config.js             # Vite config
│  ├─ .env.example               # Config template (VITE_API_URL)
│  ├─ .env                       # Config thực tế
│  ├─ README.md
│  └─ node_modules/              # JS dependencies (npm packages)
│
└─ docs/                         # (Tùy chọn) Tài liệu chi tiết
```

###  **Files Quan Trọng:**
- **Backend API:** `backend/routes/api.php` (định nghĩa routes)
- **Backend Controller:** `backend/app/Http/Controllers/ProductController.php` (xử lý logic)
- **Groq Service:** `backend/app/Services/ProductSearchService.php` (AI search)
- **Frontend Main:** `my-app/src/pages/ShopPage.jsx` (giao diện tìm kiếm)
- **Database Setup:** `backend/database/seeders/ProductSeeder.php` (63 sản phẩm)
- **Reports:** `BAOCAO2.md` (chi tiết luồng tìm kiếm AI)

---

##  Cách Chạy Dự Án

### 1️ Chuẩn Bị Môi Trường

**Backend (Laravel):**
```bash
cd backend

# Cài đặt dependencies PHP
composer install

# Cấu hình .env theo đúng tên database bạn đã tạo đoạn sau:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE= tên database bạn đã tạo
# DB_USERNAME=root
# DB_PASSWORD=

# Tạo app key nếu cần
php artisan key:generate

# Và Chạy migration và seeding để có đầy đủ giữ liệu test
php artisan migrate:refresh --seed
```

**Frontend (React):**
```bash
cd my-app

# Cài đặt dependencies Node
npm install
```

---

### 2️ Khởi Động Servers

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

 Frontend sẽ tự động mở ở: **http://localhost:5173**
 Backend API: **http://127.0.0.1:8000/api**

---

## ⚙️ Yêu Cầu Hệ Thống

- **PHP**: >= 8.2
- **Node.js**: >= 16.0
- **MySQL**: >= 5.7
- **npm**: >= 8.0

---

##  Các API Endpoints

###  1. Lấy Danh Sách Sản Phẩm (Có Phân Trang)

```bash
GET http://127.0.0.1:8000/api/products?page=1

# Response:
{
  "success": true,
  "products": [
    {
      "id": 1,
      "name": "Áo Len Vặn Eo Cổ V Màu Vàng",
      "category": "Coat",
      "price": "299000.00",
      "image": "img/products/product-1.jpg",
      "description": "Áo len cao cấp..."
    },
    ...
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 8,
    "total": 63
  },
  "is_ai": false,
  "explanation": null
}
```

###  2. Tìm Kiếm Với AI

```bash
GET http://127.0.0.1:8000/api/products?query=áo%20khoác%20màu%20xanh&page=1

# Request từ Frontend:
# /api/products?query=áo khoác xanh&page=1

# Response:
{
  "success": true,
  "products": [
    {
      "id": 3,
      "name": "Áo Khoác Xanh Navy",
      "category": "Coat",
      "price": "450000.00",
      "image": "img/products/product-3.jpg"
    },
    ...
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 1,
    "total": 5
  },
  "is_ai": true,
  "explanation": "Tôi tìm thấy áo khoác xanh trong danh sách cho bạn"
}
```

**Quy trình:**
1. Frontend gửi `?query=áo khoác xanh`
2. Backend gọi **ProductSearchService** → Groq API phân tích
3. AI trả về: category = "Coat", keywords = ["xanh", "khoác"]
4. Backend lọc DB theo tiêu chí → Trả về kết quả

###  3. Lấy Chi Tiết 1 Sản Phẩm

```bash
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
    "description": "Áo len cao cấp, chất lượng tốt...",
    "created_at": "2026-05-25T07:44:19.000000Z",
    "updated_at": "2026-05-25T07:44:19.000000Z"
  }
}
```

---

##  Các Trang & Routes

| **Route** | **Mô tả** |
|---|---|
| `/` |  Trang chủ |
| `/shop` |  Trang sản phẩm (Phân trang) |
| `/product/:id` |  Chi tiết sản phẩm |
| `/cart` |  Giỏ hàng (Placeholder) |

---

## Danh Mục Sản Phẩm

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

##  Tính Năng Tìm Kiếm (AI + Fallback)

###  Tìm Kiếm AI (Groq API)
- **Model:** `llama-3.3-70b-versatile` (Groq)
- **Quy trình:**
  1. Frontend gửi `?query=áo khoác xanh giá dưới 500k`
  2. Backend gọi `ProductSearchService->analyze()` 
  3. Groq API phân tích → Trả về JSON: `{category: "Coat", keywords: ["xanh"], max_price: 500000}`
  4. Backend lọc DB theo tiêu chí → Trả về kết quả có `is_ai: true`
- **Ví dụ:**
  ```bash
  GET http://127.0.0.1:8000/api/products?query=áo khoác xanh giá dưới 500k
  ```
  Response:
  ```json
  {
    "success": true,
    "is_ai": true,
    "explanation": "Tìm kiếm áo khoác màu xanh với giá dưới 500 nghìn VND",
    "products": [ ... ],
    "pagination": { ... }
  }
  ```

###  Fallback - Tìm Kiếm Keyword
- **Kích hoạt khi:**
  - Không có `GROQ_API_KEY` trong `.env`
  - Groq API trả lỗi
  - API timeout
- **Cách hoạt động:** Tìm từ khóa trong `name` và `description` của sản phẩm
- **Response:**
  ```json
  {
    "success": true,
    "is_ai": false,
    "explanation": null,
    "products": [ ... ]
  }
  ```

###  Cấu Hình Groq API
##### Đối với bài tập hiện tại tôi đã công khai key mà tôi đã tạo bạn chỉ cần xem lại và chạy test được 
Thêm vào `backend/.env`:
```env
GROQ_API_KEY=gsk_XXXXXXXXXXXXX
```
Lấy API key từ: https://console.groq.com/keys

---

##  Công Nghệ Sử Dụng

### Frontend Stack
- **React 18** - UI framework
- **React Router v6** - Navigation
- **Vite** - Build tool
- **Bootstrap 5** - CSS Framework
- **Axios** - HTTP client

### Backend Stack
- **Laravel 11** - Web framework
- **MySQL** - Database
- **Groq API** - AI model (LLaMA 3.3-70b)
- **PHP 8.2+** - Runtime

### Công Nghệ Khác
- **Node.js / npm** - Package management
- **Composer** - PHP package manager
- **REST API** - Backend communication

##  Yêu Cầu & Cài Đặt Hệ Thống

###  Yêu cầu tối thiểu:
- **PHP**: >= 8.2
- **Node.js**: >= 16.0
- **MySQL**: >= 5.7 hoặc MariaDB >= 10.3
- **npm**: >= 8.0
- **Composer**: >= 2.0

###  Biến Môi Trường Quan Trọng:

**Backend** (`backend/.env`):
```env
APP_NAME=FASHIAI
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=root
DB_PASSWORD=

# Groq API (TÌM KIẾM AI)
GROQ_API_KEY=gsk_XXXXXXXXXXXXX  # Lấy từ https://console.groq.com/keys

# CORS
CORS_ALLOWED_ORIGINS=http://localhost:5173,http://localhost:3000
```

**Frontend** (`my-app/.env`):
```env
VITE_API_URL=http://127.0.0.1:8000/api
```

---

##  Thống Kê

| **Chỉ số** | **Giá trị** |
|---|---|
|  Tổng sản phẩm | 63 |
|  Trang trên UI | 8 (phân trang 8 item/page) |
|  Danh mục | 9 |
|  API endpoints | 3 chính |
|  React components | 10+ |
|  Routes | 4 |
|  Database tables | 2 (products, users) |



---

##  Ghi Chú & Lưu Ý

###  Cấu Hình Quan Trọng:

1. **Database MySQL:**
   - Database: `reactjs_final`
   - User: `root` (mặc định)
   - Chạy migration/seeding để tạo 63 sản phẩm:
     ```bash
     cd backend
     php artisan migrate:refresh --seed
     ```

2. **Groq API (Bắt buộc cho AI Search):**
   - Đăng ký tại: https://console.groq.com
   - Tạo API key
   - Thêm vào `backend/.env`: `GROQ_API_KEY=gsk_XXXXXXXXXXXXX`
   - Sau khi cập nhật `.env`, chạy:
     ```bash
     cd backend
     php artisan config:clear
     php artisan cache:clear
     ```
   - **Nếu không có API key**: Hệ thống sẽ fallback sang tìm kiếm keyword thường (vẫn hoạt động bình thường)

3. **Frontend API URL:**
   - Kiểm tra `my-app/.env` để `VITE_API_URL` đúng
   - Mặc định: `http://127.0.0.1:8000/api`
   - Nếu backend chạy ở port khác, cập nhật URL này

4. **CORS Configuration:**
   - Đã cấu hình trong `backend/config/cors.php`
   - Cho phép request từ frontend (port 5173)
   - Trong production, hạn chế origins cụ thể

5. **Images & Assets:**
   - Hình ảnh sản phẩm: `my-app/public/img/products/product-{id}.jpg`
   - Favicon & manifest: `my-app/public/`

---

##  Phát Triển Tiếp Theo (Scope Mở Rộng)

-  Giỏ hàng (Cart system)
-  Checkout & Thanh toán (Payment gateway)
-  User authentication & Account (Login/Register)
-  Order management (Lịch sử mua hàng)
-  Admin dashboard (Quản lý sản phẩm)
-  Unit tests & E2E tests (Testing)
-  Wishlist feature
-  Product reviews & ratings

---

##  Tài Liệu Tham Khảo

| **Công Nghệ** | **Liên Kết** |
|---|---|
| React Docs | https://react.dev |
| Laravel Docs | https://laravel.com/docs |
| Groq API | https://console.groq.com |
| Bootstrap | https://getbootstrap.com |
| Vite | https://vitejs.dev |

---

## Báo Cáo & Tài Liệu Dự Án

-  **[BAOCAO2.md](BAOCAO2.md)** - Báo cáo số 2: Chi tiết luồng tìm kiếm AI (22 phần)

---

##  Thông Tin Tác Giả

**Văn Tiết** (VanTiet / End123)

Email: End123@finaltest.PNV.com  
Địa chỉ: Số 123, Đường ABC, Quận Sơn Trà, TP. Đà Nẵng  
Phone: (+84) 111 222 321

---

##  License

 **Bản quyền:** Dự án này là bài thi cuối kỳ môn **Thiết kế Web Nâng cao** & **ReactJS**.  
 **Không được phép:** Sử dụng thương mại, sao chép hoặc phân phối mà không có sự cho phép.

---

**Ngày tạo**: 25/05/2026  
**Ngày cập nhật**: 04/06/2026  

 *Happy Shopping with AI!* 
