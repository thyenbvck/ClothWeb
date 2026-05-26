<?php
require_once("controllers/main/base_controller.php");
require_once('models/Category.php');
require_once('models/Product.php');
require_once('models/Cart.php');
require_once('models/CartItem.php');
require_once('connection.php');
class ProductsController extends BaseController
{
    function __construct()
    {
        $this->folder = "products";
    }
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        if (isset($_GET['cat_id'])) {

            $products = Product::getProductsByCategoryId(intval($_GET['cat_id']));
            $p_cat = Category::getAll();
            $link = Category::get($_GET['cat_id']);
            $data1 = array('products' => $products);
            $data2 = array('p_cat' => $p_cat);
            $data3 = array('link' => $link);
            $data = $data1 + $data2 + $data3;
        }else{
            $products = Product::getAll();
            $p_cat = Category::getAll();
            $data1 = array('products' => $products);
            $data2 = array('p_cat' => $p_cat);
            $data = $data1 + $data2;
        }
        $this->render('index', $data);
    }

    public function viewdetail()
    {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $products = Product::getAll();
        $p_cat = Category::getAll();
        $category = Product::getcate(intval($_GET['id']));
        $product = Product::getProductById(intval($_GET['id']));
        $likeproducts = Product::getProductsByCategoryId($category->getCategoryId());
        $data = array('products' => $products, 'p_cat' => $p_cat, 'product' => $product, 'category' => $category, 'likeproducts' => $likeproducts);
        // $data1 = array('products' => $products);
        // $data2 = array('p_cat' => $p_cat);
        // $data3 = array('product' => $product);
        // $data4 = array('category' => $category);
        // $data5 = array('likeproducts' => $likeproducts);
        // $data = $data1 + $data2 + $data3 + $data4 + $data5;
        $this->render('viewdetail', $data);
    }

    public function addtocart(){
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        if(!isset($_SESSION['username'])){
            echo json_encode(array('status' => 401, 'message' => 'Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng'));
        }else{
            $cart = Cart::getCartByUsername($_SESSION['username']);
            $cartItem = CartItem::getCartItemByCartIdAndProductIdAndSize($cart->getCartId(), intval($_POST['productId']), $_POST['size']);
            //echo $cartItem;
            if($cartItem){
                CartItem::updateQuantity($cartItem->getCartItemId(), $cartItem->getQuantity() + intval($_POST['qty']));
            }else{
                CartItem::save($cart->getCartId(), intval($_POST['productId']), intval($_POST['qty']), $_POST['size']);
            }
            $cart = Cart::getCartByUsername($_SESSION['username']);
            $cartItems = CartItem::getCartItemsByCartId($cart->getCartId());
            $list_item = [];
            foreach($cartItems as $item){
                $product = Product::getProductById($item->getProductId());
                $list_item[] = [
                    'productId' => $product->getProductId(),
                    'productName' => $product->getProductName(),
                    'price' => $product->getPrice(),
                    'image' => $product->getImageUrl(),
                    'quantity' => $item->getQuantity(),
                    'size' => $item->getSize()
                ];
            }
            $data = array('cartItems' => $list_item);
            echo json_encode(array('status' => 200, 'message' => 'Thêm sản phẩm vào giỏ hàng thành công', 'data' => $data));
        }
    }
}
?> 