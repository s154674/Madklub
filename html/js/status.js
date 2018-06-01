function initStatus(){
    console.log("Status blev åbnet");

    $.ajax({
        url: "public/status",
        method: "GET",
        contentType: 'application/json',
        dataType: 'json',
        beforeSend: addJWT,
        complete: updateJWT,
        success: function(data, textStatus, jqXhr){
            peopletabledata = $("#content-status table tbody");
            peopletabledata.empty();
            $.each(data, function(i, person){
                cell1 = "<td>"+person['name']+"</td>";
                cell2 = "<td>"+person['fraction']+"</td>";
                cell3 = "<td>"+person['balance']+"</td>";

                row = "<tr>"+cell1+cell2+cell3+"</tr>";
                peopletabledata.append(row);
            });
        },
        error: function(data, textStatus, jqXhr){
            console.log(data);
        }
    });
}