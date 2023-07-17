 <?php

require_once dirname(__DIR__) . '/classes/dbquery.php';
$dbcon = new DBconnect();
if(isset($_REQUEST['action'])) {

  switch($_REQUEST['action']){
    case'getsubscribers':
      $requestData= $_REQUEST;
      $columns = array( 
        0 => 'e_mail',
        1 => 'date_regist',
        2 => 'status',
        3 => 'language',
        4 => 'delete'
      );

      $sql="SELECT * FROM email_letter";
      $stmt = $dbcon->runQuery($sql);
            $stmt->execute();
            $totalData = $stmt->rowCount();
            $totalFiltered = $totalData;

            if( !empty($requestData['search']['value']) ) {
        $sql = "SELECT * ";
        $sql.=" FROM email_letter";
        $sql.=" WHERE e_mail LIKE '".$requestData['search']['value']."%' ";
        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; 
        $result = $dbcon->query($sql);
        
      } else {  
        $sql = "SELECT * ";
        $sql.=" FROM email_letter";
        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."  ";
        $result = $dbcon->query($sql);
      }

      $output = array();
      foreach ($result as $value) {

        $nestedData=array(); 
        $nestedData[] = $value["e_mail"];
        $nestedData[] = date_format(date_create($value["date_regist"]),"d/m/Y - H:i");
        $nestedData[] = $value["status"];
        $nestedData[] = $value["language"];

        $nestedData[] = '<center><a data-id="'.$value["id"].'" class="btn btn-danger btn-xs delete-email"><i class="fa fa-trash-o"></i></a></center>';
        
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

    case'deleteemail':
      $table = "email_letter";
      $where = "id = '".$_REQUEST['id']."'";
      $result = $dbcon->delete($table, $where);
      echo json_encode($result);
    break;
	}
}
?>