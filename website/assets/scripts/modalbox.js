ModalBox = (function () {
    var $modalBox =  $('#modalBox'),
        $dashboard =  $('.dashboard');


    var open = function($button) {
        var buttonUrl = $button.attr('data-modalBoxUrl'),
            buttonParam = $button.attr('data-modalBoxUrlParam');

        $.ajax({
            url: buttonUrl + ((buttonParam) ? buttonParam : '')
        }).done(function(data) {
            $modalBox.modal('show');
            $dashboard.css({filter: 'blur(5px)'});
            $modalBox.find('.modal-content').html(data);
        });

        $modalBox.on('hidden.bs.modal', function() {
            $dashboard.css({filter: 'blur(0)'});
        });
    };


    var close = function() {
        $modalBox.modal('hide');
    };


    return {
        open: open,
        close: close
    };

})();
