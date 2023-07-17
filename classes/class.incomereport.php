<?php
class incomereport  
{
    private $dbcon;
    private $language_available;
    private $site_url = ROOT_URL;

    public function __construct()
    {
        $this->dbcon = new DBconnect();
        $this->language_available = getData::get_language_array();
    }
    
  

    public function calc_income(){
        #ข้อมูลรายได้ของเว็บไซต์
        $sql ="SELECT * FROM income_site ";
        $result['income'] = $this->dbcon->query($sql); 
        #จำนวนรายการสินค้า
        $sql = 'SELECT count(*) as p_numb FROM product WHERE status = 6 OR status = 7';
        $rows['products'] = $this->dbcon->query($sql); 
        #จำนวนรายการสมาชิก
        $sql = 'SELECT count(*) as m_numb FROM members_log'; 
        $rows['members'] = $this->dbcon->query($sql); 
        $p_numb = $rows['products'][0]['p_numb'];
        $m_numb = $rows['members'][0]['m_numb'];   
        #ถ้าจำนวนสินค้า หรือ สมาชิกมีการเปลี่ยนแปลง ทำการคำนวณใหม่เพื่อเก็บค่า 
        if($p_numb != $result['income'][0]['inc_count_product'] || $m_numb != $result['income'][0]['inc_count_member']){
            $netpay = $result['income'][0]['inc_netpay']; 
            #คำนวณรายได้จากสินค้าทั้งหมด
            $sql ="SELECT * FROM product WHERE  status = 6 OR status = 7    ";  
            $products = $this->dbcon->query($sql); 
            if(!empty($products)){  
                #ค่าธรรมเนียมคิดเป็น % ของ price_special ดูเงื่อนไขได้ที่ function calculate_netpay
                $p_netpay['post'] = 0; #ผู้ขายเสียค่าธรรมเนียมเต็มจำนวน
                $p_netpay['buy'] = 0; #ผู้ซื้อเสียค่าธรรมเนียมครึ่งหนึ่งของผู้ขาย
                foreach($products as $key => $val){  
                    $price = $this->calculate_netpay((float)$val['price_special']);  
                    $p_netpay['post'] += ($price);
                    $p_netpay['buy'] += ($price / 2 );  
                }
            } 
            $p_post_netpay = (float)$p_netpay['post'];
            $p_buy_netpay =  (float)$p_netpay['buy']; 
            
            #คำนวณรายได้จากข้อมูลของสมาชิกทั้งหมด
            $sql = "SELECT ml.log_activate_paid as paid FROM members as m 
                    INNER JOIN members_log as ml  ON m.mem_id = ml.log_member_id  
                    ORDER BY   ml.log_member_id DESC, ml.log_year ASC     ";
            $members = $this->dbcon->query($sql);
            if(!empty($members)){
                $m_netpay = 0;
                foreach($members as $key => $val){  
                    $m_netpay += (float)$val['paid']; 
                } 
                $m_netpay =  (float)$m_netpay; 
            }   
        
            #อัพเดทลงฐานข้อมูลเว็บไซต์
            $netpay = $p_post_netpay + $p_buy_netpay + $m_netpay; 
            $table = "income_site";
            $set = " inc_count_product = :cproduct
                    ,inc_count_member = :cmember
                    ,inc_date_update = :date
                    ,inc_netpay = :netpay
                    ,inc_post_paid_total = :post
                    ,inc_buy_paid_total = :buy
                    ,inc_register_total = :regist
                ";
            $where = "inc_id != 99999";
            $value = array( 
                ':cproduct' => $p_numb,
                ':cmember' =>  $m_numb,
                ':date' => date("Y-m-d H:i:s"),
                ':netpay' => $netpay,
                ':post' =>  $p_post_netpay,
                ':buy' =>  $p_buy_netpay,
                ':regist'=> $m_netpay 
            );
             
            $updates = $this->dbcon->update_prepare($table, $set, $where, $value);
 
        }  
 
        $sql = 'SELECT * FROM income_site ORDER BY inc_id ASC LIMIT 0,1  ';
        $result = $this->dbcon->query($sql); 
        return $result[0]; 
    }

    public function get_income_website(){
        $sql ="SELECT * FROM income_config ORDER BY id ASC  LIMIT 0,1";
        $result= $this->dbcon->query($sql);
        return $result[0];
    }

    public  function calculate_netpay($price){
        $c = ($type == "store")?1:2; //ถ้าเป็น ร้านค่าจะหารด้วย 1 ถ้าเป็น คนที่ซื้อจะหารด้วย2
        if($price <= 30000 ){
          $a = (($price / 100) * 1);
          return ($a < 10)?10:$a;
        }else if($price <= 100000){
          return (($price / 100) * 0.75);
        }else if($price <= 500000){
          return (($price / 100) * 0.50);
        }else{
          return (($price / 100) * 0.25);
        }
    }
 

    #คำนวณรายได้จาก ajax แบบเรียลไทม์
    public function ajax_calc_income_report($getpost){
        #สิ่งที่ต้องการสำหรับการคำนวณมี 2 ส่วนคือ วันที่เริ่มต้น จนถึง วันที่สิ้นสุด  
        #date format = "yyyy-mm-dd" 
        $start = $getpost['date_start'];
        $expire = $getpost['date_expire'];

        #คำนวณรายได้จากข้อมูลของสมาชิกทั้งหมด
        $sql ="SELECT * FROM product WHERE ( status = 6 OR status = 7  ) AND (date_update between '".$start."' AND '".$expire."' ) ";  
        $products = $this->dbcon->query($sql); 
        if(!empty($products)){  
            #ค่าธรรมเนียมคิดเป็น % ของ price_special ดูเงื่อนไขได้ที่ function calculate_netpay
            $netay_p['post'] = 0; #ผู้ขายเสียค่าธรรมเนียมเต็มจำนวน
            $netay_p['buy'] = 0; #ผู้ซื้อเสียค่าธรรมเนียมครึ่งหนึ่งของผู้ขาย
            foreach($products as $key => $val){  
                $price = $this->calculate_netpay((float)$val['price_special']);  
                $netay_p['post'] += ($price);
                $netay_p['buy'] += ($price / 2 );  
            }
        } 
        $p_post_netpay = (float)$netay_p['post'];
        $p_buy_netpay =  (float)$netay_p['buy']; 
        
        #คำนวณรายได้จากข้อมูลของสมาชิกทั้งหมด
        $sql = "SELECT ml.log_activate_paid as paid FROM members as m 
                INNER JOIN members_log as ml  ON m.mem_id = ml.log_member_id  
                WHERE  (ml.log_activate_date between '".$start."' AND '".$expire."' ) 
                ORDER BY   ml.log_member_id DESC, ml.log_year ASC     ";
        $members = $this->dbcon->query($sql);
        if(!empty($members)){
            $netpay_member = 0;
            foreach($members as $key => $val){  
                $netpay_member += (float)$val['paid']; 
            } 
            $netpay_member =  (float)$netpay_member; 
        }    
        $netpay = $p_post_netpay + $p_buy_netpay + $netpay_member; 
       
        #สิ่งที่ต้องการ
        $ret = array();
        $ret['register_total'] =  number_format( $netpay_member );
        $ret['post_total'] = number_format( $p_post_netpay ); 
        $ret['buy_total'] = number_format( $p_buy_netpay );  
        $ret['netpay'] = number_format( $netpay );   
        
        return $ret;

    }



}
