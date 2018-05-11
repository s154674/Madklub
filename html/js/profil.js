function initProfil(){
    console.log("Profil blev åbnet");

    decoded = jwt_decode(localStorage.getItem("jwt"));
    id = decoded.bruger.id;

    $.ajax({
        url: "public/users/"+id,
        method: "GET",
        contentType: 'application/json',
        dataType: 'json',
        success: function(data, textStatus, jqXhr){
            $("#name-profil").val(data.name);
            $("#login-profil").val(data.login);
            $("#numerator-profil").html(data.numerator);
            $("#denominator-profil").html(data.denominator);
        },
        error: function (data, textStatus, jqXhr) {
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

$("#name-profil").on('keyup', function(){
    decoded = jwt_decode(localStorage.getItem("jwt"));
    id = decoded.bruger.id;

    name = $(this).val();
    payload = {"name": name};

    $.ajax({
        url: "public/users/"+id,
        method: "PUT",
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify(payload),
        success: function(data, textStatus, jqXhr){
            console.log(data);
        },
        error: function (data, textStatus, jqXhr) {
            localStorage.removeItem("jwt");
        },
        beforeSend: function (xhr, settings) {
            xhr.setRequestHeader('Authorization', 'Bearer ' + localStorage.getItem("jwt"));
        },
        complete: function (jqXhr, textStatus) {
            reloadRelevant();
        }
    });
});


$("#skift-password-form").on('submit', function(e){
    e.preventDefault();

    decoded = jwt_decode(localStorage.getItem("jwt"));
    id = decoded.bruger.id;

    oldpass = $(this).find("input[name='old']").val();
    newpass = $(this).find("input[name='new']").val();
    newpassrepeat = $(this).find("input[name='new-repeat']").val();

    payload = {"old":oldpass, "new": newpass, "new-repeat":newpassrepeat};

    $.ajax({
        url: "public/users/"+id+"/changepassword",
        method: "PUT",
        contentType: 'application/json',
        data: JSON.stringify(payload),
        success: function(data, textStatus, jqXhr){
            $("#skift-password-profil").foundation('close');
            $(this).trigger('reset');
        },
        error: function (data, textStatus, jqXhr) {
            localStorage.removeItem("jwt");
        },
        beforeSend: function (xhr, settings) {
            xhr.setRequestHeader('Authorization', 'Bearer ' + localStorage.getItem("jwt"));
        },
        complete: function (jqXhr, textStatus) {
            reloadRelevant();
        }
    });
});