function initLogin(){
    console.log("Forside blev åbnet");

}

// LOG IND, skal nok i sin egen fil senere
$("#content-logind form").on('submit', function(e){
    e.preventDefault();

    login = $("#content-logind form input[name='login']").val();
    password = $("#content-logind form input[name='password']").val();
    payload = {"login": login, "password" :password};

    $.ajax({
        url: "public/login",
        method: "POST",
        contentType: 'application/json',
        data: JSON.stringify(payload),
        success: function(data, textStatus, jqXhr){
            localStorage.setItem("jwt", data);
            reloadRelevant();
            navigateToForside();

            $("#wrongtext-logind").hide();
            $("#notext-logind").show();
        },
        error: function(data){
            $("#wrongtext-logind").slideDown();
            $("#notext-logind").slideUp();
        },
        complete: function(){

        }
    });

});