
<?php
session_start();
require_once dirname(__DIR__) . '/classes/class.protected_web.php';
ProtectedWeb::methodPostOnly();
ProtectedWeb::login_only();

require_once dirname(__DIR__) . '/classes/dbquery.php';
require_once dirname(__DIR__) . '/classes/preheader.php';
$dbcon = new DBconnect();
getData::init();

if (isset($_REQUEST['action'])) {

    $lang_config = getData::lang_config();

    switch ($_REQUEST['action']) {

        //=========== รายชื่อ customer ================

        case 'get_customerList':

            $requestData = $_REQUEST;

            $columns = array(
                0 => 'customer.date_create',
                1 => 'customer.name',
                2 => 'customer.phoneNumber',
                3 => 'car_model.car_model',
                4 => 'province.province_name',
                5 => 'customer.car_status',
            );

            $sql = "SELECT customer . * , car_model.car_model, province.province_name
                    FROM customer
                    INNER JOIN car_model ON customer.car_model = car_model.car_model_id
                    INNER JOIN province ON customer.province = province.id";

            if (!empty($requestData['search']['value'])) {

                $sql .= " WHERE customer.phoneNumber LIKE '" . $requestData['search']['value'] . "%' ";
                $sql .= " OR customer.name LIKE '" . $requestData['search']['value'] . "%' ";
                $sql .= " OR province.province_name LIKE '" . $requestData['search']['value'] . "%' ";
                $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'];
            } else {
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
                foreach ($result as $value) {

                    $label_status = '';
                    if ($value['status'] == 'อ่านแล้ว') {
                        $label_status = 'label-success';
                    } else {
                        $label_status = 'label-warning';
                    }

                    $link_share = ROOT_URL . 'customerlink2?token=' . $value['token'];

                    $nestedData = array();
                    $nestedData[] = date_format(date_create($value["date_create"]), "d/m/Y - H:i");
                    $nestedData[] = $value['titleName'] . $value["name"];
                    $nestedData[] = $value["car_model"];
                    $nestedData[] = $value["phoneNumber"];
                    $nestedData[] = $value["province_name"];
                    $nestedData[] = $value['car_status'];
                    $nestedData[] = '<span class="label ' . $label_status . '">' . $value["status"] . '</span>';
                    $action = '<div class="box-tools tdChild" style="text-align: center;">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown">
                                            <i class="fa fa-bars"></i></button>
                                        <ul class="dropdown-menu pull-right" role="menu">
                                            <li><a href="#" class="bt-view"   data-id="' . $value['id'] . '"  data-customer="' . $value['titleName'] . $value["name"] . '" ><i class="fa  fa-eye  text-black"></i> ดูรายละเอียด</a></li>
                                            <li><a href="#" class="bt-edit"   data-id="' . $value['id'] . '"  data-customer="' . $value['titleName'] . $value["name"] . '" ><i class="fa fa-pencil  text-aqua"></i> แก้ไขข้อมูล</a></li>
                                            <li><a href="#" class="bt-link"   data-id="' . $value['id'] . '"  data-link="' . $link_share . '" ><i class="fa  fa-chain"></i>  แชร์ลิงค์</a></li>';

                    if (in_array($_SESSION['role'], array('superamin', 'admin'))) {
                        $action .= ' <li><a href="#" class="bt-delete" data-id="' . $value['id'] . '"  data-customer="' . $value['titleName'] . $value["name"] . '"><i class="fa fa-remove  text-red"></i> ลบ</a></li>';
                    }
                    $nestedData[] = $action . '</ul>
                                    </div>
                                </div>';
                    //<li><a href="#" class="bt-view-reply"   data-id="' . $value['id'] . '"  data-customer="' . $value['titleName'] . $value["name"] . '" ><i class="fa fa-list text-green"></i>  รายการเสนอลูกค้า</a></li>

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

        //=========== จบรายชื่อ customer ================

        case 'get_customer':

            $id = filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);
            $sql = "SELECT customer.*,car_model.car_type_id,car_model.car_brand_id FROM customer
                    INNER JOIN car_model ON car_model.car_model_id = customer.car_model
                    WHERE customer.id = '" . $id . "'";
            $result = $dbcon->query($sql);
            echo json_encode(current($result));
            break;

        case 'get_customer_print':

            $id = filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);

            //update view
            $where = " id = '{$id}' ";
            $dbcon->update('customer', " status = 'อ่านแล้ว' ", $where);

            $sql = "SELECT customer.*,car_model.car_model,car_model.car_model_price,car_color.car_color,car_brand.car_brand,car_type.car_type,province_name
                        FROM customer
                    LEFT JOIN car_model ON customer.car_model = car_model.car_model_id
                    LEFT JOIN car_type ON car_model.car_type_id = car_type.car_type_id
                    LEFT JOIN car_brand ON car_model.car_brand_id = car_brand.car_brand_id
                    LEFT JOIN car_color ON customer.car_color = car_color.car_color_id
                    LEFT JOIN province ON customer.province = province.id

            WHERE customer.id='{$id}'";
            // echo $sql;
            $resultCustomer = $dbcon->fetch($sql);

            $table = ' <table class="table table-bordered">
            <tbody>
                <tr>
                    <td colspan="2"><center>ข้อมูลลูกค้า</center></td>
                </tr>
                <tr>
                    <th style="width: 150px">ชื่อ - นามสกุล</th>
                    <td>' . $resultCustomer['titleName'] . $resultCustomer['name'] . '</td>
                </tr>
                <tr>
                    <th style="">เบอร์โทร</th>
                    <td>' . $resultCustomer['phoneNumber'] . '</td>
                </tr>
                <tr>
                    <th style="">Line ID</th>
                    <td>' . $resultCustomer['lineID'] . '</td>
                </tr>
                <tr>
                    <th style="">ประเภทรถยนต์</th>
                    <td>' . $resultCustomer['car_type'] . ' - ' . $resultCustomer['car_brand'] . ' ( สี ' . $resultCustomer['car_color'] . ' )</td>
                </tr>
                 <tr>
                 <th style="">รุ่น</th>
                     <td>' . $resultCustomer['car_model'] . '  ( ราคา ' . number_format($resultCustomer['car_model_price']) . ' บาท )</td>
                 </tr>
                 <tr>
                    <th style="">ดาวน์</th>
                    <td>' . $resultCustomer['downPaymentPercent'] . ' % </td>
                 </tr>
                 <tr>
                    <th style="">จำนวนเงินดาวน์</th>
                    <td>' . number_format($resultCustomer['downPayment']) . ' บาท</td>
                 </tr>
                 <tr>
                    <th style="">ผ่อนชำระ</th>
                    <td>' . $resultCustomer['installment'] . ' งวด</td>
                </tr>
                <tr>
                    <th style="">สิ่งที่ลูกค้าต้องการ</th>
                    <td>' . $resultCustomer['customerRequire'] . ' งวด</td>
                </tr>

         </tbody>
        </table>';
            echo $table;

            break;

        case 'update_customer':

            $data_post = ProtectedWeb::magic_quotes_special_chars_array($_POST);

            $setUpdate = "car_model='{$data_post['subbrandCar']}',
            car_color='{$data_post['colorCar']}',
            installment='{$data_post['installment']}',
            customerRequire='{$data_post['customerRequire']}',
            downPayment='{$data_post['downPayment']}',
            downPaymentPercent='{$data_post['downPaymentPercent']}',
            titleName='{$data_post['titleName']}',
            name='{$data_post['name']}',
            phoneNumber='{$data_post['phoneNumber']}',
            lineID='{$data_post['lineID']}',
            province='{$data_post['province']}',
            car_status='{$data_post['carStatus']}'";

            $where = " id = {$data_post['customer_id_edit']} ";
            $result = $dbcon->update('customer', $setUpdate, $where);
            echo json_encode($result);

            break;

        case 'delete_customer':

            $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
            $where = "id = '" . $id . "'";
            $result = $dbcon->delete('customer', $where);

            //ลบแบบตอบกลับลูกค้าด้วย
            echo json_encode($result);

            break;

        case 'get_car_brand':
            $car_brand = filter_input(INPUT_POST, 'car_brand', FILTER_SANITIZE_NUMBER_INT);

            $sql = "SELECT * FROM car_model WHERE car_type_id='{$car_cat}' AND car_brand_id='{$car_brand}'";
            $result = $dbcon->query($sql);
            if (!$result) {
                echo json_encode('no_result');
            } else {
                echo json_encode($result);
            }
            break;

        case 'get_car_model':

            $car_cat = filter_input(INPUT_POST, 'car_cat', FILTER_SANITIZE_NUMBER_INT);
            $car_brand = filter_input(INPUT_POST, 'car_brand', FILTER_SANITIZE_NUMBER_INT);

            $sql = "SELECT * FROM car_model WHERE car_type_id='{$car_cat}' AND car_brand_id='{$car_brand}'";
            $result = $dbcon->query($sql);
            if (!$result) {
                echo json_encode('no_result');
            } else {
                echo json_encode($result);
            }

            break;
    }
}
