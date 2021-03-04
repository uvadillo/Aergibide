document.getElementById('imagen').onchange = function () {
    var x = this.value;
    var y = x.split("\\");
    $("label[for=imagen]").text(y[y.length - 1]);

};