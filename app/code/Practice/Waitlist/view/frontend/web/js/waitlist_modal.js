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
            buttons: []
        };
        console.log(element);
        var popup = modal(options, $('#join-waitlist-modal'));

        $(element).find('#join-waitlist-btn').on('click', function () {
            $('#join-waitlist-modal').modal('openModal');
        });

        $('#waitlist-submit').on('click', function () {
            alert('hello');
            $.ajax({
                url: '/waitlist/manage/submit',
                type: 'POST',
                data: {
                    customer_name: $('#waitlist-name').val(),
                    customer_email: $('#waitlist-email').val(),
                    customer_phone: $('#waitlist-phone').val(),
                    customer_comment: $('#waitlist-comment').val(),
                    product_id: $('#waitlist-product-id').val()
                },
                success: function (response) {
                    if (response.success) {
                        alert(response.message);
                    }
                }
            });
        });
    };
});