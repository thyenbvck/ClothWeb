<?php
require_once('controllers/main/base_controller.php');
require_once("models/Product.php");
require_once("models/Category.php");
require_once('models/Cart.php');
require_once('models/CartItem.php');

class HomeController extends BaseController
{
	function __construct()
	{
		$this->folder = 'home';
	}

	public function index()
	{
		if (session_status() === PHP_SESSION_NONE) { session_start(); }
		$products = Product::getAll();
		$categories = Category::getAll();
		$data = array('products' => $products, 'categories' => $categories);
		//print_r($_SESSION);
		$this->render('index', $data);
	}
}

