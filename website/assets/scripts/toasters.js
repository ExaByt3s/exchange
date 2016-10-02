Toasters = (function () {

    var $body = $('body'),
        timer;


    var create = function (message) {

        remove();

        $body.append('<div class="toaster">' + message + '</div>');
        $body.find('.toaster').animate({top: '25px'}, 250);

        timer = setTimeout(function() {
            remove();
        }, 7000);
    };


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