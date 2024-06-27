$(document).ready(function(){
    $(".cardTitreMain").hide().slideDown(3000);
});

$(document).ready(function(){
    $(".description").hide()
});
$(document).ready(function(){
    $(".description").delay(1000).show(3000)
});
$(document).ready(function(){
    $(".imgFondantRecette").hide().show(6000)
});

// carousel

var bReady;
$(document).ready(function(){
 
// This pauses the re-stacking until the expand animation is finished
$(".ps-photoset").hover(function(){
  bReady = false;
  setTimeout(function(){
    bReady = true;
  }, 600);  
});
});


// 3 images //
$(".ps-photo").hover(function(){
  if (bReady == true){
    $(".ps-photo").css("z-index", "auto");
    $(this).css("z-index", "4");
  } else {
    // condole.log("stacking temporarily disabled")
  }
});
// end carousel

//hidden form

$(document).ready(function () {
  $("#contactForm").hide();
  $(".showForm").click(function () {
      $("#contactForm").show(2000);
  });
});

