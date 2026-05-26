<?php 
require_once("controllers/main/base_controller.php");
require_once('models/Product.php');
require_once('models/Cart.php');
require_once('models/CartItem.php');
class AboutController extends BaseController
{
    function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $this->folder = "about";
    }
    public function index()
    {
        $data = array();
        $this->render("index", $data);
    }   
}
?>