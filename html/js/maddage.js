function initMaddage(){
    console.log("Maddage blev åbnet");

    $.ajax({
        url: "public/futuredates",
        method: "GET",
        contentType: 'application/json',
        dataType: 'json',
        success: function(data, textStatus, jqXhr){
            datetabledata = $("#content-maddage table tbody");
            datetabledata.empty();
            $.each(data, function(i, maddag) {
                maddag['attending'] = maddag['attending']==="1";
                maddag['late'] = maddag['late']==="1";
                maddag['guest'] = maddag['guest']==="1";

                cell1 = "<td>" + maddag['date'] + "</td>";
                cell2 = "<td>" + maddag['cookname'] + "</td>";
                cell3 = "<td>" + maddag['dish'] + "</td>";

                if (!maddag['attending']){
                    img = "noee";
                } else if (maddag['attending'] && maddag['late'] && maddag['guest']){
                    img = "yellowplus1";
                } else if (maddag['attending'] && maddag['late'] && !maddag['guest']){
                    img = "yellowcheck";
                } else if (maddag['attending'] && !maddag['late'] && maddag['guest']) {
                    img = "greenplus1";
                } else if (maddag['attending']) {
                    img = "greencheck";
                }
                currentimg = "<img class='current' src='logos/"+img+".png' data-toggle='drop-"+maddag['date_id']+"'>";   // current status

                logo1 = "<li data-id='"+maddag['date_id']+"' class='attend'><img src='logos/greencheck.png'></li>";
                logo2 = "<li data-id='"+maddag['date_id']+"' class='guest'><img src='logos/greenplus1.png'></li>";
                logo3 = "<li data-id='"+maddag['date_id']+"'class='late'><img src='logos/yellowcheck.png'></li>";
                logo4 = "<li data-id='"+maddag['date_id']+"' class='lateguest'><img src='logos/yellowplus1.png'></li>";
                logo5 = "<li data-id='"+maddag['date_id']+"' class='disatend'><img src='logos/noee.png'></li>";
                menu = "<ul class='menu expanded'>"+logo1+logo2+logo3+logo4+logo5+"</ul>";
                cell = "<div class='cell small-12'>"+menu+"</div>";
                grid = "<div class='grid-x grid-margin-x'>"+cell+"</div>";
                container = "<div class='grid-container'>"+grid+"</div>";
                dropdown = "<div class='dropdown-pane' data-position='top' data-alignment='center' id='drop-"+maddag['date_id']+"' data-dropdown>"+container+"</div>";
                cell4 = "<td>"+currentimg+dropdown+"</td>";

                row = "<tr id='date-"+maddag['date_id']+"' data-id="+maddag['date_id']+">"+cell1+cell2+cell3+cell4+"</tr>";
                datetabledata.append(row);
                new Foundation.Dropdown($("#drop-"+maddag['date_id']));

                $("#content-maddage table tbody tr .dropdown-pane").on("show.zf.dropdown", function() {
                    var dropdown = $(this);
                    dropdown.css('display', 'none');
                    dropdown.fadeIn('fast');
                });
            });

            $("#content-maddage table tbody tr td:not(:last-child)").click(function(){
                datoid = $(this).parent("tr").data("id");
                $.ajax({
                    url: "public/dates/"+datoid,
                    method: "GET",
                    contentType: 'application/json',
                    dataType: 'json',
                    success: function(data, textStatus, jqXhr){
                        // attendance-se-maddag
                        // dato-se-maddag
                        // cook-se-maddag
                        // dish-se-maddag
                        // help-se-maddag

                        console.log(data);

                        $("#dato-se-maddag").html(data.date);
                        $("#cook-se-maddag").val(data.cook);
                        $("#dish-se-maddag").val(data.dish);
                        $("#help-se-maddag").val(data.help);

                    },
                    error: function(data, textStatus, jqXhr){

                    },
                    beforeSend: function(xhr, settings) {
                        xhr.setRequestHeader('Authorization','Bearer ' + localStorage.getItem("jwt"));
                    },
                    complete: function(jqXhr, textStatus) {
                        reloadRelevant();
                    }
                });

                $('#se-maddag').foundation('open');

            });

            $("#content-maddage table tbody tr .dropdown-pane li").click(function(){
                $(this).parent("ul").parent("div").parent("div").parent("div").parent(".dropdown-pane").foundation('close');
                id = $(this).data('id');

                switch(this.className) {
                    case "attend":
                        attend(id);
                        break;
                    case "late":
                        late(id);
                        break;
                    case "guest":
                        guest(id);
                        break;
                    case "lateguest":
                        lateguest(id);
                        break;
                    case "disatend":
                        disattend(id);
                        break;
                }
            });

            localStorage.setItem("jwt", jqXhr.getResponseHeader('Authorization').substring(7));
        },
        error: function(data, textStatus, jqXhr){
            console.log(textStatus);
            console.log(data);
            localStorage.removeItem("jwt");
        },
        beforeSend: function(xhr, settings) {
            xhr.setRequestHeader('Authorization','Bearer ' + localStorage.getItem("jwt"));
        },
        complete: function(jqXhr, textStatus){
            reloadRelevant();
        }
    });
}

function attend(id){
    myuserid = jwt_decode(localStorage.getItem('jwt')).bruger.id;

    $.ajax({
        url: "public/dates/"+id+"/attendees/"+myuserid,
        method: "PUT",
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({late: 0, guest:0}),
        success: function(data, textStatus, jqXhr){
            console.log("attended");
            $("#content-maddage table tbody tr#date-"+id+" td:last-child img.current").attr("src","logos/greencheck.png");
        },
        error: function(data, textStatus, jqXhr){
            console.log(textStatus);
            console.log(data);
            //localStorage.removeItem("jwt");
        },
        beforeSend: function(xhr, settings) {
            xhr.setRequestHeader('Authorization','Bearer ' + localStorage.getItem("jwt"));
        },
        complete: function(jqXhr, textStatus){
            //reloadRelevant();
        }
    });
}
function late(id){
    myuserid = jwt_decode(localStorage.getItem('jwt')).bruger.id;

    $.ajax({
        url: "public/dates/"+id+"/attendees/"+myuserid,
        method: "PUT",
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({late: 1, guest:0}),
        success: function(data, textStatus, jqXhr){
            console.log("latered");
            $("#content-maddage table tbody tr#date-"+id+" td:last-child img.current").attr("src","logos/yellowcheck.png");
        },
        error: function(data, textStatus, jqXhr){
            console.log(textStatus);
            console.log(data);
            //localStorage.removeItem("jwt");
        },
        beforeSend: function(xhr, settings) {
            xhr.setRequestHeader('Authorization','Bearer ' + localStorage.getItem("jwt"));
        },
        complete: function(jqXhr, textStatus){
            //reloadRelevant();
        }
    });
}
function guest(id){
    myuserid = jwt_decode(localStorage.getItem('jwt')).bruger.id;

    $.ajax({
        url: "public/dates/"+id+"/attendees/"+myuserid,
        method: "PUT",
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({late: 0, guest:1}),
        success: function(data, textStatus, jqXhr){
            console.log("guested");
            $("#content-maddage table tbody tr#date-"+id+" td:last-child img.current").attr("src","logos/greenplus1.png");
        },
        error: function(data, textStatus, jqXhr){
            console.log(textStatus);
            console.log(data);
            //localStorage.removeItem("jwt");
        },
        beforeSend: function(xhr, settings) {
            xhr.setRequestHeader('Authorization','Bearer ' + localStorage.getItem("jwt"));
        },
        complete: function(jqXhr, textStatus){
            //reloadRelevant();
        }
    });
}
function lateguest(id){
    myuserid = jwt_decode(localStorage.getItem('jwt')).bruger.id;

    $.ajax({
        url: "public/dates/"+id+"/attendees/"+myuserid,
        method: "PUT",
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({late: 1, guest:1}),
        success: function(data, textStatus, jqXhr){
            console.log("laterguested");
            $("#content-maddage table tbody tr#date-"+id+" td:last-child img.current").attr("src","logos/yellowplus1.png");
        },
        error: function(data, textStatus, jqXhr){
            console.log(textStatus);
            console.log(data);
            //localStorage.removeItem("jwt");
        },
        beforeSend: function(xhr, settings) {
            xhr.setRequestHeader('Authorization','Bearer ' + localStorage.getItem("jwt"));
        },
        complete: function(jqXhr, textStatus){
            //reloadRelevant();
        }
    });
}
function disattend(id){
    myuserid = jwt_decode(localStorage.getItem('jwt')).bruger.id;

    $.ajax({
        url: "public/dates/"+id+"/attendees/"+myuserid,
        method: "DELETE",
        contentType: 'application/json',
        dataType: 'json',
        success: function(data, textStatus, jqXhr){
            console.log("disattended");
            $("#content-maddage table tbody tr#date-"+id+" td:last-child img.current").attr("src","logos/noee.png");
        },
        error: function(data, textStatus, jqXhr){
            console.log(textStatus);
            console.log(data);
            //localStorage.removeItem("jwt");
        },
        beforeSend: function(xhr, settings) {
            xhr.setRequestHeader('Authorization','Bearer ' + localStorage.getItem("jwt"));
        },
        complete: function(jqXhr, textStatus){
            //reloadRelevant();
        }
    });
}


