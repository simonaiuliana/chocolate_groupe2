$(document).ready(function() {
    let interval_ids = [];
    $(".step").click(function (){
        if (!$(this).hasClass("clicked")){
            $(this).addClass("clicked");
            let index = 0;
            const p = $(this).children().eq(0);
            const interval_id = setInterval(()=>{
                if (StrikeThrough(index, p) == false){
                    clearInterval(interval_id);
                    interval_ids.splice(interval_ids.indexOf(interval_id), 1);
                }
                index++;
            }, 2000 / p.text().length);
            interval_ids.push(interval_id);
        }else{
            for (const interval_id of interval_ids){
                clearInterval(interval_id);
            }
            StrikeThrough(-1, $(this).children().eq(0));
            interval_ids = [];
            $(this).removeClass("clicked");
        }
    });
});

function StrikeThrough(index, element) {
    console.log(index);
    const text = element.text();
    if (index >= text.length)return false;

    let sToStrike = text.substr(0, index + 1);
    let sAfter = (index < (text.length - 1)) ? text.substr(index + 1, text.length - index) : "";
    element.html("<strike>" + sToStrike + "</strike>" + sAfter);
    return true;
}