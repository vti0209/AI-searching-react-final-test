# MTYTSHOP - Backend API (Laravel 11)

Nền tảng API thương mại điện tử thời trang sử dụng Laravel 11 + SQLite

## 🚀 Khởi Động Nhanh

```bash
# Cài đặt dependencies
composer install

# Copy .env
cp .env.example .env

# Tạo app key
php artisan key:generate

# Chạy migrations + seeding
php artisan migrate:refresh --seed

# Khởi động server
php artisan serve --host=127.0.0.1 --port=8000
```

## 📚 API Endpoints

### Sản Phẩm
```
GET  /api/products           # Lấy danh sách (phân trang)
GET  /api/products/{id}      # Lấy chi tiết sản phẩm
POST /api/search             # Tìm kiếm AI + Keyword
```

## 📁 Cấu Trúc

- `app/Models/Product.php` - Model sản phẩm
- `app/Http/Controllers/ProductController.php` - API Controller
- `database/seeders/ProductSeeder.php` - 63 sản phẩm
- `routes/api.php` - API routes
- `config/` - Cấu hình (CORS, auth, etc)

## 🔑 Biến Môi Trường (.env)

```
APP_NAME=MTYTSHOP
APP_KEY=
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reactjs_final
DB_USERNAME=root
DB_PASSWORD=
GEMINI_API_KEY=  # Nếu dùng AI search
```

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
