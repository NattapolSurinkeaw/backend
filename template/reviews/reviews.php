<div class="content-wrapper reviewspage">
    <section class="content-header">
      <h1>
        <i class="fa fa-comments"></i> Reviews 
      </h1>
      <ol class="breadcrumb"> 
        <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> หน้าหลัก</a></li>
        <li class="active">Reviews</li>
      </ol>
    </section>  
    <section class="content newForm ">
      <div class="row"> 
        <div class="col-lg-8 col-xs-12">			
          <div class="box box-primary">
            <div class="box-body"> 
                <div class="box-head-action">							 
			          <span>ข้อมูลการรีวิว</span>
			          <button  type="button" onclick="prepare_reviews()" class="btn btn-table-add"><i class="fa fa-fw fa-plus"></i> เพิ่มรีวิว </button>
			     </div>
			    <hr>
		        <table id="admin-grid" class="table table-striped table-bordered table-hover no-footer" width="100%"> 
                 <thead> 
                    <tr>
                      <th class="text-center">ลำดับ</th>
                      <th>เรื่อง</th>     
                      <th>ภาพรีวิว</th>      
                      <th>PIN</th>                    
                      <th>จัดการ</th>
                    </tr> 
                  </thead>
              </table>
            </div>
          </div>
	    </div> 
			 
      </div>
		</section>
  </div>
 </div>
  <!-- css -->
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/reviews.css?v=<?=rand(1,1000)?>">
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/meStyle.css?v=<?=rand(1,1000)?>">

 <!-- script -->
 <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <script type="text/javascript" src="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.pack.js?v=2.1.7"></script>
 <script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
 <script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
 <script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script> 
 <script src="<?php echo SITE_URL; ?>js/pages/reviews/reviews.js?v=<?=date('ymd-his')?>"></script>
 <script src="<?php echo SITE_URL; ?>plugins/jquery-confirm/js/jquery-confirm.min.js"></script>


  
 