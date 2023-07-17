<?php	
session_start();

include '../config/database.php';
require_once('../classes/dbquery.php');
#require_once('../classes/class.upload.php');
require_once('../classes/preheader.php');
require_once('../classes/class.chart_order.php');

$dbcon = new DBconnect();
$data = new getData();
$mydata = new chart_order();

if(isset($_REQUEST['action'])) {

    switch($_REQUEST['action']){

        case 'getOrder_Year':

            $year = " WHERE YEAR(create_date) = '2019' ";
            if(!empty($_POST['year'])){
                $year = " WHERE YEAR(create_date) = '".$_POST['year']."'  ";
            }

            $table = !empty($_POST['type'])?" order_hospital ":" order_general ";

            $sql = "SELECT SUM(p_qty) as qty,
                            SUM(p_price) as price,
                            MONTH(create_date) as month,
                            YEAR(create_date) as year
                    FROM ".$table."
                    ".$year."
                    GROUP BY MONTH(create_date)
                    ";
            $value = array();
            $result = $dbcon->fetchAll($sql,$value);
            // print_r($result); exit();


            $Total = $dbcon->fetchObject("SELECT SUM(p_price) as price , SUM(p_qty) as qty FROM ".$table." ".$year,[]);

            if(empty($result)){
                echo json_encode([
                    'Message' => 'OK',
                    'Detail'  => 'empty'
                ]); exit();
            }

            $price = array(
                '1' => 0,
                '2' => 0,
                '3' => 0,
                '4' => 0,
                '5' => 0,
                '6' => 0,
                '7' => 0,
                '8' => 0,
                '9' => 0,
                '10' => 0,
                '11' => 0,
                '12' => 0,
            );
            $qty = array(
                '1' => 0,
                '2' => 0,
                '3' => 0,
                '4' => 0,
                '5' => 0,
                '6' => 0,
                '7' => 0,
                '8' => 0,
                '9' => 0,
                '10' => 0,
                '11' => 0,
                '12' => 0,
            );

            $year = $result[0]['year'];

            foreach($result as $key => $res){
                $price[$res['month']] = $res['price'];
                $qty[$res['month']] = $res['qty'];
            }

            echo json_encode([
                'Message' => 'OK',
                'Year'  => $year,
                'Price' => array_values($price),
                'Qty' => array_values($qty),
                'PriceTotal' => number_format($Total->price),
                'QtyTotal' => number_format($Total->qty),
            ]);

        break;

        case 'getOrder_ALL_By_member_id':

            $date = "";
            if(!empty($_POST['start'])){
                $start = $_POST['start']."-00";
                $end = $_POST['end']."-00";
                $date .= " AND MONTH(create_date) >= MONTH('".$start."')  AND MONTH(create_date) <= MONTH('".$end."') ";
            }

            $table = !empty($_POST['type'])?"order_hospital":"order_general";

            $sql = "SELECT SUM(p_price) as price , SUM(p_qty) as qty
                    FROM ".$table." WHERE member_id =:member_id ".$date;

            $value = array(":member_id" => $_POST['memberID']);
            $priceTotal = $dbcon->fetchObject($sql,$value);
            // print_r($priceTotal);
            // echo "\n";

            $sql = "SELECT post.title as name,post.saleprice as price , SUM(p_price) as priceSum  , SUM(p_qty) as qty , YEAR(create_date) as year
                    FROM ".$table." INNER JOIN post ON post.id = p_id WHERE member_id =:member_id ".$date." GROUP BY p_id ";

                    
            $value = array(":member_id" => $_POST['memberID']);
            $product = $dbcon->fetchAll($sql,$value);
            // print_r($product);

            $name = array();
            $price = array();
            $priceSum = array();
            $qty = array();
            $count = 0;
            foreach($product as $key => $pd){
                array_push($name,$pd['name']);
                array_push($price,$pd['price']);
                array_push($priceSum,$pd['priceSum']);
                array_push($qty,$pd['qty']);
                $count++;
            }

            echo json_encode([
                'Message' => 'OK',
                'Year'  => $product[0]['year'],
                'Name'  => $name,
                'Price'  => $price,
                'PriceSum'  => $priceSum,
                'PriceTotal' => number_format($priceTotal->price),
                'qty'  => $qty,
                'QtyTotal'  => number_format($priceTotal->qty),
                'Count' => $count
            ]);
        break;

        case 'getProductBestSale_price_10ByYear':
            $year = " WHERE YEAR(create_date) = '2018' ";
            if(!empty($_POST['year'])){
                $year = " WHERE YEAR(create_date) = '".$_POST['year']."'  ";
            }

            $sql = "SELECT SUM(p_price) as price , p_id , title
                    FROM order_hospital
                    INNER JOIN post ON post.id = p_id 
                    ".$year." 
                    GROUP BY p_id 
                    ORDER BY SUM(p_price) DESC
                    LIMIT 10
                    ";
            $value = array();
            $resultH = $dbcon->fetchAll($sql,$value);
            // print_r($resultH); 
            // exit();

            $sql2 = "SELECT SUM(p_price) as price , p_id , title
                    FROM order_general
                    INNER JOIN post ON post.id = p_id 
                    ".$year." 
                    GROUP BY p_id 
                    ORDER BY SUM(p_price) DESC
                    LIMIT 10
                    ";
            $value2 = array();
            $resultG = $dbcon->fetchAll($sql2,$value2);
            // print_r($resultG); 
            // exit();
            

            // if(empty($result)){
            //     echo json_encode([
            //         'Message' => 'Error',
            //         'Detail'  => 'Empty'
            //     ]); exit();
            // }

            $price = array();
            $name = array();

            foreach($resultH as $key => $resh){
                $price[$resh['p_id']] = $resh['price'];
                $name[$resh['p_id']] = $resh['title'];
            }

            foreach($resultG as $key => $resg){
                $price[$resg['p_id']] = $price[$resg['p_id']] + $resg['price'];
                $name[$resg['p_id']] = $resg['title'];
            }
            // print_r($price);
            // print_r($name);
            echo json_encode([
                'Message' => 'OK',
                'Price' => array_values($price),
                'Name' => array_values($name),
                'Count' => count($name),
            ]);
        break;

        case 'getProductBestSale_qty_10ByYear':
            $year = " WHERE YEAR(create_date) = '2018' ";
            if(!empty($_POST['year'])){
                $year = " WHERE YEAR(create_date) = '".$_POST['year']."'  ";
            }

            $sql = "SELECT SUM(p_qty) as qty , p_id , title
                    FROM order_hospital
                    INNER JOIN post ON post.id = p_id 
                    ".$year." 
                    GROUP BY p_id 
                    ORDER BY SUM(p_qty) DESC
                    LIMIT 10
                    ";
            $value = array();
            $resultH = $dbcon->fetchAll($sql,$value);
            // print_r($resultH); 
            // exit();

            $sql2 = "SELECT SUM(p_qty) as qty , p_id , title
                    FROM order_general
                    INNER JOIN post ON post.id = p_id 
                    ".$year." 
                    GROUP BY p_id 
                    ORDER BY SUM(p_qty) DESC
                    LIMIT 10
                    ";
            $value2 = array();
            $resultG = $dbcon->fetchAll($sql2,$value2);
            // print_r($resultG); 
            // exit();
            

            // if(empty($result)){
            //     echo json_encode([
            //         'Message' => 'Error',
            //         'Detail'  => 'Empty'
            //     ]); exit();
            // }

            $qty = array();
            $name = array();

            foreach($resultH as $key => $resh){
                $qty[$resh['p_id']] = $resh['qty'];
                $name[$resh['p_id']] = $resh['title'];
            }

            foreach($resultG as $key => $resg){
                $qty[$resg['p_id']] = $qty[$resg['p_id']] + $resg['qty'];
                $name[$resg['p_id']] = $resg['title'];
            }
            // print_r($price);
            // print_r($name);
            echo json_encode([
                'Message' => 'OK',
                'Qty' => array_values($qty),
                'Name' => array_values($name),
                'Count' => count($name),
            ]);
        break;

        case 'getOrder_By_productID':

            $year = " WHERE YEAR(create_date) = '2019' ";
            if(!empty($_POST['year'])){
                $year = " WHERE YEAR(create_date) = '".$_POST['year']."'  ";
            }

            $p_id = $_REQUEST['pid'];

            $sql1 = "SELECT SUM(p_qty) as qty,
                            SUM(p_price) as price,
                            MONTH(create_date) as month,
                            YEAR(create_date) as year
                    FROM order_general
                    ".$year." AND p_id =:p_id
                    GROUP BY MONTH(create_date)
                    ";
            $value = array(":p_id" => $p_id);
            $resultG = $dbcon->fetchAll($sql1,$value);
            // print_r($resultG); 

            $sql2 = "SELECT SUM(p_qty) as qty,
                            SUM(p_price) as price,
                            MONTH(create_date) as month,
                            YEAR(create_date) as year
                    FROM order_general
                    ".$year." AND p_id = :p_id
                    GROUP BY MONTH(create_date)
                    ";
            $value = array(":p_id" => $p_id);
            $resultH = $dbcon->fetchAll($sql2,$value);
            // print_r($resultH); 



            

            // $Total = $dbcon->fetchObject("SELECT SUM(p_price) as price , SUM(p_qty) as qty FROM ".$table." ".$year,[]);

            if(empty($resultG)){
                echo json_encode([
                    'Message' => 'OK',
                    'Detail'  => 'empty'
                ]); exit();
            }

            $price = array(
                '1' => 0,
                '2' => 0,
                '3' => 0,
                '4' => 0,
                '5' => 0,
                '6' => 0,
                '7' => 0,
                '8' => 0,
                '9' => 0,
                '10' => 0,
                '11' => 0,
                '12' => 0,
            );
            $qty = array(
                '1' => 0,
                '2' => 0,
                '3' => 0,
                '4' => 0,
                '5' => 0,
                '6' => 0,
                '7' => 0,
                '8' => 0,
                '9' => 0,
                '10' => 0,
                '11' => 0,
                '12' => 0,
            );


            foreach($resultG as $key => $res){
                $price[$res['month']] = $res['price'];
                $qty[$res['month']] = $res['qty'];
            }

            foreach($resultH as $key => $res){
                $price[$res['month']] = $price[$res['month']] + $res['price'];
                $qty[$res['month']]   = $qty[$res['month']] + $res['qty'];
            }

            echo json_encode([
                'Message' => 'OK',
                'Price' => array_values($price),
                'Qty' => array_values($qty),
                
            ]);

        break;

        case 'BestSaleOfMonth_price':

            $sql = "SELECT SUM(p_price) as price , p_id , post.title , create_date
                    FROM order_hospital
                    INNER JOIN post ON post.id = p_id 
                    WHERE create_date like '".$_POST['month']."%' 
                    GROUP BY p_id 
                    ORDER BY SUM(p_price) DESC
                    LIMIT 10
                    "; 
            $value = array();
            $resultH = $dbcon->fetchAll($sql,$value);
            // print_r($resultH); 
            // exit();

            $sql2 = "SELECT SUM(p_price) as price , p_id , title , create_date
                    FROM order_general
                    INNER JOIN post ON post.id = p_id 
                    WHERE create_date like '".$_POST['month']."%' 
                    GROUP BY p_id 
                    ORDER BY SUM(p_price) DESC
                    LIMIT 10
                    ";
            $value2 = array();
            $resultG = $dbcon->fetchAll($sql2,$value2);
            // print_r($resultG); 
            // exit();
            

            // if(empty($result)){
            //     echo json_encode([
            //         'Message' => 'Error',
            //         'Detail'  => 'Empty'
            //     ]); exit();
            // }

            $price = array();
            $name = array();

            foreach($resultH as $key => $resh){
                $price[$resh['p_id']] = $resh['price'];
                $name[$resh['p_id']] = $resh['title'];
            }
           
            foreach($resultG as $key => $resg){
                $price[$resg['p_id']] = $price[$resg['p_id']] + $resg['price'];
                $name[$resg['p_id']] = $resg['title'];
            }
            
            arsort($price);
            
            $nameNew = array();
            $i=1;
            foreach($price as $key => $p){
                if($i <= 10){
                    // array_push($nameNew,$name[$key]);
                    $nameNew[$key] = $name[$key];
                }
                $i++;
            }

            $priceNew = array();
            $i=1;
            foreach($price as $key => $p){
                if($i <= 10){
                    $priceNew[$key] = $p;
                }
                $i++;
            }

            // print_r($name);
            // print_r($nameNew);
            // print_r($priceNew);
            // exit();
            echo json_encode([
                'Message' => 'OK',
                'Price' => array_values($priceNew),
                'Name' => array_values($nameNew),
                'Count' => count($nameNew),
            ]);
        break;

        case 'BestSaleOfMonth_qty':

            $sql = "SELECT SUM(p_qty) as qty , p_id , post.title , create_date
                    FROM order_hospital
                    INNER JOIN post ON post.id = p_id 
                    WHERE create_date like '".$_POST['month']."%' 
                    GROUP BY p_id 
                    ORDER BY SUM(p_qty) DESC
                    LIMIT 10
                    "; 
            $value = array();
            $resultH = $dbcon->fetchAll($sql,$value);
            // print_r($resultH); 
            // exit();

            $sql2 = "SELECT SUM(p_qty) as qty , p_id , title , create_date
                    FROM order_general
                    INNER JOIN post ON post.id = p_id 
                    WHERE create_date like '".$_POST['month']."%' 
                    GROUP BY p_id 
                    ORDER BY SUM(p_qty) DESC
                    LIMIT 10
                    ";
            $value2 = array();
            $resultG = $dbcon->fetchAll($sql2,$value2);
            // print_r($resultG); 
            // exit();
            

            // if(empty($result)){
            //     echo json_encode([
            //         'Message' => 'Error',
            //         'Detail'  => 'Empty'
            //     ]); exit();
            // }

            $qty = array();
            $name = array();

            foreach($resultH as $key => $resh){
                $qty[$resh['p_id']] = $resh['qty'];
                $name[$resh['p_id']] = $resh['title'];
            }
           
            foreach($resultG as $key => $resg){
                $qty[$resg['p_id']] = $qty[$resg['p_id']] + $resg['qty'];
                $name[$resg['p_id']] = $resg['title'];
            }
            
            arsort($qty);
            
            $nameNew = array();
            $i=1;
            foreach($qty as $key => $p){
                if($i <= 10){
                    // array_push($nameNew,$name[$key]);
                    $nameNew[$key] = $name[$key];
                }
                $i++;
            }

            $qtyNew = array();
            $i=1;
            foreach($qty as $key => $p){
                if($i <= 10){
                    $qtyNew[$key] = $p;
                }
                $i++;
            }

            // print_r($name);
            // print_r($nameNew);
            // print_r($qtyNew);
            // exit();
            echo json_encode([
                'Message' => 'OK',
                'Qty' => array_values($qtyNew),
                'Name' => array_values($nameNew),
                'Count' => count($nameNew),
            ]);
        break;
	}
}
