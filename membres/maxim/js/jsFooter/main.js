"use strict";

(function ($) {
  $(".set-bg").each(function () {
    var bg = $(this).data("setbg");
    $(this).css("background-image", "url(" + bg + ")");
  });
})(jQuery);

window.printDiv = function (page) {
  $("#" + page + " img").hide();
  var printContents = document.getElementById(page);
  var originalContents = document.body.innerHTML;
  printContents = printContents.innerHTML;

  document.body.innerHTML = printContents;

  window.print();

  document.body.innerHTML = originalContents;
  $("#" + page + " img").show();
};
