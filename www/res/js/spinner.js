$(document).ajaxStart(function () {
    $('.loading').show();  // show loading indicator
});

$(document).ajaxStop(function() {
    $('.loading').hide();  // hide loading indicator
});