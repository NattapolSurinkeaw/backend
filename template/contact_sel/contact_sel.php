
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-id-card-o" aria-hidden="true"></i> ติดต่อเรา
      <small>( <?php echo $language_fullname['display_name']; ?> ) </small>
    </h1>
    <ol class="breadcrumb">
      <li>
        <a href="<?php echo SITE_URL; ?>">
          <i class="fa fa-dashboard"></i> 
            <?php echo $LANG_LABEL['home'];?>
        </a>
      </li>
      <li class="active">ติดต่อเรา</li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary kt:box-shadow">
          <!-- <div style="display: block; width: 100%; text-align:right;"> -->
            <!-- <a class="btn kt:btn-info" onclick="showFormAddProductCate()" style=" padding: 8px 40px; margin: 10px 10px 5px;color:white"><i class="fa fa-plus"></i> เพิ่มหมวดหมู่</a> -->
          <!-- </div> -->
          <div class="box-body">
            <div class="" style="margin-top: 20px;">
              <label for="">Logo Image <i class="fa fa-hospital-o" aria-hidden="true"></i> </label>
 
              <div class="form-group form-add-images">
                  <div id="image-preview">
                    <label for="image-upload" class="image-label">
                      <i class="fa fa-camera"></i>
                    </label>
                    <div class="blog-preview-add"></div>
                    <input type="file" name="imagesadd[]" class="exampleInputFile" id="add-images-content" data-preview="blog-preview-add" data-type="add"/>
                  </div>
                  <input type="hidden" id="add-images-content-hidden" name="add-images-content-hidden" required>
                  <div class="b-row space-15"></div>                                            
              </div>
          
            </div>
            <div class="" style="margin-top: 20px;">
              <label for="">Logo text</label>
              <input type="text" id="logo-text" class="form-control" placeholder="ช่องใส่ คำอธิบาย logo">
            </div>
            <div class="" style="">
              <label for="">ชื่อร้าน/บริษัท <i class="fa fa-hospital-o" aria-hidden="true"></i> </label>
              <textarea name="" class="form-control" id="contact-name" cols="10" rows="3" placeholder="ช่องใส่ชื่อโรงบาลหรือคลีนิค"></textarea>
            </div>
            <div class="" style="margin-top: 10px;">
              <label for="">ที่อยู่ <i class="fa fa-location-arrow" aria-hidden="true"></i></label>
              <textarea name="" class="form-control" id="contact-address" cols="10" rows="3" placeholder="ช่องใส่ที่อยู่"></textarea>
            </div>
            <div class="" style="margin-top: 10px;">
              <div style="display:flex;">
                <label for="">เบอร์โทรศัพท์ <i class="fa fa-phone" aria-hidden="true"></i></label>
                <label class="switch" id="phoneToggle"  title="เปิด-ปิดการแสดงช่องทางติดต่อ">
                  <input type="checkbox" id="contact-phone-display" onchange="toggleContactDisplay(this)" value="phone">
                  <span class="slider round"></span>
                </label>
              </div>
              <input type="tel" id="contact-phone" class="form-control" placeholder="ช่องใส่เบอร์โทรศัพท์">

            </div>
            <div class="" style="margin-top: 10px;">
              <label for="">Email <i class="fa fa-envelope-o" aria-hidden="true"></i></label>
              <input type="email" id="contact-email" class="form-control" placeholder="ช่องใส่ Email">
            </div>
            <div class="" style="margin-top: 10px;">
              <label for="#">แผนที่ <i class="fa fa-map-marker" aria-hidden="true"></i></label> <a href="#"  onclick="previewMap('map')">กดดูแผนที่</a>
              <textarea name="" class="form-control" id="contact-map" cols="10" rows="6" placeholder="ช่องใส่ map"></textarea>
            </div>

              <div class="" style="margin-top: 20px;">
                  <div style="display:flex;">
                    <label for="">Line ID <i class="fab fa-line" aria-hidden="true"></i></label> 
                    <a target="_blank" href="" id="contact-lineId-href" style="padding:0 .25rem;">Preview</a>
                    <label class="switch" id="lineToggle"  title="เปิด-ปิดการแสดงช่องทางติดต่อ">
                      <input type="checkbox" id="contact-line-display" onchange="toggleContactDisplay(this)" value="line">
                      <span class="slider round"></span>
                    </label>
                  </div>
                  <input type="text" id="contact-lineId" class="form-control" placeholder="ช่องใส่ Line ID">
              </div> 
             <div class="" style="margin-top: 20px;">
                  <label for="">OG DESCRIPTION </label>  
                  <input type="text" id="og-description" class="form-control" placeholder="คำอธิบาย OG TAG">
              </div> 

            <div class="" style="margin-top: 20px;">
              <label for="">Link Youtube <i class="fab fa-youtube"></i></label> <a target="_blank" href="" id="contact-youtube-href" style="padding:0 .25rem;">Preview</a>
              <input type="text" id="contact-youtube" class="form-control" placeholder="ช่องใส่ Link Youtube">
            </div>
            <div class="" style="margin-top: 20px;">
              <div style="display:flex;">
                <label for="">Link Facebook <i class="fab fa-facebook-square"></i> </label> <a target="_blank" href="" id="contact-facebook-href" style="padding:0 .25rem;">Preview</a>
                <label class="switch" id="msgToggle"  title="เปิด-ปิดการแสดงช่องทางติดต่อ">
                  <input type="checkbox" id="contact-facebook-display"onchange="toggleContactDisplay(this)"  value="facebook">
                  <span class="slider round"></span>
                </label>
              </div>
              <input type="text" id="contact-facebook" class="form-control" placeholder="ช่องใส่ Link Facebook">
            </div>
            <!-- <div class="" style="margin-top: 20px;">
              <label for="">Link Twitter <i class="fa fa-twitter" aria-hidden="true"></i> </label> <a target="_blank" href="" id="contact-twitter-href" style="padding:0 .25rem;">Preview</a>
              <input type="text" id="contact-twitter" class="form-control" placeholder="ช่องใส่ Link twitter">
            </div> -->
            <div class="" style="margin-top: 20px;">
              <label for="">Link Instagram <i class="fab fa-instagram-square"></i> </label> <a target="_blank" href="" id="contact-instagram-href" style="padding:0 .25rem;">Preview</a>
              <input type="text" id="contact-instagram" class="form-control" placeholder="ช่องใส่ Link instagram">
            </div>

            <div class="" style="margin-top: 20px;">
              <label for="">คู่มือการทำนาย <i class="fas fa-book" aria-hidden="true"></i> </label> 
              <a  id="manual-preidct-href"  onclick="previewMap('predict')" style="padding:0 .25rem;">Preview</a>
              <input type="text" id="manual-preidct" class="form-control" placeholder="ช่องใส่ Link predict">
            </div>

            <div class="" style="margin-top: 20px;">
              <label for="">Footer Title <i class="fas fa-book" aria-hidden="true"></i> </label> 
              <a  id="footer-title-href"  onclick="previewMap('footer-title')" style="padding:0 .25rem;">Preview</a>
              <input type="text" id="footer-title" class="form-control" placeholder="ช่องใส่ข้อมูล">
            </div>

            <div class="" style="margin-top: 20px;">
              <label for="">Footer Description <i class="fas fa-book" aria-hidden="true"></i> 
              </label> <a  id="footer-txt-href"  onclick="previewMap('footer-desc')" style="padding:0 .25rem;">Preview</a>
              <input type="text" id="footer-txt" class="form-control" placeholder="ช่องใส่ข้อมูล">
            </div>


            <div style="margin-top: 20px;">
              <a href="" class="btn btn-success pull-right kt:btn-success" style="padding:10px 40px;" onclick="addDataContact(event)"><i class="fa fa-save"></i> บันทึกข้อมูล</a>
            </div>

          </div>
        </div>
      </div>

    </div>
  </section>


  <div class="modal" id="model-preview-map" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document" style="width:768px;">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" style="text-align:center"></h3>

        </div>
        <div class="modal-body" id="model-map-show-body">
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeModal(event)">Close</button>
          <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
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
<script src="<?php echo SITE_URL; ?>js/pages/contact_sel/contact_sel.js?v=<?=date('mdhis')?>"></script>