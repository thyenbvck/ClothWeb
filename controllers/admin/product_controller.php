<?php

require_once('controllers/admin/base_controller.php');
require_once('models/Product.php');
require_once('models/Category.php');
class ProductController extends BaseController
{
    function __construct()
    {
        $this->folder = 'products';
    }
    
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $products = Product::getAll();
        $categories = Category::getAll();
        $data = array('products' => $products, 'categories' => $categories);
        $this->render('index', $data);
    }
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // generate id for filename
            $fileId = (string)date("Y_m_d_h_i_sa");
            
            // Lấy dữ liệu từ form
            $product_name = $_POST['product_name'];
            $description = $_POST['description'];
            $image_url = $_POST['image_url'];
            $price = $_POST['price'];
            $category_id = $_POST['category_id'];

            $target_dir = "public/img/products/";
            $fileName = $_FILES['image_url']['name'];
            $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
            
            // genearte filename
            $fileId .= "." . $fileType;
            $target_url = $target_dir.basename($fileId);
            if (file_exists($target_url)) {
                echo "Sorry, file already exists.";
            }
            // Check file type
            if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "gif" ) {
                    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            }
            if ($_FILES["image_url"]["size"] > 10485760) {
                echo $_FILES["image_url"]["size"]."<br>";
                echo "Sorry, your file is too large.";
            }

            if(!move_uploaded_file($_FILES["image_url"]["tmp_name"], $target_url)) {
                echo "Sorry, there was an error uploading your file.";
            }

            // Tạo sản phẩm mới
            $newProduct = new Product($product_name, $description, $target_url, $price, $category_id);
            if ($newProduct->addProduct()) {
                Header("Location: index.php?page=admin&controller=product&action=index");
            } else {
                echo "Failed to add product.";
            }          
        } else {
            // Hiển thị form thêm sản phẩm
            $this->index();
        }
    }
    public function edit()
    {
        echo "Edit product";
    }
    public function delete(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['productId'])) {
            $productId = intval($_POST['productId']);
            if (Product::deleteProduct($productId)) {
                Header("Location: index.php?page=admin&controller=product&action=index");
            } else {
                echo "Failed to delete product.";
            }   
        }else{
            $this->index();
        }
    }

}
?>