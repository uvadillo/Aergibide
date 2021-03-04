var botones = $('.labels').toArray()
for (x=0;x<botones.length;x++){
    botones[x].addEventListener("click",meterEtiqueta)
}
function meterEtiqueta(){
    var a = event.target.value;
    $('button.labels').css("background","white").css("border","1px solid #ffb600");
    $('button[value='+a+']').css("background","#ffb600").css("border","1px solid black");

    let caja = $(':input#tags');
    caja.val(a);
}