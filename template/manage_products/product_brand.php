<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-user-secret"></i>แบรนด์สินค้า
      <small>( <?php echo $language_fullname['display_name']; ?> ) </small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['home']; //หน้าหลัก     
                                                                            ?></a></li>
      <li class="active">แบรนด์สินค้า <?php //echo $LANG_LABEL['sales']; //ผู้ดูแลระบบ  ?></li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-9">
        <div class="box box-primary kt:box-shadow">
          <div style="display: block; width: 100%; text-align:right;">
            <a class="btn kt:btn-info" onclick="showFormAddProductBrand()" style=" padding: 8px 40px; margin: 10px 10px 5px;color:white"><i class="fa fa-plus"></i> เพิ่มแบรนด์</a>
          </div>
          <div class="box-body">
            <table id="product-cate-grid" class="table table-striped table-bordered table-hover no-footer" width="100%">
              <thead>
                <tr>
                  <th></th>
                  <th>id</th>
                  <th>ลำดับความสำคัญ</th>
                  <th>แบรนด์สินค้า</th>
                  <th>แสดงผลบนเว็บไซต์</th>
                  <th style="width:200px;">จัดการ</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="box box-primary kt:box-shadow" id="form-add-product-cate" style="display:none">
          <div class="box-header kt:box-header-border-radius" style="text-align:center; background-color: #1e9dea; color:white;">
            เพิ่มแบรนด์สินค้า
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12"><label style="text-align:center;display:block;" for="">ชื่อแบรนด์สินค้า</label></div>
              <div class="col-md-12"><input type="text" class="form-control" id="product_brand_name" placeholder="ชื่อแบรนด์สินค้า "></div>
              <div class="col-md-12" style="margin-top: 10px;">
                <div class="form-group form-add-images">
                  <label style="text-align:center;display:block;" >อัพโหลดรูปภาพแบรนด์สินค้า<?php //echo $LANG_LABEL['uploadimage'];
                                            ?> <span style="color:red">*</span> </label>
                  <div id="image-preview" style="margin:auto;">
                    <label for="image-upload" class="image-label">
                      <i class="fa fa-camera"></i>
                    </label>
                    <div class="blog-preview-add"></div>
                    <input type="file" name="imagesadd[]" class="exampleInputFile" id="add-images-content" data-preview="blog-preview-add" data-type="add" />
                  </div>
                  <input type="hidden" id="add-images-content-hidden" name="add-images-content-hidden" required>
                  <div class="b-row space-15"></div>
                </div>
              </div>
              <div class="col-md-12 ve_product_brand" style="display:none;"><label for="">วันที่สร้าง</label></div>
              <div class="col-md-12 ve_product_brand" style="display:none;"><input type="text" class="form-control" disabled id="product_brand_create"></div>
              <div class="col-md-12 ve_product_brand" style="display:none;" style="margin-top: 5px;"><label for="">วันที่แก้ไข</label></div>
              <div class="col-md-12 ve_product_brand" style="display:none;"><input type="text" class="form-control" disabled id="product_brand_update"></div>
              <div class="col-md-12" style="margin-top: 5px;">
                  <label for="" style="text-align:center;display:block;">แสดงผลบนเว็บไซต์</label>
                  <div class="toggle-switch" style="margin: auto">
                    <span class="switch"></span>
                  </div>
                  <input type="hidden" class="form-control" id="product_brand_status" value="no">
              </div>
              <div class="col-md-12" style="margin-top: 10px;">
                <label for="" style="text-align:center;display:block;">ลำดับความสำคัญ</label>
                <input type="number" class="form-control" id="product_brand_priority" style="width:100px;margin:auto" value="1">
              </div>
              <div class="col-md-12">
                <hr>
              </div>
              <div class="col-md-12 text-center">
                <input type="hidden" id="edit_product_brand_id">
                <button class="btn kt:btn-success" id="add_product_brand" style="display:none;color: white;padding: 8px 40px;"><i class="fa fa-check" aria-hidden="true"></i> เพิ่มแบรนด์</button>
                <button class="btn kt:btn-success" id="edit_product_brand" style="display:none;color: white;padding: 8px 40px;"><i class="fa fa-check" aria-hidden="true"></i> แก้ไขแบรนด์</button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- popup status download  -->
  <div class="wrapper-pop">
    <div class="pop">
        <div class="loader10"></div>
        <h2 class="loadper" style="text-align:center;padding-top:50px;">0 %</h2>
        <h4 style="padding-top:30px">กำลังอัพโหลดรูปภาพ</h4>
    </div>
  </div>



</div>

<!-- css -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/animate.css">

<script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datepicker/js/bootstrap-datepicker.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datepicker/js/locales/bootstrap-datepicker.th.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/manage_products/product_brand.js"></script>