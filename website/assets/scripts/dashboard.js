Dashboard = (function () {
    var ViewModel = {
        currencies: ko.observableArray([]),
        lastUpdateTime: ko.observable(),
        modalBoxTitle: ko.observable()
    };


    var formatTime = function(time) {
        return (time < 10) ? '0' + time : time;
    };


    var bindWebSocket = function() {
        var apiSocket = new WebSocket("ws://webtask.future-processing.com:8068/ws/currencies");

        apiSocket.onmessage = function (data) {
            var currencies = JSON.parse(data.data),
                lastUpdateTime = new Date(currencies.PublicationDate),
                currenciesData = [];

            currencies.Items.forEach(function(currency) {
                currency.SellPrice = parseFloat(currency.SellPrice).toFixed(2);
                currency.UnitPrice = parseFloat(currency.PurchasePrice).toFixed(2);
                currency.PurchasePrice = parseFloat(currency.PurchasePrice * currency.Unit).toFixed(2);
                currency.AvailableInWallet = $('body').find('[data-currency=' + currency.Code.toLowerCase() + ']').text();
                currenciesData.push(currency);
            });

            ViewModel.currencies(currenciesData);
            ViewModel.lastUpdateTime(formatTime(lastUpdateTime.getHours()) + ':' + formatTime(lastUpdateTime.getMinutes()) + ':' + formatTime(lastUpdateTime.getSeconds()));

            $('[data-toggle="tooltip"]').tooltip();
        };

        ko.applyBindings(ViewModel, document.getElementsByClassName('dashboard')[0]);
    };


    var bindModalBoxes = function () {
        $('body').on('click', '[data-openModalBox="modalBox"]', function() {
            ModalBox.open($(this));
        });
    };


    var bindModalForm = function () {
        $('#modalBox').on('submit', 'form', function() {
            var currencyName = $('#currency').val(),
                currencyAmount = $('#amount').val(),
                total = $('#total').val();

            if (! confirm('Are you sure you want to buy ' + currencyAmount + ' ' + currencyName + ' and pay ' + total + ' PLN?')) return false;

            var $form = $(this),
                $submit = $form.find('button[type=submit]'),
                $submitVal = $submit.text();

            $submit.text('Wait...');

            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serialize()
            }).done(function(data) {
                var message = JSON.parse(data);

                if (typeof message.message !== 'undefined' && message.message.length > 0) {
                    Toasters.create(message.message);
                    ModalBox.close();
                }

                $submit.text($submitVal);

                if (message.success === true) {
                    location.reload();
                }
            });

            return false;
        });
    };


    var init = function () {
        bindWebSocket();
        bindModalBoxes();
        bindModalForm();
    };


    return {
        init: init
    };

})();
