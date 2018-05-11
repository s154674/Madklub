function initForside(){
    console.log("Forside blev åbnet i did this")

    $.ajax({
        url: "public/frontpage",
        method: "GET",
        contentType: 'application/json',
        success: function(data, textStatus, jqXhr){
            data = JSON.parse(data);
           // console.log(JSON.stringify(data) + " i did this");
            console.log(data);

            peopletabledata = $("#first-box");
            peopletabledata.empty();

                row1 = "<p>" +"<b> Dato: </b>" +data.date+"</p>";
                row2 = "<p>" +"<b> Kok: </b>" +data.cook+"</p>";
                row3 = "<p>" +"<b> Hjælpere: </b>" +data.help+"</p>";
                row4 = "<p>" +"<b> Ret: </b>" +data.dish+"</p>";
                peopletabledata.append(row1);
                peopletabledata.append(row2);
                peopletabledata.append(row3);
                peopletabledata.append(row4);

        },
        error: function(data, textStatus, jqXhr){
            console.log(data);
        },
        beforeSend: function(xhr, settings) {
            xhr.setRequestHeader('Authorization','Bearer ' + localStorage.getItem("jwt"));
        },

    });

    $.ajax({
        url: "public/dates/id",
        method: "GET",
        contentType: 'application/json',
        success: function(data, textStatus, jqXhr){
            console.log(data);
        },

    });

    datoid = $("#first-box").data();
    $.ajax({
        url: "public/dates/"+datoid+"/attendees",
        method: "GET",
        contentType: 'application/json',
        dataType: 'json',
        success: function(data, textStatus, jqXhr){
            data = JSON.parse(data);

            tab1 = $("#forside-tab1 table tbody");
            tab1.empty();

            $.each(data, function(i, attendance){

                user = users.find(function(user){
                    return user.user_id === attendance.userid;
                });

                text = user.name;
                row = "<tr><td>"+text+"</td></tr>";

                if (attendance.late==="1"){
                    tab1.append(row);
                } else {
                    tab1.append(row);
                }
            });
        },
        error: function(data, textStatus, jqXhr){
            console.log(data);
        },
        beforeSend: function(xhr, settings) {
            xhr.setRequestHeader('Authorization','Bearer ' + localStorage.getItem("jwt"));
        }

    });
}
