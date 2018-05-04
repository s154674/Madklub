$(document).ready(function(){
    $("#slider").slick({
        arrows: false,
        slidesToShow: 1
    });
});



$(".menulinks a").on('click', function(){
    switch($(this).html()) {
        case "Forside":
            loadForside();
            break;
        case "Status":
            loadStatus();
            break;
        case "Maddage":
            loadMaddage();
            break;
        case "Profil":
            loadProfil();
            break;
        case "Regler":
            loadRegler();
            break;
        case "Login":
            loadLogin();
            break;
        default:
            // Noget er gået galt
    }

    $(".menulinks a").removeClass("active-page");
    $(this).addClass("active-page");
})

function loadForside(){
    $("#toggle").prop('checked', false);
    $(".content").slideUp();
    $("#forside-content").slideDown();

}

function loadStatus(){
    $("#toggle").prop('checked', false);
    $(".content").slideUp();
    $("#status-content").slideDown();
}

function loadMaddage(){
    $("#toggle").prop('checked', false);
    $(".content").slideUp();
    $("#maddage-content").slideDown();
}

function loadProfil(){
    $("#toggle").prop('checked', false);
    $(".content").slideUp();
    $("#profil-content").slideDown();
}

function loadRegler(){
    $("#toggle").prop('checked', false);
    $(".content").slideUp();
    $("#regler-content").slideDown();
}

function loadLogin(){
    $("#toggle").prop('checked', false);
    $(".content").slideUp();
    $("#login-content").slideDown();
}