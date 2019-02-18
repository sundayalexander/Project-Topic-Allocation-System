// JavaScript Document

$("#submit").click(function(){
	var formData = $("#topicForm").serialize()
	//Display connection message here
	var matNo = $("#exampleInputMatricNo").val();
    var supervisor = $("#supervisor").val();
	if(matNo.length > 8 && supervisor != "NULL"){
        $.post("./routes/post.php",
            formData,
            function(data,status){

                //output response message to the user
                var response = $.parseJSON(data);
                if(response.status == 201){
                    alert(response.message);
                    $("#topicForm")[0].reset();
                }else{
                    alert(response.message);
                }
            });
    }else if(supervisor == "NULL"){
        alert("Ooops! please select your supervisor");
    }else{
	    alert("Ooops! please enter a valid matric number");
    }
	

});