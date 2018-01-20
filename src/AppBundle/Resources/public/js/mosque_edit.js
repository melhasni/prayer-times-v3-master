$("#user_complete").autocomplete({
    source: function (request, response) {
        $.ajax({
            url: $("#user_complete").data("remote"),
            dataType: "json",
            data: {
                term: request.term
            },
            success: function (data) {
                response(data);
            }
        });
    },
    minLength: 2,
    select: function (event, ui) {
        $("#user").val(ui.item.id);
    }
});


function typeDisplayHandler() {
    $type = $("#type");
    $(".mosque-block").hide();
    $("."+$type.val()+"-block").show();
}

$("#type").bind("change", function (event) {
    typeDisplayHandler();
});

typeDisplayHandler();