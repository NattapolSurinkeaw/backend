<div class="content-wrapper">
  <section class="content-header">
    <h1>
     <i class="fa fa-credit-card-alt" aria-hidden="true"></i> ประวัติการเงินของผู้ใช้ (User payments history)
      <small>( <?php echo $language_fullname['display_name']; ?> ) </small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['home']; //หน้าหลัก     
                                                                            ?></a></li>
      <li class="active">สมาชิก (Member)</li>
    </ol>
  </section>
 
  <section class="content">
    <div class="row">

      <div class="col-md-12">
        <div class="box box-primary"> 
          <div id="blog-payments">
            
            <div class="blog-selection">
              <label>Action: </label>
              <select class="form-control" id="slc_action">
                <option value="all">ทั้งหมด</option>
                <option value="deposit">เติมเงิน</option>
                <option value="withdraw">ถอนเงิน</option> 
              </select>
            </div>
            <div class="blog-selection">
              <label>สถานะรายการ: </label>
              <select class="form-control" id="slc_status">
                <option value="all">ทั้งหมด</option>
                <option value="0">รอตรวจสอบ</option>
                <option value="1">เสร็จสิ้น</option>
                <option value="2">ผิดพลาด</option> 
              </select>
            </div>  
          </div>
          <form autocomplete="off" > 
            <div class="box-body">
              <table id="members-grid" class="table table-striped table-bordered table-hover no-footer" width="100%">
                <thead>
                  <tr> 
                    <th>เลขที่</th>
                    <th>Slip</th>
                    <th>ชื่อ</th>
                    <th>Action</th>
                    <th>จำนวนเงิน</th>
                    <th>คำอธิบาย</th>  
                    <th>เงินคงเหลือ (ปัจจุบัน)</th>
                    <th>โอนไปยังธนาคาร (ชื่อบัญชี)</th>
                    <th>เลขบัญชี</th>
                    <th>วันที่โอน</th>
                    <th>วันที่อนุมัติ</th>
                    <th>สถานะรายการ</th> 
                    <th style="text-align:center;">ผู้อนุมัติ</th> 
                  </tr>
                </thead>
              </table>
            </div>
            </form>
        </div>
      </div>

      <div class="col-md-4" id="formMembers" style="display:none;">
        <div class="box box-primary">
          <div class="box-body">
            <!-- <div class="row mmm-blog">
              <div class="col-md-12 m-id membersManagements unslc-txt" >
                <span class="btnMemberDetail active" data-type="details"><label>ข้อมูลทั่วไป</label></span>
                <span class="btnMemberDeposit" data-type="deposit"><label>ข้อมูลการฝาก</label></span>
                <span class="btnMemberDraw" data-type="draw"><label>ข้อมูลการถอน</label></span> 
              </div> 
             </div>  -->
            
            <div class="row mng-blog active" id="details"> 
              <div class="col-md-12 m-id"><label for="">member ID</label></div>
              <div class="col-md-12 m-id"><input type="text" class="form-control" id="member-id" disabled></div>
              <div class="col-md-12" style="margin-top: 5px;"><label for="">member name</label></div>
              <div class="col-md-12"><input type="text" class="form-control" id="member-name"></div>
              <div class="col-md-12" style="margin-top: 5px;"><label for="">member address</label></div>
              <div class="col-md-12">
                <textarea class="form-control" name="" id="member-address" cols="30" rows="2"></textarea>
              </div>
              <div class="col-md-12" style="margin-top: 5px;"><label for="">member phone</label></div>
              <div class="col-md-12"><input type="text" class="form-control" id="member-phone"></div>
              <div class="col-md-12" style="margin-top: 5px;"><label for="">member email <span id="email_err" style="color:red"></span> </label></div>
              <div class="col-md-12"><input type="text" class="form-control" id="member-email"></div>
              <div class="col-md-12" style="margin-top: 5px;" id="formbtnpasswd">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#MemberEditPassword">
                    แก้ไขรหัสผ่าน
                </button>
              </div>

              <div class="col-md-12" style="margin-top: 5px;" id="formlbpasswd"><label for="">member password</label></div>
              <div class="col-md-12" id="forminputpasswd"><input type="password" class="form-control" id="member-password"></div>
              <div class="col-md-12" style="margin-top: 5px;"><label for="">member status</label></div>
              <div class="col-md-12">
                <div class="toggle-switch">
                  <span class="switch"></span>
                </div>
                <input type="hidden" name="" id="member-status">
              </div>
              <div class="col-md-12 text-center" style="margin-top: 20px;">
                <button class="btn kt:btn-success" id="add-member" style="color: white;padding: 8px 40px;display:none;">
                  <i class="fa fa-check" aria-hidden="true"></i>
                  เพิ่มสมาชิก
                </button>
                <button class="btn kt:btn-success" id="edit-member" style="color: white;padding: 8px 40px;display:none;">
                  <i class="fa fa-check" aria-hidden="true"></i>
                  แก้ไขสมาชิก
                </button>
              </div>
             </div>


             <!-- <div class="row mng-blog" id="deposit"> 
                <div class="col-md-12 m-id"><label for="">รายการฝาก</label></div>
                <div class="col-md-12 m-id">
                    <div class="deposit-blog deposit-list">
                        <span class="blog-list"><p>aaaa</p></span> 
                        <span class="blog-list"><p>aaaa</p></span>  

                    </div>
                </div>
             </div>
             <div class="row mng-blog" id="draw"> 
                <div class="col-md-12 m-id"><label for="">รายการถอน</label></div>
                <div class="col-md-12 m-id">
                    <div class="draw-blog draw-list">
                        <span class="blog-list"><p>bbbb</p></span> 
                        <span class="blog-list"><p>bbbb</p></span>
                    </div>
                </div>
             </div> -->
          </div>
        </div>
      </div>

    </div>
  </section>
</div>


<div class="modal" tabindex="-1" role="dialog" id="MemberEditPassword">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">แก้ไขรหัสผ่าน</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <label for="member-edit-password">กรอกรหัสผ่าน  <span class="member-edit-password-error" style="color:red"></span> </label>
        <input type="password" class="form-control" id="member-edit-password" placeholder="ช่องกรอกรหัสผ่าน">
        <input type="hidden" id="memberIdEditPassword" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="closeFormMemberEditPassword"data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
        <button type="button" class="btn kt:btn-success" onclick="editMemberPassword(event)"><i class="fa fa-save"></i> Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="MemberUploadImage">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">อัพโหลด รูปภาพ</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <form id="formUploadImg">
          <input type="file" name="inputFile" id="inputFileImg" style="display:none;">

          <label for="" style="display:block;text-align:center;font-size:1em;color:red">(ไฟล์ jpg , jpeg , png เท่านั้น)</label>
          <img id="img-handle-upload-image" src="/upload/excel/upload.png" style="margin:auto;display:block;width:200px;height:200px;cursor: pointer;padding: 20px;border: 1px solid #e3e3e3;" alt="">

          <!-- <br> -->
          <!-- <label for="" style="display:block;text-align:center;">ไฟล์ที่เลือก: <span class="showFileNameImg">คุณยังไม่ได้เลือกไฟล์</span></label> -->
          <br>

          
          <button type="submit" class="btn kt:btn-success" style="display:block;margin-top:10px;margin-right:auto;margin-left:auto;padding:10px 40px;"><i class="fa fa-upload" aria-hidden="true"></i> ยืนยันอัพโหลดไฟล์</button>
        </form>

      </div>
      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div>

<!-- css -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/meStyle.css?v=<?=date('his')?>">
<script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/payments/payments.js?v=<?=date('his')?>"></script> 
 
<!-- <link rel="stylesheet" href="./plugins/fancybox-2.1.7/jquery.fancybox.css?v=2.1.7" type="text/css" media="screen" />
<script type="text/javascript" src="./plugins/fancybox-2.1.7/jque ry.fancybox.pack.js?v=2.1.7"></script>  
<link rel="stylesheet" href="./plugins/fancybox-2.1.7/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
<script type="text/javascript" src="./plugins/fancybox-2.1.7/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="./plugins/fancybox-2.1.7/helpers/jquery.fancybox-media.js?v=1.0.6"></script> 
<link rel="stylesheet" href="./plugins/fancybox-2.1.7/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
<script type="text/javascript" src="./plugins/fancybox-2.1.7/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script> -->
 

















