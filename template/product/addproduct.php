<!-- Modal Add Content -->      
<div id="modalAddContent" class="modal fade blog-content blog-content-lg" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> เพิ่มสินค้า</h4>
      </div>

      <div class="modal-body">
        <div class="row body-row-content">
          <div class="col-content col-md-3">
            <div class="box box-content-cate">
            
              <div class="box-header with-border">
                <h3 class="box-title">วัน</h3>
              </div>
              <div class="box-body" id="add-blog-category-days">
              </div>

              <div class="box-header with-border">
                <h3 class="box-title">เบอร์มงคล</h3>
              </div>
              <div class="box-body" id="add-blog-category-bermongkol">
              </div>

              <div class="box-header with-border">
                <h3 class="box-title">ผลรวมดี</h3>
              </div>
              <div class="box-body" id="add-blog-category-power">
              </div>

              <div class="box-header with-border">
                <h3 class="box-title">โปรโมชั่น/แพคเกจ</h3>
              </div>
              <div class="box-body" id="add-blog-category-promotion">
              </div>

              <div class="box-header with-border">
                <h3 class="box-title">เครือข่าย</h3>
              </div>
              <div class="box-body" id="add-blog-category-network">
              </div>
            </div>
          </div>

          <div class="col-content col-md-9 scrollbar" id="scrollbar-add">
            <form id="form-add-content">

              <div class="col-md-12">
                <div class="form-group form-add-images">
                  <label>อัพโหลดภาพสินค้า</label>
                  <div id="image-preview">
                    <label for="image-upload" class="image-label">
                      <i class="fa fa-camera"></i>
                    </label>
                    <div class="blog-preview-add"></div>
                    <input type="file" name="imagesadd[]" class="exampleInputFile" id="add-images-content" data-preview="blog-preview-add" data-type="add" />
                  </div>
                  <span class="help-block add-images-error">Please select images file!</span>
                  <input type="hidden" id="add-images-content-hidden">
                  <div class="b-row space-15"></div>                                            
                </div>
              </div>

              <div class="col-md-12"> 
                <div class="blog-more-images">
                  <label>รูปภาพเพิ่มเติม</label>
                  <div class="box box-tag">
                    <div id="prog-add"></div>
                    <div class="overlay" id="overlay-add-more-img" style="display: none; margin-top: 5px; border-radius: 0;">
                      <i class="fa fa-refresh fa-spin"></i>
                    </div>
                    <div class="box-body">
                      <div id="show-add-img-more"></div>
                      <div class="blog-show-image">
                        <div id="image-preview">
                          <label for="image-upload" class="image-label">
                            <i class="fa fa-camera"></i>
                          </label>
                          <input type="file" name="moreimagesadd[]" class="exampleInputFile" id="add-more-images" data-preview="preview-add-more-img" data-type="add" multiple="" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group form-add-title">
                  <label>ชื่อสินค้า</label>
                  <input type="text" class="form-control" id="add-title" name="add-title" placeholder="Title">
                  <span class="help-block add-title-error">Please fill out this field.</span>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label>คำสำคัญ</label>
                  <input type="text" class="form-control" id="add-keyword" name="add-keyword" placeholder="Keyword">
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group form-add-description">
                  <label>คำอธิบายสินค้า</label>
                  <input type="text" class="form-control" id="add-description" name="add-description" placeholder="Description">
                  <span class="help-block add-description-error">Please fill out this field.</span>
                </div> 
              </div>

              <div class="col-md-12">
                <div class="form-group form-add-slug">
                  <label>URL</label>
                  <div class="input-group">
                    <span class="input-group-addon input-group-url"><?=ROOT_URL?></span>
                    <input type="text" class="form-control" id="add-slug" name="add-slug" placeholder="">
                  </div>     
                  <span class="help-block add-slug-error">Please fill out this field.</span>
                </div>
              </div>

              <?php
              if($_SESSION['topic']=='yes') {
              ?>
            <!--   <div class="col-md-12">
                <div class="form-group">
                  <label>สถานะเบอร์</label>
                  <input type="text" class="form-control" id="add-topic" name="add-topic" placeholder="special attribute">
                </div>
              </div> -->
              <?php
              }
              ?>

              <?php
              if($_SESSION['SEO']=='yes') {
              ?>
              <div class="col-md-12">
                <div class="form-group">
                  <label>(HTML)</label>
                  <textarea class="form-control" rows="3" id="add-freetag" name="add-freetag" placeholder="Enter ..."></textarea>
                </div> 
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label>H1</label>
                  <input type="text" class="form-control" id="add-h1" name="add-h1" placeholder="Text for H1">
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label>H2</label>
                  <input type="text" class="form-control" id="add-h2" name="add-h2" placeholder="Text for H2">
                </div>  
              </div>
              <?php
              }
              ?>

              <div class="col-md-6">
                <div class="form-group">
                  <label>จำนวนสินค้า</label>
                  <input type="number" class="form-control" id="add-amount" name="add-amount" min="0" value="1" max = "1">
                </div>  
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label>ราคาขาย</label>
                  <input type="number" class="form-control" id="add-saleprice" name="add-saleprice" min="0" value="0">
                </div>
              </div>

              <div class="col-md-4" style="display: none;">
                <div class="form-group">
                  <label>ราคาพิเศษ</label>
                  <input type="number" class="form-control" id="add-specialprice" name="add-specialprice" min="0" value="0">
                </div>  
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label>เลือกสี</label>
                  <input type="color" class="form-control" id="add-color_dot" name="add-color_dot" value="#40ff00">
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label>รายละเอียดสินค้า</label>
                  <textarea class="form-input" id="add-content" name="add-content"></textarea>
                </div>  
              </div>

              <div class="col-md-12"> 
                <div class="form-group">
                  <label>ลิงค์วีดีโอ</label>
                  <input type="text" class="form-control" id="add-video" name="add-video" placeholder="youtube or facebook">
                </div>
              </div>

              <div class="col-md-12"> 
                <div class="blog-content-tag">
                  <label>แท็ก</label>
                  <div class="box box-tag">
                    <div class="box-body">
                      <div class="form-group">
                        <input type="text" class="form-control" id="add-search-tag" name="add-search-tag" placeholder="ค้นหาแท็ก">
                        <div class="sub-tag" id="add-searchtagresult"></div>
                      </div>

                      <div class="form-group">
                        <input type="text" class="form-control" id="add-tag" name="add-tag" placeholder="เพิ่มแท็ก">
                      </div>
                      <div class="edit-blog-tag form-group" id="add-blog-tag"></div>
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
                        <input type="text" class="form-control" id="add-link-fb" name="add-link-fb" placeholder="Fackbook EX: https://www.facebook.com/20531316728/posts/10154009990506729/">
                      </div>
                      <div class="form-social"> 
                        <i class="fa fa-twitter-square"></i>
                        <input type="text" class="form-control" id="add-link-tw" name="add-link-tw" placeholder="Twitter EX: https://twitter.com/example/status/568091707801092097">
                      </div>
                      <div class="form-social"> 
                        <i class="fa fa-instagram"></i>
                        <input type="text" class="form-control" id="add-link-ig" name="add-link-ig" placeholder="Instagram EX:https://www.instagram.com/p/BCxr3rJhpe1/">
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
                        <input type="text" class="form-control pull-right" id="add-date-display" placeholder="วันที่">
                        <input type="hidden" class="form-control pull-right" id="add-date-display-hidden">
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
                          <input type="text" class="form-control timepicker" id="add-time-display" placeholder="เวลา">
                      </div>
                  </div> 
                </div>
              </div>

<!--               <div class="col-md-12"> 
                <label>วันที่หมดอายุ</label>
              </div>
              <div class="col-md-6"> 
                <div class="form-group">
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right" id="add-date-expire" placeholder="วันที่">
                        <input type="hidden" class="form-control pull-right" id="add-date-expire-hidden">
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
                          <input type="text" class="form-control timepicker" id="add-time-expire" placeholder="เวลา">
                      </div>
                  </div> 
                </div>
              </div> -->

              <div class="col-md-6">  
                <div class="form-group">
                  <label>แสดงผลบนเว็บไซต์</label>
                  <select class="form-control" name="add-display" id="add-display" style="width: 100%;">
                      <option id="add-display-yes" value="yes">แสดง</option>
                      <option id="add-display-no" value="no">ซ่อน</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">  
                <div class="form-group">
                  <label>ปักหมุด</label>
                  <select class="form-control" name="add-pin" id="add-pin" style="width: 100%;">
                      <option id="add-pin-no" value="no">ไม่</option>
                      <option id="add-pin-yes" value="yes">ใช่</option>
                  </select>
                </div>
              </div>
            </form>
          </div>

        </div>
      </div>
      <div class="modal-footer "> 
        <button type="submit" class="btn btn-default pull-left" id="reset-add">
          <i class="fa fa-repeat" aria-hidden="true"></i> ล้างค่า
        </button>
        <button type="submit" class="btn btn-success pull-right" id="save-add">
          <i class="fa fa-floppy-o"></i> บันทึก
        </button>
      </div>
    </div>
  </div>
</div>