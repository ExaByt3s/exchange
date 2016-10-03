Toasters = (function () {

    var $body = $('body'),
        timer;


    /**
     * Creates new toaster with message and remove old if exists.
     *
     * @param {string} message
     */
    var create = function (message) {
        remove();

        $('<div class="toaster">' + message + '</div>')
            .appendTo('body')
            .animate({top: '25px'}, 250);

        timer = setTimeout(function() {
            remove();
        }, 7000);
    };


    /**
     * Removes visible toasters.
     */
    var remove = function () {
        if (typeof timer !== 'undefined') {
            clearTimeout(timer);
        }

        $body.find('.toaster').animate({top: '-250px'}, 250, function() {
            $(this).remove();
        });
    };


    return {
        create: create
    };

})();