$('.sortable').sortable({
    update: function (event, ui) {
        $.ajax({
            type: "PUT",
            url: ui.item.data('remote'),
            data: {
                'id': ui.item.data('id'),
                'position': ui.item.index()
            },
            success: function () {
            },
            error: function () {
                showModal("Order has been failed", "Error");
            }
        });
    }
}).disableSelection();