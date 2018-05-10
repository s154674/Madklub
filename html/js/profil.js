function initProfil(){
    console.log("Profil blev åbnet");

    decoded = jwt_decode(localStorage.getItem("jwt"));
    id = decoded.bruger.id

    $.ajax({
        url: "public/users/"+id,
        method: "GET",
        contentType: 'application/json',
        dataType: 'json',
        success: function(data, textStatus, jqXhr){
            console.log(data);
            $("#name-profil").html(data.name);
            $("#login-profil").html(data.login);
            $("#numerator-profil").html(data.numerator);
            $("#denominator-profil").html(data.denominator);
            $("#admin-profil").html(data.admin);
        },
        error: function (data, textStatus, jqXhr) {
            console.log(textStatus);
            console.log(data);
            localStorage.removeItem("jwt");
        },
        beforeSend: function (xhr, settings) {
            xhr.setRequestHeader('Authorization', 'Bearer ' + localStorage.getItem("jwt"));
        },
        complete: function (jqXhr, textStatus) {
            reloadRelevant();
        }
    });
}