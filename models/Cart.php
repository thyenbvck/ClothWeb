<?php
require_once('connection.php');
require_once('models/User.php');
class Cart {
    private $cart_id;
    private $user_id;
    private $created_at;

    public function __construct($cart_id, $user_id, $created_at) {
        $this->cart_id = $cart_id;
        $this->user_id = $user_id;
        $this->created_at = $created_at;
    }

    // Getters
    public function getCartId() {
        return $this->cart_id;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    // Setters

    public function setCartId($cart_id) {
        $this->cart_id = $cart_id;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }

    // toString
    public function __toString() {
        return $this->cart_id . ' - ' . $this->user_id . ' - ' . $this->created_at;
    }

    public static function save($user_id) {
        // Save or update cart in database
        $db = DB::getInstance();
        $stmt = $db->prepare('INSERT INTO cart (user_id) VALUES (?)');
        $stmt->bind_param('i', $user_id);
        $req = $stmt->execute();
        if ($req) {
            return true;
        } else {
            return false;
        }
    }

    // find cart by user_id
    public static function findCartByUserId($user_id) {
        $db = DB::getInstance();
        $stmt = $db->prepare('SELECT * FROM cart WHERE user_id = ?');
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $cart = new Cart($result['cart_id'], $result['user_id'], $result['created_at']);
        return $cart;
    }

    // get Cart by username
    public static function getCartByUsername($username) {
        $db = DB::getInstance();
        $user = User::getUserByUsername($username);
        $user_id = $user->getUserId();          // store in variable — bind_param needs a reference
        $stmt = $db->prepare('SELECT * FROM cart WHERE user_id = ?');
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row  = $result->fetch_assoc();
            return new Cart($row['cart_id'], $row['user_id'], $row['created_at']);
        }
        return null;
    }

    // create cart
    public static function createCart($username) {
        $db = DB::getInstance();
        $user    = User::getUserByUsername($username);
        $user_id = $user->getUserId();
        $stmt = $db->prepare('INSERT INTO cart (user_id) VALUES (?)');
        $stmt->bind_param('i', $user_id);
        return $stmt->execute();                // execute() returns bool for INSERT
    }
}
?>
