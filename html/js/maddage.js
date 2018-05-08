function initMaddage(){
    console.log("Maddage blev åbnet");

    $.ajax({
        url: "public/futuredates",
        method: "GET",
        contentType: 'application/json',
        success: function(data, textStatus, jqXhr){
            console.log(data);
            data = JSON.parse(data);

            datetabledata = $("#content-maddage table tbody");
            datetabledata.empty();
            $.each(data, function(i, maddag) {
                console.log(maddag);
                cell1 = "<td>" + maddag['date'] + "</td>";
                cell2 = "<td>" + maddag['cookname'] + "</td>";
                cell3 = "<td>" + maddag['dish'] + "</td>";
                cell4 = "<td>" + maddag['dish'] + "</td>";

                row = "<tr data-id="+maddag['date_id']+">"+cell1+cell2+cell3+cell4+"</tr>";
                datetabledata.append(row);
            });

            console.log(data);
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


