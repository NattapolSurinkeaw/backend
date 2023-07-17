<!-- Modal Add Content -->      
<div id="modalAddContent" class="modal fade blog-content blog-content-lg" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> เพิ่มสินค้า<?php //echo $LANG_LABEL['addcontent'];?></h4>
      </div>

      <!--ดึงข้อมูล categoryมาแสดง-->
      <div class="modal-body">
        <div class="row body-row-content">
         
          <div class="col-content col-md-3">
            <div class="box box-content-cate box-content-cate-add">
             
              <div class="box-header with-border">
                <h3 class="box-title"><?php echo $LANG_LABEL['categories'];?></h3>
              </div>
               <div class="box-body" id="add-blog-category-tree"></div>

            </div>
          </div>

          <!--รับค่าข้อมูล-->
          <div class="col-content col-md-9 scrollbar" id="scrollbar-add">
            <form id="form-add-content">

            <div class="col-md-4">
                <div class="form-group form-add-images">
                  <label>รูปด้านที่ 1</label>
                  <div id="image-preview">
                    <img id="blah-left" src="#" alt="" />
                    <label for="image-upload" class="image-label">
                      <i class="fa fa-camera"></i>
                    </label>
                    <input type="file" name="images-left[]"   class="exampleInputFile" id="imgInp-left" data-type="add"/>
                  </div>
                  <div class="b-row space-15"></div>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group form-add-images">
                  <label>รูปด้านที่ 2</label>
                  <div id="image-preview">
                    <img id="blah-right" src="#" alt="" />
                    <label for="image-upload" class="image-label">
                      <i class="fa fa-camera"></i>
                    </label>
                    <input type="file" name="images-right[]"   class="exampleInputFile" id="imgInp-right" data-type="add"/>
                  </div>
                  <div class="b-row space-15"></div>
                </div>
              </div>


              <div class="col-md-4">
                <div class="form-group form-add-images">
                  <label>รูป Size</label>
                  <div id="image-preview">
                    <img id="blah" src="#" alt="" />
                    <label for="image-upload" class="image-label">
                      <i class="fa fa-camera"></i>
                    </label>
                    <input type="file" name="images-size[]"   class="exampleInputFile" id="imgInp" data-preview="blog-preview-add" data-type="add"/>
                  </div>
                  <div class="b-row space-15"></div>
                </div>
              </div>

              <div class="col-md-12"> 
                <div class="blog-more-images">
                  <label>อัพโหลดรูปภาพสินค้าเพิ่มเติม<?php //echo $LANG_LABEL['moreimg'];?></label>
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

              <div class="col-md-6">
                <div class="form-group form-add-title">
                  <label>หมวดหมู่สินค้า: <span style="color:red">*</span></label>
  
                  <select class="form-control" required id="add-product-cate" name="add-product-cate" style="width: 240px; display: inline-block; margin: 0 10px 5px 5px;"> 
                  <option value="">เลือกหมวดหมู่สินค้า</option>
                  <?php

                  foreach ($product_cate as $value) {
                      echo "<option value='".$value['product_cate_id']."'>".$value['product_cate_name']."</option>";
                    }
                  ?>
                  </select>
                  <input type="hidden" id="add-url-product-cate-hide" name="add-url-product-cate">
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group form-add-title">
                  <label>แบรนด์สินค้า:</label>
  
                  <select class="form-control" id="add-product-brand" name="add-product-brand" style="width: 240px; display: inline-block; margin: 0 10px 5px 5px;"> 
                  <option value="">เลือกแบรนด์สินค้า</option>
                  <?php
                  foreach ($product_brand as $value) {
                      echo "<option value='".$value['product_bn_id']."'>".$value['product_bn_name']."</option>";
                    }
                  ?>
                  </select>
                  <input type="hidden" id="add-url-product-brand-hide" name="add-url-product-brand">

                </div>
              </div>


              <div class="col-md-12">
                <div class="form-group form-add-title">
                  <label>ชื่อสินค้า<?php //echo $LANG_LABEL['title'];?> <span style="color:red">*</span></label>
                  <input type="text" class="form-control" id="add-title" name="add-title" placeholder="ชื่อสินค้า<?php //echo $LANG_LABEL['title'];?>" required>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label><?php echo $LANG_LABEL['keyword'];?> <span style="color:red">*</span></label>
                  <input type="text" class="form-control" id="add-keyword" name="add-keyword" placeholder="Keyword" required>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group form-add-description">
                  <label><?php echo $LANG_LABEL['description'];?> <span style="color:red">*</span></label>
                  <input type="text" class="form-control" id="add-description" name="add-description" placeholder="Description" required>
                </div> 
              </div>

              <div class="col-md-12">
                <div class="form-group form-add-slug">
                  <label>URL <span style="color:red">*</span></label>
                  <div class="input-group">
                    <span class="input-group-addon input-group-url"><?php echo  ROOT_URL; ?></span>
                    <input type="text" class="form-control" id="add-slug" name="add-slug" placeholder="" required>
                  </div>     
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group form-add-description">
                  <label>ราคาสินค้า <span style="color:red">*</span></label>
                  <input type="number" class="form-control" id="add-price" name="add-price" placeholder="ราคาสินค้า" required OnKeyUp="calDiscount();" min="0">
                </div> 
              </div>

              <div class="col-md-4">
                <div class="form-group form-add-description">
                  <label>ราคาพิเศษ</label>
                  <input type="number" class="form-control" id="add-specialprice" name="add-specialprice" placeholder="ราคาพิเศษ" OnKeyUp="calDiscount();" min="0">
                </div> 
              </div> 

              <div class="col-md-4">
                <div class="form-group form-add-description">
                  <label>เปอร์เซ็นส่วนลด</label>
                  <input type="number" class="form-control" id="add-discount" name="add-discount" placeholder="80">
                </div> 
              </div>  

              <!-- <div class="col-md-12">
                <div class="form-group form-add-description">
                  <label>Code สินค้า <span style="color:red">*</span></label>
                  <input type="text" class="form-control" id="add-product-code" name="add-product-code" placeholder="Code สินค้า" required>
                </div> 
              </div> -->

              <?php
              if($_SESSION['topic']=='yes') {
              ?>
              <div class="col-md-12">
                <div class="form-group">
                  <label><?php echo $LANG_LABEL['topic']?></label>
                  <input type="text" class="form-control" id="add-topic" name="add-topic" placeholder="special attribute" >
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
                  <textarea class="form-control ignore" rows="3" id="add-freetag" name="add-freetag" placeholder="Enter ..."></textarea>
                </div> 
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label>H1</label>
                  <input type="text" class="form-control ignore" id="add-h1" name="add-h1" placeholder="Text for H1">
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label>H2</label>
                  <input type="text" class="form-control ignore" id="add-h2" name="add-h2" placeholder="Text for H2">
                </div>  
              </div>
              <?php
              }
              ?>

              <div class="col-md-12">
                  <div class="form-group">
                    <label>รายการสินค้าย่อย<span style="color:red"></span></label>
                    <button class="btn btn-sm kt:btn-info" onclick="addProductSub(event)"><i class="fa fa-plus"></i> เพิ่มรายการ</button>
                    <div class="row">
                      <div class="col-md-5">
                        <label for="">ชื่อ</label>
                      </div>
                      <!-- <div class="col-md-5">
                        <label for="">จำนวน</label>
                      </div> -->
                    </div>

                    <div id="list-product-body"></div>

                  </div>  
              </div>
              
              <!-- <div class="col-md-12">
                  <div class="form-group">
                    <label>รายการสินค้าย่อย<span style="color:red"></span></label>
                    <button class="btn btn-sm kt:btn-info" onclick="addProductSub(event)"><i class="fa fa-plus"></i> เพิ่มสินค้าย่อย</button>
                    <div class="row">
                      <div class="col-md-8">
                        <label for="">ชื่อสินค้าย่อย</label>
                      </div>
                      <div class="col-md-4">
                        <label for="">ราคา</label>
                      </div>
                    </div>
                    <div id="list-product-body">
                      
                    </div>
                  </div>  
              </div> -->
              

              <div class="col-md-12">
                <div class="form-group">
                  <label>รายละเอียดของสินค้า<?php //echo $LANG_LABEL['contentdetail'];?> <span style="color:red">*</span></label>
                  <textarea class="form-input" id="add_content" name="add_content"  required></textarea>
                </div>  
              </div>


              <!-- <div class="col-md-12">
                <div class="form-group">
                  <label>รายละเอียดคำแนะนำสินค้า (จะแสดงเวลาพิมพ์ใบเสร็จ) <?php //echo $LANG_LABEL['contentdetail'];?> <span style="color:red">*</span></label>
                  <textarea class="form-input form-control" id="add_example" rows="5" name="add_example" required placeholder="รายละเอียดคำแนะนำสินค้า (จะแสดงเวลาพิมพ์ใบเสร็จ)"></textarea>
                  
                </div>  
              </div> -->




              <div class="col-md-12"> 
                <div class="form-group">
                  <label><?php echo $LANG_LABEL['vdolink'];?></label>
                  <input type="text" class="form-control ignore" id="add-video" name="add-video" placeholder="youtube or facebook">
                </div>
              </div>

              <div class="col-md-12"> 
                <div class="blog-content-tag">
                  <label><?php echo $LANG_LABEL['tags'];?></label>
                  <div class="box box-tag">
                    <div class="box-body">
                      <div class="form-group">
                        <input type="text" class="form-control" id="add-search-tag" name="add-search-tag" placeholder="<?php echo $LANG_LABEL['findtag'];?>">
                        <div class="sub-tag" id="add-searchtagresult"></div>
                      </div>

                      <div class="form-group">
                        <input type="text" class="form-control" id="add-tag" name="add-tag[]" placeholder="<?php echo $LANG_LABEL['addtag'];?>">
                      </div>
                      <div class="edit-blog-tag form-group" id="add-blog-tag"></div>
                    </div>
                  </div>  
                </div> 
              </div> 

              <div class="col-md-12"> 
                <div class="blog-content-social">
                  <label><?php echo $LANG_LABEL['sociallink'];?></label>
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
                <label><?php echo $LANG_LABEL['displaydate'];?></label>
              </div>
              <div class="col-md-6"> 
                <div class="form-group">
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right" id="add-date-display" placeholder="<?php echo $LANG_LABEL['date'];?>">
                        <input type="hidden" class="form-control pull-right" id="add-date-display-hidden" name="add-date-display-hidden">
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
                          <input type="text" class="form-control timepicker" id="add-time-display" name="add-time-display" placeholder="<?php echo $LANG_LABEL['time'];?>">
                      </div>
                  </div> 
                </div>
              </div>

              <div class="col-md-6">  
                <div class="form-group">
                  <label><?php echo $LANG_LABEL['displayonsite']?></label>
                  <select class="form-control" name="add-display" id="add-display" style="width: 100%;">
                      <option id="add-display-yes" value="yes"><?php echo $LANG_LABEL['show'];?></option>
                      <option id="add-display-no" value="no"><?php echo $LANG_LABEL['hide'];?></option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">  
                <div class="form-group">
                  <label><?php echo $LANG_LABEL['pinpost'];?></label>
                  <select class="form-control" name="add-pin" id="add-pin" style="width: 100%;">
                      <option id="add-pin-no" value="no"><?php echo $LANG_LABEL['no'];?></option>
                      <option id="add-pin-yes" value="yes"><?php echo $LANG_LABEL['yes'];?></option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">  
                <div class="form-group">
                  <label><?php echo $LANG_LABEL['priority'];?></label>
                  <input type="text" class="form-control" id="add-priority"name="priority" placeholder="<?php echo $LANG_LABEL['numberonly'];?>"> 
                </div>
              </div>

              <input type="hidden" name="action" value="addcontent" />
              <input type="hidden" name="imgmoreId" id="imgmoreId" value="" />
              <input type="hidden" name="add-category" id="add-category" class="add-category" value="" required/>
            </form>
          </div>

        </div>
      </div>
      <div class="modal-footer "> 
        <button type="submit" class="btn btn-default pull-left" id="reset-add">
          <i class="fa fa-repeat" aria-hidden="true"></i> <?php echo $LANG_LABEL['clear'];?>
        </button>
        <button type="submit" class="btn btn-success pull-right kt:btn-success" id="save-add" style="padding: 10px 40px;">
          <i class="fa fa-floppy-o"></i> <?php echo $LANG_LABEL['save'];?>
        </button>
      </div>
    </div>
  </div>
</div>