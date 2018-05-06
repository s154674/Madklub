function updateForside(){
    $.get( "http://www.lmlige.dk/public/cooks", function( data ) {
        data = JSON.parse(data);
        data = data[0]// fordi johan
        console.log(data)
        $("#date").html(data['date'])
        $("#cook").html(data['cook'])
        $("#help").html(data['help'])
        $("#dish").html(data['dish'])
    })
}