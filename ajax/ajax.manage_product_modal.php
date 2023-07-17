<?php
class manage_products_modals
{
    function viewProductCateModalA($data) {
        $camera = ($data['thumbnail'] != "")?"hidden":"";
        $thumbnail = ($data['thumbnail'] == "")?"hidden":""; 
        $dateBegin = explode(" ", $data['discount_begin']);
        $dateExpire = explode(" ", $data['discount_begin']); 
        return <<<HTML
            <div class="cate-blog-icon">   
                <div>
                    <label for="">Category icon <i class="fa fa-hospital-o" aria-hidden="true"></i> </label>
                    <div class="form-group form-add-images">
                        <div id="image-preview">
                            <label for="image-upload" class="image-label">
                                <i class="fa fa-camera {$camera}" ></i>
                            </label>
                            <div class="blog-preview-add">
                                <div class="col-img-preview {$thumbnail}" id="col_img_preview_1" data-id="1" > 
                                    <img class="preview-img" id="preview_img_1" src="/{$data['thumbnail']}">
                                </div>
                            </div>
                            <input type="file" name="imagesadd[]" class="exampleInputFile" id="add-images-content" data-preview="blog-preview-add" data-type="add"/>
                        </div>
                        <input type="hidden" id="add-images-content-hidden" name="add-images-content-hidden" value="{$data['thumbnail']}" required />  
                        <input id="swal-input2" class="swal2-input txt_abbrev" style="text-align:center;" placeholder="ชื่อย่อ" value="{$data['bercate_title']}" />
                    </div> 
                </div>
            </div>
            <div style="text-align:start;">ชื่อหมวด:</div>
            <input  class="swal2-input txt_catename" placeholder="ชื่อหมวด" value="{$data['bercate_name']}">
            <div style="text-align:start;">เลขที่ต้องการ:</div>
            <textarea  class="swal2-input input-area txt_needful" placeholder="เช่น 12,133,144,155">{$data['bercate_needful']}</textarea>
            <div style="text-align:start;">เลขที่ไม่ต้องการ:</div>
            <textarea  class="swal2-input input-area txt_needless" placeholder="เช่น 12,133,144,155">{$data['bercate_needless']}</textarea>

            <div class="cate-discount">
                <label class="container">แสดงเฉพาะหมวด
                    <input type="checkbox" id="cateDisCountOnly">
                    <span class="checkmark"></span>
                </label>
            </div>
            <div style="text-align:start;">ส่วนลดทั้งหมวด: %</div>
                <input  type="number" class="form-control txt_catediscount text-center ml-left "style="width:30%;" value="{$data['bercate_discount']}" min="0" max="100"  placeholder="% ส่วนลด"> 
            </div>  
            <div style="text-align:start; margin-bottom: .5rem; margin-top: 1rem; font-size:16px;">ระยะเวลาส่วนลด</div>
                <div class="col-6 " style="gap:.75rem;display:flex;" >
                    <div class="form-group">
                        <div class="input-group date" style="display:flex;">
                            <input type="text" class="form-control pull-right" id="edit-ad-date-display" placeholder="dd/mm/yyyy" value="">
                            <input type="hidden" class="form-control pull-right" id="edit-input-date-display" value="">
                            <div class="bootstrap-timepicker">
                                <div class="input-group">
                                    <input type="text" class="form-control timepicker" id="edit-time-display" name="edit-time-display" placeholder="เวลา" value="">
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group date" style="display:flex;">
                            <input type="text" class="form-control pull-right" id="edit-ad-date-hidden" placeholder="dd/mm/yyyy" value="">
                            <input type="hidden" class="form-control pull-right" id="edit-input-date-hidden" value="">
                            <div class="bootstrap-timepicker">
                                <div class="input-group">
                                    <input type="text" class="form-control timepicker" id="edit-time-hidden" name="edit-time-hidden" placeholder="เวลา" value="">
                                </div>
                            </div> 
                        </div>
                    </div>
                </div> 
            </div> 
            <div style="text-align:start;">ลำดับการแสดงผล:</div>
            <input  class="swal2-input txt_priority" placeholder="กรุณาใส่ตัวเลข" value="{$data['priority']}" >  
            <div class="seo-title" style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;" onclick="collapSeo()">
            <label>บทความสำหรับ SEO</label><i class="fas fa-arrow-down"></i></div>
            <hr>
            <div class="seo-block" style="display:none">
                <div style="text-align:start;">H1:</div>
                <input  class="swal2-input seo_h1 " value="{$data['bercate_h1']}" placeholder="H1">

                <div style="text-align:start;">H2:</div>
                <input  class="swal2-input seo_h2 " value="{$data['bercate_h2']}" placeholder="H2">

                <div style="text-align:start;">เนื้อหา:</div>
                <textarea type="text" class="swal2-textarea seo_content " id="seo-content" name="seo-content">{$data['bercate_content']}</textarea>
                <br>

                <div style="text-align:start;">Meta Title:</div>
                <input  class="swal2-input seo_title" id="seo-title" value="{$data['meta_title']}" placeholder="Meta title">

                <div style="text-align:start;">Meta Description:</div>
                <textarea type="text" class="swal2-textarea seo_description" id="seo-description" name="seo-description">{$data['meta_description']}</textarea>
            </div>
            <hr> 
        HTML;
    }
    function viewProductCateModalB($data) {
        $camera = ($data['thumbnail'] != "")?"hidden":"";
        $thumbnail = ($data['thumbnail'] == "")?"hidden":"";
        $dateBegin = explode(" ", $data['discount_begin']);
        $dateExpire = explode(" ", $data['discount_begin']);
        return <<<HTML
            <div class="cate-blog-icon">  
                <div>
                    <label for="">Category icon <i class="fa fa-hospital-o" aria-hidden="true"></i> </label>
                    <div class="form-group form-add-images">
                        <div id="image-preview">
                            <label for="image-upload" class="image-label">
                                <i class="fa fa-camera {$camera}" ></i>
                            </label>
                            <div class="blog-preview-add">
                                <div class="col-img-preview {$thumbnail}" id="col_img_preview_1" data-id="1" > 
                                    <img class="preview-img" id="preview_img_1" src="/{$data['thumbnail']}">
                                </div>
                            </div>
                            <input type="file" name="imagesadd[]" class="exampleInputFile" id="add-images-content" data-preview="blog-preview-add" data-type="add"/>
                        </div>
                        <input type="hidden" id="add-images-content-hidden" name="add-images-content-hidden" value="{$data['thumbnail']}" required>  
                        <input id="swal-input" class="swal2-input txt_abbrev" style="text-align:center;" placeholder="ชื่อย่อ" value="{$data['bercate_title']}">
                    </div> 
                </div>
            </div>
            <div style="text-align:start;">ชื่อหมวด:</div>  
            <input id="swal-input2" class="swal2-input txt_catename" placeholder="ชื่อหมวด" value="{$data['bercate_name']}">

            <div class="cate-discount">
                <label class="container">แสดงเฉพาะหมวด
                    <input type="checkbox" id="cateDisCountOnly">
                    <span class="checkmark"></span>
                </label>
            </div>
            <div style="text-align:start;">ส่วนลดทั้งหมวด: %</div>
            <input  type="number" class="form-control txt_catediscount text-center ml-left" style="width:30%;" value="{$data['bercate_discount']}" min="0" max="100"  placeholder="% ส่วนลด"> 
            <div style="text-align:start; margin-bottom: .5rem; margin-top: 1rem; font-size:16px;">ระยะเวลาส่วนลด</div>
                <div class="col-6 " style="gap:.75rem;display:flex;" >
                    <div class="form-group">
                        <div class="input-group date" style="display:flex;">
                            <input type="text" class="form-control pull-right" id="edit-ad-date-display" placeholder="dd/mm/yyyy" value="">
                            <input type="hidden" class="form-control pull-right" id="edit-input-date-display" value="">
                            <div class="bootstrap-timepicker">
                                <div class="input-group">
                                    <input type="text" class="form-control timepicker" id="edit-time-display" name="edit-time-display" placeholder="เวลา" value="">
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group date" style="display:flex;">
                            <input type="text" class="form-control pull-right" id="edit-ad-date-hidden" placeholder="dd/mm/yyyy" value="">
                            <input type="hidden" class="form-control pull-right" id="edit-input-date-hidden" value="">
                            <div class="bootstrap-timepicker">
                                <div class="input-group">
                                    <input type="text" class="form-control timepicker" id="edit-time-hidden" name="edit-time-hidden" placeholder="เวลา" value="">
                                </div>
                            </div> 
                        </div>
                    </div>
                </div> 
            </div> 
            <div style="text-align:start;">ลำดับการแสดงผล:</div>
            <input  class="swal2-input txt_priority" placeholder="กรุณาใส่ตัวเลข" value="{$data['priority']}">

            <div class="seo-title" style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;" onclick="collapSeo()">
                <label>บทความสำหรับ SEO</label><i class="fas fa-arrow-down"></i>
            </div>
            <hr>
            <div class="seo-block" style="display:none">
                <div style="text-align:start;">H1:</div>
                <input  class="swal2-input seo_h1 " value="{$data['bercate_h1']} " placeholder="H1">
                <div style="text-align:start;">H2:</div>
                <input  class="swal2-input seo_h2 " value="{$data['bercate_h2']} " placeholder="H2">
                <div style="text-align:start;">เนื้อหา:</div>
                <textarea type="text" class="swal2-textarea seo_content " id="seo-content" name="seo-content">{$data['bercate_content']}</textarea>
                <br>
                <div style="text-align:start;">Meta Title:</div>
                <input  class="swal2-input seo_title " id="seo-title" value="{$data['meta_title']}" placeholder="Meta title">
                <div style="text-align:start;">Meta Description:</div>
                <textarea type="text" class="swal2-textarea seo_description " id="seo-description" name="seo-description">{$data['meta_description']}</textarea>
            </div>
            <hr>
        HTML;
    }
    function viewProductCateModeA($data, $fnHTML) {
        $camera = ($data['thumbnail'] != "")?"hidden":"";
        $thumbnail = ($data['thumbnail'] == "")?"hidden":"";
        $dateBegin = explode(" ", $data['discount_begin']);
        $dateExpire = explode(" ", $data['discount_begin']);

        return <<<HTML
            <div class="cate-blog-icon">  
                <div>
                    <label for="">Category icon <i class="fa fa-hospital-o" aria-hidden="true"></i> </label>
                    <div class="form-group form-add-images">
                        <div id="image-preview">
                            <label for="image-upload" class="image-label">
                                <i class="fa fa-camera {$camera}" ></i>
                            </label>
                            <div class="blog-preview-add">
                                <div class="col-img-preview {$thumbnail}" id="col_img_preview_1" data-id="1" > 
                                    <img class="preview-img" id="preview_img_1" src="/{$data['thumbnail']}">
                                </div>
                            </div>
                            <input type="file" name="imagesadd[]" class="exampleInputFile" id="add-images-content" data-preview="blog-preview-add" data-type="add"/>
                        </div>
                        <input type="hidden" id="add-images-content-hidden" name="add-images-content-hidden" value="{$data['thumbnail']}" required>  
                        <input id="swal-input2" class="swal2-input txt_abbrev" style="text-align:center;" placeholder="ชื่อย่อ" value="{$data['bercate_title']}">
                    </div> 
                </div>
            </div>
            <div style="text-align:start; font-weight:bold;">ชื่อหมวด:</div>
            <input  class="swal2-input txt_catename" placeholder="ชื่อหมวด"  value="{$data['bercate_name']}">
            
            <div class="cate-discount">
                <label class="container">แสดงเฉพาะหมวด
                    <input type="checkbox" id="cateDisCountOnly">
                    <span class="checkmark"></span>
                </label>
            </div>
            <div style="text-align:start;">ส่วนลดทั้งหมวด: %</div>
            <input  type="number" class="form-control txt_catediscount text-center ml-left "style="width:30%;" value="{$data['bercate_discount']}" min="0" max="100"  placeholder="% ส่วนลด"> 
            <div style="text-align:start; margin-bottom: .5rem; margin-top: 1rem; font-size:16px;">ระยะเวลาส่วนลด</div>
                <div class="col-6 " style="gap:.75rem;display:flex;" >
                    <div class="form-group">
                        <div class="input-group date" style="display:flex;">
                            <input type="text" class="form-control pull-right" id="edit-ad-date-display" placeholder="dd/mm/yyyy" value="">
                            <input type="hidden" class="form-control pull-right" id="edit-input-date-display" value="">
                            <div class="bootstrap-timepicker">
                                <div class="input-group">
                                    <input type="text" class="form-control timepicker" id="edit-time-display" name="edit-time-display" placeholder="เวลา" value="">
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group date" style="display:flex;">
                            <input type="text" class="form-control pull-right" id="edit-ad-date-hidden" placeholder="dd/mm/yyyy" value="">
                            <input type="hidden" class="form-control pull-right" id="edit-input-date-hidden" value="">
                            <div class="bootstrap-timepicker">
                                <div class="input-group">
                                    <input type="text" class="form-control timepicker" id="edit-time-hidden" name="edit-time-hidden" placeholder="เวลา" value="">
                                </div>
                            </div> 
                        </div>
                    </div>
                </div> 
            </div> 
            <div style="text-align:start; font-weight:bold;">ลำดับการแสดงผล:</div>
            <input  class="swal2-input txt_priority " value="{$data['priority']}" placeholder="กรุณาใส่ตัวเลข">
            <div style="text-align:start; font-weight:bold;">ข้อมูลชุดตัวเลขจาก 7 หลักหลัง</div> 
            {$fnHTML}
            <div class="seo-title" style="display: inline-block;align-items:center;cursor:pointer;margin-top:10px; width:100%;" onclick="collapSeo()">
                <label style="float:left;">บทความสำหรับ SEO</label><i class="fas fa-arrow-down" style="float:right;padding: .25rem;"></i></div>
            <div class="seo-block" style="display:none">
                <div style="text-align:start;">H1:</div>
                <input  class="swal2-input seo_h1 " value="{$data['bercate_h1']}" placeholder="H1">
                <div style="text-align:start;">H2:</div>
                <input  class="swal2-input seo_h2 " value="{$data['bercate_h2']}" placeholder="H2">
                <div style="text-align:start;">เนื้อหา:</div>
                <textarea type="text" class="swal2-textarea seo_content " id="seo-content" name="seo-content">{$data['bercate_content']}</textarea>
                <br>
                <div style="text-align:start;">Meta Title:</div>
                <input  class="swal2-input seo_title " id="seo-title" value="{$data['meta_title']}" placeholder="Meta title">
                <div style="text-align:start;">Meta Description:</div>
                <textarea type="text" class="swal2-textarea seo_description " id="seo-description" name="seo-description">{$data['meta_description']}</textarea>
            </div>
            <hr>
        HTML;
    }
    function productConfigAdd($option) {
        return <<<HTML
          <div class="me-swal-title">หมายเลข: </div>
                <input type="tel" maxlength="10" class="swal2-input txt_tel" placeholder="0989999999"  value="">
                <div class="me-swal-title">ราคา:</div>
                <input  class="swal2-input txt_price" placeholder="999"  value="">
                <div class="blog-discount">
                <div class="me-swal-title">ส่วนลด: </div>
                <input type="tel" maxlength="3" class="swal2-input txt_discount" placeholder="0"  value="">
                <div> % </div>
            </div>
            <div class="me-swal-title">เครือข่าย:</div>
            <div class="slc-add-ber">
                <select class="swal2-input slc_network">
                    {$option}
                </select>
            </div>
            <div class="switch-form add-ber">
                <div class="col-md-12 switch-btn btnProductDisplay">
                    <span class="title-switch-btn">Display: </span>
                    <div class="toggle-switch ts-active">
                        <span class="switch"></span>
                        <input type="hidden" class="form-control" id="product_display" value="yes">
                    </div>
                </div>
                <div class="col-md-12 switch-btn btnProductPin">
                    <span class="title-switch-btn">VIP: </span>
                    <div class="toggle-switch ">
                        <span class="switch"></span>
                        <input type="hidden" class="form-control" id="product_pin" value="no">
                    </div>
                </div>
                <div class="col-md-12 switch-btn btnProductSold">
                    <span class="title-switch-btn">Sold: </span>
                    <div class="toggle-switch ">
                        <span class="switch"></span>
                        <input type="hidden" class="form-control" id="product_sold" value="no">
                    </div>
                </div> 
            </div>
        HTML;
    }
    function productConfigEdit($ber, $option){

        $soldBtn =($ber->product_sold == "yes")?"ts-active":"";
        $displayBtn = ($ber->display == "yes")?"ts-active":"";
        $vipBtn = ($ber->product_pin == "yes")?"ts-active":"";
        return <<<HTML
            <div class="me-swal-title">หมายเลข: </div>
                <input type="tel" maxlength="10" class="swal2-input txt_tel" placeholder="0989999999"  value="{$ber->product_phone}">
                <div class="me-swal-title">ราคา:</div>
                <input  class="swal2-input txt_price" placeholder="999"  value="{$ber->product_price}">
                <div class="blog-discount">
                    <div class="me-swal-title">ส่วนลด: </div>
                    <input type="tel" maxlength="3" class="swal2-input txt_discount" placeholder="0"  value="{$ber->product_discount}">
                    <div> % </div>
                </div>
                <div class="me-swal-title">เครือข่าย:</div>
                <div class="slc-add-ber">
                    <select class="swal2-input slc_network">{$option}</select>
                </div>
                <div class="switch-form add-ber">
                    <div class="col-md-12 switch-btn btnProductDisplay">
                        <span class="title-switch-btn">Display: </span>
                        <div class="toggle-switch {$displayBtn}">
                            <span class="switch"></span>
                        </div>
                        <input type="hidden" class="form-control" id="product_display" value="{$ber->display}">

                    </div>
                    <div class="col-md-12 switch-btn btnProductPin">
                        <span class="title-switch-btn">VIP: </span>
                        <div class="toggle-switch {$vipBtn}">
                            <span class="switch"></span>
                        </div>
                        <input type="hidden" class="form-control" id="product_pin" value="{$ber->product_pin}">

                    </div>
                    <div class="col-md-12 switch-btn btnProductSold">
                        <span class="title-switch-btn">Sold: </span>
                        <div class="toggle-switch {$soldBtn}">
                            <span class="switch"></span>
                        </div>
                        <input type="hidden" class="form-control" id="product_sold" value="{$ber->product_sold}">
                    </div> 
                </div>
        HTML;
    }
}

