<div class="content-wrapper berddpage emspage">
    <section class="content-header">
      <h1>
        <i class="far fa-paper-plane"></i> บันทึกการจัดส่งสินค้า 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> หน้าหลัก</a></li>
        <li class="active">บันทึกการจัดส่งสินค้า</li>
      </ol>
    </section>  
    <section class="content newForm " >
        <div class="row"> 
            <div class="col-xs-12  ">			
                <div class="box box-primary">
                    <div class="box-body"> 
                        <div class="box-head-action">							 
                          <span>ข้อมูลการจัดส่งสินค้า </span>
                        </div>
			               <hr>
			               <table id="carrier-grid" class="table table-striped table-bordered table-hover no-footer" width="100%"> 
                          <thead> 
                             <tr>
                                <th>เลขที่รายการ</th>
                                <th>เบอร์ที่ซื้อ</th>
                                <th>ชื่อผู้ซื้อ</th>  
                                <th>ราคาเบอร์ <span style="color:grey;">[รวมทั้งหมด]</span></th>     
                                <th>วันที่ส่ง</th> 
                                <th>EMS</th>
                                <th>การจัดส่ง</th>  
                                <th>Action</th>  
                             </tr>
                           </thead>  
                      </table>
                     </div>
                 </div>
		     </div> 
     </section> 
<div>

<!-- css -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">

<!-- script -->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.pack.js?v=2.1.7"></script>
<script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script> 
<script src="<?php echo SITE_URL; ?>js/pages/carrier/carrier.js?v=<?=date('his')?>"></script>
<script src="<?php echo SITE_URL; ?>plugins/jquery-confirm/js/jquery-confirm.min.js"></script>
 