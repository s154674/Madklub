function initForside(){
    console.log("Forside blev åbnet i did this");

    $.ajax({
        url: "public//forside",
        method: "GET",
        contentType: 'application/json',
        success: function(data, textStatus, jqXhr){
            console.log(JSON.stringify(data) + " i did this");

            peopletabledata = $("#first box");
            peopletabledata.empty();
            $.each(data, function(i, maddag){
                row = "<p>"+maddag['date']+"</p>";
                row2 = "<p>"+maddag['cook']+"</p>";
                row3 = "<p>"+maddag['help']+"</p>";
                row4 = "<p>"+maddag['dish']+"</p>";
                peopletabledata.append(row+row2+row3+row4);
            });
        },
        error: function(data, textStatus, jqXhr){
            console.log(data);
        },
        beforeSend: function(xhr, settings) {
            xhr.setRequestHeader('Authorization','Bearer ' + localStorage.getItem("jwt"));
        },
        complete: function(jqXhr, textStatus){
            localStorage.setItem("jwt", jqXhr.getResponseHeader('Authorization').substring(7));
            reloadRelevant();
        }
    });
}
