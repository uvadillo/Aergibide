window.onscroll = function() {myFunction()};

// Get the navbar
var etiquetas = document.getElementById("etiquetas");

// Get the offset position of the navbar
var sticky = etiquetas.offsetTop;

// Add the sticky class to the navbar when you reach its scroll position. Remove "sticky" when you leave the scroll position
function myFunction() {
    if (window.pageYOffset >= sticky) {
        etiquetas.classList.add("sticky")
    } else {
        etiquetas.classList.remove("sticky");
    }
}