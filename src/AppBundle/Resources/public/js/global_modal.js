 function showModal(text, title='Info') {
    $("#global-modal").find(".modal-title").text(title);
    $("#global-modal").find(".modal-body").text(text);
    $("#global-modal").modal();
}