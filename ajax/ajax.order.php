<?php	
session_start();

include '../config/database.php';
require_once('../classes/dbquery.php');
#require_once('../classes/class.upload.php');
require_once('../classes/preheader.php');
require_once('../classes/class.order.php');

$dbcon = new DBconnect();
$data = new getData();
$mydata = new order();

if(isset($_REQUEST['action'])) {
	$lang_config = $data->lang_config();
  $output = $_SESSION['backend_language'];
  foreach($lang_config as $a){
    foreach($a as $b => $c){
      if($b == 'param'){
        if($a[$output]!='')
          $$c = $a[$output];
        else
          $$c = $a['defaults'];
      }
    }
  }
  switch($_REQUEST['action']){
    case'getorder':
      $requestData= $_REQUEST;
      $columns = array( 
        0 => 'order_id',
        1 => 'name',
        2 => 'phone',
        3 => 'email',
        3 => 'vehicle',
        3 => 'location_route',
        6 => 'select_date',
        7 => 'action'
      );

      $sql="SELECT * FROM vehicle_orders";
      $stmt = $dbcon->runQuery($sql);
            $stmt->execute();
            $totalData = $stmt->rowCount();
            $totalFiltered = $totalData;

      if( !empty($requestData['search']['value']) ) {
        $sql = "SELECT * ";
        $sql.=" FROM vehicle_orders";
        $sql.=" WHERE order_id LIKE '".$requestData['search']['value']."%' ";
        $sql.=" OR vehicle LIKE '".$requestData['search']['value']."%' ";
        $sql.=" OR location_route LIKE '".$requestData['search']['value']."%' ";
        $sql.=" OR name LIKE '".$requestData['search']['value']."%' ";
        $sql.=" OR phone LIKE '".$requestData['search']['value']."%' ";
        $sql.=" OR email LIKE '".$requestData['search']['value']."%' ";
        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; 
        $result = $dbcon->query($sql);
        
      } else {  
        $sql = "SELECT * ";
        $sql.=" FROM vehicle_orders";
        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        $result = $dbcon->query($sql);
      }

      $output = array();
      foreach ($result as $value) {

        $nestedData=array(); 
        $nestedData[] = $value["order_id"];
        $nestedData[] = $value["name"];
        $nestedData[] = $value["phone"];
        $nestedData[] = $value["email"];
        $nestedData[] = $value["vehicle"];
        $nestedData[] = $value["location_route"];
        $nestedData[] = date_format(date_create($value["select_date"]),"d/m/Y - H:i");
        $nestedData[] = '
        <a data-id="'.$value["order_id"].'" data-type="edit" data-toggle="modal" data-target="#modal-order" class="btn btn-success btn-xs view-order" style="margin-right: 7px;">
          <i class="fa fa-eye"></i> View
        </a> |
        <a data-id="'.$value["order_id"].'" class="btn btn-danger btn-xs delete-order" style="margin-left: 7px;">
          <i class="fa fa-trash-o"></i> Delete
        </a>';
        
        $output[] = $nestedData;
      }

      $json_data = array(
        "draw"            => intval( $requestData['draw'] ),
        "recordsTotal"    => intval( $totalData ),
        "recordsFiltered" => intval( $totalFiltered ),
        "data"            => $output
      );
      echo json_encode($json_data);
    break;
    case'getorderdetail':
      $sql = "SELECT *,DATE_FORMAT(select_date, '%d %M %Y - %H:%i') AS new_date FROM vehicle_orders WHERE order_id = '".$_REQUEST['id']."'";
      $result = $dbcon->query($sql);
      echo json_encode($result);
    break;
    case'deleteorder':
      $table = "vehicle_orders";
      $where = "order_id = '".$_REQUEST['id']."'";
      $result = $dbcon->delete($table, $where);
      echo json_encode($result);
    break;

	}
}
?>