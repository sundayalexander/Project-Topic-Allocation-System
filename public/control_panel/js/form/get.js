// JavaScript Document
$("#check_status").click(function(){
    //insert loader here.....
    var formData = $("#statusForm").serialize()
    //Display connection message here
    var matricNo = $("#exampleInputMatricNo").val();
    if(matricNo.length > 8){
        $.get("./routes/get.php",
            formData,
            function(data,status){
                //Change the alert to dialog box or something else.....
                var response = $.parseJSON(data);
                if(response.code == 200 && response.status == 1){
                    alert("Congratulations!, your Project Topic: \""+
                        response.topic+"\" has been approved..");
                }else if(response.code == 200 && response.status == 0){
                    alert("Sorry!, your Project Topic: "+
                        response.topic+" has not been approved yet..");
                }else{
                    alert(response.message);
                }

            });
    }else{
        alert("Ooops! please enter a valid matric number");
    }


});