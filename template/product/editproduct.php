<!-- Modal Edit Content -->      
<div id="modalEditContent" class="modal fade blog-content blog-content-lg" role="dialog">

  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> แก้รายละเอียดสินค้า</h4>
      </div>

      <div class="modal-body">
        <div class="row body-row-content">
          <div class="col-content col-md-3">
            <div class="box box-content-cate">

              <div class="box-header with-border">
                <h3 class="box-title">วัน</h3>
              </div>
              <div class="box-body" id="edit-blog-category-days">
              </div>

              <div class="box-header with-border">
                <h3 class="box-title">เบอร์มงคล</h3>
              </div>
              <div class="box-body" id="edit-blog-category-bermongkol">
              </div>

              <div class="box-header with-border">
                <h3 class="box-title">ผลรวมดี</h3>
              </div>
              <div class="box-body" id="edit-blog-category-power">
              </div>

              <div class="box-header with-border">
                <h3 class="box-title">โปรโมชั่น/แพคเกจ</h3>
              </div>
              <div class="box-body" id="edit-blog-category-promotion">
              </div>

              <div class="box-header with-border">
                <h3 class="box-title">เครือข่าย</h3>
              </div>
              <div class="box-body" id="edit-blog-category-network">
              </div>

            </div>
          </div>

          <div class="col-content col-md-9 scrollbar" id="scrollbar-edit">
            <form id="form-edit-content">

              <div class="col-md-12">
                <div class="form-group form-edit-images">
                  <label>อัพโหลดภาพสินค้า</label>
                  <div id="image-preview">
                    <div class="blog-preview-edit"></div>
                    <input type="file" name="imagesedit[]" class="exampleInputFile" id="edit-images-content" data-preview="blog-preview-edit" data-type="edit" />
                  </div>
                  <span class="help-block add-images-error">Please select images file!</span>
                  <input type="hidden" id="edit-images-content-hidden">
                  <div class="b-row space-15"></div>                                            
                </div>
              </div>

              <div class="col-md-12"> 
                <div class="blog-more-images">
                  <label>รูปภาพเพิ่มเติม</label>
                  <div class="box box-tag">
                    <div id="prog-edit"></div>
                    <div class="overlay" id="overlay-edit-more-img" style="display: none; margin-top: 5px; border-radius: 0;">
                      <i class="fa fa-refresh fa-spin"></i>
                    </div>
                    <div class="box-body">
                      <div id="show-img-more"></div>
                      <div class="blog-show-image">
                        <div id="image-preview">
                          <label for="image-upload" class="image-label">
                            <i class="fa fa-camera"></i>
                          </label>
                          <input type="file" name="moreimagesedit[]" class="exampleInputFile" id="edit-more-images" data-preview="preview-edit-more-img" data-type="edit" multiple="" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group form-edit-title">
                  <label>ชื่อสินค้า</label>
                  <input type="text" class="form-control" id="edit-title" name="edit-title" placeholder="Title">
                  <span class="help-block edit-title-error">Please fill out this field.</span>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label>คำสำคัญ</label>
                  <input type="text" class="form-control" id="edit-keyword" name="edit-keyword" placeholder="Keyword">
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group form-edit-description">
                  <label>คำอธิบายสินค้า</label>
                  <input type="text" class="form-control" id="edit-description" name="edit-description" placeholder="Description">
                  <span class="help-block edit-description-error">Please fill out this field.</span>
                </div> 
              </div>

              <div class="col-md-12">
                <div class="form-group form-edit-slug">
                  <label>URL</label>
                  <div class="input-group">
                    <span class="input-group-addon input-group-url"><?=ROOT_URL?></span>
                    <input type="text" class="form-control" id="edit-slug" name="edit-slug" placeholder="">
                  </div>
                  <input type="hidden" id="current-url">
                  <span class="help-block edit-slug-error">Please fill out this field.</span>
                </div>
              </div>

              <?php
              if($_SESSION['topic']=='yes') {
              ?>
              <div class="col-md-12">
                <div class="form-group">
                  <label>สถานะเบอร์</label>
                  <input type="text" class="form-control" id="edit-topic" name="edit-topic" placeholder="special attribute">
                </div>
              </div>
              <?php
              }
              ?>

              <?php
              if($_SESSION['SEO']=='yes') {
              ?>
              <div class="col-md-12">
                <div class="form-group">
                  <label>(HTML)</label>
                  <textarea class="form-control" rows="3" id="edit-freetag" name="edit-freetag" placeholder="Enter ..."></textarea>
                </div> 
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label>H1</label>
                  <input type="text" class="form-control" id="edit-h1" name="edit-h1" placeholder="Text for H1">
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label>H2</label>
                  <input type="text" class="form-control" id="edit-h2" name="edit-h2" placeholder="Text for H2">
                </div>  
              </div>
              <?php
              }
              ?>

              <div class="col-md-6">
                <div class="form-group">
                  <label>จำนวนสินค้า</label>
                  <input type="number" class="form-control" id="edit-amount" name="edit-amount" min="0">
                </div>  
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label>ราคาขาย</label>
                  <input type="number" class="form-control" id="edit-saleprice" name="edit-saleprice" min="0">
                </div>
              </div>

              <div class="col-md-12" style=" border: 1px solid #d2d6de; padding: 10px; margin: 10px 0px;">
                <div class="form-group">
                  <label>โชว์ NEW จนถึงวันที่ (ปี-เดือน-วัน) </label>
                  <input type="text" class="form-control" id="edit-shownewdate" name="edit-shownewdate" disabled >
                  <button type="button" class="btn btn-info" id="inc_shownewdate" style="    margin-top: 10px;
                    position: relative;
                    left: 50%;
                    transform: translateX(-50%);">
                    <i class="fa fa-arrow-up"></i> เพิ่มวันที่แสดงผล NEW
                  </button>
                </div>
              </div>

              <div class="col-md-4" style="display: none;">
                <div class="form-group">
                  <label>ราคาพิเศษ</label>
                  <input type="number" class="form-control" id="edit-specialprice" name="edit-specialprice" min="0">
                </div>  
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label>เลือกสี</label>
                  <input type="color" class="form-control" id="edit-color_dot" name="edit-color_dot" value="">
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label>รายละเอียดสินค้า</label>
                  <textarea class="form-input" id="edit-content" name="edit-content"></textarea>
                </div>  
              </div>

              <div class="col-md-12"> 
                <div class="form-group">
                  <label>ลิงค์วีดีโอ</label>
                  <input type="text" class="form-control" id="edit-video" name="edit-video" placeholder="youtube or facebook">
                </div>
              </div>

              <div class="col-md-12"> 
                <div class="blog-content-tag">
                  <label>แท็ก</label>
                  <div class="box box-tag">
                    <div class="box-body">
                      <div class="form-group">
                        <input type="text" class="form-control" id="edit-search-tag" name="edit-search-tag" placeholder="ค้นหาแท็ก">
                        <div class="sub-tag" id="searchtagresult"></div>
                      </div>

                      <div class="form-group">
                        <input type="text" class="form-control" id="edit-add-tag" name="edit-add-tag" placeholder="เพิ่มแท็ก">
                      </div>
                      <div class="edit-blog-tag form-group" id="edit-blog-tag"></div>
                    </div>
                  </div>  
                </div> 
              </div> 

              <div class="col-md-12"> 
                <div class="blog-content-social">
                  <label>ลิงค์โซเชียล</label>
                  <div class="box box-tag">
                    <div class="box-body">
                      <div class="form-social"> 
                        <i class="fa fa-facebook-square"></i>
                        <input type="text" class="form-control" id="edit-link-fb" name="edit-link-fb" placeholder="Fackbook EX: https://www.facebook.com/20531316728/posts/10154009990506729/">
                      </div>
                      <div class="form-social"> 
                        <i class="fa fa-twitter-square"></i>
                        <input type="text" class="form-control" id="edit-link-tw" name="edit-link-tw" placeholder="Twitter EX: https://twitter.com/example/status/568091707801092097">
                      </div>
                      <div class="form-social"> 
                        <i class="fa fa-instagram"></i>
                        <input type="text" class="form-control" id="edit-link-ig" name="edit-link-ig" placeholder="Instagram EX:https://www.instagram.com/p/BCxr3rJhpe1/">
                      </div>
                    </div>
                  </div>  
                </div> 
              </div>

              <div class="col-md-12"> 
                <label>วันที่สร้าง</label>
              </div>
              <div class="col-md-6"> 
                <div class="form-group">
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right" id="date-display" placeholder="วันที่">
                        <input type="hidden" class="form-control pull-right" id="date-display-hidden">
                    </div>
                </div>
              </div>
              <div class="col-md-6"> 
                <div class="form-group">
                    <div class="bootstrap-timepicker">
                      <div class="input-group">
                          <div class="input-group-addon">
                              <i class="fa fa-clock-o"></i>
                          </div>
                          <input type="text" class="form-control timepicker" id="time-display" placeholder="เวลา">
                      </div>
                  </div> 
                </div>
              </div>

              <div class="col-md-12"> 
                <label>วันที่หมดอายุ</label>
              </div>
              <div class="col-md-6"> 
                <div class="form-group">
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right" id="date-expire" placeholder="วันที่">
                        <input type="hidden" class="form-control pull-right" id="date-expire-hidden">
                    </div>
                </div>
              </div>
              <div class="col-md-6"> 
                <div class="form-group">
                    <div class="bootstrap-timepicker">
                      <div class="input-group">
                          <div class="input-group-addon">
                              <i class="fa fa-clock-o"></i>
                          </div>
                          <input type="text" class="form-control timepicker" id="time-expire" placeholder="เวลา">
                      </div>
                  </div> 
                </div>
              </div>

              <div class="col-md-6">  
                <div class="form-group">
                  <label>แสดงผลบนเว็บไซต์</label>
                  <select class="form-control" name="edit-display" id="edit-display" style="width: 100%;">
                      <option id="edit-display-yes" value="yes">แสดง</option>
                      <option id="edit-display-no" value="no">ซ่อน</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">  
                <div class="form-group">
                  <label>ปักหมุด</label>
                  <select class="form-control" name="edit-pin" id="edit-pin" style="width: 100%;">
                      <option id="edit-pin-no" value="no">ไม่</option>
                      <option id="edit-pin-yes" value="yes">ใช่</option>
                  </select>
                </div>
              </div>
              <input type="hidden" class="form-control" id="edit-content-id">
            </form>
          </div>

        </div>
      </div>
      <div class="modal-footer "> 
        <input type="hidden" id="submit-type">
        <input type="hidden" id="date-created">
        <button type="submit" class="btn btn-default pull-left" id="reset-edit">
          <i class="fa fa-repeat" aria-hidden="true"></i> ล้างค่า
        </button>
        <button type="submit" class="btn btn-success pull-right" id="save-edit">
          <i class="fa fa-floppy-o"></i> บันทึก
        </button>
      </div>
    </div>
  </div>
</div>