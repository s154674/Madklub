function initMaddage(){
    console.log("Maddage blev åbnet");
}


$("#content-maddage table tbody button.attend").click(function(){
    $(this).hide();
    $(this).parent("td").find("button.disattend").show();
});

$("#content-maddage table tbody button.disattend").click(function(){
    $(this).hide();
    $(this).parent("td").find("button.attend").show();
});