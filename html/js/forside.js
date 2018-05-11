function updateAttendees(id) {
    $.ajax({
        url: "public/dates/"+id+"/attendees",
        method: "GET",
        contentType: 'application/json',
        dataType: 'json',
        success: function(data, textStatus, jqXhr){
            console.log(data);

            tab1 = $("#panel1 table tbody");
            tab2 = $("#panel2 table tbody");
            tilmeldte = $("#tilmeldte-antal");
            tab1.empty();
            tab2.empty();
            tilmeldte.empty();

            countfortable = 0;

            $.each(data, function(i, attendance){

                text = attendance.name;

                if (attendance.guest==="1") {
                    text += " + Gæst";
                    countfortable+=2;
                } else {
                    countfortable+=1;
                }


                row = "<tr><td>"+text+"</td></tr>";

                if (attendance.late==="1"){
                    tab2.append(row);
                } else {
                    tab1.append(row);
                }
                console.log(countfortable);

            });
            antaltilmeldte = "<h2>" +"Tilmeldte(" + countfortable +"):"+"</h2>";
            tilmeldte.append(antaltilmeldte);
        },
        error: function(data, textStatus, jqXhr){
            console.log(data);
        },
        beforeSend: function(xhr, settings) {
            xhr.setRequestHeader('Authorization','Bearer ' + localStorage.getItem("jwt"));
        }

    });
}
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
            datoid = data.date_id;

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

            updateAttendees(datoid);
        },
        error: function(data, textStatus, jqXhr){
            console.log(data);
        },
        beforeSend: function(xhr, settings) {
            xhr.setRequestHeader('Authorization','Bearer ' + localStorage.getItem("jwt"));
        },

    });
    $.ajax({
        url: "public/washers",
        method: "GET",
        contentType: 'application/json',
        dataType: 'json',
        success: function(data, textStatus, jqXhr){
            console.log(data);

            tab3 = $("#opvaskere table tbody");
            tab3.empty();

            $.each(data, function(i, washer){

                text = washer.name;
                row = "<tr><td>"+text+"</td></tr>";

                if (i < 5) {
                    tab3.append(row);
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
