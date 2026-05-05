define([
    'jquery',
    'Magento_Ui/js/modal/modal'
], function ($, modal) {
    'use strict';

    return function (config, element) {
        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            modalClass: 'waitlist-modal',
            buttons: [{
                text: $.mage.__('Continue'),
                class: 'mymodal1',
                click: function () {
                    this.closeModal();
                }
            }]
        };

        var popup = modal(options, $('#join-waitlist-modal'));

        $(element).find('#join-waitlist-btn').on('click', function () {
            $('#join-waitlist-modal').modal('openModal');
        });
    };
});