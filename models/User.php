<?php
require_once('connection.php');
require_once('models/Cart.php');
class User {
    private $user_id;
    private $username;
    private $password;
    private $email;
    private $full_name;
    private $avatar_url;
    private $phone;
    private $address;
    private $status;
    private $role_id;
    private $created_at;
    private $updated_at;

    public function __construct($username, $password, $email, $full_name, $avatar_url, $phone, $address, $status, $role_id) {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->full_name = $full_name;
        $this->avatar_url = $avatar_url;
        $this->phone = $phone;
        $this->address = $address;
        $this->status = $status;
        $this->role_id = $role_id;
    }

    // Getter and Setter for user_id
    public function getUserId() {
        return $this->user_id;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    // Getter and Setter for username
    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    // Getter and Setter for password
    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    // Getter and Setter for email
    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    // Getter and Setter for full_name
    public function getFullName() {
        return $this->full_name;
    }

    public function setFullName($full_name) {
        $this->full_name = $full_name;
    }

    // Getter and Setter for avatar_url
    public function getAvatarUrl() {
        return $this->avatar_url;
    }

    public function setAvatarUrl($avatar_url) {
        $this->avatar_url = $avatar_url;
    }

    // Getter and Setter for phone
    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    // Getter and Setter for address
    public function getAddress() {
        return $this->address;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    // Getter and Setter for status
    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    // Getter and Setter for created_at
    public function getCreatedAt() {
        return $this->created_at;
    }

    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }

    // Getter and Setter for updated_at
    public function getUpdatedAt() {
        return $this->updated_at;
    }

    public function setUpdatedAt($updated_at) {
        $this->updated_at = $updated_at;
    }

    // Getter and Setter for role_id
    public function getRoleId() {
        return $this->role_id;
    }

    public function setRoleId($role_id) {
        $this->role_id = $role_id;
    }

    // toString
    public function __toString() {
        return "User: [user_id: {$this->user_id}, username: {$this->username}, password: {$this->password}, email: {$this->email}, full_name: {$this->full_name}, avatar_url: {$this->avatar_url}, phone: {$this->phone}, address: {$this->address}, status: {$this->status}, role_id: {$this->role_id}, created_at: {$this->created_at}, updated_at: {$this->updated_at}]";
    }

    // get all users
    public static function getAllUser() {
        $db = DB::getInstance();
        $sql = "SELECT * FROM users";
        $result = $db->query($sql);
        $users = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $user = new User($row['username'], $row['password'], $row['email'], $row['full_name'], $row['avatar_url'], $row['phone'], $row['address'], $row['status'], $row['role_id']);
                $user->user_id = $row['user_id'];
                $user->created_at = $row['created_at'];
                $user->updated_at = $row['updated_at'];
                $users[] = $user;
            }
        }
        return $users;
    }

    // get user by id
    public static function getUserById($user_id) {
        $db = DB::getInstance();
        $sql = "SELECT * FROM users WHERE user_id = $user_id";
        $result = $db->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user = new User($row['username'], $row['password'], $row['email'], $row['full_name'], $row['avatar_url'], $row['phone'], $row['address'], $row['status'], $row['role_id']);
            $user->user_id = $row['user_id'];
            $user->created_at = $row['created_at'];
            $user->updated_at = $row['updated_at'];
            return $user;
        }
        return null;
    }

    // get user by username
    public static function getUserByUsername($username) {
        $db = DB::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user = new User($row['username'], $row['password'], $row['email'], $row['full_name'], $row['avatar_url'], $row['phone'], $row['address'], $row['status'], $row['role_id']);
            $user->user_id = $row['user_id'];
            $user->created_at = $row['created_at'];
            $user->updated_at = $row['updated_at'];
            return $user;
        }
        return null;
    }

    // add user
    public static function addUser($user) {
        echo "add user";
        $password = password_hash($password, PASSWORD_BCRYPT);
        $db = DB::getInstance();
        if ($avatar_url == "") {
            // hard code default avatar
            $avatar_url = "https://firebasestorage.googleapis.com/v0/b/fir-42a90.appspot.com/o/avatar-people-user-svgrepo-com.svg?alt=media&token=d19e3ab3-4ff0-4088-a0b8-d2d7bfa6c54d";
        } 
        $stmt = $db->prepare(
            "INSERT INTO users (username, password, email, full_name, avatar_url, phone, address, role_id, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        if ($stmt === false) {
            die('MySQL prepare failed: ' . $db->error);
        }
        $stmt->bind_param('sssssssis', $user->username, $user->password, $user->email, $user->full_name, $user->avatar_url, $user->phone, $user->address, $user->role_id, "active");
        $result = $stmt->execute();
        if ($result) {
            echo "add user success";
            return true;
        } else {
            echo "add user failed";
            return false;
        }
    }

    static function insert($username, $password, $email, $full_name, $avatar_url, $phone, $address, $role_id, $status)
    {
        $password = password_hash($password, PASSWORD_BCRYPT);
        $db = DB::getInstance();
        if ($avatar_url == "") {
            // hard code default avatar
            $avatar_url = "https://firebasestorage.googleapis.com/v0/b/fir-42a90.appspot.com/o/avatar-people-user-svgrepo-com.svg?alt=media&token=d19e3ab3-4ff0-4088-a0b8-d2d7bfa6c54d";
        } 
        $stmt = $db->prepare(
            "INSERT INTO users (username, password, email, full_name, avatar_url, phone, address, role_id, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        if ($stmt === false) {
            die('MySQL prepare failed: ' . $db->error);
        }
        $stmt->bind_param('sssssssis', $username, $password, $email, $full_name, $avatar_url, $phone, $address, $role_id, $status);
        $result = $stmt->execute();
        if ($result) {
            return true;
            Cart::createCart($username);
        } else {
            return false;
        }
    }
    static function validation($username, $password)
    {
        $db = DB::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $req = $stmt->get_result();
        if($result = $req -> fetch_assoc()) {
            if (1)
                return ['check'=> 1, 'user_id' => $result['user_id'], 'username'=>$result['username'], 'role_id' => $result['role_id']];
        }
        return ['check'=> 0];
    }

    // update status
    public static function updateStatus($user_id, $status) {
        $db = DB::getInstance();
        $sql = "UPDATE users SET status = '$status' WHERE user_id = '$user_id'";
        $result = $db->query($sql);
        return $result;
    }

    // delete user
    public static function deleteUser($user_id) {
        $db = DB::getInstance();
        $sql = "DELETE FROM users WHERE user_id = '$user_id'";
        $result = $db->query($sql);
        return $result;
    }

    // count all users
    public static function countAllUser() {
        $db = DB::getInstance();
        $sql = "SELECT COUNT(*) as total FROM users";
        $result = $db->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['total'];
        }
        return 0;
    }

    // update user
    public static function updateUser($user) {
        $db = DB::getInstance();
        $stmt = $db->prepare(
            "UPDATE users SET username = ?, email = ?, full_name = ?, phone = ?, address = ?, role_id = ? WHERE user_id = ?"
        );
        $stmt->bind_param('ssssssi', $user->getUsername(), $user->getEmail(), $user->getFullName(), $user->getPhone(), $user->getAddress(), $user->getRoleId(), $user->getUserId());
        $result = $stmt->execute();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
?>
