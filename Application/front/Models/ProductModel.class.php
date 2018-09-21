<?php
class ProductModel extends BaseModel {
    function GetAllProduct(){
        $sql = "select product.*, protype_name from product inner join product_type as t on t.protype_id = product.protype_id";
        return $this->_dao->GetRows($sql);
    }
    function AddProductAction($pro_name, $protype_id, $price, $pinpai, $chandi){
        $sql = "insert into product(pro_name ,protype_id,price,pinpai,chandi) values ('$pro_name', '$protype_id', '$price', '$pinpai', '$chandi')";
        $date = $this->_dao->exec($sql);
        return $date;
    }
    function delProductById($id){
        $sql = "delete from product where pro_id = $id";
        $date = $this->_dao->exec($sql);
        return $date;
    }
}