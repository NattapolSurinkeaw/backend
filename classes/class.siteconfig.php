<?php
class siteconfig
{
    private $dbcon;
    private $language_available;
    private $site_url = ROOT_URL;

    public function __construct()
    {
        $this->dbcon = new DBconnect();
        $this->language_available = getData::get_language_array();
    }

    //@get_web_info ฟังก์ชั่นดึงข้อมูล ตั้งค่าเว็บไซต์  @type
    public function get_web_info($type)
    {
				$sql = "SELECT * FROM web_info WHERE info_type = '" . $type . "' ORDER BY info_id ,info_display DESC, priority ASC, FIELD(defaults,'yes') DESC";
			//	echo $sql;exit;
				$result = $this->dbcon->query($sql);
				$webInfo = getData::convertResultPost($result,'info_id');
				return $webInfo;	
    }

		//ดึงข้อมูลประเภทของข้อมูลเว็บไซต์ที่ตั้งค่าเช่น อีเมล , บัญชีธนาคาร
    public function get_web_info_type()
    {
        $sql = "SELECT * FROM web_info_type ORDER BY id ASC, FIELD(defaults,'yes') DESC";
				$result = $this->dbcon->query($sql);
				$webInfoType = getData::convertResultPost($result);
				return $webInfoType;	
				
    }

    public function get_web_info_type_by_field($data_type, $info_value)
    {
        $sql = "SELECT * FROM web_info_type WHERE " . $data_type . " = '" . $info_value . "' ORDER BY FIELD(defaults,'yes') DESC";
        $res = $this->dbcon->query($sql);

        foreach ($res as $b) {
            if ($b['defaults'] == 'yes') {
                $result = $b;
            }
            if ($b['language'] == $_SESSION['backend_language']) {
                $result = $b;
            }
        }
        return $result;
    }

    public function get_web_info_by_field($data_type, $info_value)
    {
				$sql = "SELECT * FROM web_info WHERE " . $data_type . " = '" . $info_value . "' ORDER BY FIELD(defaults,'yes') DESC";
				$result = $this->dbcon->query($sql);
				$websiteDetail = getData::convertResultPost($result,'info_id');
				//ฟังก์ชั่น current ดึงข้อมูลอาร์เรย์ช่องแรก
				return current($websiteDetail);
    }

		//รายละเอียดเว็บไซต์ดึงจากตาราง category โดยใช้ cate_id =1
    public function get_website_detail()
    {
        $sql = "SELECT * FROM category WHERE cate_id = '1' ORDER BY FIELD( defaults,  'yes')DESC";
				$result = $this->dbcon->query($sql);
				$websiteDetail = getData::convertResultPost($result,'cate_id');
				//ฟังก์ชั่น current จะดึงค่าอาร์เรย์ตัวแรกออกมา
				return current($websiteDetail);		
    }

}
