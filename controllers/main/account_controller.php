<?php
require_once("controllers/main/base_controller.php");
require_once("models/User.php");
require_once("models/Role.php");
require_once('models/Product.php');
require_once('models/Cart.php');
require_once('models/CartItem.php');
require_once('models/Payment.php');

class AccountController extends BaseController {
    function __construct()
    {
        $this->folder = "account";
    }
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        if(isset($_SESSION['username']) && isset($_SESSION['role_id'])) {
            // get user by username
            $user = User::getUserByUsername($_SESSION['username']);
            $payments = Payment::getPaymentsByUserId($user->getUserId());
            // get role
            $role = Role::getRoleById($user->getRoleId());
            $data = [
                'username' => $user->getUsername(),
                'role' => $role->getRoleName(),
                'user' => $user,
            ];
            $orders = [];
            foreach($payments as $item){
                $orders[] = [
                    'payment_id' => $item->getPaymentId(),
                    'payment_date' => $item->getPaymentDate(),
                    'total' => $item->getTotalPrice(),
                    'payment_method' => $item->getMethod(),
                    'status' => $item->getStatus()
                ];
            }
            $data['orders'] = $orders;
            $this->render("index", $data);
        } else {
            header("Location: index.php?controller=main&action=login");
        }
    } 
    
    public function edit(){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $userId = $_POST['userId'];
            $email = $_POST['email'];
            $fullName = $_POST['fullName'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];

            // find user to update
            $user = User::getUserById($userId);
            // set value to user
            $user->setEmail($email);
            $user->setFullName($fullName);
            $user->setPhone($phone);
            $user->setAddress($address);
            // update user
            $result = User::updateUser($user);
            if($result){
                echo json_encode(array('status' => 200, 'message' => 'update user success'));
            }
            else{
                echo json_encode(array('status' => 500, 'message' => 'update user failed'));
            }
        }else{
            echo json_encode(array('status' => 404, 'message' => 'method not allowed'));
        }
    }
}

?>