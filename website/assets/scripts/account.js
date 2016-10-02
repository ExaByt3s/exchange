Account = (function () {

    var bindTransferForm = function () {
        $('#transferForm').on('submit', function() {
            var $form = $(this),
                $submit = $form.find('button'),
                $submitVal = $submit.text();

            $form.find('button').text('Wait...');

            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serialize()
            }).done(function(data) {
                var message = JSON.parse(data);
                Toasters.create(message.message);
                $form.find('button').text($submitVal);
            });

            return false;
        });
    };


    var init = function () {
        bindTransferForm();
    };


    return {
        init: init
    };

})();

Account.init();