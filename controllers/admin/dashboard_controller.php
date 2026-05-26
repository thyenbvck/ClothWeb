<?php
require_once('controllers/admin/base_controller.php');
require_once('models/Payment.php');
require_once('models/User.php');
require_once('models/Product.php');
class DashboardController extends BaseController
{
  function __construct()
  {
    $this->folder = 'home';
  }
  public function index()
  {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    $data = array();
    $totalUser = User::countAllUser();
    $totalProduct = Product::countAllProducts();
    $totalPayment = Payment::countAllPayments();
    $revenueOf6Months = Payment::getMonthlyRevenueOfLast6Months();
    $payments = Payment::getAllPayments();
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
    $data = array(
      'orders' => $orders,
      'totalUser' => $totalUser,
      'totalProduct' => $totalProduct,
      'totalOrder' => $totalPayment['orders'],
      'revenue' => $totalPayment['totals'],
      'revenueOf6Months' => $revenueOf6Months
    );
    $this->render('index', $data);
  }
}
?>