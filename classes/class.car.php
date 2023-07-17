<?php
class car
{
    private $dbcon;
    private $language_available;
    private $site_url = ROOT_URL;

    public function __construct()
    {
        $this->dbcon = new DBconnect();
        $this->language_available = getData::get_language_array();
    }

        public function get_carTypeList(){
            $sql = "select * from car_type";
            $return =  $this->dbcon->query($sql);            
            return $return;
        }

     

		/*
		@get_cate_radio ฟังก์ชั่นสร้าง radio สำหรับให้เลือกกรณีหน้าเพิ่มหมวดหมู่ และแก้ไขหมวดหมู่
		
		@category ค่า category ที่ดึงจากฐานข้อมูล
		@$type ประเภทของการแสดงผลในหน้าเว็บมี 2 ค่า   add,edit
        */
    public function get_cate_radio($category, $type)
    {
                $radioHtml = array();
                $cateid_all = array_keys($category); // ดึงเอา id ของ category ทั้หมดออกมาก่อน
				for ($i = count($cateid_all)-1; $i >= 0 ; $i--) { // ทำการ loop จากหลังสุดมาหน้าสุด  เพราะว่าด้านหน้าของอาร์จะเป็น พ่อ ของ category
                    $cateId_current =  $cateid_all[$i];  //ดึง id ออกมาเพื่อว่าเอาไปใช้งานแล้วอาเรมันจะไม่เยอะเกินไปทำให้ไม่ลายตา
                    $cate_parentId =   $category[$cateId_current ]['parent_id']; //ดึง id ของพ่อออกมา
                    if ($category[$cateId_current]['main_page'] != 'yes') { //เช็คว่าแสดงหน้าหลักหรือไม่
                        if(!isset($radioHtml[$cate_parentId])){ $radioHtml[$cate_parentId] = ""; } //ถ้ายังไม่ได้กำหนดค่า ให้เซตค่าว่างไว้ก่อน
                        if($cate_parentId  == 0){ // เช็คว่าเป็น พ่อ หรือไม่
                            $radioHtml[$cateId_current] = '
                            <div class="radio">
                                <label>
                                    <input type="radio" name="parent-id-' . $type . '" id="' . $type . '-cate-' . $cateId_current . '" value="' .  $cateId_current . '">' . $category[$cateId_current]['cate_name'] . '
                                </label>
                                <div class="radio-children">' . $radioHtml[$cateId_current] . '</div>
                            </div>';
                        }else{
                            $radioHtml[$cate_parentId] .= '
                            <div class="radio">
                                <label>
                                    <input type="radio" name="parent-id-' . $type . '" id="' . $type . '-cate-' . $cateId_current . '" value="' .  $cateId_current . '">' . $category[$cateId_current]['cate_name'] . '
                                </label>
                            </div>';
                        }
                    }
                }
               return implode(array_reverse($radioHtml));
    }
}
