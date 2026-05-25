-- SQL Dump for phpMyAdmin
-- Database: reactjs_final
-- Table Structure for products

CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table products

INSERT INTO `products` (`id`, `name`, `category`, `price`, `image`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Pure Pineapple Towel', 'Towel', 14.00, 'img/products/product-1.jpg', 'Khăn lau dứa dại nguyên chất từ sợi thiên nhiên, mềm mại và siêu thấm hút.', NOW(), NOW()),
(2, 'Guangzhou sweater', 'Coat', 13.00, 'img/products/product-2.jpg', 'Áo len Quảng Châu ấm áp, chất liệu vải mềm mại co giãn tốt, thiết kế hiện đại cho mùa đông.', NOW(), NOW()),
(3, 'Guangzhou sweater shoes', 'Shoes', 34.00, 'img/products/product-3.jpg', 'Giày thể thao Quảng Châu phong cách trẻ trung năng động, đế cao su chống trơn trượt siêu nhẹ.', NOW(), NOW()),
(4, 'Microfiber Wool Scarf', 'Coat', 64.00, 'img/products/product-4.jpg', 'Khăn quàng cổ len sợi siêu mịn Microfiber thời trang, giữ ấm cực tốt và không gây ngứa da.', NOW(), NOW()),
(5, 'Men\'s Painted Hat', 'Shoes', 44.00, 'img/products/product-5.jpg', 'Mũ sơn vẽ nghệ thuật nam tính cá tính, phụ kiện thời trang độc đáo dành cho nam giới.', NOW(), NOW()),
(6, 'Converse Shoes', 'Shoes', 34.00, 'img/products/product-6.jpg', 'Giày Converse cổ thấp huyền thoại, năng động, bền bỉ, dễ dàng phối hợp với nhiều trang phục.', NOW(), NOW()),
(7, 'Pure Pineapple Luxury Towel', 'Towel', 64.00, 'img/products/product-7.jpg', 'Khăn tắm dứa dại cao cấp cỡ lớn, chất vải dày dặn, mềm mại và thấm hút nước vượt trội.', NOW(), NOW()),
(8, '2 Layer Windbreaker', 'Coat', 44.00, 'img/products/product-8.jpg', 'Áo gió 2 lớp chống cản gió tốt, cản nước nhẹ, thiết kế thể thao ôm dáng gọn gàng.', NOW(), NOW()),
(9, 'Converse High Top Shoes', 'Shoes', 34.00, 'img/products/product-9.jpg', 'Giày Converse cổ cao sành điệu, chất liệu vải canvas cao cấp, tạo điểm nhấn cá tính cho trang phục.', NOW(), NOW());
