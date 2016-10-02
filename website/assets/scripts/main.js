$(function() {

    if (document.location.pathname === '/')
        Homepage.init();

    if (document.location.pathname === '/account/edit')
        Account.init();

    if (document.location.pathname === '/dashboard')
        Dashboard.init();

    if (document.location.pathname.lastIndexOf('/register/activation', 0) === 0)
        Activation.init();

});
