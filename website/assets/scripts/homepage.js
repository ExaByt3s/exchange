Homepage = (function () {

    var bindRegisterForm = function () {
        $('#registryForm').on('submit', function() {
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

                if (message.success === true) {
                    $form.find('input:not(:checkbox)').val('');
                    $form.find(':checkbox').prop('checked', false);
                }
            });

            return false;
        });
    };

    var bindLoginForm = function () {
        $('#loginForm').on('submit', function() {
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
                   location.href = '/dashboard';
                }
            });

            return false;
        });
    };


    var init = function () {
        bindRegisterForm();
        bindLoginForm();
    };


    return {
        init: init
    };

})();

Homepage.init();