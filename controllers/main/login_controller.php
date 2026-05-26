<?php
require_once('controllers/main/base_controller.php');
require_once('models/User.php');
class LoginController extends BaseController
{
	function __construct()
	{
		$this->folder = 'login';
	}

	public function index()
	{
		if (session_status() === PHP_SESSION_NONE) { session_start(); }
		if (!isset($_SESSION['username']) &&  !isset($_SESSION['role_id']) && isset($_COOKIE['username']) && isset($_COOKIE['role_id'])){
			echo "string";
			// Tự động đăng nhập
			$_SESSION['username'] = $_COOKIE['username'];
			$_SESSION['role_id'] = $_COOKIE['role_id'];
			if ($_SESSION['role_id'] == 1){
				header('Location: index.php?page=admin&controller=dashboard&action=index');
			}else{
				header('Location: index.php?page=main&controller=home&action=index');
			}
		}else if (isset($_POST['signin'])){
			
			$username = $_POST['your_username'];
			$password = $_POST['your_pass'];
			if(isset($_POST['remember-me'])){
				$remember = $_POST['remember-me'];
			}
			//unset($_POST);
			$check = User::validation($username, $password);

			if ($check['check'] == 1)
			{
				// lưu session
				$_SESSION['username'] = $check['username'];
				$_SESSION['role_id'] = $check['role_id'];



				// set cookie
				if (isset($remember)){
					setcookie('username', $check['username'], time() + 60, '/');
					setcookie('role_id', $check['role_id'], time() + 60, '/');
				}
				if ($check['role_id'] == 1){
					header('Location: index.php?page=admin&controller=dashboard&action=index');
				}else{
					header('Location: index.php?page=main&controller=home&action=index');
				}
			}
			else 
			{
				$err = "Sai tài khoản hoặc mật khẩu";
				$data = array('err' => $err);
				$this->render('index', $data);
			}
		}else{
			$this->render('index');
		}
	}

	public function logout()
	{
		if (session_status() === PHP_SESSION_NONE) { session_start(); }
		unset($_SESSION['username']);
		unset($_SESSION['role_id']);
		session_destroy();
		header("Location: index.php?page=main&controller=home&action=index");
	}
}
