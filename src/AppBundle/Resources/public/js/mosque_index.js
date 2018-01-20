$(".btn-refresh").click(function () {
    $.ajax({
        url: $(this).data('url'),
        success: function () {
            showModal("Demande de rafraîchissement de l'écran effectuée avec succès");
        },
        error: function () {
            showModal("Une erreur est survenue !");
        }
    });
});
