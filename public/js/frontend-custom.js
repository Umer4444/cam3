


//rating stars init
$(document).ready(function () {
    $("#tabs").tabs();//init tabs
    $(".stars").each(function () {
        if ($(this).hasClass("disableRate")) {
            disabled = true;
        } else {
            disabled = false;
        }
        var size = $(this).data('star-size');
        $(this).jRating({
            phpPath: '/process',
            action: 'rating',
            isDisabled: disabled,
            bigStarsPath: '/js/jrating/icons/stars.png', // path of the icon stars.png
            smallStarsPath: '/js/jrating/icons/small.png', // path of the icon small.png
            type: size, // type of the rate.. can be set to 'small' or 'big'
            length: 5, // nb of stars
            decimalLength: 0, // number of decimal in the rate
            step: true, //fil stats full
            rateMax: 5
        });
    });
});



