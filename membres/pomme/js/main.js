$(document).ready(function() {
    let intervals = {}
    let indexes = {}
    $(".step").click(function (){
        console.log("click");
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
});

function StrikeThrough(index, element) {
    const text = element.text();
    if (index >= text.length || index < -1)return false;

    let sToStrike = text.substr(0, index + 1);
    let sAfter = (index < (text.length - 1)) ? text.substr(index + 1, text.length - index) : "";
    element.html("<strike>" + sToStrike + "</strike>" + sAfter);
    return true;
}