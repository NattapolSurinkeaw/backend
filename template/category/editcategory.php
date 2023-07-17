<!-- Modal Edit Category -->      
<div id="modalEditCategory" class="modal fade blog-content blog-content-lg" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> แก้ไขหมวดหมู่</h4>
      </div>

      <div class="modal-body">
        <div class="row body-row-content">
          <div class="col-content col-md-3">
            <div class="box box-content-cate">
              <div class="box-header with-border">
                <h3 class="box-title">หมวดหมู่</h3>
              </div>
              <div class="box-body category-tree" id="blog-category-tree">
                <div class="form-group">
                  <div class="radio">
                    <label>
                      <input type="radio" name="parent-id-edit" id="edit-cate-0" value="0" checked>หมวดหมู่หลัก
                    </label>
                  </div>
                  <?php
                    echo $mydata->get_cate_radio($category,'edit');
                  ?>
                </div>
                <input type="hidden" id="current-parent-id">
              </div>
            </div>
          </div>

          <div class="col-content col-md-9 scrollbar" id="scrollbar">
            <form id="form-edit-category">
              <div class="col-md-12">
                <div class="form-group form-edit-images">
                  <label>อัพโหลดรูปภาพ</label>
                  <div id="image-preview">
                    <div class="blog-preview-edit"></div>
                    <input type="file" name="imagesedit[]" class="exampleInputFile" id="edit-images-cate" data-preview="blog-preview-edit" data-type="edit" />
                  </div>
                  <span class="help-block edit-images-error">Please select images file!</span>
                  <input type="hidden" id="edit-images-cate-hidden">
                  <div class="b-row space-15"></div>                                            
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group form-edit-name">
                  <label>ชื่อหมวดหมู่</label>
                  <input type="text" class="form-control" id="edit-name" name="edit-name">
                  <span class="help-block edit-name-error">Please fill out this field.</span>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group form-edit-title">
                  <label>หัวเรื่อง</label>
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
                <div class="form-group">
                  <label>คำอธิบาย</label>
                  <input type="text" class="form-control" id="edit-description" name="edit-description" placeholder="Description">
                </div> 
              </div>

              <div class="col-md-12">
                <div class="form-group form-edit-slug">
                  <label>URL</label>
                  <div class="input-group">
                    <span class="input-group-addon input-group-url"><?= ROOT_URL ?></span>
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
                  <label>หัวข้อ</label>
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
                  <label>แสดงบนแถบเมนู</label>
                  <select class="form-control" name="edit-show-on-menu" id="edit-show-on-menu" style="width: 100%;">
                    <option id="edit-menu-no" value="no">ซ่อน</option>
                    <option id="edit-menu-yes" value="yes">แสดง</option>
                  </select>
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
                  <label>ตำแหน่งในหน้าแรก</label>
                  <input type="number" class="form-control" id="edit-position" name="edit-position" value="0" min="0">
                </div>
              </div>

              <div class="col-md-6">  
                <div class="form-group">
                  <label>ลำดับ</label>
                  <input type="number" class="form-control" id="edit-priority" name="edit-priority" value="0" min="1">
                </div>
                <input type="hidden" id="current-priority">
              </div>
              
            </form>
          </div>
          <input type="hidden" id="edit-category-id">
        </div>
      </div>
      <div class="modal-footer "> 
        <input type="hidden" id="submit-type">
        <button type="submit" class="btn btn-default pull-left" id="reset-edit-category">
          <i class="fa fa-repeat" aria-hidden="true"></i> ล้างค่า
        </button>
        <button type="submit" class="btn btn-success pull-right" id="save-edit-category">
          <i class="fa fa-floppy-o"></i> บันทึก
        </button>
      </div>
    </div>
  </div>
</div>