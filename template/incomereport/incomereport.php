<?php
  $details = $mydata->get_income_website();
  $income = $mydata->calc_income();

  
  
?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-file-text" aria-hidden="true"></i>  สรุปรายได้ (Income)
      <small>( <?php echo $language_fullname['display_name']; ?> ) </small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['home']; //หน้าหลัก     
                                                                            ?></a></li>
      <li class="active">สรุปรายได้ (Income)</li>
    </ol>
  </section> 
  <section class="content income-report">
    <div class="reportincome-blog-config unslc-txt">
      <div class="config-title"> 
        <div>
          <span>
            <p>ค่าสมาชิกรายปี: </p>
            <p class="register-msg"> <?=number_format($details['register_paid'])?> บาท  </p>
          </span>
          <span>
            <p>เติมเงินครั้งแรก: </p>
            <p class="fist-time-msg"> <?=number_format($details['first_time_paid'])?> บาท  </p>
          </span>
          <span>
            <p>เติมเงินขั้นต่ำ: </p>
            <p class="minimum-msg"> <?=number_format($details['minimum_paid'])?> บาท </p>
          </span> 
        </div> 
        <label>กดเพื่อปรับแต่งข้อมูล</label>
      </div> 
    </div> 
    <!-- -------------------------------------- -->
    <div class="reportincome-blog">
                <div class="body-report" >
                    <div class="row-report" > 
                        <div class="blog-report header-blog"> 
                            สรุปรายได้
                        </div>
            
                        <div class="blog-report">
                            <span class="blog-report-grid">
                                <label>ค่าสมัครสมาชิก: </label> 
                                <span class="register_total"><?=number_format($income['inc_register_total'])?> บาท</span>
                            </span>
                        </div>
                        <div class="blog-report">
                            <span class="blog-report-grid">
                                <label>% จากผู้ขาย: </label> 
                                <span class="postpaid_total"><?=number_format($income['inc_post_paid_total'])?> บาท</span>
                            </span>
                        </div>
                        <div class="blog-report"> 
                            <span class="blog-report-grid">
                                <label>% จากผู้ซื้อ: </label> 
                                <span class="buypaid_total"><?=number_format($income['inc_buy_paid_total'])?> บาท</span>
                            </span>
                        </div> 
                        <div class="blog-report" style="font-size: 1.2em;">
                            <span class="blog-report-grid netpay-blog" style="display: grid; grid-template-columns: 1fr 1fr;">
                                <label>รายได้สุทธิ: </label> 
                                <span>
                                      <label  class="netpay" data-number="0"><?=number_format($income['inc_netpay'])?></label> 
                                      บาท
                                </span> 
                            </span>
                        </div>
                        <!-- <div class="blog-report">
                            <span class="blog-report-grid date-blog">
                                <span>วันที่:</span> 
                                <span>เริ่มต้น</span> 
                                <span>จนถึง:</span> 
                                <span>ปัจจุบัน</span> 
                            </span>
                        </div> --> 
                    </div>
                </div>
           </div>
 
    
    <!-- --------------------------------------- -->
 
    <div class="row"> 
      <div class="col-md-12">
        <div class="box box-primary"> 
          <form autocomplete="off" > 
            <div id="blog-payments"> 
             <div class="blog-selection">
               <label >Action: </label>
               <select class="form-control" id="slc_action"> 
                 <option value="incomedeal" selected>ยอดซื้อ-ขาย</option> 
                 <option value="incomeregister">ยอดผู้ใข้งาน</option> 
               </select>
             </div>  
         
            <div class="blog-selection date_input_blog">
              <label >วันที่: </label>
              <div class="form-group">
                  <div class="input-group date" style="margin-left:5px;">
                      <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right inputDate" id="add-date-display-start"   autocomplete="off"  placeholder="<?php echo $LANG_LABEL['date'];?>">
                      <input type="hidden" class="form-control pull-right" id="add-date-display-start-hidden" name="add-date-display-start-hidden">
                  </div>
                </div>
            </div>
            <div class="blog-selection date_input_blog">
              <label >ถึงวันที่: </label>
              <div class="form-group">
                  <div class="input-group date" style="margin-left:5px;">
                      <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right  inputDate" id="add-date-display-expire"   autocomplete="off"  placeholder="<?php echo $LANG_LABEL['date'];?>">
                      <input type="hidden" class="form-control pull-right" id="add-date-display-expire-hidden" name="add-date-display-expire-hidden">
                  </div>
                </div>
            </div>
            <!-- <div class="blog-selection">
              <label>สถานะรายการ: </label>
              <select class="form-control" id="slc_status">
                <option value="all">ทั้งหมด</option>
                <option value="0">รอตรวจสอบ</option>
                <option value="1">เสร็จสิ้น</option>
                <option value="2">ผิดพลาด</option> 
              </select>
            </div> -->
          </div> 
     
         
 
            <div class="box-body" id="incomedeal"> 
              <table id="incomereport-grid" class="table table-striped table-bordered table-hover no-footer" width="100%">
                <thead>
                  <tr>  
                    <th>เลขที่</th> 
                    <th>ชื่อรายการ</th> 
                    <th>ราคาขายสินค้า</th>  
                    <th>หักจากผู้ขาย</th> 
                    <th>หักจากผู้ซื้อ</th>
                    <th>รายได้รวม</th> 
                    <th>วันที่ทำรายการ</th>  
                  </tr>
                </thead>
              </table>
            </div>
       
              <div class="box-body" id="incomeregister" style="display:none;">
                <table id="members-income-grid" class="table table-striped table-bordered table-hover no-footer" width="100%">
                  <thead>
                    <tr>  
                      <th>รหัสสมาชิก</th> 
                      <th>ชื่อสมาชิก</th>  
                      <th>วันที่เปิดใช้งาน</th>  
                      <th>วันหมดอายุ</th> 
                      <th>อายุการใช้งาน</th>
                      <th>จำนวนเงิน(รวมทุกปี)</th> 
                      <th>สถานะ</th> 
                    </tr>
                  </thead>
                </table>
              </div> 
            </form>

        </div>
      </div>
  <?php /*
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


             <div class="row mng-blog" id="deposit"> 
                <div class="col-md-12 m-id"><label for="">รายการฝาก</label></div>
                <div class="col-md-12 m-id">
                    <div class="deposit-blog deposit-list">
                        <span class="blog-list"><p>aaaa</p></span> 
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
             </div>
          </div>
        </div>
      </div>
 */ ?> 

 
 
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
<script src="<?php echo SITE_URL; ?>plugins/datepicker/js/bootstrap-datepicker.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datepicker/js/locales/bootstrap-datepicker.th.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/timepicker/bootstrap-timepicker.min.js"></script> 
<script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/incomereport/incomereport.js?v=<?=date('his')?>"></script>