$(document).ready(function () {
    var btnFinish = $('<button></button>').text('Valider')
        .addClass('btn btn-info')
        .on('click', function () {
            submit();
        });
    var btnCancel = $('<button></button>').text('Annuler')
        .addClass('btn btn-danger')
        .on('click', function () {
            $('#smartwizard').smartWizard("reset");
        });
    // Smart Wizard
    $('#smartwizard').smartWizard({
        selected: 0,
        theme: 'arrows',
        transition: {
            animation: 'slide-horizontal', // Effect on navigation, none/fade/slide-horizontal/slide-vertical/slide-swing
        },
        toolbarSettings: {
            toolbarExtraButtons: [btnFinish, btnCancel]
        },
        lang: { // Language variables for button
            next: 'Suivant',
            previous: 'Précedent'

        }
    });

});