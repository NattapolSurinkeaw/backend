<?php
class purchaseOrderData 
{
    private static $dbcon;
    private $site_url = ROOT_URL;
    private static $language_available;
    public function __construct()
    {
        self::init();
    }
    public static function init()
    {
        self::$dbcon = new DBconnect();
    }
    
    public static function format_thai_date($date){
        $newDate = date_create($date);
        $format = date_format($newDate,"d-m-Y H:i:s");  
        return $format;
    }
    public function fomat_default_date($date){
        $newDate = date_create($date);
        $format = date_format($newDate,"Y-m-d H:i:s");  
        return $format;
    }

    public static function check_ber_soldout($id,$phone,$status){
        $sql = "SELECT m.status FROM berproduct_manage as m  
                INNER JOIN berproduct_order_list as o 
                ON o.order_id = m.order_id AND o.status = :status 
                WHERE m.order_id != :id AND m.product_phone = :phone ";
        $value = [
            ":id" => $id,
            ":phone" => $phone,
            ":status" => "publish"
        ];
        $result = self::$dbcon->fetchObject($sql,$value); 
        $status = (!empty($result))?"empty":$status;
        return $status;
    }
    public static function loop_check_method($ret){
        foreach($ret as $key =>$val){ 
            if($val['message'] != "OK"){
                $result['message'] = "error";
                $result['key'] = $key;
                break;
            } else {
                $result['message'] = "OK";
            }
        }
        return $result;
    }

  

    public static function update_product_status_by_orderid($order_id){
        $sql ="SELECT product_phone FROM berproduct_manage WHERE status = 'soldout' AND order_id = :id";
        $result = self::$dbcon->fetchAll($sql,[":id"=>$order_id]);
        if(!empty($result)){
            $idIn = "";
            foreach($result as $key => $val){ 
                $idIn .= ($idIn != "")?",".$val['product_phone']:$val['product_phone'];
            }
            $table = "berproduct"; 
            $set = "product_sold = :product_sold"; 
            $where = "product_phone IN (".$idIn.")";
            $value = array(
                ":product_sold" => 'yes' 
            );
            $display = self::$dbcon->update_prepare($table,$set,$where,$value);

            $where = "product_phone IN (".$idIn.")";
            $table = "berproduct_alover"; 
            $alover_sold = self::$dbcon->update_prepare($table,$set,$where,$value); 

            #ลดจำนวนหมวดหมู่
            // $cate_amount = self::update_category_amount($idIn);
        }
        return $display;
    }

    public function service_charge(){
        Global $service_charge,$below_price;
        $sql = "SELECT service_charge,below_price FROM contact_sel ";
        $result = self::$dbcon->fetchObject($sql,[]);
        $service_charge = $result->service_charge;
        $below_price = $result->below_price;
    }

    public static function update_category_amount($list){
        $sql = "SELECT product_category FROM berproduct WHERE product_phone IN (".$list.") ";
        $result = self::$dbcon->fetchAll($sql,[]);

        if(!empty($result)){
            foreach($result as $key =>$val){
               $cate_id[] = explode(",", $val['product_category']);
            } 
            $beforeArr = array();
            foreach($cate_id as $key =>$val){
                if($val != ""){
                    if(isset($beforeArr[$val])){
                         $beforeArr[$val]['amount'] += 1; 
                    }else{
                        $beforeArr[$val]['amount'] = 1;
                    }
                }
            }
            if(!empty($beforeArr)){
                foreach($beforeArr as $key => $val){ 
                    $table = "berproduct_category"; 
                    $set = " bercate_total = (bercate_total - :amount )"; 
                    $where = "bercate_id = :id";
                    $value = array(
                        ":id" => $key,
                        ":amount" =>  $val
                    );
                    $status = self::$dbcon->update_prepare($table,$set,$where,$value);
                }
            }
           
        }
        return $status;
    }



}
