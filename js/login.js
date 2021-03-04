$(document).ready(function () {
    buttonsChangeForm();
    checkAvailability("Username");
    checkAvailability("Email");
});

function buttonsChangeForm() {
    //variable para saber que esta mostrando, true=signUp, false=logIn
    var signUpSingInstatus = false;
    $(".changeForm").click(function () {
        if (signUpSingInstatus == false) {
            //ocultar uno de los formularios y mostrar el otro
            $("#login").css("display", "none");
            $("#signup").css("display", "flex");
            signUpSingInstatus = true;
            $(".username").eq(0).focus();
        } else {
            //ocultar uno de los formularios y mostrar el otro
            $("#signup").css("display", "none");
            $("#login").css("display", "flex");
            signUpSingInstatus = false;
            $(".username").eq(1).focus();
        }
        //limpio los campos de los formularios
        clearForms();
    });
}
function validateForm(type) {
    //segun desde que submit se llame a la funcion el type sera "login" o "signup"
    try {
        if (type == "login") {
            return true;
        } else {
            //signUp
            //llamo a los metodos que validan los campos
            regexUsername($(".username").eq(1).val());
            regexPassword($(".password").eq(1).val(),$("#password2").val());
            regexName($("#nombre").val());
            regexSurname($("#apellido").val());
            regexEmail($("#email").val());

            return true;
        }
    } catch (error) {
        alert(error);
        return false;
    }
}
function regexUsername(username){
    var usernameRegex = new RegExp("^(?=[a-zA-Z0-9._-]{3,16}$)(?!.*[_.-]{2})[^_.-].*[^_.-]$");
    if (!usernameRegex.test(username)) {
        throw "El nombre de usuario tiene un formato incorrecto";
    } else {
        return true;
    }
}
function regexPassword(password, password2){
    var passwordRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@#$%&?¿!¡._-]).{8,64}$");
    if (passwordRegex.test(password)) {
        if (password != password2) {
            throw "Las contraseñas no coinciden";
        } else {
            return true;
        }
    } else {

        throw "La contraseña debe contener 8 caracteres, una mayuscula, una minuscula, un numero y un caracter especial (@#$%&?¿!¡._-)";

    }
}
function regexName(nombre) {
    var nombreRegex = new RegExp("^(([a-zA-Z ])?[a-zA-Z]*){1,3}$");
    if (!nombreRegex.test(nombre)) {
        throw "El nombre tiene un formato incorrecto";
    } else {
        return true;
    }
}
function regexSurname(surname){
    var apellidoRegex = new RegExp("^(([a-zA-Z ])?[a-zA-Z]*){1,4}$");
    if (!apellidoRegex.test(surname)) {
        throw "El apellido tiene un formato incorrecto";
    } else {
        return true;
    }
}
function regexEmail(email){
    var emailRgex = new RegExp("^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$");
    if (!emailRgex.test(email)) {
        throw "El email tiene un formato incorrecto";
    } else {
        return true;
    }
}
function clearForms() {
    $("form")[0].reset();
    $("form")[1].reset();
}
function checkAvailability(variable) {
    var field;
    var input;
    var inputVal;
    var regex;
    var errorField;
    //defino variables segun se tenga que validar el username o el email
    switch (variable) {
        case "Username":
            field = "Username";
            input = $(".username");
            regex = new RegExp("^(?=[a-zA-Z0-9._-]{3,16}$)(?!.*[_.-]{2})[^_.-].*[^_.-]$");
            errorField = $("#errorUsername");
            break;
        case "Email":
            field = "Email";
            input = $("#email");
            regex =  new RegExp("^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$");
            errorField = $("#errorEmail");
            break;
    }

    input.on("input", function Availability(){
        if (field=="Username"){
        inputVal = $(".username").eq(1).val().trim();
        }else{
        inputVal = $("#email").val().trim();
        }

        if (regex.test(inputVal)){
            $.ajax({
                type: "post",
                url: "../php/ajax.php",
                data: {action: "check"+field, value: inputVal },
                success: function (response) {
                    //si devuelve 1 es que esta pillado si no devuelve nada es que no
                    if (response==1){
                        errorField.append(field+" no disponible.");
                        $("[type=submit]").attr("disabled", true);
                        $("[type=submit]").css("background-color", "red");
                    } else {
                        errorField.empty();
                        $("[type=submit]").attr("disabled", false);
                        $("[type=submit]").css("background-color", "black");
                    }
                }
            });
        }
    })

}
function onSignIn(googleUser) {
    // Useful data for your client-side scripts:
    var profile = googleUser.getBasicProfile();
    console.log("ID: " + profile.getId()); // Don't send this directly to your server!
    console.log('Full Name: ' + profile.getName());
    console.log('Given Name: ' + profile.getGivenName());
    console.log('Family Name: ' + profile.getFamilyName());
    console.log("Image URL: " + profile.getImageUrl());
    console.log("Email: " + profile.getEmail());

    // The ID token you need to pass to your backend:
    var id_token = googleUser.getAuthResponse().id_token;
    console.log("ID Token: " + id_token);
}