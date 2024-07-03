$(document).ready(function() {
    // click recipe instructions
    let intervals = {}
    let indexes = {}
    $(".step").click(function (){
        if (intervals[$(this)[0].outerText]){
            for (const interval_id of intervals[$(this)[0].outerText]){
                clearInterval(interval_id);
            }
        }
        let interval_ids = [];
        //set default index
        if (!indexes[$(this)[0].outerText])indexes[$(this)[0].outerText] = 0
        if (!$(this).hasClass("clicked")){
            $(this).addClass("clicked");
            const p = $(this).children().eq(0);
            const interval_id = setInterval(()=>{
                indexes[$(this)[0].outerText]++;
                if (StrikeThrough(indexes[$(this)[0].outerText], p) == false){
                    clearInterval(interval_id);
                    interval_ids.splice(interval_ids.indexOf(interval_id), 1);
                }
            }, 2000 / p.text().length);
            interval_ids.push(interval_id);
            intervals[$(this)[0].outerText] = interval_ids.map((el)=>el);
        }else{
            $(this).removeClass("clicked");
            const p = $(this).children().eq(0);
            const interval_id = setInterval(()=>{
                indexes[$(this)[0].outerText]--;
                if (StrikeThrough(indexes[$(this)[0].outerText], p) == false){
                    clearInterval(interval_id);
                    interval_ids.splice(interval_ids.indexOf(interval_id), 1);
                }
            }, 2000 / p.text().length);
            interval_ids.push(interval_id);
            intervals[$(this)[0].outerText] = interval_ids.map((el)=>el);
        }
    });
    // comments slide down
    $("#comments-form-button").click(function (){
        // toggle downside <-> upside arrow
        $(this).children("img").toggleClass("rotated-animation");
        // toggle comment form section
        $("#comment-form").slideToggle(1000);
    });
});

function StrikeThrough(index, element) {
    const text = element.text();
    if (index >= text.length || index < -1)return false;

    let sToStrike = text.substr(0, index + 1);
    let sAfter = (index < (text.length - 1)) ? text.substr(index + 1, text.length - index) : "";
    element.children().eq(0).text(sToStrike);
    element.children().eq(1).text(sAfter);
    return true;
}