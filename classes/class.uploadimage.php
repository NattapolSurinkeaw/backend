<?php
class uploadimage
{
    private $dbcon;
    private $language_available;
    private $site_url = ROOT_URL;

    public function __construct()
    {
        $this->dbcon = new DBconnect();
        $this->language_available = getData::get_language_array();
    }

    public function upload_image_thumb($new_folder,$fieldImg = 'images')
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
                ),
                'th'
            ); 
            if ($handle->uploaded) {  
                $newname = uniqid() . self::randomString(5); // . microtime(true)
                // $newname = explode('.', $_FILES[$fieldImg]['name'][$i])[0];
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


}
