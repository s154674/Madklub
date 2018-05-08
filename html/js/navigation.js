function reloadRelevant() {
    try {
        decoded = jwt_decode(localStorage.getItem("jwt"));
        $("#menu-navn").html(decoded.bruger.login);

        $("#menu-forside").show(); $("#offmenu-forside").show();
        $("#menu-status").show();  $("#offmenu-status").show();
        $("#menu-maddage").show(); $("#offmenu-maddage").show();
        $("#menu-regler").show();  $("#offmenu-regler").show();
        $("#menu-navn").show();    $("#offmenu-navn").show();
        $("#menu-profil").show();  $("#offmenu-profil").show();
        $("#menu-logud").show();   $("#offmenu-logud").show();
        $("#menu-logind").hide();  $("#offmenu-logind").hide();

        if (decoded.bruger.admin==="1"){
            // ADMIN
            $("#menu-admin").show(); $("#offmenu-admin").show();
        } else {
            // ALMINDELIG BRUGER
            $("#menu-admin").hide(); $("#offmenu-admin").hide();
        }
    } catch(err) {
        // IKKE LOGGET IND
        $("#menu-forside").show(); $("#offmenu-forside").show();
        $("#menu-status").hide();  $("#offmenu-status").hide();
        $("#menu-maddage").hide(); $("#offmenu-maddage").hide();
        $("#menu-regler").hide();  $("#offmenu-regler").hide();
        $("#menu-admin").hide();   $("#offmenu-admin").hide();
        $("#menu-navn").hide();    $("#offmenu-navn").hide();
        $("#menu-profil").hide();  $("#offmenu-profil").hide();
        $("#menu-logud").hide();   $("#offmenu-logud").hide();
        $("#menu-logind").show();  $("#offmenu-logind").show();
    }
}

function offMenuCurrentClear(){
    $("#offmenu-forside").removeClass("current");
    $("#offmenu-status").removeClass("current");
    $("#offmenu-maddage").removeClass("current");
    $("#offmenu-regler").removeClass("current");
    $("#offmenu-admin").removeClass("current");
    $("#offmenu-profil").removeClass("current");
    $("#offmenu-logud").removeClass("current");
    $("#offmenu-logind").removeClass("current");
}
function menuCurrentClear(){
    $("#menu-forside").removeClass("current");
    $("#menu-status").removeClass("current");
    $("#menu-maddage").removeClass("current");
    $("#menu-regler").removeClass("current");
    $("#menu-admin").removeClass("current");
    $("#menu-navn").parent("li").removeClass("current");
    $("#menu-profil").removeClass("current");
    $("#menu-logud").removeClass("current");
    $("#menu-logind").removeClass("current");
}
function contentCurrentClear(){
    $("#content-forside").removeClass("current").slideUp();
    $("#content-status").removeClass("current").slideUp();
    $("#content-maddage").removeClass("current").slideUp();
    $("#content-regler").removeClass("current").slideUp();
    $("#content-admin").removeClass("current").slideUp();
    $("#content-profil").removeClass("current").slideUp();
    $("#content-logind").removeClass("current").slideUp();
}

// Forside
function navigateToForside(){
    offMenuCurrentClear();
    menuCurrentClear();
    contentCurrentClear();

    $("#current").html("Forside");
    $("#offmenu-forside").addClass("current");
    $("#menu-forside").addClass("current");
    content = $("#content-forside");
    content.prependTo(content.parent("div"));
    content.addClass("current").slideDown();
    $("#offCanvas").foundation('close');
    initForside();
}
$("#offmenu-forside").click(navigateToForside);
$("#menu-forside").click(navigateToForside);

// Status
function navigateToStatus(){
    offMenuCurrentClear();
    menuCurrentClear();
    contentCurrentClear();

    $("#current").html("Status");
    $("#offmenu-status").addClass("current");
    $("#menu-status").addClass("current");
    content = $("#content-status");
    content.prependTo(content.parent("div"));
    content.addClass("current").slideDown();
    $("#offCanvas").foundation('close');
    initStatus();
}
$("#offmenu-status").click(navigateToStatus);
$("#menu-status").click(navigateToStatus);

// Maddage
function navigateToMaddage(){
    offMenuCurrentClear();
    menuCurrentClear();
    contentCurrentClear();

    $("#current").html("Maddage");
    $("#offmenu-maddage").addClass("current");
    $("#menu-maddage").addClass("current");
    content = $("#content-maddage");
    content.prependTo(content.parent("div"));
    content.addClass("current").slideDown();
    $("#offCanvas").foundation('close');
    initMaddage();
}
$("#offmenu-maddage").click(navigateToMaddage);
$("#menu-maddage").click(navigateToMaddage);

// Regler
function navigateToRegler(){
    offMenuCurrentClear();
    menuCurrentClear();
    contentCurrentClear();

    $("#current").html("Regler");
    $("#offmenu-regler").addClass("current");
    $("#menu-regler").addClass("current");
    content = $("#content-regler");
    content.prependTo(content.parent("div"));
    content.addClass("current").slideDown();
    $("#offCanvas").foundation('close');
    initRegler();
}
$("#offmenu-regler").click(navigateToRegler);
$("#menu-regler").click(navigateToRegler);

// Admin
function navigateToAdmin(){
    offMenuCurrentClear();
    menuCurrentClear();
    contentCurrentClear();

    $("#current").html("Admin");
    $("#offmenu-admin").addClass("current");
    $("#menu-admin").addClass("current");
    content = $("#content-admin");
    content.prependTo(content.parent("div"));
    content.addClass("current").slideDown();
    $("#offCanvas").foundation('close');
    initAdmin();
}
$("#offmenu-admin").click(navigateToAdmin);
$("#menu-admin").click(navigateToAdmin);

// Profil
function navigateToProfil(){
    offMenuCurrentClear();
    menuCurrentClear();
    contentCurrentClear();

    $("#current").html("Profil");
    $("#offmenu-profil").addClass("current");
    $("#menu-profil").addClass("current");
    $("#menu-navn").parent("li").addClass("current");
    content = $("#content-profil");
    content.prependTo(content.parent("div"));
    content.addClass("current").slideDown();
    $("#offCanvas").foundation('close');
    initProfil();
}
$("#menu-navn").click(navigateToProfil);
$("#offmenu-profil").click(navigateToProfil);
$("#menu-profil").click(navigateToProfil);

// Log ud
function navigateToLogud(){
    offMenuCurrentClear();
    menuCurrentClear();
    contentCurrentClear();

    $("#current").html("Log ind");
    $("#offmenu-logind").addClass("current");
    $("#menu-logind").addClass("current");
    $("#content-logind").addClass("current").slideDown();
    $("#offCanvas").foundation('close');
    //
    localStorage.removeItem("jwt");
    reloadRelevant();
}
$("#offmenu-logud").click(navigateToLogud);
$("#menu-logud").click(navigateToLogud);

// Log ind
function navigateToLogind(){
    offMenuCurrentClear();
    menuCurrentClear();
    contentCurrentClear();

    $("#current").html("Log ind");
    $("#offmenu-logind").addClass("current");
    $("#menu-logind").addClass("current");
    content = $("#content-logind");
    content.prependTo(content.parent("div"));
    content.addClass("current").slideDown();
    $("#offCanvas").foundation('close');
}
$("#offmenu-logind").click(navigateToLogind);
$("#menu-logind").click(navigateToLogind);