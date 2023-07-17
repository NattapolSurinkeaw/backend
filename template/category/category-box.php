  <div class="attachment-block clearfix">
  
    <div class="content-info">
      <h1><?php echo $category[$id]['cate_name'];?></h1>

      <p class="text-datetime"><i class="fa fa-folder-open-o" aria-hidden="true"></i> <?php echo (@$category[$category[$id]]['cate_name'] == '') ? $LANG_LABEL['mainpage'] : $category[$category[$id]]['cate_name']; ?></p>
      <p class="text-editor"><i class="fa fa-globe" aria-hidden="true"></i> <?php echo $category[$id]['lang_info']; ?></p>
    </div>
    <div class="content-button pull-right">
      <?php
if (strpos($category[$id]['lang_info'], $_SESSION['backend_language']) > -1) {
    ?>
        <button type="button" class="btn btn-success margin-r-10 btn-edit-category kt:btn-success" style="padding:10px 40px;" data-id="<?php echo $category[$id]['cate_id']; ?>" data-type="edit" data-toggle="modal" data-target="#modalEditCategory">
          <i class="fa fa-pencil-square-o"></i> <?php echo $LANG_LABEL['edit']; ?>
        </button> 
      <?php
} else {
    ?>
        <button type="button" class="btn btn-primary margin-r-10 btn-edit-category" data-id="<?php echo $category[$id]['cate_id']; ?>" data-type="add" data-toggle="modal" data-target="#modalEditCategory">
          <i class="fa fa-plus"></i> <?php echo $LANG_LABEL['add']; ?>
        </button>
        
      <?php
}
?>
    </div>
  </div>