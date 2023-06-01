$(document).ready(function () {

    $('.imageWrapper').css('background', 'black');

    $('#fancy').fancybox({
        padding: 0,
        modal: true,
        helpers: {
            buttons: {},
            overlay: {
                opacity: 1
                // css: {'background-color': '#111'}
            }
        },
        afterShow: function (e) {
            $("#disqus_thread").appendTo('.comments');

        },

        afterLoad: function () {


            setTimeout(function () {
                var prev = '{{ previous }}';

                if (prev != 'none') {

                    $('.btnPrev').attr('href', '{{ previous }}#open');

                } else {

                    $('.btnPrev').attr('href', '#');
                    $('.btnPrev').addClass('disabled');
                    $('.btnPrev').addClass('hidden');
                }

                var next = '{{ nextUrl }}';

                if (next != 'none') {

                    $('.btnNext').attr('href', '{{ next }}#open');

                } else {

                    $('.btnNext').attr('href', '#');
                    $('.btnNext').addClass('disabled');
                    $('.btnNext').addClass('hidden');
                }

                $('.btnNext').unbind();
                $('.btnPrev').unbind();
                $('.btnPlay').parent().html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');

            }, 500);
        },
        nextEffect: 'fade',
        prevEffect: 'fade',
        'titleFormat': function (title, currentArray, currentIndex, currentOpts) {
            return '<span id="fancybox-title-over">Image</span>';
        },
        beforeClose: function () {

            $("#disqus_thread").appendTo('#tabs');

            $('#com').click();

        },
        beforeLoad: function () {

            $('.comments').children().show();
        }
    }); // fancybox

    if (window.location.hash == '#open') {

        $('#fancy').click();

    };

    $('#review_button').click(function () {

        $('#add_review').show();

    });


    var disqus = $('#disqus_thread').html();
    $('#a').append(disqus);
    $(document).ready(function () {
        $('#review_button').click(function() {
            $('#add_review').removeClass('hidden');
        });
        $('.imageWrapper').css('background', 'black');

        $('#fancy').fancybox({
            padding: 0,
            modal: true,
            helpers: {
                buttons: {},
                overlay: {
                    opacity: 1
                    // css: {'background-color': '#111'}
                }
            },
            afterShow: function (e) {
                $("#disqus_thread").appendTo('.comments');
                $('.fancybox-overlay').click(function () {
                    $.fancybox.close();
                });
            },
            nextEffect: 'fade',
            prevEffect: 'fade',
            'titleFormat': function (title, currentArray, currentIndex, currentOpts) {
                return '<span id="fancybox-title-over">Image</span>';
            },
            beforeClose: function () {
                $("#disqus_thread").appendTo('.tab-content');
            },
            beforeLoad: function () {
                $('.comments').children().show();
            }
        }); // fancybox

        if (window.location.hash == '#open') {
            $('#fancy').click();
        }

    });

});
