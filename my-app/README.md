# MTYTSHOP - Frontend (React + Vite)

Giao diện web thương mại điện tử thời trang sử dụng React 18 + Vite + Bootstrap 5

## 🚀 Khởi Động Nhanh

```bash
# Cài đặt dependencies
npm install

# Chạy development server (Vite)
npm run dev

# Build production
npm run build
```

Server sẽ chạy tại: **http://localhost:5173**

## 📚 Các Trang

| Route | Mô tả |
|-------|-------|
| `/` | 🏠 Trang chủ |
| `/shop` | 🛍️ Danh sách sản phẩm (phân trang 8 trang) |
| `/product/:id` | 📄 Chi tiết sản phẩm |
| `/cart` | 🛒 Giỏ hàng (Placeholder) |

## 🔍 Tính Năng Tìm Kiếm

- **AI Search**: Gemini 2.5 Flash API
- **Keyword Search**: Fallback search
- **Lọc nâng cao**: Danh mục, giá tiền

## 📁 Cấu Trúc

```
src/
├─ pages/             # React pages
│  ├─ HomePage.jsx
│  ├─ ShopPage.jsx
│  └─ ProductDetailPage.jsx
├─ components/        # Reusable components
├─ layout/           # Layout wrapper
├─ css/              # Styles
└─ App.jsx           # Main component
```

## 🔑 Biến Môi Trường

```
VITE_API_URL=http://127.0.0.1:8000/api
```

## 📦 Dependencies

- React 18
- Vite 8
- Bootstrap 5
- Axios (API calls)
- React Router (Navigation)

## 🗄️ Database

```sql
Database: reactjs_final
User: root
Password: (empty)
Port: 3306
```

### `npm run eject`

**Note: this is a one-way operation. Once you `eject`, you can't go back!**

If you aren't satisfied with the build tool and configuration choices, you can `eject` at any time. This command will remove the single build dependency from your project.

Instead, it will copy all the configuration files and the transitive dependencies (webpack, Babel, ESLint, etc) right into your project so you have full control over them. All of the commands except `eject` will still work, but they will point to the copied scripts so you can tweak them. At this point you're on your own.

You don't have to ever use `eject`. The curated feature set is suitable for small and middle deployments, and you shouldn't feel obligated to use this feature. However we understand that this tool wouldn't be useful if you couldn't customize it when you are ready for it.

## Learn More

You can learn more in the [Create React App documentation](https://facebook.github.io/create-react-app/docs/getting-started).

To learn React, check out the [React documentation](https://reactjs.org/).

### Code Splitting

This section has moved here: [https://facebook.github.io/create-react-app/docs/code-splitting](https://facebook.github.io/create-react-app/docs/code-splitting)

### Analyzing the Bundle Size

This section has moved here: [https://facebook.github.io/create-react-app/docs/analyzing-the-bundle-size](https://facebook.github.io/create-react-app/docs/analyzing-the-bundle-size)

### Making a Progressive Web App

This section has moved here: [https://facebook.github.io/create-react-app/docs/making-a-progressive-web-app](https://facebook.github.io/create-react-app/docs/making-a-progressive-web-app)

### Advanced Configuration

This section has moved here: [https://facebook.github.io/create-react-app/docs/advanced-configuration](https://facebook.github.io/create-react-app/docs/advanced-configuration)

### Deployment

This section has moved here: [https://facebook.github.io/create-react-app/docs/deployment](https://facebook.github.io/create-react-app/docs/deployment)

### `npm run build` fails to minify

This section has moved here: [https://facebook.github.io/create-react-app/docs/troubleshooting#npm-run-build-fails-to-minify](https://facebook.github.io/create-react-app/docs/troubleshooting#npm-run-build-fails-to-minify)
