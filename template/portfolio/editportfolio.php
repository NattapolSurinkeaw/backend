<!-- Modal Edit Content -->      
<div id="modalEditContent" class="modal fade blog-content blog-content-lg" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> <?php echo $LANG_LABEL['editcontent'];?></h4>
      </div>

      <div class="modal-body">
        <div class="row body-row-content">
          <div class="col-content col-md-3">
            <div class="box box-content-cate">
              <div class="box-header with-border">
                <h3 class="box-title"><?php echo $LANG_LABEL['categories'];?></h3>
              </div>
              <div class="box-body box-content-cate-edit" id="blog-category-tree" >
              </div>
            </div>
          </div>

          <div class="col-content col-md-9 scrollbar" id="scrollbar-edit">
            <form id="form-edit-content">

              <div class="col-md-12">
                <div class="form-group form-edit-images">
                <label><?php echo $LANG_LABEL['uploadimage'];?></label>
                  <div id="image-preview">
                    <div class="blog-preview-edit"></div>
                    <input type="file" name="imagesedit[]" class="exampleInputFile" id="edit-images-content" data-preview="blog-preview-edit" data-type="edit" />
                  </div>
                  <input type="hidden" id="edit-images-content-hidden" name="edit-images-content-hidden">
                  <div class="b-row space-15"></div>                                            
                </div>
              </div>

              <div class="col-md-12"> 
                <div class="blog-more-images">
                  <label><?php echo $LANG_LABEL['moreimg'];?></label>
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
                <label><?php echo $LANG_LABEL['title'];?></label>
                  <input type="text" class="form-control" id="edit-title" name="edit-title" placeholder="<?php echo $LANG_LABEL['title'];?>" required>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                <label><?php echo $LANG_LABEL['keyword'];?></label>
                  <input type="text" class="form-control" id="edit-keyword" name="edit-keyword" placeholder="<?php echo $LANG_LABEL['keyword'];?>" required>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group form-edit-description">
                <label><?php echo $LANG_LABEL['description'];?></label>
                  <input type="text" class="form-control" id="edit-description" name="edit-description" placeholder="<?php echo $LANG_LABEL['description'];?>" required>
                </div> 
              </div>

              <div class="col-md-12">
                <div class="form-group form-edit-slug">
                  <label>URL</label>
                  <div class="input-group">
                    <span class="input-group-addon input-group-url"><?= ROOT_URL ?></span>
                    <input type="text" class="form-control" id="edit-slug" name="edit-slug" placeholder="" required>
                  </div>
                  <input type="hidden" id="current-url" name="current-url">
                </div>
              </div>

              <?php
              if($_SESSION['topic']=='yes') {
              ?>
              <div class="col-md-12">
                <div class="form-group">
                <label><?php echo $LANG_LABEL['topic']?></label>
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

              <div class="col-md-12">
                <div class="form-group">
                <label><?php echo $LANG_LABEL['contentdetail'];?></label>
                  <textarea class="form-input" id="edit_content" name="edit_content" required></textarea>
                </div>  
              </div>

              <div class="col-md-12"> 
                <div class="form-group">
                <label><?php echo $LANG_LABEL['vdolink'];?></label>
                  <input type="text" class="form-control" id="edit-video" name="edit-video" placeholder="youtube or facebook">
                </div>

                <div id="show-video"></div>
              </div>

              <div class="col-md-12"> 
                <div class="blog-content-tag">
                <label><?php echo $LANG_LABEL['tags'];?></label>
                  <div class="box box-tag">
                    <div class="box-body">
                      <div class="form-group">
                        <input type="text" class="form-control" id="edit-search-tag" name="edit-search-tag" placeholder="<?php echo $LANG_LABEL['findtag'];?>">
                        <div class="sub-tag" id="searchtagresult"></div>
                      </div>

                      <div class="form-group">
                        <input type="text" class="form-control" id="edit-add-tag" name="edit-add-tag" placeholder="<?php echo $LANG_LABEL['addtag'];?>">
                      </div>
                      <div class="edit-blog-tag form-group" id="edit-blog-tag"></div>
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
              <label><?php echo $LANG_LABEL['displaydate'];?></label>
              </div>
              <div class="col-md-6"> 
                <div class="form-group">
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right" id="date-display" placeholder="<?php echo $LANG_LABEL['date'];?>">
                        <input type="hidden" class="form-control pull-right" id="date-display-hidden" name="date-display-hidden">
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
                          <input type="text" class="form-control timepicker" id="time-display" name="time-display" placeholder="<?php echo $LANG_LABEL['time'];?>">
                      </div>
                  </div> 
                </div>
              </div>

              <div class="col-md-6">  
                <div class="form-group">
                  <label><?php echo $LANG_LABEL['displayonsite']?></label>
                  <select class="form-control" name="edit-display" id="edit-display" style="width: 100%;">
                      <option id="edit-display-yes" value="yes"><?php echo $LANG_LABEL['show'];?></option>
                      <option id="edit-display-no" value="no"><?php echo $LANG_LABEL['hide'];?></option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">  
                <div class="form-group">
                <label><?php echo $LANG_LABEL['pinpost']." ( ผลงานเด่นของเรา )";?></label>
                  <select class="form-control" name="edit-pin" id="edit-pin" style="width: 100%;">
                      <option id="edit-pin-no" value="no"><?php echo $LANG_LABEL['no'];?></option>
                      <option id="edit-pin-yes" value="yes"><?php echo $LANG_LABEL['yes'];?></option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">  
                <div class="form-group">
                <label><?php echo $LANG_LABEL['priority'];?></label>
                  <input type="text" class="form-control" id="edit-priority"name="edit-priority" placeholder="<?php echo $LANG_LABEL['numberonly'];?>">    
                </div>
              </div>
              <input type="hidden" name="action" value="editcontent" />
              <input type="hidden" class="form-control" id="imgmoreId-edit" name="imgmoreId-edit">
              <input type="hidden" class="form-control" id="edit-content-id" name="edit-content-id">
              <input type="hidden" name="edit-category" id="edit-category" class="edit-category" value="" required/>
              <input type="hidden" id="submit-type" name="submit-type">
              <input type="hidden" id="date-created" name="date-created">
              
            </form>
          </div>

        </div>
      </div>
      <div class="modal-footer "> 
        <button type="submit" class="btn btn-default pull-left" id="reset-edit">
          <i class="fa fa-repeat" aria-hidden="true"></i>  <?php echo $LANG_LABEL['clear'];?>
        </button>
        <button type="submit" class="btn btn-success pull-right kt:btn-success" id="save-edit" style="padding: 10px 40px;">
          <i class="fa fa-floppy-o"></i> <?php echo $LANG_LABEL['save'];?>
        </button>
      </div>
    </div>
  </div>
</div>