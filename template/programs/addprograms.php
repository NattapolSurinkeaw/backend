<!-- Modal Add Content -->      
<div id="modalAddContent" class="modal fade blog-content blog-content-lg" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> <?php echo $LANG_LABEL['addcontent'];?></h4>
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
              <div class="col-md-12">
                <div class="form-group form-add-images">
                  <label><?php echo $LANG_LABEL['uploadimage'];?></label>
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

              <div class="col-md-12"> 
                <div class="blog-more-images">
                  <label><?php echo $LANG_LABEL['moreimg'];?></label>
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
                  <label><?php echo $LANG_LABEL['title'];?></label>
                  <input type="text" class="form-control" id="add-title" name="add-title" placeholder="<?php echo $LANG_LABEL['title'];?>" required>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label><?php echo $LANG_LABEL['keyword'];?></label>
                  <input type="text" class="form-control" id="add-keyword" name="add-keyword" placeholder="Keyword" required>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group form-add-description">
                  <label><?php echo $LANG_LABEL['description'];?></label>
                  <input type="text" class="form-control" id="add-description" name="add-description" placeholder="Description" required>
                </div> 
              </div>

              <div class="col-md-12">
                <div class="form-group form-add-slug">
                  <label>URL</label>
                  <div class="input-group">
                    <span class="input-group-addon input-group-url"><?php echo  ROOT_URL; ?></span>
                    <input type="text" class="form-control" id="add-slug" name="add-slug" placeholder="" required>
                  </div>     
                </div>
              </div>

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
                  <label><?php echo $LANG_LABEL['contentdetail'];?></label>
                  <textarea class="form-input" id="add_content" name="add_content" required></textarea>
                </div>  
              </div>

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