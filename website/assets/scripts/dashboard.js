$(document).ready(function(){

    var ViewModel = {
        currencies: ko.observableArray([]),
        lastUpdateTime: ko.observable(),
        modalBoxTitle: ko.observable()
    };

    function formatTime(time) {
        return (time < 10) ? '0' + time : time;
    }


    var exampleSocket = new WebSocket("ws://webtask.future-processing.com:8068/ws/currencies");

    exampleSocket.onmessage = function (data) {
        var currencies = JSON.parse(data.data),
            lastUpdateTime = new Date(currencies.PublicationDate);

        var currenciesData = [];
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

});