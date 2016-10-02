Activation = (function () {

    var bindForm = function () {
        $('#activationForm').on('submit', function() {
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

                if (typeof message.message !== 'undefined' && message.message.length > 0) {
                    Toasters.create(message.message);
                }

                $form.find('button').text($submitVal);

                if (message.success === true) {
                    location.href = '/';
                }
            });

            return false;
        });
    };


    var init = function () {
        bindForm();
    };


    return {
        init: init
    };

})();
