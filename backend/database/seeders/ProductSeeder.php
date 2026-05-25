<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::truncate();

        $products = [
            // =================== COAT - Áo Khoác (10 sản phẩm) ===================
            [
                'name'        => 'Áo Len Vặn Eo Cổ V Màu Vàng',
                'category'    => 'Coat',
                'price'       => 299000,
                'image'       => 'img/products/product-1.jpg',
                'description' => 'Áo len nữ cổ V vặn eo độc đáo, màu vàng rực rỡ. Chất len dày ấm, thiết kế ôm body tôn dáng, phù hợp đi chơi hay đi làm.',
            ],
            [
                'name'        => 'Áo Len Croptop Cổ Đan Dây Hồng Phấn',
                'category'    => 'Coat',
                'price'       => 269000,
                'image'       => 'img/products/product-2.jpg',
                'description' => 'Áo len nữ croptop cổ đan dây chéo màu hồng phấn ngọt ngào. Dáng cá tính trẻ trung, chất len mềm co giãn tốt.',
            ],
            [
                'name'        => 'Áo Jacket Quân Đội Oversize Xanh Rêu',
                'category'    => 'Coat',
                'price'       => 459000,
                'image'       => 'img/products/product-3.jpg',
                'description' => 'Áo jacket phong cách quân đội màu xanh rêu, dáng oversize unisex. 4 túi hộp tiện lợi, vải cotton dày chắc chắn, cá tính.',
            ],
            [
                'name'        => 'Áo Sweater Gấu Cute Màu Be',
                'category'    => 'Coat',
                'price'       => 239000,
                'image'       => 'img/products/product-6.jpg',
                'description' => 'Áo sweater len mềm họa tiết gấu cute, màu be kem nhẹ nhàng. Dáng rộng thoải mái, phù hợp phong cách Hàn Quốc dễ thương.',
            ],
            [
                'name'        => 'Áo Gió 2 Lớp Hoodie Chống Nước Nam',
                'category'    => 'Coat',
                'price'       => 549000,
                'image'       => 'img/products/product-8.jpg',
                'description' => 'Áo gió 2 lớp có mũ trùm, chống gió và cản nước tốt. Thiết kế thể thao ôm dáng gọn gàng, phù hợp đi phượt ngoài trời.',
            ],
            [
                'name'        => 'Áo Len Nữ Cổ V Xoắn Màu Vàng Rực',
                'category'    => 'Coat',
                'price'       => 289000,
                'image'       => 'img/products/women-1.jpg',
                'description' => 'Áo len nữ cổ V kiểu twist xoắn sang trọng. Màu vàng nổi bật, len mềm ấm áp, phù hợp mặc đi chơi hay đi làm.',
            ],
            [
                'name'        => 'Áo Len Cổ V Xám Tối Giản Nữ',
                'category'    => 'Coat',
                'price'       => 249000,
                'image'       => 'img/products/women-3.jpg',
                'description' => 'Áo len nữ cổ V tối giản màu xám thanh lịch. Sợi len mịn nhẹ, dễ phối đồ với quần jeans hay váy dạo phố.',
            ],
            [
                'name'        => 'Áo Khoác Gió Hoodie Nam Slim Fit',
                'category'    => 'Coat',
                'price'       => 499000,
                'image'       => 'img/products/man-3.jpg',
                'description' => 'Áo khoác gió nam có mũ trùm, dáng slim fit ôm dáng. Màu xanh rêu trung tính, chống gió nhẹ, thích hợp mặc dạo phố.',
            ],
            [
                'name'        => 'Áo Khoác Denim Wash Cổ Điển Unisex',
                'category'    => 'Coat',
                'price'       => 429000,
                'image'       => 'img/products/man-4.jpg',
                'description' => 'Áo khoác denim wash cổ điển, màu xanh nhạt vintage. Vải denim dày co giãn, 4 túi tiện lợi, phong cách casual năng động.',
            ],
            [
                'name'        => 'Áo Len Croptop Đan Chéo Hồng Nhạt',
                'category'    => 'Coat',
                'price'       => 259000,
                'image'       => 'img/products/women-2.jpg',
                'description' => 'Áo len nữ kiểu croptop cổ đan chéo màu hồng pastel dịu dàng. Chất len mịn không xổ, dáng cá tính trẻ trung.',
            ],

            // =================== SHIRT - Áo Thun (8 sản phẩm) ===================
            [
                'name'        => 'Áo Thun Trắng Basic Cotton Premium',
                'category'    => 'Shirt',
                'price'       => 159000,
                'image'       => 'img/products/tshirt-1.jpg',
                'description' => 'Áo thun trắng basic 100% cotton cao cấp. Cổ tròn vừa vặn, form regular unisex, mềm mịn thoáng khí, dễ phối với mọi trang phục.',
            ],
            [
                'name'        => 'Áo Thun Nam Oversize Tay Lỡ Đen',
                'category'    => 'Shirt',
                'price'       => 189000,
                'image'       => 'img/products/tshirt-2.jpg',
                'description' => 'Áo thun nam dáng oversize tay lỡ màu đen, phong cách streetwear. Cổ tròn rộng, chất cotton dày mịn, không nhăn sau giặt.',
            ],
            [
                'name'        => 'Áo Thun Graphic In Hoạ Tiết Nghệ Thuật',
                'category'    => 'Shirt',
                'price'       => 219000,
                'image'       => 'img/products/tshirt-3.jpg',
                'description' => 'Áo thun in hoạ tiết nghệ thuật độc quyền, màu sắc phong phú. Chất cotton mềm thoáng, in kỹ thuật số siêu nét không phai màu.',
            ],
            [
                'name'        => 'Áo Thun Polo Nam Cổ Bẻ Classic',
                'category'    => 'Shirt',
                'price'       => 249000,
                'image'       => 'img/products/tshirt-4.jpg',
                'description' => 'Áo polo nam cổ bẻ classic, vải piqué cotton cao cấp thoáng mát. Màu trơn thanh lịch, phù hợp đi làm hay dự tiệc nhẹ.',
            ],
            [
                'name'        => 'Áo Thun Nữ Croptop Trơn Nhiều Màu',
                'category'    => 'Shirt',
                'price'       => 169000,
                'image'       => 'img/products/tshirt-1.jpg',
                'description' => 'Áo thun nữ croptop cổ tròn trơn màu, nhiều màu sắc lựa chọn. Chất cotton mềm mịn, dáng ôm nhẹ tôn dáng, phối với quần cao eo rất đẹp.',
            ],
            [
                'name'        => 'Áo Thun Unisex Tie-Dye Loang Màu',
                'category'    => 'Shirt',
                'price'       => 199000,
                'image'       => 'img/products/tshirt-3.jpg',
                'description' => 'Áo thun tie-dye loang màu độc đáo, mỗi chiếc một kiểu riêng biệt. Vải cotton dày dặn, màu sắc tươi sáng bền đẹp sau nhiều lần giặt.',
            ],
            [
                'name'        => 'Áo Sơ Mi Nam Kẻ Sọc Flannel Ấm Áp',
                'category'    => 'Shirt',
                'price'       => 299000,
                'image'       => 'img/products/tshirt-2.jpg',
                'description' => 'Áo sơ mi flannel kẻ sọc ấm áp, vải dày nhẹ mềm mại. Thiết kế 2 túi ngực, phù hợp mặc lót hay mặc ngoài layering mùa đông.',
            ],
            [
                'name'        => 'Áo Thun Henley Cổ Cài Nút Vintage',
                'category'    => 'Shirt',
                'price'       => 229000,
                'image'       => 'img/products/tshirt-4.jpg',
                'description' => 'Áo thun cổ Henley cài nút phong cách vintage Mỹ. Vải cotton pha spandex co giãn 4 chiều, dáng regular fit thoải mái mặc cả ngày.',
            ],

            // =================== JEANS - Quần Jeans (7 sản phẩm) ===================
            [
                'name'        => 'Quần Jeans Skinny Xanh Đậm Nam',
                'category'    => 'Jeans',
                'price'       => 399000,
                'image'       => 'img/products/jeans-1.jpg',
                'description' => 'Quần jeans skinny nam màu xanh đậm classic, co giãn 4 chiều thoải mái. Đường may chắc chắn, dáng ôm tôn vóc dáng, phù hợp mặc đi làm hay dạo phố.',
            ],
            [
                'name'        => 'Quần Jeans Rách Gối Baggy Unisex',
                'category'    => 'Jeans',
                'price'       => 429000,
                'image'       => 'img/products/jeans-2.jpg',
                'description' => 'Quần jeans rách gối dáng baggy unisex phong cách streetwear. Vải denim wash mềm mại, thiết kế rách có chủ đích, cá tính và thoải mái.',
            ],
            [
                'name'        => 'Quần Jeans Ống Rộng Vintage Wash Nữ',
                'category'    => 'Jeans',
                'price'       => 449000,
                'image'       => 'img/products/jeans-3.jpg',
                'description' => 'Quần jeans ống rộng nữ dáng vintage wash nhạt màu. Lưng cao tôn dáng, ống rộng thoải mái phong cách retro 90s đang hot trở lại.',
            ],
            [
                'name'        => 'Quần Jeans Slim Fit Đen Công Sở Nam',
                'category'    => 'Jeans',
                'price'       => 379000,
                'image'       => 'img/products/jeans-4.jpg',
                'description' => 'Quần jeans slim fit màu đen tuyền, phù hợp đi làm công sở. Vải jeans co giãn nhẹ, dáng ôm vừa phải, dễ phối áo sơ mi hay áo khoác.',
            ],
            [
                'name'        => 'Quần Jeans Mom Jean Lưng Cao Pastel',
                'category'    => 'Jeans',
                'price'       => 419000,
                'image'       => 'img/products/jeans-1.jpg',
                'description' => 'Quần jeans mom jean lưng cao màu pastel nhạt, xu hướng hot 2026. Ống thẳng tôn dáng chân dài, dễ phối với áo croptop hay áo thun tucked in.',
            ],
            [
                'name'        => 'Quần Short Jeans Rách Cạp Cao Nữ',
                'category'    => 'Jeans',
                'price'       => 299000,
                'image'       => 'img/products/jeans-2.jpg',
                'description' => 'Quần short jeans rách cạp cao nữ, phong cách hè năng động. Vải denim nhẹ thoáng mát, hoàn thiện viền chân ống tự nhiên thời trang.',
            ],
            [
                'name'        => 'Quần Jeans Ống Đứng Trơn Xanh Nhạt',
                'category'    => 'Jeans',
                'price'       => 359000,
                'image'       => 'img/products/jeans-3.jpg',
                'description' => 'Quần jeans ống đứng màu xanh nhạt cổ điển, phù hợp mọi vóc dáng. Vải denim cao cấp bền đẹp, đường may tỉ mỉ chắc chắn.',
            ],

            // =================== DRESS - Váy Đầm (7 sản phẩm) ===================
            [
                'name'        => 'Váy Maxi Hoa Nhí Nhẹ Nhàng Dạo Phố',
                'category'    => 'Dress',
                'price'       => 349000,
                'image'       => 'img/products/dress-1.jpg',
                'description' => 'Váy maxi hoa nhí nhẹ nhàng duyên dáng, vải voan mỏng nhẹ thoáng mát. Dáng A-line tôn dáng, phù hợp dạo phố hay đi picnic mùa hè.',
            ],
            [
                'name'        => 'Đầm Ôm Bodycon Cổ Đổ Sang Trọng',
                'category'    => 'Dress',
                'price'       => 429000,
                'image'       => 'img/products/dress-2.jpg',
                'description' => 'Đầm ôm bodycon cổ đổ sang trọng, vải gân co giãn ôm sát. Thiết kế tôn đường cong cơ thể, phù hợp đi tiệc hay sự kiện quan trọng.',
            ],
            [
                'name'        => 'Váy Midi Kẻ Sọc Bút Chì Công Sở',
                'category'    => 'Dress',
                'price'       => 389000,
                'image'       => 'img/products/dress-3.jpg',
                'description' => 'Váy midi kẻ sọc bút chì thanh lịch công sở, dáng A-line xuống ngang bắp chân. Vải dệt dày chắc, không nhăn, phù hợp mặc cả tuần.',
            ],
            [
                'name'        => 'Đầm Wrap Cúc Bọc Họa Tiết Nhiệt Đới',
                'category'    => 'Dress',
                'price'       => 369000,
                'image'       => 'img/products/dress-4.jpg',
                'description' => 'Đầm wrap cúc bọc họa tiết nhiệt đới sống động, màu sắc tươi vui. Cổ chéo thời trang, dễ điều chỉnh size, phù hợp đi biển hay đi chơi hè.',
            ],
            [
                'name'        => 'Váy Babydoll Nơ Vai Trắng Tinh Khôi',
                'category'    => 'Dress',
                'price'       => 319000,
                'image'       => 'img/products/dress-1.jpg',
                'description' => 'Váy babydoll nơ vai trắng tinh khôi dễ thương, vải linen thoáng mát. Dáng xòe nhẹ, phối với sandal hay sneaker đều đẹp, phong cách ulzzang.',
            ],
            [
                'name'        => 'Đầm Linen Tay Ngắn Cổ Vuông Dễ Thương',
                'category'    => 'Dress',
                'price'       => 299000,
                'image'       => 'img/products/dress-3.jpg',
                'description' => 'Đầm linen cổ vuông tay ngắn cute, vải linen tự nhiên thoáng mát. Màu trơn nhẹ nhàng, dáng suông rộng thoải mái phù hợp thời tiết nóng.',
            ],
            [
                'name'        => 'Váy Xòe Pleated Midi Phong Cách Vintage',
                'category'    => 'Dress',
                'price'       => 409000,
                'image'       => 'img/products/dress-2.jpg',
                'description' => 'Váy xòe pleated midi phong cách vintage thanh lịch. Vải satin bóng nhẹ, xếp ly đều tay, tôn dáng phù hợp các buổi tiệc nhẹ hoặc đi dạo.',
            ],

            // =================== SHOES - Giày (8 sản phẩm) ===================
            [
                'name'        => 'Giày Converse Cổ Cao Da Lộn Vàng',
                'category'    => 'Shoes',
                'price'       => 799000,
                'image'       => 'img/products/product-9.jpg',
                'description' => 'Giày Converse Pro Leather cổ cao da lộn màu vàng nổi bật. Đế cao su chắc chắn, logo ngôi sao huyền thoại, phong cách classic không bao giờ lỗi mốt.',
            ],
            [
                'name'        => 'Giày Converse High Top Classic Nam Nữ',
                'category'    => 'Shoes',
                'price'       => 749000,
                'image'       => 'img/products/man-2.jpg',
                'description' => 'Giày Converse cổ cao canvas classic, mũi tròn đặc trưng. Đế cao su bền bỉ, phù hợp mặc hàng ngày hay phối với nhiều style khác nhau.',
            ],
            [
                'name'        => 'Giày Sneaker Nike Air Running Đỏ Trắng',
                'category'    => 'Shoes',
                'price'       => 1290000,
                'image'       => 'img/products/shoes-2.jpg',
                'description' => 'Giày sneaker thể thao màu đỏ trắng năng động, đế Air đàn hồi tốt. Phù hợp chạy bộ, tập gym hoặc dạo phố phong cách thể thao casual.',
            ],
            [
                'name'        => 'Giày Sneaker Platform Nữ Màu Trắng',
                'category'    => 'Shoes',
                'price'       => 689000,
                'image'       => 'img/products/shoes-3.jpg',
                'description' => 'Giày sneaker platform nữ màu trắng tinh, đế độn 4cm tôn chiều cao. Vải mesh thoáng khí, đế ngoài rubber chống trơn, phong cách chunky trend.',
            ],
            [
                'name'        => 'Giày Slip-On Vải Canvas Unisex Đơn Giản',
                'category'    => 'Shoes',
                'price'       => 459000,
                'image'       => 'img/products/shoes-4.jpg',
                'description' => 'Giày slip-on vải canvas không dây tiện lợi, kiểu dáng tối giản. Lót giày êm ái, đế cao su nhẹ không trơn, phù hợp mọi dịp đi lại.',
            ],
            [
                'name'        => 'Giày Boot Chelsea Da PU Cao Cổ Nữ',
                'category'    => 'Shoes',
                'price'       => 849000,
                'image'       => 'img/products/shoes-2.jpg',
                'description' => 'Giày boot Chelsea da PU cao cấp màu đen, cổ cao 5cm thời trang. Dây đàn hồi 2 bên dễ mang, đế lắc chắc chắn, phong cách Anh quốc lịch lãm.',
            ],
            [
                'name'        => 'Giày Vải Espadrilles Đế Cói Nữ Hè',
                'category'    => 'Shoes',
                'price'       => 399000,
                'image'       => 'img/products/shoes-3.jpg',
                'description' => 'Giày espadrilles vải canvas đế cói đan thủ công, phong cách Địa Trung Hải. Nhẹ và thoáng, perfect cho mùa hè đi biển hoặc dạo phố.',
            ],
            [
                'name'        => 'Dép Sandal Quai Ngang Da Thật Unisex',
                'category'    => 'Shoes',
                'price'       => 349000,
                'image'       => 'img/products/shoes-4.jpg',
                'description' => 'Dép sandal quai ngang da bò thật màu nâu cổ điển, đế EVA êm nhẹ. Khóa cài điều chỉnh vừa chân, bền đẹp sử dụng lâu dài.',
            ],

            // =================== BAG - Túi Xách & Balo (6 sản phẩm) ===================
            [
                'name'        => 'Balo Thể Thao Chống Thấm Nước 40L',
                'category'    => 'Bag',
                'price'       => 649000,
                'image'       => 'img/products/product-7.jpg',
                'description' => 'Balo thể thao dung tích 40L, chất liệu chống thấm nước tốt. Nhiều ngăn tiện lợi, đai vai êm, phù hợp đi phượt, leo núi hay đi học.',
            ],
            [
                'name'        => 'Balo Leo Núi Outdoor Vàng 45L',
                'category'    => 'Bag',
                'price'       => 699000,
                'image'       => 'img/products/man-1.jpg',
                'description' => 'Balo leo núi outdoor 45L màu vàng, đệm lưng thoáng khí. Dây đai ngực và hông hỗ trợ tốt, bền bỉ trong điều kiện thời tiết khắc nghiệt.',
            ],
            [
                'name'        => 'Túi Đeo Chéo Đính Hạt Thủ Công Trắng',
                'category'    => 'Bag',
                'price'       => 449000,
                'image'       => 'img/products/women-4.jpg',
                'description' => 'Túi đeo chéo đính hạt thủ công màu trắng ngà sang trọng. Khung tay gỗ tự nhiên, dây xích mảnh, phù hợp đi tiệc hoặc chụp ảnh.',
            ],
            [
                'name'        => 'Túi Tote Canvas In Logo Minimalist',
                'category'    => 'Bag',
                'price'       => 199000,
                'image'       => 'img/products/bag-2.jpg',
                'description' => 'Túi tote canvas in logo tối giản, dây quai dài chắc chắn. Dung tích lớn đựng được laptop 13 inch, sách vở hay đồ đi chợ mỗi ngày.',
            ],
            [
                'name'        => 'Túi Xách Đeo Vai Nữ Da PU Cao Cấp',
                'category'    => 'Bag',
                'price'       => 589000,
                'image'       => 'img/products/bag-3.jpg',
                'description' => 'Túi xách đeo vai nữ da PU cao cấp màu caramel sang trọng. Nhiều ngăn bên trong, khoá từ tiện lợi, phù hợp đi làm hay đi chơi.',
            ],
            [
                'name'        => 'Túi Clutch Cầm Tay Da Cá Sấu Mini',
                'category'    => 'Bag',
                'price'       => 329000,
                'image'       => 'img/products/women-4.jpg',
                'description' => 'Túi clutch cầm tay da dập vân cá sấu mini, khóa bấm vàng sang trọng. Nhỏ gọn đựng điện thoại và ví tiền, phù hợp đi tiệc buổi tối.',
            ],

            // =================== HAT - Mũ Nón (5 sản phẩm) ===================
            [
                'name'        => 'Mũ Snapback Vàng Thêu Chữ Nghệ Thuật',
                'category'    => 'Hat',
                'price'       => 199000,
                'image'       => 'img/products/product-5.jpg',
                'description' => 'Mũ snapback màu vàng nổi bật, thêu chữ nghệ thuật. Dây rút điều chỉnh size, vành cứng uốn cong, phong cách streetwear cá tính.',
            ],
            [
                'name'        => 'Mũ Bucket Hat Vải Kaki Unisex',
                'category'    => 'Hat',
                'price'       => 169000,
                'image'       => 'img/products/hat-2.jpg',
                'description' => 'Mũ bucket hat vải kaki dày dặn, vành rộng che nắng tốt. Kiểu dáng unisex phù hợp cả nam lẫn nữ, đang hot trở lại theo xu hướng Y2K.',
            ],
            [
                'name'        => 'Nón Lưỡi Trai Baseball Cap Thêu Logo',
                'category'    => 'Hat',
                'price'       => 189000,
                'image'       => 'img/products/hat-3.jpg',
                'description' => 'Nón lưỡi trai baseball cap thêu logo classic, vải cotton thoáng mát. Khóa điều chỉnh size phía sau, phù hợp mặc hằng ngày hay đi chơi.',
            ],
            [
                'name'        => 'Mũ Beanie Len Dày Ấm Mùa Đông Unisex',
                'category'    => 'Hat',
                'price'       => 149000,
                'image'       => 'img/products/product-5.jpg',
                'description' => 'Mũ beanie len dày ấm áp phù hợp mùa đông. Chất liệu acrylic mềm mịn không gây ngứa, co giãn vừa mọi size đầu, màu sắc trẻ trung.',
            ],
            [
                'name'        => 'Nón Cowboy Rơm Đi Biển Phong Cách Boho',
                'category'    => 'Hat',
                'price'       => 229000,
                'image'       => 'img/products/hat-2.jpg',
                'description' => 'Nón cowboy rơm đan thủ công phong cách Boho-chic. Vành rộng che nắng hiệu quả, nhẹ thoáng, perfect cho kỳ nghỉ biển hay festival.',
            ],

            // =================== TOWEL - Khăn (6 sản phẩm) ===================
            [
                'name'        => 'Khăn Quàng Cổ Len Xám Đan Cable Knit',
                'category'    => 'Towel',
                'price'       => 149000,
                'image'       => 'img/products/product-4.jpg',
                'description' => 'Khăn quàng cổ len đan cable knit màu xám trung tính, giữ ấm cực tốt. Sợi len dày không xổ, dài vừa phải, dễ mix đồ mùa đông.',
            ],
            [
                'name'        => 'Khăn Len Đan Thủ Công Màu Be Cổ Điển',
                'category'    => 'Towel',
                'price'       => 179000,
                'image'       => 'img/products/product-4.jpg',
                'description' => 'Khăn len đan thủ công màu be sang trọng, sợi len mềm mại tự nhiên. Siêu ấm và bền bỉ theo thời gian, mỗi chiếc là một tác phẩm thủ công.',
            ],
            [
                'name'        => 'Khăn Tay Cotton Cao Cấp Set 3 Cái',
                'category'    => 'Towel',
                'price'       => 99000,
                'image'       => 'img/products/product-4.jpg',
                'description' => 'Bộ 3 khăn tay cotton 100% siêu mềm mại, thấm hút tốt. Nhiều màu sắc tươi sáng, phù hợp sử dụng hàng ngày hoặc làm quà tặng.',
            ],
            [
                'name'        => 'Khăn Thể Thao Quick Dry Microfiber',
                'category'    => 'Towel',
                'price'       => 89000,
                'image'       => 'img/products/product-4.jpg',
                'description' => 'Khăn thể thao công nghệ Quick Dry microfiber, khô nhanh sau 20 phút. Siêu nhẹ gấp gọn tiện lợi, mang theo khi tập gym hoặc du lịch.',
            ],
            [
                'name'        => 'Khăn Quàng Cổ Lụa Tơ Tằm Họa Tiết',
                'category'    => 'Towel',
                'price'       => 299000,
                'image'       => 'img/products/product-4.jpg',
                'description' => 'Khăn quàng cổ lụa tơ tằm thiên nhiên, họa tiết hoa văn sang trọng. Mềm mượt như da, không gây ngứa, phù hợp quà tặng cao cấp.',
            ],
            [
                'name'        => 'Khăn Len Sọc Bắc Âu Phong Cách Scandinavia',
                'category'    => 'Towel',
                'price'       => 159000,
                'image'       => 'img/products/product-4.jpg',
                'description' => 'Khăn len họa tiết sọc phong cách Scandinavia tối giản tinh tế. Sợi len dày ấm, màu sắc trung tính dễ kết hợp với mọi trang phục đông.',
            ],

            // =================== ACCESSORIES - Phụ Kiện (6 sản phẩm) ===================
            [
                'name'        => 'Kính Mắt Gọng Tròn Retro Unisex',
                'category'    => 'Accessories',
                'price'       => 249000,
                'image'       => 'img/products/accessory-1.jpg',
                'description' => 'Kính mắt thời trang gọng tròn retro unisex, tròng chống UV400. Gọng kim loại nhẹ chắc chắn, nhiều màu sắc lựa chọn, phong cách vintage cổ điển.',
            ],
            [
                'name'        => 'Thắt Lưng Da Bò Thật Khóa Bạc Classic',
                'category'    => 'Accessories',
                'price'       => 329000,
                'image'       => 'img/products/accessory-2.jpg',
                'description' => 'Thắt lưng da bò thật màu nâu cổ điển, khóa inox bạc bền chắc. Bề mặt da mịn bóng, điều chỉnh 5 lỗ, phù hợp quần jeans hoặc quần tây.',
            ],
            [
                'name'        => 'Vòng Tay Đá Tự Nhiên Mix Thạch Anh',
                'category'    => 'Accessories',
                'price'       => 189000,
                'image'       => 'img/products/accessory-3.jpg',
                'description' => 'Vòng tay phong thủy đá tự nhiên mix thạch anh nhiều màu. Hạt đá 8mm tròn đều, dây đàn hồi bền, phù hợp làm quà tặng ý nghĩa.',
            ],
            [
                'name'        => 'Kính Râm Aviator Kim Loại Unisex Cổ Điển',
                'category'    => 'Accessories',
                'price'       => 279000,
                'image'       => 'img/products/accessory-1.jpg',
                'description' => 'Kính râm aviator gọng kim loại mỏng nhẹ, tròng phân cực chống UV100%. Thiết kế classic không bao giờ lỗi mốt, phù hợp mọi khuôn mặt.',
            ],
            [
                'name'        => 'Dây Chuyền Bạc 925 Mặt Ngôi Sao Nữ',
                'category'    => 'Accessories',
                'price'       => 219000,
                'image'       => 'img/products/accessory-3.jpg',
                'description' => 'Dây chuyền bạc 925 mặt ngôi sao tinh xảo, không gây dị ứng da. Dây mỏng nhẹ thanh lịch, phù hợp đeo hàng ngày hoặc layering nhiều vòng.',
            ],
            [
                'name'        => 'Ví Da Nam Gấp Đôi Slim Wallet Caro',
                'category'    => 'Accessories',
                'price'       => 259000,
                'image'       => 'img/products/accessory-2.jpg',
                'description' => 'Ví da nam gấp đôi dập vân caro, mỏng gọn chỉ 6mm khi đầy. Chứa được 6 thẻ ATM và tiền mặt, chất liệu da PU cao cấp bền đẹp.',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
