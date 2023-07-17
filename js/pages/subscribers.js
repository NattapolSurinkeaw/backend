$(function () {
  $('.mailbox-messages input[type="checkbox"]').iCheck({
    checkboxClass: 'icheckbox_flat',
    radioClass: 'iradio_flat'
  });

  $(".checkbox-toggle").click(function () {
    var clicks = $(this).data('clicks');
    if (clicks) {
      $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
      $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
    } else {
      $(".mailbox-messages input[type='checkbox']").iCheck("check");
      $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
    }
    $(this).data("clicks", !clicks);
  });
});