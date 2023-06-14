$(document).on("click", ".js-loginSumbit", function(){
    let email = $(".js-loginEmail").val();
    let password = $(".js-loginPassword").val();

    $.ajax({
        type: "POST",
        url: "/",
        content: {email: email, password: password},
        success: function(response){

        }
    });
})