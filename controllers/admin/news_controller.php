<?php
require_once('controllers/admin/base_controller.php');
require_once('models/News.php');
class NewsController extends BaseController
{
    function __construct()
    {
        $this->folder = 'news';
    }
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $newslist = News::getAll();
        $data = array('newses' => $newslist);
        $this->render('index', $data);
    }
    public function add()
    {
        $this->render('add');
    }
    public function edit()
    {
        $this->render('edit');
    }
    public function delete()
    {
        $this->render('delete');
    }
}
?>