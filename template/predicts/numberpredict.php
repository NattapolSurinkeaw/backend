<div class="content-wrapper page-predict-numb">
    <section class="content-header">
      <h1>
        <i class="fas fa-yin-yang"></i> ทำนายเบอร์จากความหมาย 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> หน้าหลัก</a></li>

        <li class="active">ทำนายเบอร์จากความหมาย</li>
      </ol>
    </section>  
    <section class="content newForm predictCate">
      <!-- <button  type="button" onclick="edit_background_color()" class="addagentForm edit_background_color  btn "><i class="fas fa-palette fa-lg"></i> ตั้งค่าสีพื้นหลัง </button> -->
      <div class="row"> 
        <div class="col-lg-10 col-md-12 col-xs-12">			
          <div class="box box-primary">
            <div class="box-body"> 
			        <div class="box-head-action">							 
                <span class="btn-back unslc-txt "><i class="fas fa-long-arrow-alt-left"> </i> ออกจาก [หมวดย่อย<span class="name-cate"></span>] </span>
			          <button  type="button" onclick="add_category_numb()" data-name="prepare_add_numbcate" class="addCategoryNumb  btn "><i class="fa fa-fw fa-plus"></i> เพิ่มหมวดหมู่</button>
			         </div>
			        <hr>
		         
              <div id="predicts-cate-tables" class="toggle">
                <table id="predicts-grid" class="table table-striped table-bordered table-hover no-footer "  width="100%"> 
                  <thead>  
                  <tr>
                    <th class="">ลำดับ</th>
                    <th class="">ชื่อหมวดหมู่</th>  
                    <th class="">ชื่อแบบย่อ</th>  
                    <th class="text-center">ปุ่มเสริมดวงด้าน</th>       
                    <th class="text-center">จัดการ</th>
                  </tr>
                  </thead>
                </table>
              </div>
              <div id="predicts-numb-tables" class="toggle inactive" >
                <table id="predicts-numb-grid" class="table table-striped table-bordered table-hover no-footer" width="100%"> 
                  <thead> 
                  <tr>
                    <th class="">ลำดับ</th>
                    <th class="">ชื่อหมวดหมู่</th>  
                    <th class="">เลขที่ต้องการ</th>  
                    <th class="">เลขที่ไม่ต้องการ</th>  
                    <th class="text-center">การแสดงผล</th>    
                    <th class="text-center">จัดการ</th>
                  </tr>
                  </thead>
                </table>
              </div>
         
             </div>
           </div>
		  </div>			
     </section>

</div>

  <!-- css -->
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/predicts.css?v=<?=date('dhis')?>">
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/meStyle.css?v=<?=date('dhis')?>"> 

  <!-- script -->
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script> 
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script type="text/javascript" src="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.pack.js?v=2.1.7"></script>
  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>	 
  <script src="<?php echo SITE_URL; ?>js/pages/predicts/predictnumb.js?v=<?=date('dhis')?>"></script>

 