

<div class="content-wrapper">
  <section class="content-header">
    <h1>
    <i class="fa fa-graduation-cap" aria-hidden="true"></i> จัดการข้อสอบ
      <small>( <?php echo $language_fullname['display_name']; ?> ) </small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['home']; //หน้าหลัก     
                                                                            ?></a></li>
      <li class="active">จัดการข้อสอบ</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">

      <div class="col-md-8">
        <div class="box box-primary">
          <div style="display: block; width: 100%; text-align:right;">
            <a class="btn kt:btn-primary" style=" padding: 8px 40px; margin: 10px 10px 5px;color:white" onclick="openFormAddImage(event)"><i class="fa fa-plus"></i> อัพโหลดรูปภาพ</a>
            <a class="btn kt:btn-info" style=" padding: 8px 40px; margin: 10px 10px 5px;color:white" onclick="openFormAddCerify(event)"><i class="fa fa-plus"></i> อัพโหลดข้อสอบ</a>
          </div>
          <div class="box-body">
            <table id="certify-grid" class="table table-striped table-bordered table-hover no-footer" width="100%">
              <thead>
                <tr>
                  <th></th>
                  <th>ลำดับ</th>
                  <th>หมวดหมู่</th>
                  <th>จำนวนข้อสอบ</th>
                  <th>เปอร์เซ็นที่จะให้ผ่าน</th>
                  <th>สถานะ</th>
                  <th style="width:200px;">จัดการ</th>
                  <th style="">ไฟล์</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-4" id="formUploadExcel" style="display:none;">
        <div class="box box-primary">
          <div style="display: block; width: 100%; text-align:center;">
            <h3>อัพโหลดข้อสอบ</h3>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <form id="formupload">
                  <input type="file" name="inputFile" id="inputFile" style="display:none;">
                  <label for="">คลิกเลือกไฟล์</label>

                  <img id="img-handle-upload" src="/upload/excel/upload.png" style="margin:auto;display:block;width:200px;height:200px;cursor: pointer;padding: 20px;border: 1px solid #e3e3e3;" alt="">
                  <label for="" style="display:block;text-align:center;font-size:1em;color:red;">***(ไฟล์ xlsx , xlsm , xls เท่านั้น)</label>
                  <br>
                  <label for="" style="display:block;text-align:center;">ไฟล์ที่เลือก: <span class="showFileName">คุณยังไม่ได้เลือกไฟล์</span></label>

                  <button type="submit" class="btn kt:btn-success" style="display:block;margin-top:10px;margin-right:auto;margin-left:auto;padding:10px 40px;"><i class="fa fa-upload" aria-hidden="true"></i> ยืนยันอัพโหลดไฟล์</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4" id="formUploadImgWrap" style="display:none;">
        <div class="box box-primary">
          <div style="display: block; width: 100%; text-align:center;">
            <h3>อัพโหลดรูปภาพสำหรับข้อสอบ</h3>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <form id="formUploadImg">
                  <input type="file" name="inputFile" id="inputFileImg" style="display:none;">

                  <label for="" style="display:block;text-align:center;font-size:1em;color:red">(ไฟล์ jpg , jpeg , png เท่านั้น)</label>
                  <img id="img-handle-upload-image" src="/upload/excel/upload.png" style="margin:auto;display:block;width:200px;height:200px;cursor: pointer;padding: 20px;border: 1px solid #e3e3e3;" alt="">

                  <!-- <br> -->
                  <!-- <label for="" style="display:block;text-align:center;">ไฟล์ที่เลือก: <span class="showFileNameImg">คุณยังไม่ได้เลือกไฟล์</span></label> -->
                  <br>
                  <label for="" class="titleLink" style="display:block"></label>
                  <textarea name="" class="form-control linkImg" id="" cols="30" rows="5" style="display:none">

                  </textarea>
                  <a href="" class="previewImg" target="_blank" style="display:none;">Preview รูปภาพ</a>
                  <button type="submit" class="btn kt:btn-success" style="display:block;margin-top:10px;margin-right:auto;margin-left:auto;padding:10px 40px;"><i class="fa fa-upload" aria-hidden="true"></i> ยืนยันอัพโหลดไฟล์</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>


    </div>
  </section>


  <div class="modal" id="model-orderGeneral-show" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width:1200px;">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" style="text-align:center" id="modal-general-date">Modal title</h3>
          <h3 class="modal-title" style="text-align:center" id="modal-general-order_id">Modal title</h3>
          <h3 class="modal-title" style="text-align:center" id="modal-general-member_name"></h3>
          <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal(event)">
              <span aria-hidden="true">&times;</span>
            </button> -->
        </div>
        <div class="modal-body" id="model-orderGeneral-show-body">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeModal(event)">Close</button>
          <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
        </div>
      </div>
    </div>
  </div>

</div>

<!-- popup status download  -->
<div class="wrapper-pop">
  <div class="pop">
    <div class="loader10"></div>
    <h2 class="loadper" style="text-align:center;padding-top:50px;">0 %</h2>
    <h4 style="padding-top:30px">กำลังอัพโหลดรูปภาพ</h4>
  </div>
</div>

<!-- css -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/animate.css">


<script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/certify/certify.js"></script>
