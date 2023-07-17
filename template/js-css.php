

<!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <!-- Material icon -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"> 
  <!-- Fontawesome 5.8 -->
  <link rel="stylesheet" href="<?=SITE_URL?>plugins/font-awesome5-13/css/all.css" >
	<link rel="stylesheet" href="<?=SITE_URL?>plugins/font-awesome5-13/css/fontawesome.min.css">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/admin.css">
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/skins.css">
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datepicker/css/bootstrap-datepicker3.css">
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/timepicker/bootstrap-timepicker.min.css">
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/uploadImage/css/uploadimg.css">
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/jquery-confirm/css/jquery-confirm.css">
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/style.css">
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/ktstyle.css">
  
  <!-- script -->
  <script src="<?php echo SITE_URL; ?>js/jquery/jquery.min.js"></script>
  <script src="<?php echo SITE_URL; ?>js/jquery-ui.min.js"></script>
  <script src="<?php echo SITE_URL; ?>js/bootstrap.min.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/jquery-confirm/js/jquery-confirm.min.js"></script>
  <script src="<?php echo SITE_URL; ?>js/ws-script.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/jquery-print/jQuery.print.min.js"></script>
  <script src="<?php echo SITE_URL; ?>js/statusOrder.js"></script>
  <script> 
    var site_url = '<?php echo SITE_URL; ?>', root_url = '<?php echo ROOT_URL; ?>', url_ajax_request = '<?php echo AJAX_REQUEST_URL; ?>', backend_language = '<?php echo $_SESSION['backend_language']; ?>';
    var LANG_LABEL = <?php echo json_encode(
      array(
        'input_warning_title' => $LANG_LABEL['input_warning_title'], 
        'close' => $LANG_LABEL['close'], 
        'selectimage' => $LANG_LABEL['selectimage'],  
        'urlisuse' => $LANG_LABEL['urlisuse'],  
        'selectcategory'=> $LANG_LABEL['selectcategory'] 
        ));?>
  </script>
  

 