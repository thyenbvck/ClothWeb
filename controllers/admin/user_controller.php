<?php
    require_once('controllers/admin/base_controller.php');
    require_once('models/User.php');
    require_once('models/Role.php');
    
    class UserController extends BaseController
    {
        function __construct()
        {
            $this->folder = 'user';
        }
        public function index()
        {
            if (session_status() === PHP_SESSION_NONE) { session_start(); }
            $roles = Role::getAllRoles();
            $users = User::getAllUser();
            $data = array('roles' => $roles, 'users' => $users);
            $this->render('index', $data);
        }
        public function add()
        {
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $username = $_POST['username'];
                $password = $_POST['password'];
                $email = $_POST['email'];
                $fullName = $_POST['fullName'];
                $phone = $_POST['phone'];
                $address = $_POST['address'];
                $roleId = $_POST['roleId'];
                $target_dir = "public/img/users/";
                $fileName = $_FILES['avatar']['name'];
                $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
                // genearte filename
                $fileId = $username . "." . $fileType;
                $target_url = $target_dir.basename($fileId);
                if (file_exists($target_url)) {
                    echo "Sorry, file already exists.";
                }
                // Check file type
                if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "gif" ) {
                        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                }
                if ($_FILES["avatar"]["size"] > 10485760) {
                    echo "Sorry, your file is too large.";
                }

                if(!move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_url)) {
                    echo "Sorry, there was an error uploading your file.";
                }
                
                // save user
                $result = User::insert($username, $password, $email, $fullName, $target_url, $phone, $address, $roleId,'active');
                if ($result){
                    $data = array('roles' => Role::getAllRoles(), 'users' => User::getAllUser());
                    $users = [];
                    foreach ($data['users'] as $user) {
                        $users[] = [
                            'userId' => $user->getUserId(),
                            'username' => $user->getUsername(),
                            'password' => $user->getPassword(),
                            'email' => $user->getEmail(),
                            'fullName' => $user->getFullName(),
                            'avatarUrl' => $user->getAvatarUrl(),
                            'phone' => $user->getPhone(),
                            'address' => $user->getAddress(),
                            'status' => $user->getStatus(),
                            'roleId' => $user->getRoleId(),
                            'createdAt' => $user->getCreatedAt(),
                            'updatedAt' => $user->getUpdatedAt()
                        ];
                    }
                    $roles = [];
                    foreach ($data['roles'] as $role) {
                        $roles[] = [
                            'roleId' => $role->getRoleId(),
                            'roleName' => $role->getRoleName()
                        ];
                    }
                    $data = array('roles' => $roles, 'users' => $users);
                    echo json_encode(array('status' => 200, 'message' => 'add user success', 'data' => $data));
                }else{
                    echo json_encode(array('status' => 500, 'message' => 'add user failed'));
                }
            }else{
                this->index();
            }
        }
        public function changeStatus()
        {
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $userId = intval($_POST['userId']);
                $status = $_POST['status'];
                $result = User::updateStatus($userId, $status);
                if($result){
                    $data = array('roles' => Role::getAllRoles(), 'users' => User::getAllUser());
                    $users = [];
                    foreach ($data['users'] as $user) {
                        $users[] = [
                            'userId' => $user->getUserId(),
                            'username' => $user->getUsername(),
                            'password' => $user->getPassword(),
                            'email' => $user->getEmail(),
                            'fullName' => $user->getFullName(),
                            'avatarUrl' => $user->getAvatarUrl(),
                            'phone' => $user->getPhone(),
                            'address' => $user->getAddress(),
                            'status' => $user->getStatus(),
                            'roleId' => $user->getRoleId(),
                            'createdAt' => $user->getCreatedAt(),
                            'updatedAt' => $user->getUpdatedAt()
                        ];
                    }
                    $roles = [];
                    foreach ($data['roles'] as $role) {
                        $roles[] = [
                            'roleId' => $role->getRoleId(),
                            'roleName' => $role->getRoleName()
                        ];
                    }
                    echo json_encode(array('status' => 200, 'message' => 'update status success', 'data' => ['users' => $users, 'roles' => $roles]));
                }else{
                    echo json_encode(array('status' => 500, 'message' => 'update status failed'));
                }
            }else{
                echo json_encode(array('status' => 400, 'message' => 'bad request'));
            }

        }

        public function edit(){
            if($_SERVER['REQUEST_METHOD']=='POST'){
                $userId = $_POST['userId'];
                $username = $_POST['username'];
                $email = $_POST['email'];
                $fullName = $_POST['fullName'];
                $phone = $_POST['phone'];
                $address = $_POST['address'];
                $roleId = $_POST['roleId'];

                // find user to update
                $user = User::getUserById($userId);
                // set value to user
                $user->setUsername($username);
                $user->setEmail($email);
                $user->setFullName($fullName);
                $user->setPhone($phone);
                $user->setAddress($address);
                $user->setRoleId($roleId);
                // update user
                $result = User::updateUser($user);
                if($result){
                    $data = array('users' => User::getAllUser());
                    $users = [];
                    foreach ($data['users'] as $user) {
                        $users[] = [
                            'userId' => $user->getUserId(),
                            'username' => $user->getUsername(),
                            'password' => $user->getPassword(),
                            'email' => $user->getEmail(),
                            'fullName' => $user->getFullName(),
                            'avatarUrl' => $user->getAvatarUrl(),
                            'phone' => $user->getPhone(),
                            'address' => $user->getAddress(),
                            'status' => $user->getStatus(),
                            'roleId' => $user->getRoleId(),
                            'createdAt' => $user->getCreatedAt(),
                            'updatedAt' => $user->getUpdatedAt()
                        ];
                    }
                    echo json_encode(array('status' => 200, 'message' => 'update user success', 'data' => $users));
                }
                else{
                    echo json_encode(array('status' => 500, 'message' => 'update user failed'));
                }
            }
        }
        public function delete()
        {
            if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['userId'])){
                $id = intval($_POST['userId']);
                $result = User::deleteUser($id);
                if($result){
                    $data = array('roles' => Role::getAllRoles(), 'users' => User::getAllUser());
                    $users = [];
                    foreach ($data['users'] as $user) {
                        $users[] = [
                            'userId' => $user->getUserId(),
                            'username' => $user->getUsername(),
                            'password' => $user->getPassword(),
                            'email' => $user->getEmail(),
                            'fullName' => $user->getFullName(),
                            'avatarUrl' => $user->getAvatarUrl(),
                            'phone' => $user->getPhone(),
                            'address' => $user->getAddress(),
                            'status' => $user->getStatus(),
                            'roleId' => $user->getRoleId(),
                            'createdAt' => $user->getCreatedAt(),
                            'updatedAt' => $user->getUpdatedAt()
                        ];
                    }
                    $roles = [];
                    foreach ($data['roles'] as $role) {
                        $roles[] = [
                            'roleId' => $role->getRoleId(),
                            'roleName' => $role->getRoleName()
                        ];
                    }
                    echo json_encode(array('status' => 200, 'message' => 'delete user success', 'data' => ['users' => $users, 'roles' => $roles]));
                }else{
                    echo json_encode(array('status' => 500, 'message' => 'delete user failed'));
                }
            }else{
                echo json_encode(array('status' => 400, 'message' => 'bad request'));
            }
        }
    }
?>