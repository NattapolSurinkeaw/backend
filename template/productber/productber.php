<div class="content-wrapper berddpage productBer"> 
  <div class="loader-box-cate">
    <div class="loader "></div>
    <p style="font-weight:bold;">กำลังประมวลผล กรุณารอสักครู่</p>
  </div>
  <div class="loader-box ">  
    <section class="content-header"> 
      <h1>
      <i class="fas fa-mobile"></i> เบอร์สินค้า 
        <small>( <?php echo $language_name['display_name']; ?> )</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> หน้าหลัก</a></li>
        <li class="active">เบอร์สินค้า</li>
      </ol>
    </section>  
    <!-- <section  class="filesUploadExcel">
        <div class="labUpload">
            <label>อัพโหลดไฟล์ excel เช่น .xlsx .xlsm .csv</label>
        </div>  
        <div>     
           <form action="" method="post" enctype="multipart/form-data" name="myform1" id="myform1">
              <div class="uploadExcelbtnForm">
                <input type="hidden" name="action" value="uploadExcelFile" />
                <input value="" class="formSelectFile" type="file" name="file_upload" style="display:none;"  id="file_upload" accept=".xlsx, .xls, .csv, application/vnd.ms-excel" /> 
                <span class="slcFile"> <label class="btnUpload">เลือกไฟล์</label><label class="txtNameUpload">No file selected</label> </span>    
                <input type="submit" id="submitButton"  name="submitButton" value="Upload">
              </div>
              <div class="networkMngt">
                <span class="btnFixForm btn btn-primary btnEditProduct active" > <i class="fa fa-edit"></i> จัดการสินค้า </span>
                <span class="btnFixForm btn btn-primary btnEditNetwork" > <i class="fa fa-edit"></i> จัดการระบบ </span>
                <span class="btnFixForm btn btn-primary btnGetAdsExcel" > <i class="fa fa-download"> </i> ADS export file</span>  
                <span class="btnFixForm btn btn-primary btnGetExcel" > <i class="fa fa-download"> </i> Export Excel </span>  
              </div>
           </form>    
           <div class="upLoadWaiting"></div>           
        </div>  
    </section>   -->
     <?php    
         include 'template/productber/productCate.php';
        //  include 'template/productber/productSec.php';
        //  include 'template/productber/network.php';
     ?>
    
    <div class="modal fade" id="loverformnumber" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel" style="font-weight:bold;">เพิ่มเบอร์คู่รัก</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
 
            <span class="txt-title"><p>เพิ่มชุดเลข</p></span>
            <span class="input-row-lover" data-row="1">
              <input type="text" class="form-control txt_lover" data-row="1">
              <i class="fa fa-plus"  data-row="1"></i>
            </span>
 
          </div>
          <div class="modal-footer">
            <button type="button" class="btn  btnSaveLoverNumber">บันทึกข้อมูล</button> 
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
          </div>
        </div>
      </div>
    </div> 
  </div>
 
  <!-- css -->
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css"> 
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/meStyle.css?v=<?=date('Ymdhis')?>"> 
  <!-- script -->
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script> 
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script type="text/javascript" src="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.pack.js?v=2.1.7"></script>
  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>	 
  <script src="<?php echo SITE_URL; ?>js/pages/productber/productber.js?v=<?=date('ymdhis')?>"></script>
  <script src="<?php echo SITE_URL; ?>plugins/jquery-confirm/js/jquery-confirm.min.js"></script>
 