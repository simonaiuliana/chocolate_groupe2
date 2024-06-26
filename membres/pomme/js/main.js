$(document).ready(function() {
    let intervals = {}
    $(".step").click(function (){
        console.log($(this));
        if (intervals[$(this)[0].outerText]){
            for (const interval_id of intervals[$(this)[0].outerText]){
                clearInterval(interval_id);
            }
        }
        let interval_ids = [];
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
            intervals[$(this)[0].outerText] = interval_ids.map((el)=>el);
        }else{
            StrikeThrough(-1, $(this).children().eq(0));
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