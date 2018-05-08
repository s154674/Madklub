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
                maddag['attending'] = maddag['attending']==="1";
                maddag['late'] = maddag['late']==="1";
                maddag['guest'] = maddag['guest']==="1";

                console.log(maddag);
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
                console.log(img);
                currentimg = "<img class='current' src='logos/"+img+".png' data-toggle='drop-"+maddag['date_id']+"'>";   // current status

                logo1 = "<li data-id='"+maddag['date_id']+"' class='attend'><img src='logos/greencheck.png'></li>";
                logo2 = "<li data-id='"+maddag['date_id']+"'class='late'><img src='logos/yellowcheck.png'></li>";
                logo3 = "<li data-id='"+maddag['date_id']+"' class='guest'><img src='logos/greenplus1.png'></li>";
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

function attend(id){
    $("#content-maddage table tbody tr#date-"+id+" td:last-child img.current").attr("src","logos/greencheck.png");
    console.log("Der blev trykket på en knap");
}
function late(id){
    $("#content-maddage table tbody tr#date-"+id+" td:last-child img.current").attr("src","logos/yellowcheck.png");
    console.log("Der blev trykket på en knap");
}
function guest(id){
    $("#content-maddage table tbody tr#date-"+id+" td:last-child img.current").attr("src","logos/greenplus1.png");
    console.log("Der blev trykket på en knap");
}
function lateguest(id){
    $("#content-maddage table tbody tr#date-"+id+" td:last-child img.current").attr("src","logos/yellowplus1.png");
    console.log("Der blev trykket på en knap");
}
function disattend(id){
    $("#content-maddage table tbody tr#date-"+id+" td:last-child img.current").attr("src","logos/noee.png");
    console.log("Der blev trykket på en knap");
}