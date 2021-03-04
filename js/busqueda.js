$(document).ready(function () {
    liveSearch();
});
function liveSearch(){
    $("#searchInput").on("input", function () {
        var busqueda = $("#searchInput").val().trim();
        if (busqueda.length>2){
            $.ajax({
                type: "post",
                url: "../php/ajax.php",
                data: {action: "liveSearch", value: busqueda},
                success: function (response) {
                    alert(response);
                    var titulos=response.split("%%%");
                    $("#listabusqueda").css("display", "block");
                    for (i=5;i>titulos.length;i--){
                        $("#resultado"+i).css("display","none");
                    }
                },
                
            });
        }
    });
}