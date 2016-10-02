ModalBox = (function () {
    var $modalBox =  $('#modalBox'),
        $dashboard =  $('.dashboard');

    var init = function () {
        $('body').on('click', '[data-openModalBox="modalBox"]', function() {
            var $button = $(this),
                buttonUrl = $button.attr('data-modalBoxUrl'),
                buttonParam = $button.attr('data-modalBoxUrlParam');

            $.ajax({
                url: buttonUrl + ((buttonParam) ? ('/' + buttonParam) : '')
            }).done(function(data) {
                openModalBox();
                $dashboard.css({filter: 'blur(5px)'});
                $modalBox.find('.modal-content').html(data);
            });

        });

        $modalBox.on('hidden.bs.modal', function() {
            $dashboard.css({filter: 'blur(0)'});
        });
    };


    var openModalBox = function () {
        $modalBox.modal('show');
    };

    var closeModalBox = function () {
        $modalBox.modal('hide');
    };


    return {
        init: init
    };

})();


ModalBox.init();
