<?php
class manage_products
{
    private $dbcon;
    private $language_available;
    private $site_url = ROOT_URL;

    public function __construct()
    {
        $this->dbcon = new DBconnect();
        $this->language_available = getData::get_language_array();
    }

    public function upload_images_thumb($new_folder,$fieldImg = 'images')
    {

        $files = array();
        $oldmask = umask(0);
        if (!file_exists($new_folder)) {
            @mkdir($new_folder, 0777, true);
        }
        umask($oldmask); 
        $images = array();
        $totalFile = count($_FILES[$fieldImg]['name']);
        for ($i = 0; $i < $totalFile; $i++) {
            $handle = new Upload(
                array(
                    'name' => $_FILES[$fieldImg]['name'][$i],
                    'type' => $_FILES[$fieldImg]['type'][$i],
                    'tmp_name' => $_FILES[$fieldImg]['tmp_name'][$i],
                    'error' => $_FILES[$fieldImg]['error'][$i],
                    'size' => $_FILES[$fieldImg]['size'][$i],
                )
            ); 
            if ($handle->uploaded) {  
                $newname = uniqid() . self::randomString(5); // . microtime(true)
                $ext = strchr($_FILES[$fieldImg]['name'][$i], ".");
                $handle->file_new_name_body = $newname;
                $handle->Process($new_folder);
                $images[$i] = 'upload/' . date('Y') . '/' . date('m') . '/' . $newname . strtolower($ext);
                $handle->Clean();
            }
        }
        return $images;
    }
    
     public static function randomString($length = 5)
    { //กำหนดความยาวข้อความที่ต้องการ
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    public function update_category_discount($cate_id, $discount){
		$discount = (int)$discount;
		$id  = $cate_id;
		$table =   ' pro 
					INNER JOIN (
						SELECT max(bc.bercate_discount) as discount,bp.product_phone,bp.product_discount,bp.product_category 
						FROM berproduct bp 
						INNER JOIN berproduct_category bc 
							ON bp.product_category LIKE concat("%,",bc.bercate_id,",%")  
						GROUP BY bp.product_phone ASC 
					) as gg ON pro.product_phone = gg.product_phone';
		$set = ' pro.product_discount = if(gg.discount >= '.$discount.' , gg.discount, '.$discount.' ) ';
		$where = ' pro.product_category LIKE "%,'.$id.',%" ';
		$value = array( [] ); 
        $result['maincate'] = $this->dbcon->update_prepare(" berproduct ".$table, $set, $where,$value);
        $where = ' pro.category = '.$id.' ';
        $result['alover'] = $this->dbcon->update_prepare(" berproduct_alover ".$table, $set, $where,$value);


    
        return $result;
    }

}
