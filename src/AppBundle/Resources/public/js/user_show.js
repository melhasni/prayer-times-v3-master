$("#user_enabled").change(function (event) {
    $.ajax({
        url: $(this).data('url').replace('_status_', $(this).is(':checked')),
        success: function () {
             showModal("Utilisateur maj avec succès");
        },
        error: function () {
            showModal("Une erreur est survenue");
        }
    });
});
