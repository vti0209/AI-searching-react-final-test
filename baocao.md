# Luồng Xây Dựng Dự Án MTYTSHOP (Từ A đến Z)

Báo cáo này trình bày chi tiết luồng thực hiện dự án từ những bước đầu tiên (khởi tạo Database Backend) cho đến khi hoàn thiện giao diện và tích hợp AI ở Frontend.

---

## PHẦN 1: KHỞI TẠO BACKEND (LARAVEL)

Dự án bắt đầu từ phía Backend để xây dựng nền tảng dữ liệu và API vững chắc.

### Bước 1: Cấu hình môi trường (`.env`)
- **File:** `backend/.env`
- **Thực hiện:** Đây là file đầu tiên được động tới. Chúng ta thiết lập thông tin kết nối tới Database MySQL (DB_DATABASE, DB_USERNAME, DB_PASSWORD) và khai báo `GROQ_API_KEY` (trong `backend/.env`) dùng cho tính năng tìm kiếm AI.

### Bước 2: Thiết kế Cấu trúc Database (Migration)
- **File:** `backend/database/migrations/xxxx_xx_xx_create_products_table.php`
- **Thực hiện:** Định nghĩa bảng `products` trong CSDL với các cột như `name` (tên sản phẩm), `price` (giá), `description` (mô tả), `image` (link ảnh), `category` (danh mục).

### Bước 3: Đổ dữ liệu mẫu (Seeder & Factory)
- **File:** `backend/database/seeders/DatabaseSeeder.php` và `backend/database/factories/ProductFactory.php`
- **Thực hiện:** Để có dữ liệu test giao diện, chúng ta định nghĩa Factory để sinh dữ liệu giả và Seeder để tạo ra **63 sản phẩm** thời trang khác nhau.
- **Lệnh chạy:** Chạy lệnh `php artisan migrate:fresh --seed` để xóa sạch DB cũ, tạo lại bảng mới và nạp 63 sản phẩm này vào Database.

### Bước 4: Xây dựng Logic Xử lý (Controller & AI Integration)
- **File:** `backend/app/Http/Controllers/ProductController.php`
- **Thực hiện:** Xây dựng 2 hàm chính:
  1. `show($id)`: Lấy thông tin chi tiết của 1 sản phẩm.
  2. `index(Request $request)`: Xử lý tìm kiếm. Logic AI được tách thành một service riêng `App\\Services\\ProductSearchService` để gọi Groq API. Khi có `query`, controller gọi service này, nhận về tiêu chí lọc JSON (category, min_price, max_price, keywords, sort_by, explanation) rồi áp filter vào DB.

### Bước 5: Mở API cho Frontend (Routes)
- **File:** `backend/routes/api.php`
- **Thực hiện:** Khai báo 2 endpoint để Frontend có thể gọi tới:
  - `Route::get('/products', [ProductController::class, 'index']);`
  - `Route::get('/products/{id}', [ProductController::class, 'show']);`

---

## PHẦN 2: XÂY DỰNG FRONTEND (REACTJS / VITE)

Sau khi Backend đã có API và Dữ liệu, luồng công việc chuyển sang Frontend.

### Bước 1: Thiết lập Biến môi trường Frontend
- **File:** `my-app/.env`
- **Thực hiện:** Khai báo `VITE_API_URL=http://127.0.0.1:8000/api` để gọi API và để trống `VITE_BASE_URL=` để Frontend tự hiểu load ảnh tĩnh từ thư mục `public/img` của chính nó (Vite server).

### Bước 2: Khởi tạo luồng chạy chính (Entry Point)
- **File:** `my-app/src/index.jsx` và `my-app/src/App.jsx`
- **Thực hiện:** 
  - `index.jsx`: Nơi bọc toàn bộ ứng dụng bằng `<BrowserRouter>` để hỗ trợ chuyển trang.
  - `App.jsx`: Định nghĩa các tuyến đường (Routes) của web. Mọi route đều nằm trong một Layout chung (`MainLayout`).

### Bước 3: Xây dựng Layout và Component dùng chung
- **File:** `my-app/src/layout/MainLayout.jsx`, `components/Header.jsx`, `components/Footer.jsx`
- **Thực hiện:** `MainLayout` sẽ gọi `Header` (chứa Logo FASHIAI và menu) ở trên cùng, `<Outlet />` ở giữa (nơi nội dung các trang thay đổi), và `Footer` ở dưới cùng. Như vậy, trang nào cũng sẽ có Header và Footer đồng nhất.

### Bước 4: Xây dựng các Trang (Pages)
- **Trang Chủ (`pages/HomePage.jsx`):**
  - Hiển thị Banner Hero, thanh tìm kiếm lớn ở giữa màn hình. Khi ấn tìm kiếm, nó sẽ chuyển hướng (navigate) người dùng sang trang `ShopPage` kèm theo từ khóa trên URL (ví dụ: `/shop?query=Áo`).
  - Render các danh mục tĩnh, load ảnh thông qua `VITE_BASE_URL`.

- **Trang Cửa Hàng & Kết quả AI (`pages/ShopPage.jsx`):**
  - **Luồng hoạt động:** Sử dụng `useEffect` để bắt sự thay đổi của từ khóa (query) trên URL.
  - Gửi lệnh `fetch` gọi API Backend: `${import.meta.env.VITE_API_URL}/products?query=...`
  - **Hiển thị AI:** Nếu kết quả trả về có chứa câu giải thích từ AI (`data.explanation`), nó sẽ render một khối Alert nổi bật (màu xanh/icon Robot) để hiển thị lời khuyên của AI cho khách hàng.
  - Render danh sách các thẻ sản phẩm (Product Cards) kèm phân trang (Pagination).

- **Trang Chi Tiết Sản Phẩm (`pages/ProductDetailPage.jsx`):**
  - Nhận `id` sản phẩm từ URL.
  - Gọi API `${import.meta.env.VITE_API_URL}/products/${id}` để lấy thông tin.
  - Render chi tiết: Ảnh lớn bên trái, Tên, Giá, Mô tả và nút "Thêm vào giỏ hàng" bên phải.

### Bước 5: Chỉnh sửa và Hoàn thiện (Refactor)
- Rà soát lại việc hiển thị hình ảnh (`<img>`) trên tất cả các trang, sử dụng hàm thay thế chuỗi an toàn (`.slice()`) để ghép chính xác với biến môi trường, đảm bảo code chạy mượt mà, không gặp lỗi Parse Error của trình biên dịch Vite.

---

## TỔNG KẾT LUỒNG CHẠY THỰC TẾ CỦA USER
1. Khách hàng vào web (Load `index.jsx` -> `App.jsx` -> `HomePage.jsx`).
2. Khách hàng nhập *"tìm áo len màu vàng"* vào thanh tìm kiếm ở HomePage.
3. React chuyển hướng sang `ShopPage.jsx` với URL `?query=tìm áo len màu vàng`.
4. `ShopPage.jsx` gọi API sang Backend Laravel.
5. `ProductController.php` ở Backend nhận câu hỏi và gọi `ProductSearchService` để gửi prompt tới **Groq API**.
6. Groq trả về JSON mô tả tiêu chí lọc (ví dụ: category = "Shirt", max_price = 500000, keywords = ["áo"]).
7. Backend áp tiêu chí đó vào truy vấn DB, trả về danh sách sản phẩm phù hợp cùng `explanation` do Groq cung cấp.
8. `ShopPage.jsx` nhận Data, hiển thị câu tư vấn của AI lên Alert và render danh sách "Áo len màu vàng" bên dưới.
9. Khách click vào một áo len, chuyển sang `ProductDetailPage.jsx` để xem chi tiết và mua hàng.
