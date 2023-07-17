<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-upload"></i> อัพโหลด PDF
      <small>( <?php echo $language_fullname['display_name']; ?> ) </small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['home']; //หน้าหลัก     
                                                                            ?></a></li>
      <li class="active"> อัพโหลด PDF <?php //echo $LANG_LABEL['sales']; //ผู้ดูแลระบบ    
                                        ?></li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-9">
        <div class="box box-primary kt:box-shadow">
          <div style="display: block; width: 100%; text-align:right;">
            <a class="btn kt:btn-info" 
              onclick="showFormAddUploadPdf()" 
              style=" padding: 8px 40px; margin: 10px 10px 5px;color:white"
            >
              <i class="fa fa-plus"></i> เพิ่ม PDF</a>
          </div>
          <div class="box-body">
            <table id="UploadPdf-grid" class="table table-striped table-bordered table-hover no-footer" width="100%">
              <thead>
                <tr>
                  <th></th>
                  <th>ลำดับ</th>
                  <th>หมวดหมู่</th>
                  <th>ชื่อ</th>
                  <th>Link PDF</th>
                  <th>Preview</th>
                  <th>จัดการ</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-3" id="form-add" >
        <div class="box box-primary kt:box-shadow" id="formUploadPdf" style="display:none">
          <div class="box-header kt:box-header-border-radius" style="text-align:center; background-color: #1e9dea; color:white;">
            อัพโหลด PDF
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12"><label style="text-align:center;display:block;" for="">หมวดหมู่</label></div>
              <div class="col-md-12"><input type="text" class="form-control" id="pdfcategory" placeholder="หมวดหมู่ "></div>
              <div class="col-md-12" style="margin-top:10px;"><label style="text-align:center;display:block;" for="">ชื่อ</label></div>
              <div class="col-md-12"><input type="text" class="form-control" id="pdfname" placeholder="ชื่อ "></div>
              <div class="col-md-12" style="margin-top:10px;">
                <form id="formupload">
                  <input type="file" name="inputFile" id="inputFile" style="display:none;">
                  <label for="">คลิกเลือกไฟล์</label>

                  <img id="img-handle-upload" src="/upload/excel/upload.png" style="margin:auto;display:block;width:200px;height:200px;cursor: pointer;padding: 20px;border: 1px solid #e3e3e3;" alt="">
                  <label for="" style="display:block;text-align:center;font-size:1em;color:red;">***(ไฟล์ pdf เท่านั้น)</label>
                  <br>
                  <label for="" style="display:block;text-align:center;">ไฟล์ที่เลือก: <span class="showFileName">คุณยังไม่ได้เลือกไฟล์</span></label>

                  <button type="submit" class="btn kt:btn-success" style="display:block;margin-top:10px;margin-right:auto;margin-left:auto;padding:10px 40px;"><i class="fa fa-upload" aria-hidden="true"></i> ยืนยันอัพโหลดไฟล์</button>
                </form>
              </div>
            </div>
          </div>
        </div>


        <div class="box box-primary kt:box-shadow" id="edit-formUploadPdf" style="display:none">
          <div class="box-header kt:box-header-border-radius" style="text-align:center; background-color: #1e9dea; color:white;">
            แก้ไขข้อมูล PDF
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12"><label style="text-align:center;display:block;" for="">แก้ไขหมวดหมู่</label></div>
              <div class="col-md-12"><input type="text" class="form-control" id="edit-pdfcategory" placeholder="หมวดหมู่ "></div>
              <div class="col-md-12" style="margin-top:10px;"><label style="text-align:center;display:block;" for="">ชื่อ</label></div>
              <div class="col-md-12"><input type="text" class="form-control" id="edit-pdfname" placeholder="ชื่อ "></div>
              <div class="col-md-12" style="margin-top:10px;">
                <form id="edit-formupload">
                  <!-- <input type="file" name="inputFile" id="inputFile" style="display:none;">
                  <label for="">คลิกเลือกไฟล์</label>

                  <img id="img-handle-upload" src="/upload/excel/upload.png" style="margin:auto;display:block;width:200px;height:200px;cursor: pointer;padding: 20px;border: 1px solid #e3e3e3;" alt="">
                  <label for="" style="display:block;text-align:center;font-size:1em;color:red;">***(ไฟล์ pdf เท่านั้น)</label>
                  <br>
                  <label for="" style="display:block;text-align:center;">ไฟล์ที่เลือก: <span class="showFileName">คุณยังไม่ได้เลือกไฟล์</span></label> -->
                  <input type="hidden" value="" id="edit-pdfid">
                  <button type="submit" class="btn kt:btn-success" style="display:block;margin-top:10px;margin-right:auto;margin-left:auto;padding:10px 40px;"><i class="fa fa-edit" aria-hidden="true"></i> แก้ไขข้อมูล</button>
                </form>
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
<script src="<?php echo SITE_URL; ?>js/pages/uploadpdf/uploadpdf.js"></script>