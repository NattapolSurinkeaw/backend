<!-- Modal Add Slide -->      
<div id="modalAddSlide" class="modal fade blog-content blog-content-sm" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <?php echo $LANG_LABEL['addbannerslide'];?></h4>
      </div>

      <div class="modal-body">
        <div class="row body-row-content">
          <div class="col-content col-md-12 scrollbar" id="scrollbar">
            <form id="form-edit-ads">

              <div class="col-md-12">
                <div class="form-group form-add-images">
                  <label><?php echo $LANG_LABEL['uploadimage']?></label>
                  <div id="image-preview">
                    <label for="image-upload" class="image-label">
                      <i class="fa fa-camera"></i>
                    </label>
                    <div class="blog-preview-add"></div>
                    <input type="file" name="imagesadd[]" class="exampleInputFile" id="add-images-ads" data-preview="blog-preview-add" data-type="add" />
                  </div>
                  <span class="help-block add-images-error">Please select images file!</span>
                  <input type="hidden" id="add-images-ads-hidden">
                  <div class="b-row space-15"></div>                                            
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label><?php echo $LANG_LABEL['title'];?></label>
                  <input type="text" class="form-control" id="add-ads-title" name="add-ads-title">
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label><?php echo $LANG_LABEL['url'];?></label>
                  <input type="text" class="form-control" id="add-ads-link" name="add-ads-link">
                </div>
              </div>

              <div class="col-md-6">  
                <div class="form-group">
                  <label><?php echo $LANG_LABEL['position'];?></label>
                  <select class="form-control" name="add-ads-position" id="add-ads-position" style="width: 100%;">
                    <?php
                      echo getData::option('ad_type ORDER BY FIELD(position,\'pin\') DESC,position ASC','position','position','pos','position','');
                    ?>
                  </select>
                </div>
              </div>

              <div class="col-md-6">  
                <div class="form-group">
                  <label><?php echo $LANG_LABEL['priority'];?></label>
                  <input type="number" class="form-control" id="add-ads-priority" name="add-ads-priority" min="0">
                </div>
              </div>

              <div class="col-md-6">  
                <div class="form-group">
                  <label><?php echo $LANG_LABEL['show'];?></label>
                  <select class="form-control" name="add-ads-display" id="add-ads-display" style="width: 100%;">
                    <option id="add-ads-yes" value="yes"><?php echo $LANG_LABEL['yes'];?></option>
                    <option id="add-ads-no" value="no"><?php echo $LANG_LABEL['no'];?></option>
                  </select>
                </div>
              </div>

              <div class="col-md-6"> 
                <label><?php echo $LANG_LABEL['displaydate'];?></label>
              </div>
              <div class="col-md-6"> 
                <div class="form-group">
                    <div class="input-group date">
                      <input type="text" class="form-control pull-right" id="add-ad-date-display" placeholder="dd/mm/yyyy">
                      <input type="hidden" class="form-control pull-right" id="add-input-date-display">
                      <div class="input-group-addon" style="border-width: 0;">
                        <?php echo $LANG_LABEL['to'];?>
                      </div>
                      <input type="text" class="form-control pull-right" id="add-ad-date-hidden" placeholder="dd/mm/yyyy">
                      <input type="hidden" class="form-control pull-right" id="add-input-date-hidden">
                    </div>
                </div>
              </div>
            </form>
          </div>

        </div>
      </div>
      <div class="modal-footer "> 
        <button type="submit" class="btn btn-default pull-left" id="reset-add">
          <i class="fa fa-repeat" aria-hidden="true"></i> <?php echo $LANG_LABEL['clear'];?>
        </button>
        <button type="submit" class="btn btn-success pull-right kt:btn-success" style="padding: 8px 40px;" id="save-add-ads">
          <i class="fa fa-floppy-o"></i> <?php echo $LANG_LABEL['save']?>
        </button>
      </div>
    </div>
  </div>
</div>