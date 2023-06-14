$(document).on("click", ".js-OpenChat", function () {
    var modal = new bootstrap.Modal(document.getElementById('replyModal'));
    $(".js-ContactEmail").val("");
    $(".js-SubjectBox").val("");
    $(".js-MessageBox").val("");

    $("#replyModal").removeClass("animate__fadeOut");
    modal.show();

    let message_id = $(this).data("contactid");
    //fill the message email box
    $.ajax({
        method: "POST",
        url: "/messages/get-contact",
        data: {messageId: message_id},
        success: function(data){
            console.log(data);
            $(".js-ContactEmail").val(data);
        },
    })
})

$(document).on("click", ".js-SendMessage", function () {
    let email = $(".js-ContactEmail").val();
    let subject = $(".js-SubjectBox").val();
    let message = $(".js-MessageBox").val();

    $.ajax({
        method: "POST",
        url: "/messages/send-message",
        data: {message: message, email:email, subject: subject},
        success: function(data){
            var toast = new bootstrap.Toast(document.getElementById('toastAlert'));
            toast.show();

            setTimeout(function() {
                $("#replyModal").addClass("animate__fadeOut");
            },1500)

            setTimeout(function () {
                $("#replyModal").hide();
                $(".modal-backdrop").hide();
            }, 600)

        },
        error: function(){
            var toast = new bootstrap.Toast(document.getElementById('toastErrorAlert'));
            toast.show();

            setTimeout(function() {
                $("#replyModal").addClass("animate__fadeOut");
            },1500)

            setTimeout(function () {
                $("#replyModal").hide();
                $(".modal-backdrop").hide();
            }, 600)
        }
    })


})

$(document).on("click", ".js-CloseToast", function (){
    $(this).hide();
})

$(document).on("click", ".js-CloseMessage", function (){
    $("#replyModal").addClass("animate__fadeOut");
    setTimeout(function () {
        $("#replyModal").hide();
        $(".modal-backdrop").hide();
    }, 600)
})