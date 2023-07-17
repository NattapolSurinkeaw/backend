<!-- css -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/custom.css">
<style type="text/css">.col-img-preview img {
    max-height: 75px;
}</style>

<?php
if (empty($_GET['hotel'])) {
  $map = $mydata->get_map('0');
}else {
  $map = $mydata->get_map($_GET['hotel']);
}

if ($map != false) {
  foreach ($map as $value) {
    $map_id = $value['id'];
    $lat = $value['Lat'];
    $lng = $value['Lng'];
    $zoom = $value['Zoom'];
    $marker = $value['marker'];
  }
}
?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-map"></i> <?php echo $LANG_LABEL['map'];//แผ่นที่?> 
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['mainpage'];//หน้าหลัก?> </a></li>
      <li class="active"><?php echo $LANG_LABEL['map'];//แผ่นที่?> </li>
    </ol>
  </section>

  <section class="content">
    <div class="row">

      <div class="col-md-3">
        <!-- Profile Image -->
        <div class="box box-primary">
          <div class="box-body box-profile">
            <div class="form-group">
              <div class="col-sm-12">
                <div id="image-preview" style="margin: 0 auto 10px; border-radius: 50%;">
                  <?php  
                  if ($map != false) {    
                    if ($marker != '') {
                  ?>
                      <div class="blog-preview-edit">      
                        <div class="col-img-preview"> 
                            <img class="preview-img" src="<?= ROOT_URL.$marker; ?>">
                        </div>
                      </div>
                  <?php 
                    }else {
                  ?>
                      <label for="image-upload" class="image-label">
                        <i class="fa fa-map-marker"></i>
                      </label>
                      <div class="blog-preview-edit">      
                        <div class="col-img-preview"></div>
                      </div>
                  <?php
                    }
                  }else {
                  ?>
                    <label for="image-upload" class="image-label">
                      <i class="fa fa-map-marker"></i>
                    </label>
                    <div class="blog-preview-edit">      
                      <div class="col-img-preview"></div>
                    </div>
                  <?php
                  }
                  ?>
                  <input type="file" name="imagesedit[]" class="exampleInputFile" id="edit-images-content" data-preview="blog-preview-edit" data-type="edit" />
                </div>
                <span class="help-block add-images-error">Please select images file!</span>
                <div class="b-row space-15"></div>   
              </div>                                         
            </div>

            <h3 class="profile-username text-center"></h3>

            <p class="text-muted text-center"><?php echo $LANG_LABEL['changelocation'];//เปลี่ยนหมุดที่แสดงบนแผนที่?></p>

            <ul class="list-group list-group-unbordered">
              <?php
              if ($map != false) {
              ?>

                <li class="list-group-item">
                  <b>Latitude</b> <a class="pull-right" id="text-lat"><?= $lat ?></a>
                </li>
                <li class="list-group-item">
                  <b>Longitude</b> <a class="pull-right" id="text-lng"><?= $lng ?></a>
                </li>
                <li class="list-group-item">
                  <b>Zoom</b> <a class="pull-right" id="text-zoom"><?= $zoom ?></a>
                </li>
              <?php
                }
              ?>
            </ul>

            <!-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> -->
            <button class="btn btn-success" id="save-map" style="width: 100%;">
              <i class="fa fa-floppy-o"></i> <?php echo $LANG_LABEL['save'];//บันทึก?>
            </button>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>

      <div class="col-md-9">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"></h3>

            <div class="box-tools pull-right">
              <div class="sort-content">
                <input class="input-search" type="text" id="namePlace" placeholder="<?php echo $LANG_LABEL['searchlocation'];?>...">
                <button class="bt-search" name="SearchPlace" id="SearchPlace"><i class="fa fa-search"></i></button>
              </div>
            </div>
          </div>

          <div class="box-body">
              <div id="map_canvas"></div>
              <?php
              if ($map != false) {
                echo '  
                <input name="map_id" type="hidden" id="map_id" value="'.$map_id.'" />         
                <input name="lat_value" type="hidden" id="lat_value" value="'.$lat.'" />   
                <input name="lon_value" type="hidden" id="lon_value" value="'.$lng.'" />  
                <input name="zoom_value" type="hidden" id="zoom_value" value="'.$zoom.'" />
                <input name="zoom_value" type="hidden" id="map_marker" value="'.$marker.'" />
                <input name="zoom_value" type="hidden" id="city_id" value="0" />';
              }else {
              ?>
                  <input name="map_id" type="hidden" id="map_id" value="" />         
                  <input name="lat_value" type="hidden" id="lat_value" value="13.764536742234462" />   
                  <input name="lon_value" type="hidden" id="lon_value" value="100.50529353593743" />  
                  <input name="zoom_value" type="hidden" id="zoom_value" value="6" />
                  <input name="zoom_value" type="hidden" id="map_marker" value="" />
                  <input name="zoom_value" type="hidden" id="city_id" value="<?= $_GET['hotel'] ?>" />
              <?php
              }
              ?>
          </div>
          <!-- <div class="box-footer clearfix category-footer">
            <button type="submit" class="btn btn-default pull-left" id="reset-website-detail">
              <i class="fa fa-upload"></i></i> Upload Marker
            </button>
            <button class="btn btn-success pull-right" id="save-map">
              <i class="fa fa-floppy-o"></i> บันทึกแผนที่
            </button>
          </div> -->

        </div>
      </div>
    </div>
  </section>
</div>

<!-- script -->
<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/map/map.js?v=1.7"></script>
