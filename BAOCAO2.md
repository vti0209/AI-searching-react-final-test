#  BÁOCAO SỐ 2: LUỒNG ĐI CHI TIẾT TÍNH NĂNG TÌM KIẾM AI

##  TỔNG QUAN TÍNH NĂNG

Tính năng tìm kiếm AI của ứng dụng FASHIAI hoạt động theo quy trình sau:

**Người dùng nhập từ khóa → Frontend gửi request → Backend gọi Groq API → AI phân tích → Trả về tiêu chí lọc → Tìm kiếm trong DB → Hiển thị kết quả**

---

##  BƯỚC 1: NGƯỜI DÙNG NHẬP TỪ KHÓA TRÊN FRONTEND

###  File: `my-app/src/pages/ShopPage.jsx`
**Vai trò:** Trang chính để người dùng tìm kiếm sản phẩm.

#### 🔹 Khác 1: Định nghĩa State và lấy tham số URL

```javascript
// Dòng 6-17
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
```

**Giải thích chi tiết từng dòng:**

1. **`const [searchParams] = useSearchParams();`** - Dòng 6
   - Hook React Router để lấy tham số từ URL
   - Ví dụ: URL là `/shop?query=áo&page=2` → `searchParams` chứa `{query: 'áo', page: '2'}`
   - Trả về cặp `[searchParams, setSearchParams]` nhưng ta chỉ cần `searchParams`

2. **`const query = searchParams.get('query') || '';`** - Dòng 7
   - Lấy giá trị của tham số `query` từ URL
   - **`searchParams.get('query')`**: Nếu URL có `?query=áo` → trả về string `"áo"`
   - **`||` (hoặc)**: Nếu không có tham số `query` hoặc giá trị là `null` → dùng string rỗng `""`
   - Ví dụ: `/shop` (không có query) → `query = ""`; `/shop?query=áo` → `query = "áo"`

3. **`const page = searchParams.get('page') || '1';`** - Dòng 8
   - Lấy số trang từ URL, mặc định là trang 1
   - **`searchParams.get('page')`**: Nếu URL có `?page=2` → trả về string `"2"`
   - **`||` (hoặc)**: Nếu không có tham số `page` → dùng `"1"`
   - Ví dụ: `/shop` → `page = "1"`; `/shop?page=3` → `page = "3"`
   -  Lưu ý: Giá trị là string, không phải số (JavaScript URL parameters luôn là string)

4. **`const navigate = useNavigate();`** - Dòng 9
   - Lấy hook từ React Router để thay đổi URL mà không reload trang
   - Dùng để khi user submit form → thay đổi URL `/shop?query=áo%20khoác&page=1`
   - Giúp lưu trạng thái tìm kiếm trong URL (người dùng có thể chia sẻ link)

5. **`const [products, setProducts] = useState([]);`** - Dòng 11
   - State lưu danh sách 8 sản phẩm từ API
   - **`[]` (mảng rỗng)**: Giá trị ban đầu (chưa gọi API)
   - **`setProducts()`**: Hàm để cập nhật state
   - Ví dụ khi nhận từ API: `setProducts([{id: 1, name: "Áo 1", ...}, {id: 2, name: "Áo 2", ...}, ...])`
   - Component sẽ re-render khi `products` thay đổi

6. **`const [pagination, setPagination] = useState({ current_page: 1, last_page: 1, total: 0 });`** - Dòng 12
   - State lưu thông tin phân trang (trang hiện tại, trang cuối, tổng sản phẩm)
   - **Giá trị ban đầu:**
     - `current_page: 1` = Ở trang 1
     - `last_page: 1` = Trang cuối là 1 (chỉ 1 trang)
     - `total: 0` = Tổng 0 sản phẩm
   - **Dùng để hiển thị:** Nút phân trang `<1> <2> <3> <4> <5>` và trạng thái disabled

7. **`const [loading, setLoading] = useState(true);`** - Dòng 13
   - State để tracking khi đang gọi API (loading data)
   - **`true`**: Ban đầu đang loading (hiển thị spinner)
   - **Dùng để:** Hiển thị/ẩn biểu tượng loading spinner
   - Khi API trả về → `setLoading(false)` → ẩn spinner

8. **`const [aiExplanation, setAiExplanation] = useState(null);`** - Dòng 14
   - State lưu câu giải thích từ AI
   - **`null`**: Ban đầu không có giải thích (chưa tìm kiếm)
   - **Ví dụ từ API:** `"Tôi tìm thấy 40 áo khoác với giá dưới 500 nghìn VND"`
   - **Dùng để:** Hiển thị hộp thông báo cho user

9. **`const [isAi, setIsAi] = useState(false);`** - Dòng 15
   - State flag xác định có dùng AI hay không
   - **`false`**: Ban đầu không dùng AI (chưa tìm kiếm)
   - **Từ API:** `true` = Dùng Groq API thành công; `false` = Fallback tìm kiếm từ khóa
   - **Dùng để:** Quyết định icon hiển thị (robot hay search icon)

10. **`const [searchText, setSearchText] = useState(query);`** - Dòng 16
    - State lưu text hiện tại trong input (trong quá trình gõ)
    - **Giá trị ban đầu:** `query` (từ URL)
    - **Cập nhật khi:** User gõ từ khóa vào input (event `onChange`)
    - **Dùng để:** Hiển thị giá trị trong input field
    - Ví dụ: User gõ "áo" → `searchText` = "áo" → input value = "áo"

#### 🔹 Khác 2: Form tìm kiếm - Người dùng nhập từ khóa

```javascript
// Dòng 62-79
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
```

**Giải thích chi tiết từng dòng:**

1. **`<form onSubmit={handleSearchSubmit}>`** - Dòng 62-63
   - HTML form element để submit tìm kiếm
   - **`onSubmit={handleSearchSubmit}`**: Khi user nhấn nút submit hoặc Enter → gọi hàm `handleSearchSubmit()`
   - **`className="hero-search-form-premium"`**: CSS class để style form

2. **`<div className="input-group ...">`** - Dòng 64
   - Container cho input + button
   - **`input-group`**: Bootstrap class để ghép input và button lại
   - **`shadow-sm rounded-pill overflow-hidden bg-white border`**: CSS classes để style bóng, bo góc, màu trắng, border

3. **`<input type="text" ... />`** - Dòng 65-71
   - Input field để user nhập từ khóa
   - **`type="text"`**: Loại input là text (cho phép gõ bất kỳ ký tự)
   - **`placeholder="Bạn muốn tìm gì..."`**: Text hiển thị khi input rỗng (gợi ý cho user)
   - **`value={searchText}`**: Giá trị hiển thị trong input từ state `searchText`
     - Ví dụ: User gõ "áo" → state `searchText` = "áo" → input value = "áo"
   - **`onChange={(e) => setSearchText(e.target.value)}`**: Khi user gõ
     - `e` = event object
     - `e.target` = input element
     - `e.target.value` = giá trị text hiện tại trong input
     - `setSearchText()` = cập nhật state
     - Ví dụ: User gõ "á" → `setSearchText("á")` → state `searchText` = "á" → input re-render với value="á"

4. **`<button type="submit" ...>`** - Dòng 72-76
   - Nút submit form
   - **`type="submit"`**: Khi click button → form sẽ trigger `onSubmit` event
   - Tương đương: User nhấn Enter trong input
   - **`className="btn btn-search-premium px-4"`**: Bootstrap button class + CSS custom
   - **`<i className="bi bi-search" />`**: Icon search (biểu tượng kính lúp)

#### 🔹 Khác 3: Xử lý submit form - Gửi yêu cầu tìm kiếm

```javascript
// Dòng 50-57
const handleSearchSubmit = (e) => {
    e.preventDefault();
    if (searchText.trim()) {
        navigate(`/shop?query=${encodeURIComponent(searchText.trim())}`);
    } else {
        navigate('/shop');
    }
};
```

**Giải thích chi tiết từng dòng:**

1. **`const handleSearchSubmit = (e) => { ... };`** - Dòng 50-57
   - Hàm xử lý khi form được submit
   - **`(e) =>`**: `e` = form submit event object
   - **Arrow function**: Syntax JavaScript hiện đại

2. **`e.preventDefault();`** - Dòng 51
   - Ngăn chặn hành động mặc định của form
   - **Hành động mặc định:** Form sẽ reload trang (HTTP POST)
   - **Với `preventDefault()`**: Chỉ chạy code JavaScript, không reload
   - **Lợi ích:** User vẫn ở cùng trang, URL thay đổi mà không reload (smooth)

3. **`if (searchText.trim()) { ... } else { ... }`** - Dòng 52-57
   - Kiểm tra người dùng có nhập từ khóa hay không
   - **`searchText.trim()`**: 
     - `.trim()` = xóa khoảng trắng đầu/cuối
     - Ví dụ: `"  áo  ".trim()` → `"áo"`
     - Kiểm tra nếu còn ký tự → true, nếu rỗng → false
   - **If true** (user nhập từ khóa):
     - `navigate(`/shop?query=${encodeURIComponent(searchText.trim())}`)` → Tìm kiếm
   - **If false** (input rỗng):
     - `navigate('/shop')` → Không có tham số query → Về trang shop mặc định

4. **`navigate(`/shop?query=${encodeURIComponent(searchText.trim())}`)` ** - Dòng 53
   - Thay đổi URL và trigger re-render
   - **`navigate()`**: React Router hook để thay đổi route
   - **`/shop`**: Tuyến đường/path
   - **`?query=...`**: Query string parameter
   - **`${encodeURIComponent(searchText.trim())}`**: URL encoding
     - `encodeURIComponent()` chuyển ký tự đặc biệt thành %XX
     - Ví dụ: `"áo khoác"` → `"áo%20khoác"` (space = %20)
     - **Lý do:** URL không được chứa khoảng trắng trực tiếp
   - **Full URL example:** `/shop?query=áo%20khoác`
   - **Kết quả:** URL thay đổi → `useEffect` kích hoạt (phụ thuộc vào `query`) → gọi API

5. **`navigate('/shop');`** - Dòng 55
   - Nếu input rỗng → Chuyển tới `/shop` (không có `?query=...`)
   - Sẽ hiển thị tất cả sản phẩm (do không có `query`)
   - Backend sẽ không gọi AI, chỉ lấy toàn bộ sản phẩm phân trang

---

##  BƯỚC 2: URL THAY ĐỔI - USEEFFECT ĐƯỢC KÍCH HOẠT

###  File: `my-app/src/pages/ShopPage.jsx`

#### 🔹 Khác 4: Hook useEffect - Lắng nghe thay đổi query

```javascript
// Dòng 19-40
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
```

**Giải thích chi tiết từng dòng:**

1. **`useEffect(() => { ... }, [query, page]);`** - Cả dòng 19 và 40
   - Dòng 19: `useEffect(` → Khai báo Hook useEffect để chạy code khi dependency thay đổi
   - Dòng 40: `}, [query, page]);` → Dependency array: khi `query` hoặc `page` thay đổi → code trong useEffect tự động chạy lại
   - Ví dụ: User nhập "áo" → `query` thay đổi từ "" thành "áo" → useEffect chạy → gửi request tới API

2. **`setLoading(true);`** - Dòng 20
   - Bật trạng thái loading (hiển thị spinner/progress bar)
   - Báo cho user: "Đang tải dữ liệu..."

3. **`fetch(`${import.meta.env.VITE_API_URL}/products?query=${encodeURIComponent(query)}&page=${page}`)`** - Dòng 21
   - Gửi HTTP GET request tới backend
   - **`import.meta.env.VITE_API_URL`**: Lấy base URL từ file `.env` (ví dụ: `http://127.0.0.1:8000/api`)
   - **`/products`**: Endpoint của API
   - **`?query=${encodeURIComponent(query)}`**: Thêm tham số `query` vào URL
     - Ví dụ: `query = "áo khoác"` → `encodeURIComponent()` chuyển thành `"áo%20khoác"` (URL-safe)
     - Kết quả: `...?query=áo%20khoác`
   - **`&page=${page}`**: Thêm tham số `page` vào URL
     - Ví dụ: `page = "2"` → Kết quả: `...&page=2`
   - **Full URL example:** `http://127.0.0.1:8000/api/products?query=áo%20khoác&page=1`

4. **`.then((res) => res.json())`** - Dòng 22
   - Xử lý khi nhận được response từ server
   - `res` = HTTP response object (chứa status, headers, body)
   - `.json()` = Chuyển body (JSON string) thành JavaScript object
   - Ví dụ: `res` có body là `'{"success":true,"products":[...]}'` → chuyển thành object `{success: true, products: [...]}`

5. **`.then((data) => { ... })`** - Dòng 23-32
   - Xử lý khi JSON đã được parse thành object
   - `data` = JavaScript object từ backend
   - Ví dụ: 
   ```javascript
   data = {
       success: true,
       products: [{id: 1, name: "Áo 1", price: 200000}, ...],
       pagination: {current_page: 1, last_page: 5, total: 40},
       is_ai: true,
       explanation: "Tôi tìm thấy 40 sản phẩm..."
   }
   ```

6. **`if (data.success) { ... }`** - Dòng 24
   - Kiểm tra backend có trả về thành công không
   - Nếu `data.success === true` → Backend tìm kiếm thành công
   - Nếu `false` → Có lỗi, không cập nhật gì

7. **`setProducts(data.products);`** - Dòng 25
   - Lưu danh sách 8 sản phẩm vào state `products`
   - Component sẽ re-render để hiển thị các sản phẩm này
   - Ví dụ: `data.products = [{id: 1, name: "Áo Coat", price: 350000, ...}, ...]` (8 sản phẩm)

8. **`setPagination(data.pagination || { current_page: 1, last_page: 1, total: data.products.length });`** - Dòng 26
   - Lưu thông tin phân trang vào state
   - **`data.pagination`**: Nếu backend trả về pagination → lưu đó
   - **`||`**: Nếu `data.pagination` rỗng/undefined → dùng object mặc định
   - Ví dụ: `data.pagination = {current_page: 1, last_page: 5, total: 40}`
   - Thông tin này dùng để hiển thị nút phân trang: `<1> <2> <3> <4> <5>`

9. **`setAiExplanation(data.explanation);`** - Dòng 27
   - Lưu câu giải thích từ AI vào state
   - Ví dụ: `data.explanation = "Tôi tìm thấy 40 áo khoác với giá dưới 500 nghìn VND"`
   - Dùng để hiển thị hộp thông báo cho user

10. **`setIsAi(data.is_ai);`** - Dòng 28
    - Lưu flag xác định có dùng AI hay không
    - `true` = Dùng Groq API phân tích thành công (hiển thị icon robot)
    - `false` = AI không hoạt động, dùng tìm kiếm từ khóa (hiển thị icon search)
    - Dùng để quyết định hiển thị icon nào trong hộp thông báo

11. **`setLoading(false);`** - Dòng 30
    - Tắt trạng thái loading
    - Ẩn spinner/progress bar
    - Component re-render và hiển thị dữ liệu

12. **`.catch((err) => { ... })`** - Dòng 31-34
    - Xử lý khi có lỗi (network error, server down, ...)
    - Ví dụ lỗi: `err.message = "Failed to fetch"` (mất kết nối internet)

13. **`console.error('Error fetching products:', err);`** - Dòng 32
    - In lỗi ra browser console (để developer debug)
    - Nếu user mở DevTools (F12) → sẽ thấy thông báo lỗi

14. **`setLoading(false);`** - Dòng 33 (trong catch block)
    - Tắt loading ngay cả khi có lỗi
    - Tránh spinner hiển thị vĩnh viễn

** Dữ liệu nhận được từ API:**
```json
{
    "success": true,
    "products": [...],  // Mảng 8 sản phẩm
    "pagination": {
        "current_page": 1,
        "last_page": 5,
        "total": 40
    },
    "is_ai": true,  // Dùng AI hay không
    "explanation": "Tôi tìm thấy 40 áo khoác với giá dưới 500k"
}
```

---

## 📍 BƯỚC 3: BACKEND NHẬN REQUEST VÀ XỬ LÝ

### 📁 File: `backend/routes/api.php`

#### 🔹 Khác 5: Định tuyến - Mapping URL với hàm xử lý

```php
// Dòng 11-12
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
```

**Giải thích chi tiết từng dòng:**

1. **`Route::get('/products', [ProductController::class, 'index']);`** - Dòng 11
   - Định tuyến HTTP GET request tới endpoint `/products`
   - **`Route::get()`**: Chỉ nhận HTTP GET request
   - **`'/products'`**: Endpoint path (Frontend gọi: `/api/products?query=áo`)
   - **`[ProductController::class, 'index']`**: Sử dụng hàm `index()` trong class `ProductController`
     - **`ProductController::class`**: Lấy tên class (tương đương "App\\Http\\Controllers\\ProductController")
     - **`'index'`**: Tên hàm trong controller
   - **Luồng:** Frontend `fetch('/api/products?query=...')` → Laravel router nhận → gọi `ProductController@index()`

2. **`Route::get('/products/{id}', [ProductController::class, 'show']);`** - Dòng 12
   - Định tuyến để lấy chi tiết 1 sản phẩm
   - **`'/products/{id}'`**: Endpoint với parameter động
     - **`{id}`**: Placeholder cho ID sản phẩm
     - Frontend gọi: `/api/products/5` → `{id}` = 5
   - **`'show'`**: Hàm `show()` trong controller
   - **Cách lấy ID trong controller:** `$id` parameter sẽ tự động inject từ route
   - **Luồng:** Frontend `fetch('/api/products/5')` → Laravel router nhận → gọi `ProductController@show(5)`

---

## BƯỚC 4: CONTROLLER XỬ LÝ - LỌC LOGIC

### File: `backend/app/Http/Controllers/ProductController.php`

#### 🔹 Khác 6: Bắt đầu hàm index - Lấy tham số

```php
// Dòng 7-9
public function index(Request $request)
{
    $query = $request->query('query');  // Lấy: ?query=áo khoác
```

**Giải thích chi tiết từng dòng:**

1. **`public function index(Request $request)`** - Dòng 7-8
   - Định nghĩa hàm public (có thể gọi từ API route)
   - **`Request $request`**: Laravel tự động inject object request từ HTTP client
   - **`$request` chứa:**
     - `query`: Tất cả tham số URL (`?query=áo&page=2` → `['query' => 'áo', 'page' => '2']`)
     - `headers`: Tất cả HTTP headers (Content-Type, Authorization, ...)
     - `method`: HTTP method (GET, POST, PUT, DELETE)
     - `body`: Nội dung POST/PUT request
   - **Dependency Injection pattern**: Không cần `$_GET` hay `parse_url()`, Laravel xử lý sẵn

2. **`$query = $request->query('query');`** - Dòng 9
   - Lấy giá trị của tham số `query` từ URL query string
   - **Ví dụ request từ Frontend:**
     ```
     GET /api/products?query=áo%20khoác&page=1
     ```
   - **Kết quả:**
     - `$query = "áo khoác"` (Laravel tự động URL-decode `áo%20khoác`)
   - **Nếu Frontend gửi:** 
     ```
     GET /api/products?query=áo%20khoác%20xanh&page=2
     ```
   - **Kết quả:**
     - `$query = "áo khoác xanh"`
   - **Nếu không có tham số query:**
     ```
     GET /api/products
     ```
   - **Kết quả:**
     - `$query = null` (không phải rỗng, mà `null`)

#### 🔹 Khác 7: Kiểm tra query trống - Trả về tất cả sản phẩm

```php
// Dòng 10-23
if (empty($query)) {
    $products = Product::paginate(8);
    return response()->json([
        'success' => true,
        'products' => $products->items(),
        'pagination' => [
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'total' => $products->total(),
        ],
        'is_ai' => false,
        'explanation' => null
    ]);
}
```

**Giải thích chi tiết từng dòng:**

1. **`if (empty($query)) {`** - Dòng 10
   - Kiểm tra xem `$query` có trống không
   - **`empty()`**: Trả về `true` nếu:
     - `$query = null` (không có tham số)
     - `$query = ""` (chuỗi rỗng)
     - `$query = 0` hoặc `"0"`
   - **Ý nghĩa:** Người dùng chưa nhập từ khóa tìm kiếm nào
   - **Ví dụ:**
     - URL: `/api/products` → `empty($query)` = `true`
     - URL: `/api/products?query=` → `empty($query)` = `true`
     - URL: `/api/products?query=áo` → `empty($query)` = `false`

2. **`$products = Product::paginate(8);`** - Dòng 11
   - Lấy tất cả sản phẩm từ database, chia thành pages (8 sản phẩm/trang)
   - **`Product`**: Eloquent model (đại diện bảng products)
   - **`::`**: Gọi static method
   - **`paginate(8)`**: 
     - Truy vấn DB, lấy tất cả products (không filter)
     - Chia thành trang, mỗi trang 8 items
     - Tự động thêm `LIMIT 8 OFFSET 0` cho trang 1
   - **Kết quả `$products`:**
     ```php
     LengthAwarePaginator {
         items: [Product, Product, ..., Product],  // 8 sản phẩm
         current_page: 1,
         total: 63,  // Tổng số products trong DB
         last_page: 8,  // 63/8 = 7 dư 7 → 8 trang
         per_page: 8
     }
     ```

3. **`return response()->json([...])`** - Dòng 12
   - Trả về JSON response về Frontend
   - **`response()`**: Laravel helper tạo HTTP response
   - **`.json()`**: Chuyển array thành JSON, tự động set header `Content-Type: application/json`

4. **`'success' => true,`** - Dòng 13
   - Flag thành công, Frontend dùng để kiểm tra
   - Nếu `success = false`, Frontend sẽ hiểu có lỗi

5. **`'products' => $products->items(),`** - Dòng 14
   - **`$products->items()`**: Lấy mảng 8 sản phẩm của trang hiện tại
   - **Kết quả là mảng:**
     ```php
     [
         {id: 1, name: "Áo khoác xanh", price: 350000, category: "Coat", ...},
         {id: 2, name: "Áo sơ mi trắng", price: 150000, category: "Shirt", ...},
         ...
         {id: 8, name: "Giày thể thao", price: 800000, category: "Shoes", ...}
     ]
     ```

6. **`'pagination' => [...]`** - Dòng 15-19
   - Thông tin phân trang gửi về Frontend
   - **`'current_page'`**: Trang hiện tại (mặc định = 1)
   - **`'last_page'`**: Trang cuối cùng (tổng 63 sản phẩm, 8/trang → 8 trang)
   - **`'total'`**: Tổng số sản phẩm trong DB (63 sản phẩm)
   - Frontend dùng để:
     - Tính nút "Trang tiếp theo" có disabled không
     - Hiển thị "Trang 1 / 8"

7. **`'is_ai' => false,`** - Dòng 20
   - Không dùng AI (vì không có query cụ thể)
   - Frontend sẽ không hiển thị AI explanation

8. **`'explanation' => null`** - Dòng 21
   - Không có giải thích từ AI (null)

#### 🔹 Khác 8: Khởi tạo biến - Chuẩn bị xử lý AI

```php
// Dòng 26-31
$parsed = null;
$isAi = false;
$aiExplanation = null;

$searchService = new ProductSearchService();
$aiResult = $searchService->analyze($query);
```

**Giải thích chi tiết từng dòng:**

1. **`$parsed = null; $isAi = false; $aiExplanation = null;`** - Dòng 26-28
   - Khởi tạo các biến sẽ được dùng sau này
   - **`$parsed`**: Sẽ lưu kết quả phân tích từ AI (category, price, keywords, ...)
   - **`$isAi`**: Flag thành công (true = dùng AI thành công, false = không)
   - **`$aiExplanation`**: Giải thích từ AI gửi về Frontend
   - **Lý do khởi tạo:** Để code rõ ràng (không lỗi biến undefined)

2. **`$searchService = new ProductSearchService();`** - Dòng 30
   - Tạo instance của class `ProductSearchService`
   - **`new`**: Keyword để khởi tạo object mới
   - **`ProductSearchService`**: Class nằm ở `backend/app/Services/ProductSearchService.php`
   - **Kết quả:** `$searchService` là object có các method như `analyze()`
   - **Dependency Injection pattern**: Dễ test, dễ thay đổi logic

3. **`$aiResult = $searchService->analyze($query);`** - Dòn 31
   - Gọi method `analyze()` của service, truyền `$query` làm tham số
   - **`$query`**: Từ khóa người dùng (ví dụ: "áo khoác xanh")
   - **Cái gì xảy ra bên trong `analyze()`:**
     1. Lấy API key Groq từ `.env`
     2. Xây dựng prompt (hướng dẫn AI)
     3. Gửi HTTP request tới Groq API
     4. Nhận phản hồi JSON từ AI
     5. Parse JSON → array PHP
     6. Trả về kết quả
   - **Kết quả `$aiResult`:** Array bao gồm:
     ```php
     [
         'is_ai' => true,
         'parsed' => [
             'category' => 'Coat',
             'min_price' => null,
             'max_price' => 500000,
             'keywords' => ['áo', 'khoác'],
             'sort_by' => null
         ],
         'explanation' => 'Tôi tìm thấy các áo khoác dưới 500k cho bạn'
     ]
     ```
   - **Nếu có lỗi (quên bật API key, network error):** Trả về `['is_ai' => false, 'parsed' => null, ...]`

---

## 📍 BƯỚC 5: GỌI GROQ API - PHÂN TÍCH AI

### 📁 File: `backend/app/Services/ProductSearchService.php`

#### 🔹 Khác 9: Hàm analyze - Gọi Groq API

```php
// Dòng 5-13
public function analyze(string $query): array
{
    $apiKey = env('GROQ_API_KEY');  // Lấy API key từ file .env
    $parsed = null;
    $isAi = false;
    $explanation = null;

    if (empty($apiKey)) {
        return ['is_ai' => false, 'parsed' => null, 'explanation' => null];
    }
```

**Giải thích chi tiết từng dòng:**

1. **`public function analyze(string $query): array`** - Dòng 5
   - Định nghĩa hàm public của class `ProductSearchService`
   - **`(string $query)`**: Tham số là string (từ khóa từ user)
   - **`: array`**: Type hint - hàm sẽ trả về array PHP
   - **Gọi từ Controller:** `$aiResult = $searchService->analyze("áo khoác");`

2. **`$apiKey = env('GROQ_API_KEY');`** - Dòng 7
   - Lấy API key từ biến môi trường
   - **`env()`**: Laravel helper function để đọc file `.env`
   - **File `backend/.env` chứa:**
     ```
     GROQ_API_KEY=gsk_XXXXXXXXXXXXXXXXXXXXX
     ```
   - **Kết quả:** `$apiKey = "gsk_XXXXXXXXXXXXXXXXXXXXX"`
   - **Tại sao không hard-code:** 
     - Bảo mật (không lộ API key trên GitHub)
     - Dễ thay đổi giữa dev/production
     - Biến môi trường dùng chung trong project

3. **`$parsed = null; $isAi = false; $explanation = null;`** - Dòng 8-10
   - Khởi tạo các biến output mặc định
   - **`$parsed`**: Sẽ chứa array tiêu chí lọc từ AI
   - **`$isAi`**: Flag thành công (mặc định false)
   - **`$explanation`**: Giải thích từ AI (mặc định null)

4. **`if (empty($apiKey)) { return [...]; }`** - Dòng 12-14
   - Kiểm tra nếu API key không tồn tại
   - **`empty($apiKey)`**: True nếu:
     - Biến không có trong `.env`
     - Giá trị là null hoặc string rỗng
   - **Khi xảy ra:** 
     - App quên setup Groq API key
     - File `.env` không có dòng `GROQ_API_KEY=...`
   - **Trả về:**
     ```php
     ['is_ai' => false, 'parsed' => null, 'explanation' => null]
     ```
   - **Kết quả:** Controller sẽ thấy `is_ai = false` → fallback tìm kiếm từ khóa thơ
   - **Lợi ích:** App vẫn chạy được mà không cần API key (nhưng không có AI)

#### 🔹 Khác 10: Xây dựng prompt - Hướng dẫn AI phân tích

```php
// Dòng 16-17
$prompt = $this->buildPrompt($query);
$model = 'llama-3.3-70b-versatile';
```

**Giải thích chi tiết từng dòng:**

1. **`$prompt = $this->buildPrompt($query);`** - Dòng 16
   - Gọi method private `buildPrompt()` của class này
   - **`$this`**: Đối tượng hiện tại (ProductSearchService)
   - **`->buildPrompt()`**: Gọi method `buildPrompt()`
   - **Tham số:** `$query` từ khóa người dùng (ví dụ: "áo khoác xanh")
   - **Kết quả `$prompt`:** Một string chứa hướng dẫn cho AI
   - **Ví dụ:**
     ```
     Bạn là AI hỗ trợ tìm kiếm cho cửa hàng thời trang.
     Nhiệm vụ: Phân tích câu truy vấn của người dùng và trả về JSON.
     ...
     Câu hỏi của người dùng: "áo khoác xanh"
     ...
     ```

2. **`$model = 'llama-3.3-70b-versatile';`** - Dòng 17
   - Chọn mô hình AI của Groq
   - **`llama-3.3-70b-versatile`**: Tên mô hình LLaMA 3.3 70 tỷ parameters
   - **Tại sao chọn mô hình này:**
     - LLaMA 3.3 là mô hình mạnh, cân bằng giữa tốc độ và chất lượng
     - 70 tỷ parameters = rất thông minh
     - Groq optimize mô hình này để chạy cực nhanh (inference time < 1 giây)
   - **Mục đích:** Gửi `model` này tới Groq API để chỉ định mô hình dùng

#### 🔹 Khác 11: Xây dựng Prompt - Hướng dẫn AI

```php
// Dòng 116-140 (hàm buildPrompt)
private function buildPrompt($query)
{
    return <<<PROMPT
Bạn là AI hỗ trợ tìm kiếm cho cửa hàng thời trang. 
Nhiệm vụ: Phân tích câu truy vấn của người dùng và trả về JSON.

DANH MỤC: "Coat", "Shirt", "Jeans", "Dress", "Shoes", "Bag", "Hat", "Towel", "Accessories".
GIÁ: Trả về số nguyên VND (ví dụ: 300k = 300000).

Câu hỏi của người dùng: "{$query}"

Yêu cầu output duy nhất 1 đối tượng JSON, không giải thích gì thêm, đúng cấu trúc sau:
{
"category": string|null,
"min_price": number|null,
"max_price": number|null,
"keywords": string[],
"sort_by": "price_asc"|"price_desc"|null,
"explanation": "Câu giải thích thân thiện bằng tiếng Việt"
}
PROMPT;
}
```

**Giải thích chi tiết từng dòng:**

1. **`private function buildPrompt($query)`** - Dòng 116
   - Method private (chỉ dùng bên trong class, không gọi từ ngoài)
   - **`$query`**: Tham số từ khóa (ví dụ: "áo khoác xanh")

2. **`<<<PROMPT ... PROMPT;`** - Dòng 117-139
   - **Heredoc syntax** để định nghĩa multi-line string
   - **Cách hoạt động:**
     ```
     <<<PROMPT
     Nội dung...
     PROMPT;
     ```
   - **Lợi ích:** Dễ viết long text mà không cần escape quote

3. **`Bạn là AI hỗ trợ tìm kiếm cho cửa hàng thời trang.`**
   - Dòng đầu tiên của prompt
   - Báo cho AI vai trò của nó

4. **`Nhiệm vụ: Phân tích câu truy vấn của người dùng và trả về JSON.`**
   - Chỉ rõ AI phải làm gì
   - Output bắt buộc phải là JSON

5. **`DANH MỤC: "Coat", "Shirt", "Jeans", "Dress", "Shoes", "Bag", "Hat", "Towel", "Accessories".`**
   - Liệt kê tất cả danh mục cửa hàng
   - AI sử dụng thông tin này để phân loại
   - **Ví dụ:** User nhập "áo khoác" → AI chọn category = "Coat"

6. **`GIÁ: Trả về số nguyên VND (ví dụ: 300k = 300000).`**
   - Hướng dẫn định dạng giá
   - AI hiểu: 300k = 300000 VND (convert từ "k" sang số)

7. **`Câu hỏi của người dùng: "{$query}"`** - Dòng 125
   - **`{$query}`**: PHP variable interpolation
   - Ví dụ: Nếu `$query = "áo khoác xanh"` → Prompt sẽ có:
     ```
     Câu hỏi của người dùng: "áo khoác xanh"
     ```

8. **`Yêu cầu output ... JSON:`** - Dòng 127-138
   - Định nghĩa exact structure của JSON output
   - **`category: string|null`**: Danh mục (string) hoặc null nếu không xác định
   - **`min_price, max_price: number|null`**: Giá min/max hoặc null
   - **`keywords: string[]`**: Mảng từ khóa (array of strings)
   - **`sort_by`**: Sắp xếp "price_asc", "price_desc", hoặc null
   - **`explanation`**: Câu giải thích bằng tiếng Việt
   - **Ví dụ kết quả:**
     ```json
     {
         "category": "Coat",
         "min_price": null,
         "max_price": 500000,
         "keywords": ["xanh"],
         "sort_by": null,
         "explanation": "Tôi tìm thấy các áo khoác xanh dưới 500 nghìn VND cho bạn"
     }
     ```

#### 🔹 Khác 12: Gửi request tới Groq API

```php
// Dòng 19-34
$response = Http::withHeaders([
    'Content-Type' => 'application/json',
    'Authorization' => "Bearer {$apiKey}",
])->post("https://api.groq.com/openai/v1/chat/completions", [
    'model' => $model,
    'messages' => [
        ['role' => 'user', 'content' => $prompt]
    ],
    'response_format' => ['type' => 'json_object'],
    'temperature' => 0.1,
]);
```

**Giải thích chi tiết từng dòng:**

1. **`Http::withHeaders([...])->post(...)`** - Dòng 19-30
   - Sử dụng Laravel Http Facade để gửi HTTP request
   - **`Http::`**: Static helper của Laravel
   - **`withHeaders([...])`**: Thêm custom headers vào request
   - **`.post(...)`**: Gửi HTTP POST request

2. **`'Content-Type' => 'application/json'`** - Dòng 20-21
   - Header báo cho Groq API: "Dữ liệu tôi gửi là JSON"
   - Groq API biết cách parse request body

3. **`'Authorization' => "Bearer {$apiKey}"`** - Dòng 22
   - Header xác thực (authentication)
   - **`"Bearer {$apiKey}"`**: Standard OAuth format
   - **Ví dụ:** `"Bearer gsk_XXXXXXXXXXXXX"`
   - **Mục đích:** Groq API biết request này từ tài khoản nào, kiểm tra quota

4. **`->post("https://api.groq.com/openai/v1/chat/completions", [...])`** - Dòng 23-30
   - URL của Groq API endpoint
   - **`https://api.groq.com/openai/v1/chat/completions`**: Endpoint tiêu chuẩn OpenAI
   - **Groq support OpenAI format:** Để dễ migrate từ OpenAI sang Groq
   - Body request là array PHP:
     ```php
     [
         'model' => 'llama-3.3-70b-versatile',
         'messages' => [...],
         'response_format' => [...],
         'temperature' => 0.1
     ]
     ```

5. **`'model' => $model`** - Dòng 24
   - Gửi `model = 'llama-3.3-70b-versatile'`
   - Groq API sẽ dùng mô hình này để trả lời

6. **`'messages' => [['role' => 'user', 'content' => $prompt]]`** - Dòng 25-27
   - Định dạng hội thoại theo OpenAI Chat API
   - **`[...]`**: Array với 1 message
   - **`'role' => 'user'`**: Message từ người dùng (không phải AI)
   - **`'content' => $prompt`**: Nội dung message là prompt (hướng dẫn AI)
   - **Ví dụ:**
     ```php
     [
         ['role' => 'user', 'content' => "Bạn là AI hỗ trợ tìm kiếm..."]
     ]
     ```
   - **Lưu ý:** Nếu muốn conversation nhiều turn, thêm nhiều messages:
     ```php
     [
         ['role' => 'user', 'content' => '...'],
         ['role' => 'assistant', 'content' => '...'],
         ['role' => 'user', 'content' => '...']
     ]
     ```

7. **`'response_format' => ['type' => 'json_object']`** - Dòng 28
   - Yêu cầu API trả về JSON object (không phải text tự do)
   - **`json_object`**: Groq sẽ bắt buộc AI tuân theo JSON schema
   - **Lợi ích:** Response luôn là valid JSON, dễ parse

8. **`'temperature' => 0.1`** - Dòng 29
   - Điều chỉnh độ sáng tạo của AI
   - **Khoảng:** 0 (cực chính xác, ít sáng tạo) đến 2 (rất sáng tạo)
   - **`0.1`**: Rất chính xác, AI chỉ trả lời đúng nhất
   - **Tại sao 0.1:**
     - Tìm kiếm cần chính xác (không muốn AI "hallucinate")
     - Nếu user hỏi "giá dưới 500k" → AI phải hiểu chính xác, không guess

9. **`$response = Http::...`** - Dòng 19
   - Kết quả response object từ Groq API
   - **`$response` chứa:**
     ```php
     $response->status()  // 200 (success)
     $response->body()    // Raw JSON response
     $response->json()    // Parse thành array
     $response->successful()  // true nếu status 200-299
     ```

#### 🔹 Khác 13: Xử lý phản hồi - Phân tích JSON từ AI

```php
// Dòng 54-99
if ($response->successful()) {
    $result = $response->json();
    
    $textResponse = null;
    if (isset($result['choices'][0]['message']['content'])) {
        $textResponse = $result['choices'][0]['message']['content'];
    }
    
    if ($textResponse) {
        $cleanJson = preg_replace('/(^```(?:json)?\s*|\s*```$)/i', '', trim($textResponse));
        $parsed = json_decode($cleanJson, true);
        
        if ((json_last_error() !== JSON_ERROR_NONE) || !is_array($parsed)) {
            // Thử lấy JSON trong chuỗi nếu decode thất bại
            $start = strpos($cleanJson, '{');
            $end = strrpos($cleanJson, '}');
            if ($start !== false && $end !== false && $end > $start) {
                $maybe = substr($cleanJson, $start, $end - $start + 1);
                $maybeParsed = json_decode($maybe, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($maybeParsed)) {
                    $parsed = $maybeParsed;
                }
            }
        }
        
        if (is_array($parsed) && json_last_error() === JSON_ERROR_NONE) {
            $isAi = true;
            $explanation = $parsed['explanation'] ?? null;
        }
    }
}
```

**Giải thích chi tiết từng dòng:**

1. **`if ($response->successful()) {`** - Dòng 54
   - Kiểm tra Groq API trả về HTTP 200-299 (thành công)
   - **`$response->successful()`**: True nếu status code 200-299
   - **False nếu:**
     - API key sai (401)
     - API key hết quota (429)
     - Lỗi server (500)
   - **Nếu False:** Code bên trong không chạy

2. **`$result = $response->json();`** - Dòng 55
   - Parse response body (JSON string) thành PHP array
   - **Ví dụ response từ Groq:**
     ```json
     {
         "id": "chatcmpl-...",
         "choices": [
             {
                 "message": {
                     "role": "assistant",
                     "content": "{\"category\": \"Coat\", ...}"
                 }
             }
         ]
     }
     ```
   - **Kết quả `$result`:** Array PHP:
     ```php
     [
         'id' => 'chatcmpl-...',
         'choices' => [
             [
                 'message' => [
                     'role' => 'assistant',
                     'content' => '{"category": "Coat", ...}'
                 ]
             ]
         ]
     ]
     ```

3. **`$textResponse = null;`** - Dòng 58
   - Khởi tạo biến, mặc định null

4. **`if (isset($result['choices'][0]['message']['content'])) {`** - Dòng 59-60
   - Kiểm tra con đường `result['choices'][0]['message']['content']` có tồn tại không
   - **Lý do:** Response có thể khác format hoặc có lỗi
   - **`isset()`**: True nếu key tồn tại và khác null

5. **`$textResponse = $result['choices'][0]['message']['content'];`** - Dòng 60
   - Lấy content (JSON string từ AI)
   - **Ví dụ:**
     ```
     $textResponse = "{\"category\": \"Coat\", \"keywords\": [\"áo\"], ...}"
     ```

6. **`$cleanJson = preg_replace('/(^```(?:json)?\s*|\s*```$)/i', '', trim($textResponse));`** - Dòng 62
   - Xóa markdown formatting nếu có
   - **`trim()`**: Xóa whitespace đầu/cuối
   - **`preg_replace()`**: Regex replace
   - **Pattern:** `(^```(?:json)?\s*|\s*```$)`
     - Xóa \`\`\` ở đầu (có thể có `json` sau)
     - Xóa \`\`\` ở cuối
   - **Ví dụ:**
     - Input: `\`\`\`json\n{...}\n\`\`\``
     - Output: `{...}`

7. **`$parsed = json_decode($cleanJson, true);`** - Dòng 63
   - Parse JSON string thành PHP array
   - **`true`**: Trả về array (không phải object)
   - **Ví dụ:**
     - Input: `"{\"category\": \"Coat\", \"keywords\": [\"áo\"]}"`
     - Output: `['category' => 'Coat', 'keywords' => ['áo']]`

8. **`if ((json_last_error() !== JSON_ERROR_NONE) || !is_array($parsed)) {`** - Dòng 65-66
   - Kiểm tra parse thất bại
   - **`json_last_error() !== JSON_ERROR_NONE`**: Có lỗi JSON
   - **`!is_array($parsed)`**: Không phải array (null)
   - **Tại sao cần:** AI có thể trả về JSON format lạ, cần retry

9. **Thử lấy JSON trong chuỗi (Dòng 67-73)**
   - Fallback: Tìm JSON bằng cách tìm `{` đầu tiên và `}` cuối cùng
   - Lấy substring giữa 2 ký tự
   - Parse lại
   - Nếu thành công → dùng kết quả này

10. **`if (is_array($parsed) && json_last_error() === JSON_ERROR_NONE) {`** - Dòng 75-76
    - Kiểm tra parse thành công
    - Nếu đúng:
      - `$isAi = true` (đánh dấu dùng AI thành công)
      - `$explanation = $parsed['explanation'] ?? null` (lấy giải thích)

#### 🔹 Khác 14: Trả về kết quả

```php
// Dòng 107-109
return ['is_ai' => $isAi, 'parsed' => $parsed, 'explanation' => $explanation];
```

**Giải thích chi tiết từng dòng:**

1. **`return [...]`** - Dòng 107
   - Kết thúc hàm `analyze()`, trả về kết quả
   - **Kết quả là array:**
     ```php
     [
         'is_ai' => bool,
         'parsed' => array|null,
         'explanation' => string|null
     ]
     ```

2. **`'is_ai' => $isAi`**
   - Flag đánh dấu thành công
   - **Giá trị có thể:**
     - `true`: Parse JSON thành công, dùng được AI
     - `false`: Có lỗi (API key, network, AI response invalid)
   - **Dùng ở:** Controller kiểm tra `if ($aiResult['is_ai'])` để quyết định dùng AI hay fallback

3. **`'parsed' => $parsed`**
   - Array tiêu chí lọc từ AI
   - **Giá trị có thể:**
     ```php
     [
         'category' => 'Coat',
         'min_price' => null,
         'max_price' => 500000,
         'keywords' => ['áo', 'khoác'],
         'sort_by' => 'price_asc',
         'explanation' => '...'
     ]
     ```
   - **Hoặc `null`** nếu parse thất bại
   - **Dùng ở:** Controller sử dụng `$parsed['category']`, `$parsed['keywords']` để filter DB

4. **`'explanation' => $explanation`**
   - Giải thích từ AI về kết quả tìm kiếm
   - **Ví dụ:** `"Tôi tìm thấy các áo khoác xanh dưới 500 nghìn VND cho bạn"`
   - **Dùng ở:** Frontend hiển thị giải thích cho user
   - **Giá trị có thể:** string hoặc null

**Tóm tắt luồng Khác 9-14:**
```
1. Khác 9: Nhận query, lấy API key từ .env
2. Khác 10: Xây dựng prompt hướng dẫn AI
3. Khác 11: Prompt chi tiết (danh mục, giá, từ khóa, sắp xếp)
4. Khác 12: Gửi HTTP POST tới Groq API
5. Khác 13: Nhận response, parse JSON
6. Khác 14: Trả về kết quả (is_ai, parsed, explanation)
```

---

## BƯỚC 6: BACKEND QUAY LẠI - ÁP DỤNG TIÊU CHÍ LỌC

### File: `backend/app/Http/Controllers/ProductController.php`

#### 🔹 Khác 15: Kiểm tra kết quả AI - Có dùng AI không?

```php
// Dòng 33-75
if ($isAi && $parsed) {
    $dbQuery = Product::query();

    // Lọc theo danh mục
    if (!empty($parsed['category'])) {
        $dbQuery->where('category', $parsed['category']);
    }

    // Lọc theo giá min
    if (isset($parsed['min_price']) && is_numeric($parsed['min_price'])) {
        $dbQuery->where('price', '>=', $parsed['min_price']);
    }
    
    // Lọc theo giá max
    if (isset($parsed['max_price']) && is_numeric($parsed['max_price'])) {
        $dbQuery->where('price', '<=', $parsed['max_price']);
    }

    // Lọc theo từ khóa
    if (!empty($parsed['keywords']) && is_array($parsed['keywords'])) {
        $dbQuery->where(function ($q) use ($parsed) {
            foreach ($parsed['keywords'] as $keyword) {
                $q->orWhere('name', 'like', '%' . $keyword . '%')
                  ->orWhere('description', 'like', '%' . $keyword . '%');
            }
        });
    }

    // Sắp xếp
    if (!empty($parsed['sort_by'])) {
        if ($parsed['sort_by'] === 'price_asc') {
            $dbQuery->orderBy('price', 'asc');
        } elseif ($parsed['sort_by'] === 'price_desc') {
            $dbQuery->orderBy('price', 'desc');
        }
    }

    $products = $dbQuery->paginate(8);
```

**Giải thích chi tiết từng dòng:**

1. **`if ($isAi && $parsed) {`** - Dòng 33
   - **`$isAi && $parsed`**: Kiểm tra 2 điều kiện
     - `$isAi = true`: AI phân tích thành công
     - `$parsed` khác null: Có tiêu chí lọc
   - **Nếu true:** Dùng kết quả AI để filter sản phẩm
   - **Nếu false:** Skip block này → dùng fallback (keyword search bình thường)

2. **`$dbQuery = Product::query();`** - Dòng 34
   - Khởi tạo query builder cho model Product
   - **`Product::query()`**: Tạo một query object chưa chạy
   - **Không lấy dữ liệu ngay**, chỉ chuẩn bị query
   - **Ví dụ:** `$dbQuery` chưa là kết quả, chỉ là builder
   - **Sau đó sẽ thêm `where`, `orderBy`, `paginate` vào** trước khi execute

3. **Lọc theo danh mục - Dòng 37-39**
   ```php
   if (!empty($parsed['category'])) {
       $dbQuery->where('category', $parsed['category']);
   }
   ```
   - **`$parsed['category']`**: Danh mục từ AI (ví dụ: "Coat")
   - **`!empty()`**: Kiểm tra danh mục có tồn tại không
   - **`.where('category', 'Coat')`**: Thêm điều kiện WHERE vào query
   - **SQL tương đương:**
     ```sql
     SELECT * FROM products WHERE category = 'Coat'
     ```
   - **Ví dụ:** User hỏi "áo khoác" → AI trả `category = "Coat"` → Query lọc chỉ Coat

4. **Lọc theo giá tối thiểu - Dòng 42-44**
   ```php
   if (isset($parsed['min_price']) && is_numeric($parsed['min_price'])) {
       $dbQuery->where('price', '>=', $parsed['min_price']);
   }
   ```
   - **`isset($parsed['min_price'])`**: Kiểm tra key tồn tại
   - **`is_numeric($parsed['min_price'])`**: Kiểm tra là số (không phải string)
   - **`.where('price', '>=', 300000)`**: Thêm điều kiện WHERE price >= 300000
   - **SQL tương đương:**
     ```sql
     WHERE price >= 300000
     ```
   - **Lý do check `is_numeric`:** Bảo vệ khỏi lỗi nếu AI trả non-numeric value

5. **Lọc theo giá tối đa - Dòng 47-49**
   ```php
   if (isset($parsed['max_price']) && is_numeric($parsed['max_price'])) {
       $dbQuery->where('price', '<=', $parsed['max_price']);
   }
   ```
   - Tương tự như min_price, nhưng kiểm tra `<=` (nhỏ hơn hoặc bằng)
   - **SQL tương đương:**
     ```sql
     WHERE price <= 500000
     ```

6. **Lọc theo từ khóa - Dòng 52-57**
   ```php
   if (!empty($parsed['keywords']) && is_array($parsed['keywords'])) {
       $dbQuery->where(function ($q) use ($parsed) {
           foreach ($parsed['keywords'] as $keyword) {
               $q->orWhere('name', 'like', '%' . $keyword . '%')
                 ->orWhere('description', 'like', '%' . $keyword . '%');
           }
       });
   }
   ```
   - **`$parsed['keywords']`**: Mảng từ khóa từ AI (ví dụ: `['xanh', 'cotton']`)
   - **`!empty() && is_array()`**: Kiểm tra là mảng có phần tử
   - **`.where(function ($q) use ($parsed) {...})`**: Tạo group điều kiện (WHERE ... OR ... OR ...)
   - **`use ($parsed)`**: Truyền biến `$parsed` vào closure
   - **`foreach ($parsed['keywords']`**: Lặp mỗi từ khóa
   - **`.orWhere('name', 'like', '%xanh%')`**: Hoặc sản phẩm có tên chứa "xanh"
   - **`.orWhere('description', 'like', '%xanh%')`**: Hoặc sản phẩm có mô tả chứa "xanh"
   - **SQL tương đương:**
     ```sql
     WHERE (
         name LIKE '%xanh%' OR description LIKE '%xanh%'
         OR name LIKE '%cotton%' OR description LIKE '%cotton%'
     )
     ```
   - **Ví dụ:** AI trả `keywords = ['xanh', 'cotton']` → Tìm sản phẩm có tên hoặc mô tả chứa từ này

7. **Sắp xếp kết quả - Dòng 60-67**
   ```php
   if (!empty($parsed['sort_by'])) {
       if ($parsed['sort_by'] === 'price_asc') {
           $dbQuery->orderBy('price', 'asc');
       } elseif ($parsed['sort_by'] === 'price_desc') {
           $dbQuery->orderBy('price', 'desc');
       }
   }
   ```
   - **`$parsed['sort_by']`**: Cách sắp xếp từ AI (ví dụ: "price_asc")
   - **`'price_asc'`**: Sắp xếp từ rẻ đến đắt
   - **`.orderBy('price', 'asc')`**: SQL ORDER BY price ASC
   - **`'price_desc'`**: Sắp xếp từ đắt đến rẻ
   - **SQL tương đương:**
     ```sql
     ORDER BY price ASC  -- hoặc DESC
     ```

8. **`$products = $dbQuery->paginate(8);`** - Dòng 70
   - **Execute query** với tất cả điều kiện đã thêm
   - **`.paginate(8)`**: Lấy 8 sản phẩm/trang, page 1
   - **Kết quả `$products`:** LengthAwarePaginator object chứa:
     ```php
     items: [8 products matching all filters],
     current_page: 1,
     total: (số sản phẩm match filters),
     last_page: (ceil(total / 8)),
     per_page: 8
     ```
   }
   ```
   - Lấy sản phẩm có giá >= min_price
   - `where('price', '>=', 300000)` → Chỉ lấy sản phẩm có giá từ 300k trở lên

3. **Lọc giá tối đa:**
   ```php
   if (isset($parsed['max_price']) && is_numeric($parsed['max_price'])) {
       $dbQuery->where('price', '<=', $parsed['max_price']);
   }
   ```
   - Lấy sản phẩm có giá <= max_price
   - `where('price', '<=', 500000)` → Chỉ lấy sản phẩm có giá dưới 500k

4. **Lọc theo từ khóa:**
   ```php
   if (!empty($parsed['keywords']) && is_array($parsed['keywords'])) {
       $dbQuery->where(function ($q) use ($parsed) {
           foreach ($parsed['keywords'] as $keyword) {
               $q->orWhere('name', 'like', '%' . $keyword . '%')
                 ->orWhere('description', 'like', '%' . $keyword . '%');
           }
       });
   }
   ```
   - Lấy sản phẩm có tên HOẶC mô tả chứa từ khóa
   - Ví dụ: `keywords = ['xanh', 'cotton']` → Lấy sản phẩm nào có tên/mô tả chứa "xanh" HOẶC "cotton"
   - `like '%xanh%'` → Tìm kiếm không phân biệt vị trí từ

5. **Sắp xếp:**
   ```php
   if (!empty($parsed['sort_by'])) {
       if ($parsed['sort_by'] === 'price_asc') {
           $dbQuery->orderBy('price', 'asc');
       }
   }
   ```
   - Nếu `sort_by = 'price_asc'` → Sắp xếp tăng dần theo giá
   - Nếu `sort_by = 'price_desc'` → Sắp xếp giảm dần theo giá

6. **Lấy kết quả:**
   ```php
   $products = $dbQuery->paginate(8);
   ```
   - Thực thi tất cả filter trên → Lấy 8 sản phẩm đầu tiên
   - Lưu ý: Laravel tự động thêm `LIMIT 8 OFFSET (page-1)*8` dựa vào trang hiện tại

#### 🔹 Khác 16: Trả về kết quả tìm kiếm AI

```php
// Dòng 73-84
return response()->json([
    'success' => true,
    'products' => $products->items(),
    'pagination' => [
        'current_page' => $products->currentPage(),
        'last_page' => $products->lastPage(),
        'total' => $products->total(),
    ],
    'is_ai' => true,
    'explanation' => $explanation,
    'parsed_criteria' => $parsed
]);
```

**Giải thích chi tiết từng dòng:**

1. **`return response()->json([...])`** - Dòng 73
   - Trả về HTTP response với JSON body
   - **`response()`**: Laravel helper tạo response object
   - **`.json([...])`**: Chuyển array PHP thành JSON string, tự động set header `Content-Type: application/json`

2. **`'success' => true`** - Dòng 74
   - Flag báo tìm kiếm thành công
   - Frontend kiểm tra: nếu `success === true` → hiển thị kết quả

3. **`'products' => $products->items()`** - Dòng 75
   - **`$products->items()`**: Lấy mảng các sản phẩm của trang hiện tại
   - **Ví dụ:** 8 sản phẩm áo khoác phù hợp
   - Mỗi item là Eloquent model Product với đầy đủ thông tin (id, name, price, category, ...)

4. **`'pagination' => [...'current_page', 'last_page', 'total']`** - Dòng 76-79
   - **`'current_page'`**: Trang hiện tại (ví dụ: 1)
   - **`'last_page'`**: Trang cuối cùng (ví dụ: 5)
   - **`'total'`**: Tổng số sản phẩm match điều kiện (ví dụ: 35)
   - Frontend dùng để:
     - Tính nút "Next" có được click không (last_page > current_page)
     - Hiển thị "Trang 1 / 5" hoặc "35 kết quả"

5. **`'is_ai' => true`** - Dòng 80
   - Báo Frontend: Kết quả này tìm kiếm dùng AI
   - Frontend sẽ hiển thị badge "🤖 Tìm kiếm bằng AI" hoặc icon

6. **`'explanation' => $explanation`** - Dòng 81
   - Giải thích từ AI về kết quả tìm kiếm
   - **Ví dụ:** `"Tôi tìm thấy 35 áo khoác xanh dưới 500 nghìn VND"`
   - Frontend hiển thị trong section AI Explanation

7. **`'parsed_criteria' => $parsed`** - Dòng 82
   - Gửi về tiêu chí lọc mà AI xác định
   - **Mục đích:** Debug, hoặc hiển thị "Tìm kiếm theo: Coat, 300k-500k, từ khóa: xanh"
   - **Tùy chọn:** Có thể omit nếu không cần debug

#### 🔹 Khác 17: Fallback - Nếu AI không hoạt động

```php
// Dòng 87-110
$words = array_filter(explode(' ', trim($query)));
$dbQuery = Product::query();

if (!empty($words)) {
    $dbQuery->where(function ($q) use ($words) {
        foreach ($words as $word) {
            $q->orWhere('name', 'like', '%' . $word . '%')
              ->orWhere('description', 'like', '%' . $word . '%')
              ->orWhere('category', 'like', '%' . $word . '%');
        }
    });
}

$products = $dbQuery->paginate(8);

return response()->json([
    'success' => true,
    'products' => $products->items(),
    'pagination' => [
        'current_page' => $products->currentPage(),
        'last_page' => $products->lastPage(),
        'total' => $products->total(),
    ],
    'is_ai' => false,
    'explanation' => null
]);
```

**Giải thích chi tiết từng dòng:**

1. **`$words = array_filter(explode(' ', trim($query)));`** - Dòng 87
   - **`trim($query)`**: Xóa whitespace đầu/cuối
   - **`explode(' ', ...)`**: Tách string theo space thành mảng
   - **`array_filter(...)`**: Xóa các phần tử rỗng
   - **Ví dụ:**
     - Input: `$query = "  áo   khoác  xanh  "`
     - Sau `trim()`: `"áo   khoác  xanh"`
     - Sau `explode()`: `["áo", "", "", "khoác", "", "xanh", ""]`
     - Sau `array_filter()`: `["áo", "khoác", "xanh"]` (reindex lại index)
   - **Kết quả `$words`**: Mảng từ khóa đã tách

2. **`$dbQuery = Product::query();`** - Dòng 88
   - Khởi tạo query builder trắng (chưa có WHERE)

3. **`if (!empty($words)) {`** - Dòng 90
   - Nếu có từ khóa (không rỗng)
   - Nếu `$words` rỗng → skip block này → trả về TẤT CẢ sản phẩm

4. **`$dbQuery->where(function ($q) use ($words) {...})`** - Dòng 91-96
   - Tạo group OR điều kiện
   - **`use ($words)`**: Truyền `$words` vào closure
   - **`foreach ($words as $word)`**: Lặp mỗi từ
   - **Khác với Khác 15:** Không chỉ tìm name + description, mà còn cả category
   - **`.orWhere('name', 'like', '%áo%')`**: Hoặc tên chứa "áo"
   - **`.orWhere('description', 'like', '%áo%')`**: Hoặc mô tả chứa "áo"
   - **`.orWhere('category', 'like', '%áo%')`**: Hoặc danh mục chứa "áo" (fallback, ít khi match)
   - **SQL tương đương:**
     ```sql
     WHERE (
         name LIKE '%áo%' OR description LIKE '%áo%' OR category LIKE '%áo%'
         OR name LIKE '%khoác%' OR description LIKE '%khoác%' OR category LIKE '%khoác%'
         OR name LIKE '%xanh%' OR description LIKE '%xanh%' OR category LIKE '%xanh%'
     )
     ```

5. **`$products = $dbQuery->paginate(8);`** - Dòng 100
   - Execute query fallback (simple keyword search)
   - Lấy 8 sản phẩm/trang match bất kỳ từ khóa nào

6. **`return response()->json([...])`** - Dòng 102-110
   - Trả về kết quả fallback
   - **`'is_ai' => false`**: Báo Frontend không dùng AI
   - **`'explanation' => null`**: Không có giải thích AI
   - Các field khác giống Khác 16 (success, products, pagination)

**Tóm tắt Khác 15-17:**
```
if ($isAi && $parsed) {
    ← Khác 15: Filter thông minh bằng AI criteria
    ← Khác 16: Trả về kết quả AI (is_ai = true)
} else {
    ← Khác 17: Fallback keyword search (is_ai = false)
}
```
    'is_ai' => false,
    'explanation' => 'Tìm kiếm cơ bản với từ khóa: "' . htmlspecialchars($query) . '" (Bật API Key ở backend để có kết quả tìm kiếm AI thông minh hơn).'
]);
```

**Giải thích:**
- Nếu AI không thành công (API key trống, Groq không hoạt động) → sử dụng tìm kiếm từ khóa cơ bản
- `explode(' ', $query)` → Tách từ khóa thành các từ riêng lẻ
- Ví dụ: "áo khoác xanh" → ["áo", "khoác", "xanh"]
- Lọc sản phẩm có chứa BẤT KỲ từ nào trong danh sách
- `is_ai = false` → Báo cho frontend biết không dùng AI

---

## 📍 BƯỚC 7: FRONTEND NHẬN KẾT QUẢ - HIỂN THỊ

### 📁 File: `my-app/src/pages/ShopPage.jsx`

#### 🔹 Khác 18: Cập nhật State - Lưu kết quả nhận được

```javascript
// Dòng 23-30 (trong then của fetch)
if (data.success) {
    setProducts(data.products);
    setPagination(data.pagination || { current_page: 1, last_page: 1, total: data.products.length });
    setAiExplanation(data.explanation);
    setIsAi(data.is_ai);
}
setLoading(false);
```

**Giải thích chi tiết từng dòng:**

1. **`if (data.success) {`** - Dòng 23
   - Kiểm tra Backend trả về `success: true` (không có lỗi)
   - **Nếu true:** Cập nhật state React
   - **Nếu false:** Skip block này (có lỗi từ Backend)

2. **`setProducts(data.products);`** - Dòng 24
   - Cập nhật state `products` với mảng 8 sản phẩm từ Backend
   - **`data.products`**: Mảng 8 items tìm được
   - **Kế quả:** Component re-render hiển thị 8 thẻ sản phẩm

3. **`setPagination(data.pagination || { current_page: 1, last_page: 1, total: data.products.length });`** - Dòng 25
   - Cập nhật state `pagination` với thông tin phân trang từ Backend
   - **`data.pagination`**: Object có `{current_page, last_page, total}`
   - **`|| {...}`**: Nếu Backend không trả pagination → dùng default
   - **Kế quả:** Frontend biết trang mấy, có bao nhiêu trang, tổng bao nhiêu sản phẩm

4. **`setAiExplanation(data.explanation);`** - Dòng 26
   - Cập nhật state `aiExplanation` với giải thích từ AI
   - **Ví dụ:** `"Tôi tìm thấy 35 áo khoác xanh dưới 500k"`
   - **Hoặc `null`** nếu không dùng AI
   - **Kế quả:** Frontend sẽ hiển thị giải thích (nếu có)

5. **`setIsAi(data.is_ai);`** - Dòng 27
   - Cập nhật state `isAi` (true/false)
   - **`data.is_ai = true`**: Tìm kiếm bằng AI
   - **`data.is_ai = false`**: Tìm kiếm fallback (keyword thơ)
   - **Kế quả:** Frontend dùng để quyết định hiển thị icon/badge "🤖 AI"

6. **`setLoading(false);`** - Dòng 29
   - Tắt trạng thái loading
   - **Kế quả:** Ẩn spinner/progress bar, hiển thị kết quả

#### 🔹 Khác 19: Hiển thị giải thích từ AI

```javascript
// Dòng 106-117
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
```

**Giải thích chi tiết từng dòng:**

1. **`{aiExplanation && (...)}`** - Dòng 106
   - **React conditional rendering**: Nếu `aiExplanation` có giá trị (truthy) → hiển thị JSX bên trong
   - **`&&`**: Logical AND operator
     - Nếu `aiExplanation = null` → Không hiển thị gì (JSX null)
     - Nếu `aiExplanation = "Tôi tìm..."` → Hiển thị component
   - **Ví dụ:**
     - User tìm kiếm → Backend trả `explanation: "Tôi tìm thấy 35 áo xanh"` → Hiển thị
     - User không tìm kiếm (query rỗng) → Backend trả `explanation: null` → Không hiển thị

2. **`<div className="alert alert-primary ..."`** - Dòng 107
   - Tạo hộp thông báo (alert box)
   - **CSS classes:**
     - `alert`: Style cơ bản alert
     - `alert-primary`: Màu xanh (primary color)
     - `d-flex`: Flexbox layout (hiển thị ngang)
     - `p-4`: Padding lớn
   - **Kế quả:** Hộp thông báo xanh, có padding, nội dung nằm ngang

3. **`<i className={isAi ? "bi bi-robot" : "bi bi-search"} />`** - Dòng 109
   - Hiển thị icon tùy vào `isAi`
   - **Nếu `isAi = true`:** Icon robot `"bi bi-robot"` (🤖)
   - **Nếu `isAi = false`:** Icon search `"bi bi-search"` (🔍)
   - **Mục đích:** Báo cho user biết dùng AI hay tìm kiếm thơ
   - **Bootstrap Icons:** Dùng thư viện `bi` (Bootstrap Icons)

4. **`{isAi ? "Trợ lý AI Tìm kiếm" : "Kết quả tìm kiếm"}`** - Dòng 113
   - Hiển thị tiêu đề tùy vào `isAi`
   - **Nếu `isAi = true`:** "Trợ lý AI Tìm kiếm"
   - **Nếu `isAi = false`:** "Kết quả tìm kiếm"
   - **Ternary operator:** `condition ? ifTrue : ifFalse`

5. **`<p className="mb-0 text-dark alert-text">{aiExplanation}</p>`** - Dòng 114
   - Hiển thị giải thích từ `aiExplanation` state
   - **Ví dụ:** "Tôi tìm thấy 35 áo khoác xanh dưới 500 nghìn VND"
   - **CSS:** `text-dark` (chữ đen), `mb-0` (no margin bottom)

#### 🔹 Khác 20: Hiển thị danh sách sản phẩm

```javascript
// Dòng 142-163
{products.map((product) => (
    <div key={product.id} className="col-lg-3 col-md-4 col-sm-6 mb-4">
        <div className="card h-100 shadow-sm border-0 product-card"
            onClick={() => navigate(`/product/${product.id}`)}
        >
            <div className="position-relative">
                <span className="badge accent-primary-bg text-white position-absolute product-badge">
                    {product.category}
                </span>
                <img src={`${import.meta.env.VITE_BASE_URL}/${...}`}
                    className="card-img-top product-image"
                    alt={product.name}
                />
            </div>
            <div className="card-body d-flex flex-column">
                <h6 className="card-title text-dark font-weight-bold mb-2">
                    {product.name}
                </h6>
                <div className="mt-auto d-flex justify-content-between align-items-center">
                    <span className="accent-primary font-weight-bold price-text">
                        {new Intl.NumberFormat('vi-VN').format(parseFloat(product.price))} VNĐ
                    </span>
                    <button className="btn btn-outline-primary btn-sm rounded-circle"
                        onClick={(e) => { e.stopPropagation(); }}
                    >
                        <i className="bi bi-cart-plus"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
))}
```

**Giải thích chi tiết từng dòng:**

1. **`{products.map((product) => (...))}` - Dòng 142
   - **`.map()`**: Array method lặp qua mỗi phần tử
   - **`(product) => (...)`**: Arrow function, mỗi lần lặp trả về JSX cho 1 sản phẩm
   - **Kế quả:** Nếu có 8 sản phẩm → Render 8 component (8 thẻ product)
   - **Ví dụ:**
     ```javascript
     products = [
       {id: 1, name: "Áo khoác", price: 350000, category: "Coat", ...},
       {id: 2, name: "Áo sơ mi", price: 150000, category: "Shirt", ...},
       ...
     ]
     // Map sẽ render JSX cho từng item
     ```

2. **`key={product.id}`** - Dòng 143
   - **React key**: Giúp React theo dõi từng phần tử trong list
   - **Mục đích:** Khi list thay đổi → React biết phần tử nào update, xóa, hoặc thêm
   - **Tại sao cần:** Nếu không có key → React có thể re-render sai, gây bug

3. **`className="col-lg-3 col-md-4 col-sm-6 mb-4"`** - Dòng 143
   - **Bootstrap responsive grid:**
     - `col-lg-3`: Desktop (large screen) → 3 sản phẩm/row (12/3=4 columns)
     - `col-md-4`: Tablet (medium screen) → 3 sản phẩm/row
     - `col-sm-6`: Mobile (small screen) → 2 sản phẩm/row
     - `mb-4`: Margin bottom (spacing giữa các row)

4. **`onClick={() => navigate(\`/product/${product.id}\`)}`** - Dòng 145
   - Khi user click sản phẩm → Chuyển tới trang chi tiết
   - **`navigate()`**: React Router function chuyển trang
   - **URL:** `/product/1`, `/product/2`, ... (dựa vào product ID)
   - **Kế quả:** User thấy chi tiết sản phẩm (mô tả full, ảnh lớn, ...)

5. **`{product.category}`** - Dòng 149
   - Hiển thị danh mục (Coat, Shirt, Jeans, ...)
   - **Vị trí:** Badge góc trên bên trái ảnh
   - **CSS:** `badge`, `accent-primary-bg` (nền xanh)

6. **`<img src={...} className="card-img-top product-image" alt={product.name}" />`** - Dòng 151-154
   - Hiển thị ảnh sản phẩm
   - **`src`:** Đường dẫn ảnh từ server
   - **`alt={product.name}`:** Văn bản thay thế nếu ảnh không load
   - **Lợi ích:** Accessibility, SEO

7. **`{product.name}`** - Dòng 157
   - Hiển thị tên sản phẩm
   - **Ví dụ:** "Áo khoác xanh denim"
   - **CSS:** `card-title` (tiêu đề card), `text-dark` (chữ đen)

8. **`{new Intl.NumberFormat('vi-VN').format(parseFloat(product.price))} VNĐ`** - Dòng 161
   - Hiển thị giá định dạng Việt Nam
   - **`new Intl.NumberFormat('vi-VN')`**: Tạo formatter cho tiêu chuẩn VN
   - **`.format(parseFloat(product.price))`**: Convert giá thành số, format theo VN
   - **Ví dụ:**
     - Input: `product.price = "350000"`
     - Output: `"350.000 VNĐ"` (không "350000 VNĐ")
     - Dễ đọc cho user VN

9. **`onClick={(e) => { e.stopPropagation(); }}`** - Dòng 164
   - Nút "Thêm vào giỏ"
   - **`e.stopPropagation()`**: Ngăn chặn click event bubbling
   - **Lý do cần:** Nếu không có → Click nút "Thêm vào giỏ" → Cũng kích hoạt card `onClick` → Chuyển trang (bug!)
   - **Kế quả:** Click nút → Chỉ thêm vào giỏ, không chuyển trang

#### 🔹 Khác 21: Phân trang - Cho phép chuyển trang

```javascript
// Dòng 168-192
{pagination.last_page > 1 && (
    <div className="col-12 d-flex justify-content-center mt-4">
        <nav aria-label="Page navigation">
            <ul className="pagination">
                <li className={`page-item ${pagination.current_page === 1 ? 'disabled' : ''}`}>
                    <button className="page-link accent-primary" 
                        onClick={() => handlePageChange(pagination.current_page - 1)}
                    >
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
                    <button className="page-link accent-primary" 
                        onClick={() => handlePageChange(pagination.current_page + 1)}
                    >
                        Sau
                    </button>
                </li>
            </ul>
        </nav>
    </div>
)}
```

**Giải thích chi tiết từng dòng:**

1. **`{pagination.last_page > 1 && (...)}`** - Dòng 168
   - Nếu có nhiều trang (last_page > 1) → Hiển thị phân trang
   - Nếu chỉ có 1 trang → Không hiển thị gì

2. **`<nav aria-label="Page navigation">`** - Dòng 170-172
   - HTML semantic tag `<nav>` cho navigation
   - **`aria-label`**: Accessibility (screen reader biết đây là page navigation)

3. **Nút "Trước" - Dòng 173-179**
   ```javascript
   <li className={`page-item ${pagination.current_page === 1 ? 'disabled' : ''}`}>
       <button onClick={() => handlePageChange(pagination.current_page - 1)}>
           Trước
       </button>
   </li>
   ```
   - Hiển thị nút "Trước" để quay về trang trước
   - **`current_page === 1 ? 'disabled'`**: Nếu ở trang 1 → Disable nút (không click được)
   - **`onClick={() => handlePageChange(pagination.current_page - 1)}`**: Click → Gọi hàm chuyển trang
   - **Ví dụ:**
     - Ở trang 3 → Click "Trước" → Chuyển trang 2
     - Ở trang 1 → Nút "Trước" disabled (vô hiệu)

4. **Tạo nút số trang - Dòng 181-190**
   ```javascript
   {[...Array(pagination.last_page)].map((_, i) => (
       <li key={i + 1} className={...}>
           <button onClick={() => handlePageChange(i + 1)}>
               {i + 1}
           </button>
       </li>
   ))}
   ```
   - **`[...Array(pagination.last_page)]`**: Tạo array với N phần tử
   - **Ví dụ:** `last_page = 5` → Array có 5 phần tử → `.map()` lặp 5 lần
   - **`(_, i) => ...`**: `_` = phần tử (không dùng), `i` = index (0, 1, 2, ...)
   - **`{i + 1}`**: Hiển thị số trang (i+1 vì i bắt đầu từ 0)
   - **`current_page === i + 1 ? 'active'`**: Nếu là trang hiện tại → CSS `active` (highlight xanh)

5. **Nút "Sau" - Dòng 192-198**
   ```javascript
   <li className={`page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}`}>
       <button onClick={() => handlePageChange(pagination.current_page + 1)}>
           Sau
       </button>
   </li>
   ```
   - Hiển thị nút "Sau" để chuyển tới trang tiếp theo
   - **`current_page === last_page ? 'disabled'`**: Nếu ở trang cuối → Disable
   - **Ví dụ:**
     - Ở trang 3/5 → Click "Sau" → Chuyển trang 4
     - Ở trang 5/5 → Nút "Sau" disabled (vô hiệu)

---

## 📍 BƯỚC 8: NGƯỜI DÙNG NHẤN PHÂN TRANG

### 📁 File: `my-app/src/pages/ShopPage.jsx`

#### 🔹 Khác 22: Hàm handlePageChange - Thay đổi trang

```javascript
// Dòng 44-48
const handlePageChange = (newPage) => {
    if (newPage >= 1 && newPage <= pagination.last_page) {
        navigate(`/shop?query=${encodeURIComponent(query)}&page=${newPage}`);
    }
};
```

**Giải thích chi tiết từng dòng:**

1. **`const handlePageChange = (newPage) => { ... };`** - Dòng 44-47
   - Định nghĩa hàm xử lý thay đổi trang
   - **`(newPage)`**: Tham số là số trang mới
   - **Arrow function:** Syntax ES6 của JavaScript

2. **`if (newPage >= 1 && newPage <= pagination.last_page) {`** - Dòng 45
   - Kiểm tra số trang hợp lệ
   - **`newPage >= 1`**: Số trang không được < 1 (trang nhỏ nhất là 1)
   - **`newPage <= pagination.last_page`**: Số trang không được vượt quá trang cuối
   - **`&&`**: Cả 2 điều kiện phải true
   - **Mục đích:** Bảo vệ khỏi click spam, prevent invalid page
   - **Ví dụ:**
     - `newPage = 0` → False (không chạy)
     - `newPage = 6` nhưng `last_page = 5` → False (không chạy)
     - `newPage = 2` → True (chạy)

3. **`navigate(\`/shop?query=${encodeURIComponent(query)}&page=${newPage}\`);`** - Dòng 46
   - Chuyển đổi URL (route)
   - **`navigate()`**: React Router function (từ `useNavigate()` hook)
   - **Template literal (backtick):** Cho phép embed biến với `${...}`
   - **`encodeURIComponent(query)`**: Mã hóa URL-safe cho từ khóa
   - **Ví dụ:**
     - `query = "áo khoác"` → `encodeURIComponent` → `"áo%20khoác"`
     - URL mới: `/shop?query=áo%20khoác&page=2`

**Luồng xảy ra khi user nhấn phân trang:**
```
1. User click nút trang 2
   ↓
2. onClick={() => handlePageChange(2)}
   ↓
3. handlePageChange(2) chạy
   ↓
4. Kiểm tra: 2 >= 1 && 2 <= 5 → True
   ↓
5. navigate('/shop?query=áo%20khoác&page=2')
   ↓
6. URL thay đổi → useEffect kích hoạt (dependency: query, page)
   ↓
7. fetch(`/products?query=áo%20khoác&page=2`)
   ↓
8. Backend trả 8 sản phẩm của trang 2
   ↓
9. Frontend cập nhật state: setProducts(...), setPagination(...)
   ↓
10. Component re-render → Hiển thị 8 sản phẩm mới (trang 2)
```

---

## 📊 SƠ ĐỒ LUỒNG ĐẦY ĐỦ

```
┌─────────────────────────────────────────────────────────────┐
│ FRONTEND (React)                                            │
├─────────────────────────────────────────────────────────────┤
│ 1. Người dùng nhập: "áo khoác xanh giá dưới 500k"          │
│    ↓                                                         │
│ 2. Nhấn nút "Tìm kiếm"                                      │
│    ↓                                                         │
│ 3. handleSearchSubmit() kích hoạt                           │
│    ↓                                                         │
│ 4. navigate('/shop?query=áo%20khoác%20...')               │
│    ↓                                                         │
│ 5. URL thay đổi → useEffect kích hoạt                      │
│    ↓                                                         │
│ 6. fetch('/api/products?query=áo%20khoác...&page=1')     │
│    ↓                                                         │
└─────────────────────────────────────────────────────────────┘
                        ↓ HTTP REQUEST
┌─────────────────────────────────────────────────────────────┐
│ BACKEND (Laravel)                                           │
├─────────────────────────────────────────────────────────────┤
│ 7. ProductController::index() nhận request                  │
│    ↓                                                         │
│ 8. $query = "áo khoác xanh giá dưới 500k"                  │
│    ↓                                                         │
│ 9. ProductSearchService::analyze() gọi Groq API            │
│    ↓                                                         │
└─────────────────────────────────────────────────────────────┘
                        ↓ HTTP POST
┌─────────────────────────────────────────────────────────────┐
│ GROQ API (AI)                                               │
├─────────────────────────────────────────────────────────────┤
│ 10. Nhận prompt: "Phân tích: áo khoác xanh giá dưới 500k" │
│     ↓                                                        │
│ 11. AI phân tích và trả về JSON:                           │
│     {                                                        │
│       "category": "Coat",                                    │
│       "max_price": 500000,                                   │
│       "keywords": ["xanh"],                                  │
│       "explanation": "Tôi tìm..."                            │
│     }                                                        │
│     ↓                                                        │
└─────────────────────────────────────────────────────────────┘
                        ↓ HTTP RESPONSE
┌─────────────────────────────────────────────────────────────┐
│ BACKEND (Laravel) - Tiếp tục                               │
├─────────────────────────────────────────────────────────────┤
│ 12. Nhận kết quả JSON từ Groq                               │
│     ↓                                                        │
│ 13. Áp dụng filter:                                         │
│     - category = "Coat"                                      │
│     - max_price <= 500000                                    │
│     - keywords chứa "xanh"                                   │
│     ↓                                                        │
│ 14. Query DB: SELECT * FROM products WHERE ...              │
│     ↓                                                        │
│ 15. Lấy 8 sản phẩm đầu tiên (page 1)                       │
│     ↓                                                        │
│ 16. Trả về JSON:                                            │
│     {                                                        │
│       "success": true,                                       │
│       "products": [...8 sản phẩm...],                       │
│       "pagination": {...},                                   │
│       "is_ai": true,                                         │
│       "explanation": "Tôi tìm thấy 40 áo khoác..."         │
│     }                                                        │
│     ↓                                                        │
└─────────────────────────────────────────────────────────────┘
                        ↓ HTTP RESPONSE
┌─────────────────────────────────────────────────────────────┐
│ FRONTEND (React) - Tiếp tục                                │
├─────────────────────────────────────────────────────────────┤
│ 17. Nhận JSON response                                      │
│     ↓                                                        │
│ 18. Cập nhật state:                                         │
│     - setProducts([8 sản phẩm])                             │
│     - setAiExplanation("Tôi tìm thấy 40 áo khoác...")      │
│     - setIsAi(true)                                          │
│     ↓                                                        │
│ 19. Component re-render                                     │
│     ↓                                                        │
│ 20. Hiển thị:                                               │
│     - Hộp giải thích AI (icon robot + text)                │
│     - 8 thẻ sản phẩm                                        │
│     - Nút phân trang (1 2 3 4 5)                           │
│     ↓                                                        │
└─────────────────────────────────────────────────────────────┘
                        ↓ NGƯỜI DÙNG THẤY KẾT QUẢ
                   Người dùng hài lòng! ✅
```

---

## 🔑 CÁC KHÁI NIỆM QUAN TRỌNG

### 📌 1. Groq API là gì?
- Dịch vụ API cung cấp mô hình AI Llama
- Cho phép ứng dụng gọi AI để phân tích dữ liệu
- Bạn tạo API key trên https://console.groq.com
- Mỗi request trả về JSON với kết quả phân tích

### 📌 2. Prompt là gì?
- Hướng dẫn cho AI về việc cần làm gì
- Prompt tốt → AI trả về kết quả chính xác
- Ví dụ: "Phân tích 'áo khoác xanh' và trả JSON với category, price, keywords"

### 📌 3. Fallback là gì?
- Kế hoạch dự phòng nếu chính chế độ không hoạt động
- Ở đây: Nếu Groq API không hoạt động → sử dụng tìm kiếm từ khóa cơ bản
- Giảm rủi ro khi dùng dịch vụ bên ngoài

### 📌 4. Phân trang (Pagination) là gì?
- Chia danh sách dài thành nhiều trang nhỏ
- Mỗi trang 8 sản phẩm → Không phải load 63 sản phẩm cùng lúc
- Giúp trang web tải nhanh hơn

### 📌 5. useSearchParams & useNavigate là gì?
- **useSearchParams**: Lấy tham số từ URL (`?query=...&page=1`)
- **useNavigate**: Thay đổi URL mà không cần refresh trang
- Giúp lưu trạng thái tìm kiếm trong URL → Chia sẻ link tìm kiếm được

---

## 📝 TÓM TẮT

| **Bước** | **Nơi xử lý** | **File** | **Kết quả** |
|---|---|---|---|
| 1-3 | Frontend | `ShopPage.jsx` | Người dùng nhập từ khóa |
| 4-5 | Frontend | `ShopPage.jsx` | Gửi HTTP request tới `/api/products?query=...` |
| 6-8 | Backend | `routes/api.php` + `ProductController.php` | Nhận request, khởi tạo xử lý |
| 9-14 | Backend | `ProductSearchService.php` | Gọi Groq API, nhận tiêu chí lọc (JSON) |
| 15 | Groq Cloud | API Endpoint | Phân tích AI, trả về JSON |
| 16-21 | Backend | `ProductController.php` | Áp dụng filter, truy vấn DB |
| 22-24 | Backend | `ProductController.php` | Trả về JSON response |
| 25-29 | Frontend | `ShopPage.jsx` | Cập nhật state, re-render giao diện |
| 30+ | Frontend | `ShopPage.jsx` | Hiển thị kết quả, giải thích AI, phân trang |

---

## 🎓 KẾT LUẬN

Luồng tìm kiếm AI của FASHIAI hoạt động theo quy trình:

1. **Người dùng** nhập từ khóa "áo khoác xanh giá dưới 500k" trên Frontend
2. **Frontend** gửi request tới Backend API
3. **Backend** nhận request và gọi **Groq API** để phân tích AI
4. **AI** phân tích và trả về JSON với tiêu chí lọc (category, price, keywords, ...)
5. **Backend** áp dụng filter và truy vấn Database
6. **Backend** trả về 8 sản phẩm + giải thích AI dưới dạng JSON
7. **Frontend** nhận kết quả, cập nhật state React
8. **Frontend** hiển thị:
   - Hộp giải thích AI (với icon robot)
   - 8 thẻ sản phẩm
   - Nút phân trang để xem trang khác

** Ưu điểm của kiến trúc này:**
- Tìm kiếm thông minh nhờ AI
- Fallback khi AI không hoạt động (tìm kiếm từ khóa)
- Phân trang giảm tải server
- URL lưu trạng thái (có thể chia sẻ link tìm kiếm)
- UX tốt với giải thích AI cho người dùng
