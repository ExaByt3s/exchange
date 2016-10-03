Forms = (function () {

    /**
     * Binds ajax request to form submit event.
     *
     * @param {object} $form
     * @param {function|null} callback
     */
    var initAjaxSubmit = function ($form, callback) {
        $form.on('submit', function() {
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

                if (callback !== null) {
                    callback(message);
                }
            });

            return false;
        });
    };

    return {
        initAjaxSubmit: initAjaxSubmit
    };

})();
