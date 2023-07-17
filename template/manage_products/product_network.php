<style>
  .swal2-container .swal2-actions button{
    margin: 0 5px;
  }
</style>


<div class="loader-box">
  <div class="loader-body ">
    <div class="loader"></div>
  </div>
  <p style="font-weight:bold;">กำลังประมวลผล กรุณารอสักครู่</p>
</div>
<div class="content-wrapper manage-product"> 
  <section class="content-header">
    <h1>
      <i class="fas fa-mobile"></i> ตั้งค่าเครือข่าย
      <small>( <?php echo $language_fullname['display_name']; ?> ) </small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"> </i> <?php echo $LANG_LABEL['home']; //หน้าหลัก     
                                       ?></a></li>
      <li class="active">จัดการสินค้า <?php   //echo $LANG_LABEL['sales']; //ผู้ดูแลระบบ ?></li>
    </ol>
  </section> 

  
  <section class="content product_category_page" style="width:60%;margin:0;">
    <div class="row">
      <div class="col-xs-12">
        <div class="box box-danger kt:box-shadow">  
          <div class="header-tables-action py-3 px-3" style="margin-top: 1rem;margin-right: 1rem;">
            <a href="https://berhoro.com/backend/?page=manage_products&subpage=product_cate" class="btn kt:btn-danger"><i class="fas fa-undo"></i> กลับหมวดหมู่หลัก</a>
            <!-- <a href="https://berdedd.com/backend/?page=manage_products&subpage=product_cate" class="btn kt:btn-danger">
              <i class="fas fa-undo"></i> กลับหมวดหมู่หลัก
            </a> -->
            <!-- <a class="btn kt:btn-primary" data-toggle="modal" data-target="#modal-network-add">
              <i class="fa fa-plus"></i> เพิ่มเครือข่าย
            </a> -->
          </div>
          <div class="box-body w-100">
            <div id="boxber-network" class="active">
              <table id="table-network" class="table table-striped table-bordered table-hover no-footer" width="100%">
                <thead>
                  <tr>
                    <th style="width:80px">ลำดับ</th>
                    <th>ชื่อเครือข่าย</th> 
                    <th>รูปเครือข่าย</th> 
                    <th style="width:100px">จัดการ</th>
                  </tr>
                </thead>
                <tbody></tbody>
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

<!-- Modal -->
<div class="modal fade" id="modal-network-add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="background:#fff">
      <div class="modal-header" style="display:flex;position:relative;">
        <h3 class="modal-title" id="exampleModalLabel">เพิ่มเครือข่าย</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute;right: 1rem;top: 50%;transform: translateY(-50%);">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="add-network" enctype="multipart/form-data">
          <input type="hidden" class="form-control" name="action" value="add">
          <div class="form-group">
            <label>ชื่อเครือข่าย</label>
            <input type="text" class="form-control" name="add_name_network">
          </div>
          <div class="form-group">
            <label>รูปเครือข่าย</label>
            <input type="file" class="form-control-file" name="add_file_network" accept="image/png, image/jpeg">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" style="background-color:#7b7061;" data-dismiss="modal">ปิด</button>
        <button type="button" class="btn btn-primary" onclick="network_insert()">เพิ่ม</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal edit -->
<div class="modal fade" id="modal-network-edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="background:#fff">
      <div class="modal-header" style="display:flex;position:relative;">
        <h3 class="modal-title" id="exampleModalLabel">เพิ่มเครือข่าย</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute;right: 1rem;top: 50%;transform: translateY(-50%);">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="edit-network" enctype="multipart/form-data">
          <input type="hidden" class="form-control" name="action" value="update">
          <input type="hidden" class="form-control" name="network_id" value="">
          <div class="form-group">
            <label>ชื่อเครือข่าย</label>
            <input type="text" class="form-control" name="edit_name_network">
          </div>
          <div class="form-group">
            <label>รูปตัวอย่าง</label>
            <figure><img width="100" height="50" style="object-fit: contain;" src="" alt="" id="preview_pic_network"></figure>
          </div>
          <div class="form-group">
            <label>เปลี่ยนรูปเครือข่าย</label>
            <input type="file" class="form-control-file" name="edit_file_network" accept="image/png, image/jpeg">
          </div>
          <div class="form-group">
            <label>การแสดงผล</label>
            <div class="toggle-switch inTables">
                <span class="switch" data-id="0"></span>
            </div>
            <input type="hidden" class="form-control" id="network_status" value="">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" style="background-color:#7b7061;" data-dismiss="modal">ปิด</button>
        <button type="button" class="btn btn-primary" onclick="network_update()">แก้ไข</button>
      </div>
    </div>
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
<script src="<?php echo SITE_URL; ?>js/pages/manage_products/product_network.js?v=<?=date('ymdhis')?>"></script>