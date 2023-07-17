<?php  $province = $mydata->get_province(); ?> 
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-user-secret"></i>สมาชิก (Member)
      <small>( <?php echo $language_fullname['display_name']; ?> ) </small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['home']; //หน้าหลัก                                                ?></a></li>
      <li class="active">สมาชิก (Member)</li>
    </ol>
  </section>  
  <section class="content">
    <div class="row"> 
      <div class="col-md-9">
        <div class="box box-primary"> 
          <div style="display: block; width: 100%; text-align:right;"> 
            <!-- <a class="btn kt:btn-warning" style=" padding: 8px 40px; margin: 10px 10px 5px;color:white" 
              onclick="openFormAddImage(event)">
              <i class="fa fa-pencil"></i>
              แก้ไขรูปภาพหน้า สมัครสมาชิก
            </a> -->
            <!-- <a class="btn kt:btn-info" style=" padding: 8px 40px; margin: 10px 10px 5px;color:white" onclick="openFormAddMember(event)">
              <i class="fa fa-plus"></i>
              เพิ่มสมาชิก
            </a> --> 
          </div> 
          <form autocomplete="off" > 
          <div class="box-body">
            <table id="members-grid" class="table table-striped table-bordered table-hover no-footer" width="100%">
              <thead>
                <tr> 
                  <th>id</th>  
                  <th>ชื่อ</th>  
                  <th>เบอร์ติดต่อ</th> 
                  <th>จำนวนเงินคงเหลือ</th>  
                  <th>อัพเดทล่าสุด</th>  
                  <th>สถานะผู้ใช้งาน</th>  
                  <th style="width:200px;">จัดการ</th>  
                </tr> 
              </thead>
            </table>
          </div>
          </form> 
        </div>
      </div>

      <div class="col-md-3" id="formMembers" style="display:none;">
        <div class="box box-primary">
          <div class="box-body">
            <div class="row header-mng-blog">
              <div class="col-md-12 m-id membersManagements" >
                <span data-name="btnMemberDetail" class="btn-mng active"><label>ข้อมูลทั่วไป</label></span>
                <!-- <span data-name="btnMemberDeposit"  class="btn-mng"><label>ข้อมูลการฝาก</label></span>
                <span data-name="btnMemberDraw"  class="btn-mng"><label>ข้อมูลการถอน</label></span>  -->
              </div> 
            </div> 
             
            <div class="row  mng-blog btnMemberDetail active"> 
              <div class="col-md-12 m-id"><label for="">member ID</label></div>
              <div class="col-md-12 m-id"><input type="text" class="form-control" id="member-id" disabled></div>
              <div class="col-md-12" style="margin-top: 5px;"><label for="">Username <span id="email_err" style="color:red"></span> </label></div>
              <div class="col-md-12"><input type="text" class="form-control" disabled id="member-email"></div>
              <div class="col-md-12 m-id"style="margin-top: 5px;"><label for="">รหัสบัตรประชาชน</label></div>
              <div class="col-md-12 m-id"><input type="text" class="form-control" id="member-identification" disabled></div>
              <div class="col-md-12" style="margin-top: 5px;"><label for="">member name</label></div>
              <div class="col-md-12"><input type="text" class="form-control" id="member-name"></div>
              <div class="col-md-12" style="margin-top: 5px;"><label for="">member address</label></div>
              <div class="col-md-12">
              <select id="slc_provinces" class="form-control">
                <option SELECTED disabled value="0">เลือกจังหวัด</option>
                <?php 
                   foreach($province as $key => $value){ 
                      echo"<option value=".$value['id'].">".$value['province_name']."</option>";
                   } 
                ?> 
              </select></div> 
              <div class="col-md-12" style="margin-top: 5px;"><label for="">member phone</label></div>
              <div class="col-md-12"><input type="text" class="form-control" id="member-phone"></div>
        
    
              <div class="col-md-12 btnMemberDetail-bottom-blog "> 
                <div class="select-member-status">
                      <label for=""><i class="fa fa-calendar" aria-hidden="true"></i> สถานะผู้ใช้: </label>
                      <span class="m_status"> 
                          <select id="slc_status_member" class="form-control">
                            <option value="active">เปิดใช้งาน (Active)</option>
                            <option value="inactive">ปิดใช้งาน (Inactive)</option>
                            <option value="banned">ห้ามใช้งาน (Banned)</option>
                          </select>
                      </span> 
                  </div>
                  <div>
                      <label for=""><i class="fa fa-calendar" aria-hidden="true"></i> วันที่เปิดใช้: </label>
                      <span class="m_date_activate">03 - 02 - 2563 </span> 
                  </div>
                  <div>
                      <label for=""><i class="fa fa-calendar" aria-hidden="true"></i> วันหมดอายุ: </label>
                      <span class="m_date_expire">03 - 02 - 2564</span> 
                  </div>
                  <div>
                      <label for=""> อายุงานการใช้งาน: </label>
                      <span class="m_years"> 2 ปี </span> 
                  </div>
                  <div>
                      <label for="">ยอดเงินคงเหลือ: </label>
                      <span class="m_credit"> 19,565 บาท</span> 
                  </div>
                  <div>
                      <label  style="color:red;" for=""><i class="fa fa-star"></i> ยอดซื้อ: </label>
                      <span class="m_star_buy">200 ดวง</span> 
                  </div>
                  <div>
                      <label style="color:orange;" for=""><i class="fa fa-star"></i> ยอดขาย: </label>
                      <span class="m_star_sale">100 ดวง</span> 
                  </div>
        
                <!-- <div class="col-md-12" style="margin-top: 5px;">
                  <label for="">member status</label> 
                  <span>
                    <div class="toggle-switch">
                      <span class="switch"></span>
                    </div>
                    <input type="hidden" name="" id="member-status">
                  </span>
                </div>  -->
                
              </div> 
              <!-- <div class="col-md-12" style="margin-top: 20px; text-align:center;" id="formbtnpasswd">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#MemberEditPassword">
                    แก้ไขรหัสผ่าน
                </button>
              </div>  -->

              <!-- <div class="col-md-12" style="margin-top: 5px;" id="formlbpasswd"><label for="">member password</label></div> -->
              <!-- <div class="col-md-12" id="forminputpasswd"><input type="password" class="form-control" id="member-password"></div> -->
             
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
              
              <div class="col-md-12" style="margin-top: 20px; text-align:center; " id="formbtnwithdraw">
                <button type="button" class="btn btn-primary btn_withdraw_back" id="redeem_credit" data-id="">
                    ถอนเงิน [ชั่วคราว] ของสมาชิก
                </button>
              </div>
             </div>

<!--              
             <div class="row mng-blog btnMemberDeposit"> 
              <div class="blog-body"> check check </div>
             </div>
             <div class="row mng-blog btnMemberDraw"> 
              <div class="blog-body"> test tset </div>
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
<script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/members/members.js?v=<?=date('his')?>"></script>