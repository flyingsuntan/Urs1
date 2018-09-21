<?php
//require 'ProductModel.class.php';
//require 'ModelFactory.class.php';
//include "BaseController.class.php";
class ProductController  extends BaseController {
    function ShowALlProductAction(){
        $obj = ModelFactory::M('ProductModel');
        $date = $obj->GetAllProduct();
        include VIEW_PATH . "Product_list.html";
    }
    function DetailAction(){
    }
    function DelAction(){
        $id = $_GET['id'];
        $obj = ModelFactory::M('ProductModel');
        $date = $obj->delProductById($id);
        //echo "删除成功";
        //echo "<a href='?'>返回</a>";
        $this->GotoUrl('删除成功','?',3);
    }
    function ShowProductFormAction(){
        include VIEW_PATH . "addProduct.html";
    }
    function AddProductAction(){
        $pro_name = $_POST["pro_name"];
        $protype_id = $_POST["protype_id"];
        $price = $_POST["price"];
        $pinpai = $_POST["pinpai"];
        $chandi = $_POST["chandi"];
        $obj = ModelFactory::M('ProductModel');
        $result = $obj->AddProductAction($pro_name, $protype_id, $price, $pinpai, $chandi);
        echo "添加数据成功";
        echo "<a href='?'>返回</a>";
    }
    function IndexAction(){
        $obj = ModelFactory::M('ProductModel');
        $date = $obj->GetAllProduct();
        include VIEW_PATH . "Product_list.html";
    }
}
//$ctr1 = new ProductController();
//$act = !empty($_GET['a']) ? $_GET['a'] : "Index";
//$action = $act."Action";
//$ctr1->$action();
?>