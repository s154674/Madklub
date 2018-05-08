function initMaddage(){
    console.log("Maddage blev åbnet");
}


$("#content-maddage table tbody button.attend").click(function(){
    $(this).hide();
    $(this).parent("td").parent("tr").find("a.plus").show();
    $(this).parent("td").parent("tr").find("a.late").show();
    $(this).parent("td").find("button.disattend").show();

});

$("#content-maddage table tbody button.disattend").click(function(){
    $(this).hide();
    $(this).parent("td").parent("tr").find("a.plus").hide();
    $(this).parent("td").parent("tr").find("a.late").hide();
    $(this).parent("td").find("button.attend").show();
});