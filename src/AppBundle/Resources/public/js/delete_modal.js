$(".delete-button").click(function (event) {
    event.stopPropagation();
    var modalButton = $('#delete-modal .modal-delete-button');
    var href = modalButton.attr("href").replace('_id_', $(this).data('id'));
    modalButton.attr("href", href);
    $('#delete-modal').modal();
});