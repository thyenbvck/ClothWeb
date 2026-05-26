<?php
require_once('controllers/main/base_controller.php');
require_once('models/News.php');
require_once('models/Comment.php');
require_once('models/Product.php');
require_once('models/Cart.php');
require_once('models/CartItem.php');

class Detail_blogController extends BaseController
{
	function __construct()
	{
		$this->folder = 'detail_blog';
	}

	public function index()
	{
		if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $news_id = intval($_GET['id']);
        $news = News::getNewsById($news_id);
        $comments = Comment::getCommentsByNewsId($news_id);
		$salesNews = News::getNewsByTopic('sales');
        $data = array('news' => $news, 'comments' => $comments, 'salesNews' => $salesNews);
        $this->render('index', $data);
	}

	// public function reply()
	// {
	// 	$content = $_POST['content'];
	// 	$news_id = $_POST['news_id'];
	// 	$user_id = $_POST['user_id'];

	// 	$req = Comment::reply($content, $news_id, $user_id);
	// 	echo 'success';
	// 	exit();
	// }

	public function comment()
	{
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        if(!isset($_SESSION['username'])){
            Header('Location: index.php?page=main&controller=login&action=index');
            exit();
        }
        $content = $_POST['content'];
		$news_id = intval($_POST['news_id']);
        $username = $_SESSION['username'];

        $req = Comment::insert($content, $news_id, $username);
		if($req){
            Header('Location: index.php?page=main&controller=detail_blog&action=index&id='.$news_id);
        }
        else{
            echo 'error';
        }
	}
}