$(document).on("click", ".js-addTodo", function (){
    let todoBody = $(".js-TodoBody");
    let html ='<div class="row css-todoRow">\n' +
        '        <div class="col-10">\n' +
        '            <input type="text" class="form-control js-TodoText" name="todo_text" placeholder="What to do">\n' +
        '        </div>\n' +
        '        <div class="col align-middle js-AddTodoRow">\n' +
        '            <i class=\'bx bx-check float-end btn btn-primary \' style="font-size: 18px;"></i>\n' +
        '        </div>\n' +
        '    </div>';
    todoBody.append(html);
});

$(document).on("click", ".js-removeTodo", function (){
        let row = $(this).closest(".row");
        row.addClass("animate__animated animate__fadeOut");
        setTimeout(function (){
            row.remove();
        }, 800);
});

$(document).on("click", ".js-AddTodoRow", function (){
    let row = $(this).closest(".row");
    let row_text = row.find("input[name='todo_text']").val();
    $.ajax({
        method: "POST",
        url: "/todo/add",
        data: {text: row_text},
        success: function(data){
            console.log(data);
            $.toast({
                class: 'success',
                message: data
            })
            ;
        },
        error: function (data) {
            $.toast({
                class: 'warning',
                message: data
            })
            ;
        }
    })
});
