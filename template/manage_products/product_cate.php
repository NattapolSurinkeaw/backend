<div class="loader-box">
  <div class="loader-body ">
    <div class="loader"></div>
  </div>
  <p style="font-weight:bold;">กำลังประมวลผล กรุณารอสักครู่</p>
</div>
<div class="content-wrapper manage-product"> 
  <section class="content-header">
    <h1>
      <i class="fas fa-mobile"></i> หมวดหมู่สินค้า
      <small>( <?php echo $language_fullname['display_name']; ?> ) </small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"> </i> <?php echo $LANG_LABEL['home']; //หน้าหลัก     
                                       ?></a></li>
      <li class="active">หมวดหมู่สินค้า <?php   //echo $LANG_LABEL['sales']; //ผู้ดูแลระบบ    
                                      ?></li>
    </ol>
  </section> 

  <section  class="filesUploadExcel unslc-txt">
        <div class="labUpload" >
            <label>อัพโหลดไฟล์ excel เช่น .xlsx .xlsm .csv</label>
        </div>  
        <div>     
           <form action="#" method="post" enctype="multipart/form-data" name="myform1" id="myform1" > 
              <div class="uploadExcelbtnForm">
                <input type="hidden" name="action" value="uploadExcelFile" />
                <input value="" class="formSelectFile" type="file" name="file_upload"   id="file_upload" accept=".xlsx, .xls, .csv, application/vnd.ms-excel" /> 
                <span class="slcFile" > 
                  <label class="btnUpload">เลือกไฟล์</label>
                  <label class="txtNameUpload">No file selected</label> 
                  <input type="submit" id="submitButton"  name="submitButton" value="อัพโหลด">
                </span>    
                
              </div>
              <div class="networkMngt"> 
                <span class="btnFixForm btn btn-primary btnGetExcel" > <i class="fa fa-download"> </i> Export Excel </span>  
                <a class="btn btn-primary" onclick="gotoNetwork()"> <i class="fas fa-cog"></i> </i> ตั้งค่าเครือข่าย </a>  
              </div>
           </form>    
           <div class="upLoadWaiting"></div>           
        </div>  
    </section>  

  
  <section class="content product_category_page">
    <div class="row">
      <div class="col-xs-12">
        <div class="box box-primary kt:box-shadow">  
          <div class="header-tables-action">
            <a class="btn kt:btn-info btn-add-Category btnAddCategory active" ><i class="fa fa-plus"></i> เพิ่มหมวดหมู่</a>
            <a class="btn kt:btn-info btnBackToCategory active" ><i class="fas fa-long-arrow-alt-left"></i> กลับหมวดหมู่หลัก</a> 
            <a class="btn kt:btn-info btn-add-Category btnAddProduct">
              <i class="fa fa-plus"></i> เพิ่มสินค้า
            </a>
          </div>
          <div class="box-body">
            <div id="cate_products_table" class="active">
              <table id="product-cate-grid" class="table table-striped table-bordered table-hover no-footer active" width="100%">
                <thead>
                  <tr>
                    <th>ลำดับ</th>
                    <th>ชื่อหมวดหมู่</th> 
                    <th>รหัสหมวด</th>
                    <th class="text-center">ระบบอัตโนมัติ</th>
                    <th class="text-center">PIN</th>
                    <th class="text-center">การแสดงผล</th>
                    <th class="text-center">จัดการ</th>
                  </tr>
                </thead>
              </table>
            </div>
            <div id="products_table" >
              <table id="product-grid" class="table table-striped table-bordered table-hover no-footer" width="100%">
                <thead>
                  <tr>
                    <th>ลำดับ</th>
                    <th>หมายเลข</th> 
                    <th>ผลรวม</th>
                    <th>เครือข่าย</th>
                    <th>ราคา</th>
                    <th>Sold</th>
                    <th>ส่วนลด</th>
                    <th>แสดงผล</th>
                    <th>จัดการ</th>
                  </tr>
                </thead>
              </table>
            </div>

          </div>
        </div>
      </div> 

    </div>
  </section> 
  </div> 

  <!-- popup status download  -->
  <div class="wrapper-pop">
    <div class="pop">
        <div class="loader10"></div>
        <h2 class="loadper">0 %</h2>
        <h4>กำลังอัพโหลดรูปภาพ</h4>
    </div>
  </div>   
 
<!-- css -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/animate.css">
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/meStyle.css?v=<?=date('YmdHis')?>">
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/manage_product.css?v=<?=date('YmdHis')?>">
<script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datepicker/js/bootstrap-datepicker.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datepicker/js/locales/bootstrap-datepicker.th.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/manage_products/product_cate.js?v=1.2.1<?=date('ymdhis')?>"></script>
<script src="<?php echo SITE_URL; ?>js/pages/manage_products/product_upload.js?v=<?=date('ymdhis')?>"></script>
<script src="<?php echo SITE_URL; ?>js/pages/manage_products/product_subcate.js?v=<?=date('ymdhis')?>"></script>
