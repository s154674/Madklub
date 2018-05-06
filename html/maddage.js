function updateMaddag(){
    $.get( "http://www.lmlige.dk/public/maddage", function( data ) {
        data = JSON.parse(data);
        //data = data[0]// fordi johan
        console.log(data)

        peopletable = $("#maddaglist tbody");
        peopletable.empty();
        $.each(data, function(i, person){
            console.log(person);
            cell1 = "<td>"+person['name']+"</td>";
            cell2 = "<td>"+person['fraction']+"</td>";
            cell3 = "<td>"+person['balance']+"</td>"

            row = "<tr>"+cell1+cell2+cell3+"</tr>";
            peopletable.append(row);

        })
        // $("#date").html(data['date'])
        // $("#cook").html(data['cook'])
        // $("#help").html(data['help'])
        // $("#dish").html(data['dish'])
    })
}