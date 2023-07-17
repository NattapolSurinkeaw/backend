<?php
    session_start();
    require_once dirname(__DIR__) . '/classes/class.protected_web.php';
    ProtectedWeb::methodPostOnly();
    ProtectedWeb::login_only();

    require_once dirname(__DIR__) . '/classes/dbquery.php';
    require_once dirname(__DIR__) . '/classes/preheader.php';
    $dbcon = new DBconnect();
    getData::init();

    $dbinstance = Database::getInstance();
    $dbconn = $dbinstance->DB();

    if (isset($_REQUEST['action'])) {

        $lang_config = getData::lang_config();

        switch ($_REQUEST['action']) {

            case 'get_order_general':

                $requestData = $_REQUEST;
                $columns = array(
                    0 => '',
                    1 => 'og.id',
                    2 => '',
                    3 => '',
                    4 => '',
                    5 => 'p_qty',
                    6 => 'p_price',
                );


                $sql = "SELECT og.id , og.order_id , og.member_id , m.member_name , SUM(og.p_qty) as p_qty , 
                            SUM(og.p_price) as p_price ,og.create_date , og.update_date , og.status
                    FROM order_general as og
                    INNER JOIN members as m ON m.member_id = og.member_id
                    ";

                    if(!empty($_POST['selectOrder'])){
                        if($_POST['selectOrder'] == "orderNew"){
                            $sqlOrderNew = " AND og.status = 0 ";
                        }else if($_POST['selectOrder'] == "productCrash"){
                            $sqlOrderCrash .= " AND og.order_id IN (SELECT order_id FROM order_crash GROUP BY order_id) ";
                        }
                    }

                if (!empty($requestData['search']['value'])) {
                    $sql .= " WHERE order_id LIKE '%" . $requestData['search']['value'] . "%' ";
                    $sql .= $sqlOrderNew;
                    $sql .= $sqlOrderCrash;
                    $sql .= " OR member_name LIKE '%" . $requestData['search']['value'] . "%' ";
                    $sql .= " GROUP BY order_id ";
                    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'];
                } else {
                    $sql .= "WHERE 1 = 1 ";
                    $sql .= $sqlOrderNew;
                    $sql .= $sqlOrderCrash;
                    $sql .= " GROUP BY og.order_id ";
                    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'];
                }


                $stmt = $dbcon->runQuery($sql);
                $stmt->execute();
                $totalData = $stmt->rowCount();
                $totalFiltered = $totalData;

                $sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
                $result = $dbcon->query($sql);

                $output = array();
                if ($result) {
                    foreach ($result as $key =>  $value) {

                        $sqlCrash = "SELECT * FROM order_crash WHERE o_id =:oid AND order_id =:order_id LIMIT 1";
                        $valCrash = array(':oid' => $value['id'], ':order_id' => $value['order_id']);
                        $resCrash = $dbcon->fetchObject($sqlCrash, $valCrash);

                        $nestedData = array();
                        $statusDerr = (($value['status'] == 0) ? "<i class=\"fa fa-cart-arrow-down\" style='font-size:2em;color:#00d1b2;display:block;text-align:center;' aria-hidden=\"true\"></i>" : "<!--<i class=\"fa fa-check\" aria-hidden=\"true\" style='color:steelblue;display:block;text-align:center;'></i>-->");
                        if ($resCrash) {
                            $statusDerr .= "<i class=\"fa fa-exclamation-circle\" style='font-size:2em;color:red;display:block;text-align:center;' aria-hidden=\"true\"></i>";
                        }

                        $nestedData[] = $statusDerr;
                        $nestedData[] = $key + 1;
                        $nestedData[] = $value['order_id'];
                        $nestedData[] = $value['member_id'];
                        $nestedData[] = $value['member_name'];
                        $nestedData[] = $value['p_qty'];
                        $nestedData[] = number_format($value['p_price']);
                        $nestedData[] = $value['create_date'];


                        $sql = "SELECT
                                (SELECT order_status FROM order_general 
                                    WHERE order_id =:order_id AND order_status = 0
                                    GROUP BY order_id) as a,
                                (SELECT order_status FROM order_general 
                                    WHERE order_id =:order_id AND order_status = 1
                                    GROUP BY order_id) as b,
                                (SELECT order_status FROM order_general 
                                    WHERE order_id =:order_id AND order_status = 2
                                    GROUP BY order_id) as c
                                ";
                        $v = array(
                            ':order_id' => $value['order_id']
                        );
                        $resOG = $dbcon->fetchObject($sql, $v);
                        if ($resOG->b > $resOG->a && $resOG->b > $resOG->c) {
                            $o_s = "<span class=\"badge\" style='font-size:1.1em;background:mediumseagreen'>สมบูรณ์ทั้งหมด</span>";
                        } else if ($resOG->c > $resOG->b && $resOG->c > $resOG->a) {
                            $o_s = "<span class=\"badge\" style='font-size:1.1em;background:red'>ยังมีสินค้าที่ไม่สมบูรณ์</span>";
                        } else if ($resOG->a > $resOG->b && $resOG->a > $resOG->c) {
                            $o_s = "<span class=\"badge\" style='font-size:1.1em;'>ยังไม่ได้เช็คสินค้า</span>";
                        }
                        $nestedData[] = $o_s;



                        // $nestedData[] = (($value['status'] == 0) ? "<i class=\"fa fa-times\" aria-hidden=\"true\" style='color:red;display:block;text-align:center;'>ยังไม่ได้ดู</i>" : "<i class=\"fa fa-check\" aria-hidden=\"true\" style='color:mediumseagreen;display:block;text-align:center;'></i>");

                        $nestedData[] = '
                            <a class="btn kt:btn-primary" onclick="showOrderGeneral(event,' . $value['order_id'] . ')" style="color:white;padding:5px;" ><i class="fa fa-eye" aria-hidden="true"></i> ดู</a>
                            <a class="btn kt:btn-warning" onclick="editOrderGeneral(event,' . $value['order_id'] . ')"  style="color:white;padding:5px;" ><i class="fa fa-pencil-square-o"></i> แก้ไข</a>
                            <a class="btn kt:btn-danger"  onclick="delOrderGeneral(event,' . $value['order_id'] . ')"  style="color:white;padding:5px;" ><i class="fa fa-trash-o" aria-hidden="true"></i> ลบ</a>
                            <a href="' . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/template/print.php?type=general&order_id=' . $value['order_id'] . '" target="_blank" class="btn kt:btn-info"  style="color:white;padding:5px;" ><i class="fa fa-print" aria-hidden="true"></i> พิมพ์</a>
                    ';


                        $output[] = $nestedData;
                    }
                }

                $json_data = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data" => $output,
                );
                echo json_encode($json_data);

                break;

            case 'get_order_hospital':

                $requestData = $_REQUEST;
                $columns = array(
                    0 => '',
                    1 => 'og.id',
                    2 => '',
                    3 => '',
                    4 => '',
                    5 => 'p_qty',
                    6 => 'p_price',
                );

                $sql = "SELECT og.id , og.order_id , og.member_id , m.member_name , SUM(og.p_qty) as p_qty , 
                            SUM(og.p_price) as p_price ,og.create_date , og.update_date , og.status 
                    FROM order_hospital as og
                    INNER JOIN members as m ON m.member_id = og.member_id
                    ";

                if(!empty($_POST['selectOrder'])){
                    if($_POST['selectOrder'] == "orderNew"){
                        $sqlOrderNew = " AND og.status = 0 ";
                    }else if($_POST['selectOrder'] == "productCrash"){
                        $sqlOrderCrash .= " AND og.order_id IN (SELECT order_id FROM order_crash GROUP BY order_id) ";
                    }
                }


                if (!empty($requestData['search']['value'])) {
                    $sql .= " WHERE order_id LIKE '%" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR member_name LIKE '%" . $requestData['search']['value'] . "%' ";
                    $sql .= $sqlOrderNew;
                    $sql .= $sqlOrderCrash;
                    $sql .= " GROUP BY order_id ";
                    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'];
                } else {
                    $sql .= " WHERE 1=1 ";
                    $sql .= $sqlOrderNew;
                    $sql .= $sqlOrderCrash;
                    $sql .= " GROUP BY og.order_id ";
                    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'];
                }


                $stmt = $dbcon->runQuery($sql);
                $stmt->execute();
                $totalData = $stmt->rowCount();
                $totalFiltered = $totalData;

                $sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
                $result = $dbcon->query($sql);

                $output = array();
                if ($result) {
                    foreach ($result as $key =>  $value) {

                        $sqlCrash = "SELECT * FROM order_crash WHERE o_id =:oid AND order_id =:order_id LIMIT 1";
                        $valCrash = array(':oid' => $value['id'], ':order_id' => $value['order_id']);
                        $resCrash = $dbcon->fetchObject($sqlCrash, $valCrash);

                        $nestedData = array();
                        $statusDerr = (($value['status'] == 0) ? "<i class=\"fa fa-cart-arrow-down\" style='font-size:2em;color:#00d1b2;display:block;text-align:center;' aria-hidden=\"true\"></i>" : "<!--<i class=\"fa fa-check\" aria-hidden=\"true\" style='color:steelblue;display:block;text-align:center;'></i>-->");
                        if ($resCrash) {
                            $statusDerr .= "<i class=\"fa fa-exclamation-circle\" style='font-size:2em;color:red;display:block;text-align:center;' aria-hidden=\"true\"></i>";
                        }

                        $nestedData[] = $statusDerr;
                        $nestedData[] = $key + 1;
                        $nestedData[] = $value['order_id'];
                        $nestedData[] = $value['member_id'];
                        $nestedData[] = $value['member_name'];
                        $nestedData[] = $value['p_qty'];
                        $nestedData[] = number_format($value['p_price']);
                        $nestedData[] = $value['create_date'];


                        $sql = "SELECT
                                (SELECT order_status FROM order_hospital 
                                    WHERE order_id =:order_id AND order_status = 0
                                    GROUP BY order_id) as a,
                                (SELECT order_status FROM order_hospital 
                                    WHERE order_id =:order_id AND order_status = 1
                                    GROUP BY order_id) as b,
                                (SELECT order_status FROM order_hospital 
                                    WHERE order_id =:order_id AND order_status = 2
                                    GROUP BY order_id) as c
                                ";
                        $v = array(
                            ':order_id' => $value['order_id']
                        );
                        $resOG = $dbcon->fetchObject($sql, $v);
                        if ($resOG->b > $resOG->a && $resOG->b > $resOG->c) {
                            $o_s = "<span class=\"badge\" style='font-size:1.1em;background:mediumseagreen'>สมบูรณ์ทั้งหมด</span>";
                        } else if ($resOG->c > $resOG->b && $resOG->c > $resOG->a) {
                            $o_s = "<span class=\"badge\" style='font-size:1.1em;background:red'>ยังมีสินค้าที่ไม่สมบูรณ์</span>";
                        } else if ($resOG->a > $resOG->b && $resOG->a > $resOG->c) {
                            $o_s = "<span class=\"badge\" style='font-size:1.1em;'>ยังไม่ได้เช็คสินค้า</span>";
                        }
                        $nestedData[] = $o_s;


                        // $nestedData[] = (($value['status'] == 0) ? "<i class=\"fa fa-times\" aria-hidden=\"true\" style='color:red;display:block;text-align:center;'>ยังไม่ได้ดู</i>" : "<i class=\"fa fa-check\" aria-hidden=\"true\" style='color:mediumseagreen;display:block;text-align:center;'></i>");

                        $nestedData[] = '
                            <a class="btn kt:btn-primary" onclick="showOrderHospital(event,' . $value['order_id'] . ')" style="color:white;padding:5px;" data-id="' . $value['product_cate_id'] . '"><i class="fa fa-eye" aria-hidden="true"></i> ดู</a>
                            <a class="btn kt:btn-warning" onclick="editOrderHospital(event,' . $value['order_id'] . ')"  style="color:white;padding:5px;" data-id="' . $value['product_cate_id'] . '"><i class="fa fa-pencil-square-o"></i> แก้ไข</a>
                            <a class="btn kt:btn-danger"  onclick="delOrderHospital(event,' . $value['order_id'] . ')"  style="color:white;padding:5px;" data-id="' . $value['product_cate_id'] . '"><i class="fa fa-trash-o" aria-hidden="true"></i> ลบ</a>
                            <a href="' . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/template/print.php?type=hospital&order_id=' . $value['order_id'] . '" target="_blank" class="btn kt:btn-info"  style="color:white;padding:5px;" ><i class="fa fa-print" aria-hidden="true"></i> พิมพ์</a>
                        ';


                        $output[] = $nestedData;
                    }
                }

                $json_data = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data" => $output,
                );
                echo json_encode($json_data);

                break;



            case 'checkStatusOrder':
                /**
                 * check Status Order จะแจ้งเตือนถ้ามี Order เข้ามา
                 */
                $sql = "SELECT COUNT(DISTINCT(order_id)) as status
                    FROM order_general as og
                    INNER JOIN members as m ON m.member_id = og.member_id
                    WHERE og.status = 0 

                ";
                $resultStatusGeneral = $dbcon->fetch($sql);

                $sql = "SELECT COUNT(DISTINCT(order_id)) as status
                    FROM order_hospital as og
                    INNER JOIN members as m ON m.member_id = og.member_id
                    WHERE og.status = 0 

                ";
                $resultStatusHospital = $dbcon->fetch($sql);

                echo json_encode([
                    'general' => $resultStatusGeneral['status'],
                    'hospital' => $resultStatusHospital['status'],
                    'sum' => ($resultStatusGeneral['status'] + $resultStatusHospital['status'])
                ]);
                break;


            case 'getOrderGeneralByOrderId':
                $sql1 = "SELECT og.id , og.order_id , og.member_id , m.member_name , SUM(og.p_qty) as p_qty , 
                                SUM(og.p_price) as p_price ,og.create_date , og.update_date , og.status
                        FROM order_general as og
                        INNER JOIN members as m ON m.member_id = og.member_id
                        WHERE order_id = :order_id
                        GROUP BY order_id
                        ";
                $value1 = array(
                    ':order_id' => $_POST['order_id']
                );
                $resTotal = $dbcon->fetchObject($sql1, $value1);



                $id = filter_var($_POST['order_id'], FILTER_SANITIZE_NUMBER_INT);
                $sql2 = "SELECT og.id , og.order_id,og.order_status ,og.p_qty,og.p_price, og.member_id , m.member_name ,og.create_date , og.update_date , og.status,p.title , p.saleprice ,pc.product_cate_name
                        FROM order_general as og
                        INNER JOIN members as m ON m.member_id = og.member_id
                        INNER JOIN post as p ON og.p_id = p.id
                        INNER JOIN product_cate as pc ON p.product_cate_id = pc.product_cate_id
                        WHERE order_id = '" . $id . "'
                        ";
                $resDetail = $dbcon->query($sql2);

                $out = "
                        <table class=\"table\" style='text-align:center'>
                            <tr>
                                <th style='text-align:center'>ลำดับ</th>
                                <th style='text-align:center'>หมวดหมู่</th>
                                <th style='text-align:center'>ชื่อสินค้า</th>
                                <th style='text-align:center'>จำนวน</th>
                                <th style='text-align:center'>ราคาต่อชิ้น</th>
                                <th style='text-align:center'>ราคารวม</th>
                                <th style='text-align:center'>สถานะสินค้า</th>
                                <th style='text-align:center'>report</th>
                            </tr>";

                foreach ($resDetail as $key => $res) {
                    $out .= "<tr>";
                    $out .= "   <td>" . ($key + 1) . "</td>";
                    $out .= "   <td>" . $res['product_cate_name'] . "</td>";
                    $out .= "   <td>" . $res['title'] . "</td>";
                    $out .= "   <td>" . number_format($res['p_qty']) . "</td>";
                    $out .= "   <td>" . number_format($res['saleprice']) . "</td>";
                    $out .= "   <td>" . number_format($res['p_price']) . "</td>";


                    if (isset($_POST['option'])) {
                        if ($res['order_status'] == 0) { // ยังไม่ได้เช็ค
                            $color = "black";
                        } else if ($res['order_status'] == 1) { //OK
                            $color = "mediumseagreen";
                        } else if ($res['order_status'] == 2) { //Bad
                            $color = "red";
                        }

                        $out .= "<td>";
                        $out .= "   <select class=\"form-control orderStatusGeneral\" data-id=\"" . ($res['id']) . "\" style='color:" . $color . "' onchange=\"changeOrderStatusGeneral(event)\">";
                        $out .= "       <option value=\"0\"  " . (($res['order_status'] == 0) ? "selected" : "") . " style='color:black'>ยังไม่ได้เช็ค</option>";
                        $out .= "       <option value=\"1\"  " . (($res['order_status'] == 1) ? "selected" : "") . " style='color:mediumseagreen'>สมบูรณ์</option>";
                        $out .= "       <option value=\"2\"  " . (($res['order_status'] == 2) ? "selected" : "") . " style='color:red'> ไม่สมบูรณ์</option>";
                        $out .= "   </select>";
                        $out .= "</td>";
                    } else {
                        if ($res['order_status'] == 0) { // ยังไม่ได้เช็ค
                            $out .= "<td><span class=\"badge badge-info\" style='font-size:1.1em;'>ยังไม่ได้เช็ค</span></td>";
                        } else if ($res['order_status'] == 1) { //OK
                            $out .= "<td><span class=\"badge badge-success\" style='font-size:1.1em;background:mediumseagreen'>สมบูรณ์</span></td>";
                        } else if ($res['order_status'] == 2) { //Bad
                            $out .= "<td><span class=\"badge badge-danger\" style='font-size:1.1em;background:red'>ไม่สมบูรณ์</span></td>";
                        }
                    }

                    $sqlCrash = "SELECT qty_crash FROM order_crash WHERE order_id =:order_id AND o_id =:oid";
                    $valCrash = array(':order_id' => $res['order_id'], ':oid' => $res['id']);
                    $resCrash = $dbcon->fetchObject($sqlCrash, $valCrash);
                    if ($resCrash) {
                        $out .= "<td><span class=\"badge\" id=\"statusOrderCrash\" style=\"background:red;margin-right:0px;\">" . $resCrash->qty_crash . "</span></td>";
                    } else {
                        $out .= "<td></td>";
                    }


                    $out .= "</tr>";
                }

                $out .= "<tr>
                            <th style='text-align:center'></th>
                            <th style='text-align:center'></th>
                            <th style='text-align:center'></th>
                            <th style='text-align:center'>" . number_format($resTotal->p_qty) . "</th>
                            <th style='text-align:center'></th>
                            <th style='text-align:center'>" . number_format($resTotal->p_price) . "</th>
                        </tr>";
                $out .= "</table>";


                $set = "status = 1";
                $where = "order_id = :order_id";
                $value = array(
                    ':order_id' => $id
                );
                $dbcon->update_prepare("order_general", $set, $where, $value);

                echo json_encode([
                    'resTotal' => $resTotal,
                    'resDetail' => $out,
                    'href' => $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/template/print.php?type=general&order_id=' . $resTotal->order_id
                ]);
                break;

            case 'getOrderHospitalByOrderId':
                $sql1 = "SELECT oh.id , oh.order_id , oh.member_id , m.member_name , SUM(oh.p_qty) as p_qty , 
                                SUM(oh.p_price) as p_price ,oh.create_date , oh.update_date , oh.status
                        FROM order_hospital as oh
                        INNER JOIN members as m ON m.member_id = oh.member_id
                        WHERE order_id = :order_id
                        GROUP BY order_id
                        ";
                $value1 = array(
                    ':order_id' => $_POST['order_id']
                );
                $resTotal = $dbcon->fetchObject($sql1, $value1);

                $id = filter_var($_POST['order_id'], FILTER_SANITIZE_NUMBER_INT);

                $sqlc_id = "SELECT c_id 
                        FROM order_hospital 
                        WHERE order_id = '" . $id . "' 
                        GROUP BY c_id";
                $res_cid = $dbcon->query($sqlc_id);


                $out .= "
                <table class=\"table\" style='text-align:center'>
                    <tr>
                        <th style='text-align:center'>ลำดับ</th>
                        <th style='text-align:center'>ชื่อ</th>
                        <th style='text-align:center'>หมวดหมู่</th>
                        <th style='text-align:center'>ชื่อสินค้า</th>
                        <th style='text-align:center'>จำนวน</th>
                        <th style='text-align:center'>ราคาต่อชิ้น</th>
                        <th style='text-align:center'>ราคารวม</th>
                        <th style='text-align:center'>สถานะสินค้า</th>
                        <th style='text-align:center'>report</th>
                    </tr>";
                foreach ($res_cid as $keycid => $c_id) {
                    $sql2 = "SELECT oh.id , oh.order_id ,oh.p_qty,oh.p_price,oh.order_status, 
                                    oh.member_id , m.member_name ,oh.create_date , oh.update_date , 
                                    oh.status,p.title , p.saleprice ,pc.product_cate_name , co.name
                            FROM order_hospital as oh
                            INNER JOIN members as m ON m.member_id = oh.member_id
                            INNER JOIN post as p ON oh.p_id = p.id
                            INNER JOIN product_cate as pc ON p.product_cate_id = pc.product_cate_id
                            INNER JOIN customer_old as co ON co.c_id = oh.c_id
                            WHERE order_id = '" . $id . "' AND oh.c_id = '" . $c_id['c_id'] . "'
                            ORDER BY id ASC
                            ";
                    $resDetail = $dbcon->query($sql2);

                    $priceTotal = 0;
                    $qtyTotal = 0;

                    foreach ($resDetail as $key => $res) {
                        $priceTotal += $res['saleprice'];
                        $qtyTotal   += $res['p_qty'];

                        $out .= "<tr>";
                        $out .= "   <td>" . ($key + 1) . "</td>";
                        $out .= "   <td>" . (($key == 0) ? $res['name'] : "") . "</td>";
                        $out .= "   <td>" . $res['product_cate_name'] . "</td>";
                        $out .= "   <td>" . $res['title'] . "</td>";
                        $out .= "   <td>" . number_format($res['p_qty']) . "</td>";
                        $out .= "   <td>" . number_format($res['saleprice']) . "</td>";
                        $out .= "<td>" . number_format($res['p_price']) . "</td>";

                        if (isset($_POST['option'])) {
                            if ($res['order_status'] == 0) { // ยังไม่ได้เช็ค
                                $color = "black";
                            } else if ($res['order_status'] == 1) { //OK
                                $color = "mediumseagreen";
                            } else if ($res['order_status'] == 2) { //Bad
                                $color = "red";
                            }

                            $out .= "<td>";
                            $out .= "   <select class=\"form-control orderStatusHospital\" data-id=\"" . ($res['id']) . "\" style='color:" . $color . "' onchange=\"changeOrderStatusHospital(event)\">";
                            $out .= "       <option value=\"0\"  " . (($res['order_status'] == 0) ? "selected" : "") . " style='color:black'>ยังไม่ได้เช็ค</option>";
                            $out .= "       <option value=\"1\"  " . (($res['order_status'] == 1) ? "selected" : "") . " style='color:mediumseagreen'>สมบูรณ์</option>";
                            $out .= "       <option value=\"2\"  " . (($res['order_status'] == 2) ? "selected" : "") . " style='color:red'> ไม่สมบูรณ์</option>";
                            $out .= "   </select>";
                            $out .= "</td>";
                        } else {
                            if ($res['order_status'] == 0) { // ยังไม่ได้เช็ค
                                $out .= "<td><span class=\"badge badge-info\" style='font-size:1.1em;'>ยังไม่ได้เช็ค</span></td>";
                            } else if ($res['order_status'] == 1) { //OK
                                $out .= "<td><span class=\"badge badge-success\" style='font-size:1.1em;background:mediumseagreen'>สมบูรณ์</span></td>";
                            } else if ($res['order_status'] == 2) { //Bad
                                $out .= "<td><span class=\"badge badge-danger\" style='font-size:1.1em;background:red'>ไม่สมบูรณ์</span></td>";
                            }
                        }

                        $sqlCrash = "SELECT qty_crash FROM order_crash WHERE order_id =:order_id AND o_id =:oid";
                        $valCrash = array(':order_id' => $res['order_id'], ':oid' => $res['id']);
                        $resCrash = $dbcon->fetchObject($sqlCrash, $valCrash);
                        if ($resCrash) {
                            $out .= "<td><span class=\"badge\" id=\"statusOrderCrash\" style=\"background:red;margin-right:0px;\">" . $resCrash->qty_crash . "</span></td>";
                        } else {
                            $out .= "<td></td>";
                        }

                        $out .= "</tr>";
                    }

                    $out .= "   <tr>
                                    <th style='text-align:center'></th>
                                    <th style='text-align:center'></th>
                                    <th style='text-align:center'></th>
                                    <th style='text-align:center'></th>
                                    <th style='text-align:center'>" . number_format($qtyTotal) . "</th>
                                    <th style='text-align:center'></th>
                                    <th style='text-align:center'>" . number_format($priceTotal) . "</th>
                                </tr>";




                    $set = "status = 1";
                    $where = "order_id = :order_id";
                    $value = array(
                        ':order_id' => $id
                    );
                    $dbcon->update_prepare("order_hospital", $set, $where, $value);
                }

                $out .= "
                            <tr>
                                <th colspan='8'></th>
                            </tr>
                            <tr>
                                <th style='text-align:center'></th>
                                <th style='text-align:center'></th>
                                <th style='text-align:center'></th>
                                <th style='text-align:center'>รวมจำนวน </th>
                                <th style='text-align:center'>" . number_format($resTotal->p_qty) . "</th>
                                <th style='text-align:center'>รวมราคา</th>
                                <th style='text-align:center'>" . number_format($resTotal->p_price) . " บาท</th>
                                <th style='text-align:center'></th>
                            </tr>
                        </table>";




                echo json_encode([
                    'resTotal' => $resTotal,
                    'resDetail' => $out,
                    'href' => $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/template/print.php?type=hospital&order_id=' . $resTotal->order_id
                ]);

                break;

                // update order_status general
            case 'editOrderStatusGeneral':
                if (count($_POST['key']) !== count($_POST['val'])) {
                    echo json_encode([
                        'message' => "Error",
                        'detail'  => "not_same"
                    ]);
                    exit();
                }


                try {
                    $dbconn->beginTransaction();
                    for ($i = 0; $i < count($_POST['id']); $i++) {

                        $sql = "UPDATE order_general SET order_status =:status WHERE id =:id";
                        $stmt = $dbconn->prepare($sql);
                        $stmt->execute([
                            ':status' => $_POST['status'][$i],
                            ':id' => $_POST['id'][$i],
                        ]);

                        if($_POST['status'][$i] == 1){
                            $sql = "DELETE FROM order_crash WHERE o_id = (SELECT id FROM order_general WHERE id =:id) AND order_id = (SELECT order_id FROM order_general WHERE id =:id)";
                            $stmt = $dbconn->prepare($sql);
                            $stmt->execute([
                                ':id' => $_POST['id'][$i],
                            ]);
                        }
                    }


                    $dbconn->commit();



                   
                    $sql_ = "SELECT order_id FROM order_general WHERE id IN(".implode(',',$_POST['id']).") GROUP BY order_id";
                    $res_ = $dbcon->fetch_assoc($sql_);
                    sendEmailChangeStatusOrder('general',$res_);
                    // exit();



                    echo json_encode([
                        'message' => 'OK'
                    ]);
                } catch (PDOException $e) {
                    $dbconn->rollBack();
                    echo json_encode([
                        'message' => 'Error',
                        'detail'  => 'Error Exception'
                    ]);
                    exit();
                }



                break;

            case 'editOrderStatusHospital':

                if (count($_POST['key']) !== count($_POST['val'])) {
                    echo json_encode([
                        'message' => "Error",
                        'detail'  => "not_same"
                    ]);
                    exit();
                }


                try {
                    $dbconn->beginTransaction();
                    for ($i = 0; $i < count($_POST['id']); $i++) {

                        $sql = "UPDATE order_hospital SET order_status =:status WHERE id =:id";
                        $stmt = $dbconn->prepare($sql);
                        $stmt->execute([
                            ':status' => $_POST['status'][$i],
                            ':id' => $_POST['id'][$i],
                        ]);

                        if($_POST['status'][$i] == 1){
                            $sql = "DELETE FROM order_crash WHERE o_id = (SELECT id FROM order_hospital WHERE id =:id) AND order_id = (SELECT order_id FROM order_hospital WHERE id =:id)";
                            $stmt = $dbconn->prepare($sql);
                            $stmt->execute([
                                ':id' => $_POST['id'][$i],
                            ]);
                        }
                    }


                    $dbconn->commit();
                    $sql_ = "SELECT order_id FROM order_hospital WHERE id IN(".implode(',',$_POST['id']).") GROUP BY order_id";
                    $res_ = $dbcon->fetch_assoc($sql_);
                    sendEmailChangeStatusOrder('hospital',$res_);
                    echo json_encode([
                        'message' => 'OK'
                    ]);
                } catch (PDOException $e) {
                    $dbconn->rollBack();
                    echo json_encode([
                        'message' => 'Error',
                        'detail'  => 'Error Exception'
                    ]);
                    exit();
                }



                break;


            case 'delOrderGeneralByOrderId':
                $id = filter_var($_POST['order_id'], FILTER_SANITIZE_MAGIC_QUOTES);
                $where = "order_id = '" . $id . "' ";
                $result = $dbcon->delete("order_general", $where);
                echo json_encode($result);
                break;

            case 'delOrderHospitalByOrderId':
                $id = filter_var($_POST['order_id'], FILTER_SANITIZE_MAGIC_QUOTES);
                $where = "order_id = '" . $id . "' ";
                $result = $dbcon->delete("order_hospital", $where);
                echo json_encode($result);
                break;

            case 'checkStatusOrderCrash':
                $sqlG = "SELECT COUNT(id) as g FROM order_crash WHERE order_type = 'general' ";
                $resG = $dbcon->fetchObject($sqlG, []);

                $sqlH = "SELECT COUNT(id) as h FROM order_crash WHERE order_type = 'hospital' ";
                $resH = $dbcon->fetchObject($sqlH, []);


                echo json_encode([
                    'countG' => $resG->g,
                    'countH' => $resH->h,
                    'countSum' => ($resH->h + $resG->g)
                ]);
                break;
        }
    }

    function sendEmailChangeStatusOrder($type=null,$order_id=null){
        
        global $dbcon;

        // ดึงข้อมูลสินค้า
        if($type == 'general'){
            $sql = "SELECT og.p_qty , og.p_price  , post.id , post.title , post.saleprice , og.order_status , og.member_id
                FROM order_general  as og
                INNER JOIN post ON og.p_id = post.id
                WHERE og.order_id = :order_id";
        }else{
            $sql = "SELECT oh.p_qty , oh.p_price  , post.id , post.title , post.saleprice , oh.order_status , oh.member_id
            FROM order_hospital  as oh
            INNER JOIN post ON oh.p_id = post.id
            WHERE oh.order_id = :order_id";
        }
        $value = array(
            ':order_id' => $order_id
        );
        $resultStatus = $dbcon->fetchAll($sql,$value);
        $price = 0;

        // print_r($resultStatus); exit();

        $table =  '<table border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;width:90%;margin-right:auto;margin-left:auto">';
        $table .=  "<tr>";
        $table .=  "<th style='text-align:center'>ลำดับ</th>";
        $table .=  "<th style='text-align:center'>ชื่อสินค้า</th>";
        $table .=  "<th style='text-align:center'>ราคา/ชิ้น</th>";
        $table .=  "<th style='text-align:center'>จำนวน</th>";
        $table .=  "<th style='text-align:center'>ราคารวม</th>";
        $table .=  "<th style='text-align:center'>สถานะสินค้า</th>";
        $table .=  "</tr>";
        foreach($resultStatus as $key => $val){
            $table .=  "<tr>";
            $table .=  "<td style='text-align:center'>".$key."</td>";
            $table .=  "<td style='text-align:center'>".$val['title']."</td>";
            $table .=  "<td style='text-align:center'>".number_format($val['saleprice'])."</td>";
            $table .=  "<td style='text-align:center'>".number_format($val['p_qty'])."</td>";
            $table .=  "<td style='text-align:center'>".number_format($val['p_price'])."</td>";

            if($val['order_status'] == 0){
                $table .=  "<td style='text-align:center;color:red'>ไม่สมบูรณ์</td>";
            }else if($val['order_status'] == 1){
                $table .=  "<td style='text-align:center;color:mediumseagreen'>สมบูรณ์</td>";
            }else {
                $table .=  "<td style='text-align:center'>กำลังตรวจสอบ</td>";
            }

            $table .=  "</tr>";
            $price += $val['p_price'];
        }
        $table .=  "</table>";
        // echo $table;
        // exit();

        //ดึงข้อมูล ข้อมูลการติดต่อ ของโรงบาล
        $sql = "SELECT * FROM contact_sel WHERE id = 1 LIMIT 1";
        $resContact =  $dbcon->fetchObject($sql,[]);
        // print_r($resContact); exit();

        //ดึงข้อมูล Member 
        $sql = "SELECT * FROM members WHERE member_id = :member_id";
        $value = array(
            ':member_id' => $resultStatus[0]['member_id']
        );
        $resultMember = $dbcon->fetchObject($sql,$value);
        // print_r($resultMember); exit();

        $email_header = "
        <div style='border:1px solid rgba(0,0,0,0.1);
                    text-align:center;
                    background-color: rgba(0, 0, 0, 0.0);
                    width: 70%;
                    margin-left: auto;
                    margin-right: auto;
                    box-shadow: 0 2px 2px 0 rgba(0,0,0,0.3);
                '>
            <h1 style='color:black'>สถานะสินค้า</h1>
            <h2 style='color:black'>เลขที่สั่งสินค้า ".$order_id."</h2>
            <figure style='width:20%;border: 1px solid rgba(0,0,0,0.1);border-radius: 5px;margin-right: auto;margin-left: auto;'>
                <img src='".$_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/img/logo-sel.png' style='width:90%'>
            </figure>
            <br>
            <br>
            <h3 style='color:black'>ข้อมูลสินค้าที่สั่งซื้อ</h3>
            <hr style='width:70%;background-color:gray;'>
            ".$table."
            <a href='".$_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/ประวัติการสั่งซื้อ' 
                style=''
            >ดูประวัติการสั่งซื้อ</a>
        ";
        $email_footer = "
            <hr style='width:50%;background-color:gray;'>
            <br><br>
                <h3 style='color:black'>".$resContact->name."</h3>    
                <p style='color:black'>".$resContact->title."</p>
                
                <p style='color:black'>".$resContact->address."</p>
                <p style='color:black'>".$resContact->phone."</p>
                <p style='color:black'>".$resContact->email."</p><br>
                <div style = 'width:50%;border:0px solid black;margin:auto;background-color: rgba(0, 0, 0, 0.10);padding:15px;'>
                </div> 
        </div>";

        
        $email_info = getData::get_web_info('system_email');
        $email_config=array();
        foreach ($email_info['system_email']['data'] as $key => $value) {
            $email_config[$value['info_title']] = $value['attribute'];
        }
        
        $statusEmail = getData::sendemailSEL(
            array(
                'sendFromName' => 'คณะแพทยศาสตร์ มหาวิทยาลัยขอนแก่น',
                'subject' =>  'สถานะสินค้า',
                'addAddress' => array(
                                    array(
                                        'email' => $resultMember->member_email, 
                                        'name'=> $resultMember->member_name
                                    )
                            ),
                // 'addBcc' => array(
                //         array(
                //         'email' =>  'kotbass23@gmail.com', 
                //         'name'=> 'Support Wynnsoft Developer'
                //         )
                // ),
                'content' =>  $email_header . $email_footer
            )
        );
        // print_r($statusEmail);
    }
