<?php
require_once("controllers/main/base_controller.php");
require_once('models/Product.php');
require_once('models/Cart.php');
require_once('models/CartItem.php');
require_once('models/User.php');
require_once('models/Payment.php');

class CartController extends BaseController
{
    function __construct()
    {
        $this->folder = "cart";
    }

    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $data = array();
        $this->render("index", $data);
    }

    public function deleteItem(){
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $res = CartItem::deleteCartItemById(intval($_GET['id']));
        if($res){
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
            echo json_encode(['status' => 200, 'message' => 'Delete success', 'data' => $list_item]);
        }else{
            echo json_encode(['status' => 500, 'message' => 'Delete failed']);
        }
    }

    public function payment(){
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $data = array();
        $this->render("payment", $data);
    }

    public function makepayment(){
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        if(isset($_POST['paymentMethod']) && $_POST['paymentMethod']=='2'){
            // Cấu hình từ VNPay Sandbox
            $vnp_TmnCode = "M1K8IF92"; // Mã TMN từ VNPay
            $vnp_HashSecret = "BW8ZE1YP4ZAKCE1QAHEWS9INRYAE7Z71"; // Secret Key từ VNPay
            $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html"; // URL thanh toán Sandbox
            //$vnp_Returnurl = "http://localhost/btlweb/vnpay_return.php"; // URL trả về sau khi thanh toán
            $vnp_Returnurl = "http://localhost/btlweb/index.php?page=main&controller=cart&action=vnpay_return"; // URL trả về sau khi thanh toán
            $vnp_TxnRef = rand(10000,99999); // Mã đơn hàng (random hoặc theo hệ thống)
            $vnp_OrderInfo = "Thanh toan don hang"; // Thông tin mô tả
            $vnp_OrderType = 'billpayment';
            $vnp_Amount = $_POST['totalPrices'] * 100; // VNPay tính bằng đơn vị VND * 100
            $vnp_Locale = 'vn';
            $vnp_BankCode = ''; // Có thể để trống nếu không chọn ngân hàng cụ thể
            $vnp_IpAddr = $_SERVER['REMOTE_ADDR']; // Địa chỉ IP của người dùng
    
            // Tạo URL
            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef,
            );
    
            ksort($inputData); // Sắp xếp mảng để tạo chữ ký
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }
    
            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }
    
            echo json_encode(['status' => 200, 'message' => 'Payment success', 'data' => $vnp_Url]);
        }else{
            $cartItemsId = json_decode($_POST['cartItemsId'], true);
            // Lưu thông tin giao dịch vào CSDL
            $user = User::getUserByUsername($_SESSION['username']);
            $req = Payment::makePayment($user->getUserId(), "COD", intval($_POST['totalPrices']));
            //echo json_encode(['status' => 200, 'message' => 'Payment success']);
            if($req){
                // xoa gio hang
                foreach($cartItemsId as $cartItemId){
                    $req = CartItem::deleteCartItemById($cartItemId);
                    if(!$req){
                        echo json_encode(['status' => 500, 'message' => 'Payment failed']);
                        return;
                    }
                }
                echo json_encode(['status' => 200, 'message' => 'Payment success']);
            }else{
                echo json_encode(['status' => 500, 'message' => 'Payment failed']);
            }
        }
    }

    public function vnpay_return(){
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $vnp_TmnCode = "M1K8IF92"; // Mã TMN từ VNPay
        $vnp_HashSecret = "BW8ZE1YP4ZAKCE1QAHEWS9INRYAE7Z71"; // Secret Key từ VNPay
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html"; // URL thanh toán Sandbox
        $vnp_Returnurl = "http://localhost/btlweb/index.php?page=main&controller=cart&action=vnpay_return"; // URL trả về sau khi thanh toán
        $vnp_SecureHash = $_GET['vnp_SecureHash'];
        $inputData = array();
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        if ($secureHash == $vnp_SecureHash) {
            if ($_GET['vnp_ResponseCode'] == '00') {
                //echo "<script>alert('Giao dịch thành công');</script>";
                // Lưu thông tin giao dịch vào CSDL
                $user = User::getUserByUsername($_SESSION['username']);
                $cart = Cart::getCartByUsername($_SESSION['username']);
                $req = Payment::makePayment($user->getUserId(), "VNPay", $_GET['vnp_Amount'] / 100);
                if($req){
                    $result = CartItem::deleteCartItemByCartId($cart->getCartId());
                    if($result){
                        echo "<script>
                            alert('Giao dịch thành công');
                            window.location.href = 'index.php?page=main&controller=cart&action=payment';
                            </script>
                        ";
                    }
                }
            } else {
                echo "<script>
                alert('Giao dịch thất bại');
                window.location.href = 'index.php?page=main&controller=cart&action=payment';
                </script>";
            }
        } else {
            echo "<script>
            alert('Chữ ký không hợp lệ');
            window.location.href = 'index.php?page=main&controller=cart&action=payment';
            </script>";
        }
    }
}

?>