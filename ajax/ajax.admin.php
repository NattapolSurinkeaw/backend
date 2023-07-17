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
        case 'getalluser':
            $requestData = $_REQUEST;
            $columns = array(
                0 => 'member_id',
                1 => 'display_name',
                2 => 'email',
                3 => 'member_type',
                4 => 'language',
                5 => 'date_regis',
                6 => 'status_user',
                7 => 'edit',
                8 => 'delete',
            );

            $sql = "SELECT * FROM user";
            $stmt = $dbcon->runQuery($sql);
            $stmt->execute();
            $totalData = $stmt->rowCount();
            $totalFiltered = $totalData;

            if (!empty($requestData['search']['value'])) {
                if ($_SESSION['role'] != 'superadmin') {
                    $sql = "SELECT * ";
                    $sql .= " FROM user";
                    if ($_SESSION['role'] == 'editor' || $_SESSION['role'] == 'user') {
                        $sql .= " WHERE member_type NOT IN(1) AND member_type NOT IN(3)";
                    } else {
                        $sql .= " WHERE member_type NOT IN(1) ";
                    }
                    $sql .= " AND (username LIKE '" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR display_name LIKE '" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR email LIKE '" . $requestData['search']['value'] . "%') ";
                    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
                    $result = $dbcon->query($sql);
                } else {
                    $sql = "SELECT * ";
                    $sql .= " FROM user";
                    $sql .= " WHERE username LIKE '" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR display_name LIKE '" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR email LIKE '" . $requestData['search']['value'] . "%' ";
                    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
                    $result = $dbcon->query($sql);
                }

            } else {
                if ($_SESSION['role'] != 'superadmin') {
                    $sql = "SELECT *  FROM user ";
                    if ($_SESSION['role'] == 'editor' || $_SESSION['role'] == 'user') {
                        $sql .= " WHERE member_type NOT IN(1) AND member_type NOT IN(3)";
                    } else {
                        $sql .= " WHERE member_type NOT IN(1) ";
                    }
                    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
                    $result = $dbcon->query($sql);
                } else {
                    $sql = "SELECT * ";
                    $sql .= " FROM user";
                    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
                    $result = $dbcon->query($sql);
                }

            }

            $output = array();
            foreach ($result as $value) {
                if ($value['status_user'] == '3') {
                    $label_status = 'label-warning';
                } else if ($value['status_user'] == '1') {
                    $label_status = 'label-success';
                } else if ($value['status_user'] == '2') {
                    $label_status = 'label-danger';
                } else if ($value['status_user'] == '4') {
                    $label_status = 'label-default';
                }

                $type = getData::valuefromkey('user_type_th', 'user_type', 'id', $value['member_type']);
                $status = getData::valuefromkey('status_user_th', 'user_status', 'id', $value['status_user']);
                $nestedData = array();
                $nestedData[] = $value["member_id"];
                $nestedData[] = $value["display_name"];
                $nestedData[] = $value["email"];
                $nestedData[] = $type["user_type_th"];
                $nestedData[] = $value["language"];
                $nestedData[] = date_format(date_create($value["date_regis"]), "d/m/Y - H:i");
                $nestedData[] = '<span class="label btn-padding ' . $label_status . '">' . $status["status_user_th"] . '</span>';

                $nestedData[] = '<center><a data-id="' . $value["member_id"] . '" data-toggle="modal" data-target="#modal-admin" class="btn btn-padding btn-success kt:btn-warning btn-xs edit-admin"><i class="fas fa-edit"></i> แก้ไข</a></center>';

                $nestedData[] = '<center><a data-id="' . $value["member_id"] . '" class="btn btn-danger kt:btn-danger btn-padding btn-xs delete-admin"><i class="fas fa-trash"></i> ลบ</a></center>';

                $output[] = $nestedData;
            }

            $json_data = array(
                "draw" => intval($requestData['draw']),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $output,
            );
            echo json_encode($json_data);
            break;

        case 'getuser':

            $memberId = filter_var($_REQUEST['id'],FILTER_SANITIZE_NUMBER_INT);
            $sql = "SELECT * FROM user WHERE member_id = '" .$memberId . "'";
            $result = $dbcon->query($sql);
            echo json_encode($result);
            break;
            
        case 'edituser':
            $output = array();
            if ($_REQUEST['email'] != $_REQUEST['currentEmail']) {
                $email = $_REQUEST['email'];
                $result = getData::check_email($email);

                if (!$result) {
                    $table = "user";
                    $set = "email = '" . $_REQUEST['email'] . "'";
                    $where = "member_id = '" . $_REQUEST['id'] . "'";
                    $ret = $dbcon->update($table, $set, $where);

                    if ($ret['message'] == 'OK') {
                        $status = 'OK';
                    } else {
                        $status = 'not_found';
                    }

                } else {
                    $status = 'not_found';
                    $output['title'] = "Fail to register!";
                    $output['text'] = "This email already exists.";
                    $output['message'] = "email_already_exists";
                }
            } else {
                $status = 'OK';
            }

            if ($status == 'OK') {
                $table = "user";
                $set = "display_name = '" . $_REQUEST['display'] . "',
                member_type = '" . $_REQUEST['type'] . "',
                status_user = '" . $_REQUEST['status'] . "',
                language = '" . $_REQUEST['language'] . "',
                confirm_regis = 'yes'";
                $where = "member_id = '" . $_REQUEST['id'] . "'";
                $res = $dbcon->update($table, $set, $where);

                if ($res['message'] == 'OK') {
                    $output['message'] = "success";
                } else {
                    $output['title'] = "Fail to register!";
                    $output['text'] = "";
                    $output['message'] = "not_success";
                }
            }
            echo json_encode($output);
            break;
        case 'deleteuser':
            $table = "user";
            $where = "member_id = '" . $_REQUEST['id'] . "'";
            $result = $dbcon->delete($table, $where);
            echo json_encode($result);
            break;
        case 'resetpass':
            $email = $_REQUEST['email'];
            $randpass = getData::randompassword(8);

            $table = "user";
            $set = "password = '" . md5($randpass) . "'";
            $where = "email = '" . $email . "'";
            $result = $dbcon->update($table, $set, $where);

            $message = 'Your new password : ' . $randpass;
            $subject = 'Your password to login to backend has been changed';
            $mail = getData::sendemail($email, $message, $subject);

            $output = array();
            $output['message'] = "success";

            echo json_encode($output);
            break;
    }
}
