$(function () {

    // rating widget
    $('[data-type="rating"]').each(function () {

        $element = $(this);

        $element.jRating({
            isDisabled: $element.hasClass("disableRate"),
            bigStarsPath: '/assets/vendor/javascript/jRating/jquery/icons/stars.png',
            smallStarsPath: '/assets/vendor/javascript/jRating/jquery/icons/small.png',
            type: $element.data('size'),
            rating: $element.data('rating'),
            length: 5,
            sendRequest: false,
            decimalLength: 0,
            step: true,
            rateMax: 5,
            onClick : function(element, rate) {
                $.ajax({
                    type: 'PATCH',
                    url: $element.data('url'),
                    data: {
                        'rating': rate
                    },
                    dataType: 'json'
                });
            }
        });

    });

    // like widget
    $(".like .close").on("click", function () {
        $(this).closest(".stats").slideUp();
    });

    $(".like a").click(function () {

        if ($(this).attr('href')) {
            return true;
        }

        var data = {};
        var name = $(this).data('name');
        var container = $(this).parent();
        data[name] = $(this).data('value');

        var stats = $(this).closest(".stats");
        stats.slideDown('fast').html('Loading....');

        $.ajax({
            type: 'PATCH',
            dataType: 'json',
            url: $(this).parent().data('url'),
            data: data,
            success: function(json) {

                var total = parseInt(json.likes) + parseInt(json.dislikes);

                var option = (name == "likes") ? "Liked" : "Disliked";
                var likes = (parseInt(json.likes) * 100 ) / total;
                var dislikes = (parseInt(json.dislikes) * 100 ) / total;

                stats.html('<div class="stat-details"><span class="close"></span>You <b>' + option + '</b> this. Thanks for the feedback!<br><b>Rating for this item</b> <span id="small"> (' + total + ' total)</span><table border="0" width="100%"><tr><td width="25px"><span class="thumbs-up"></span></td><td width="50px;">' + parseInt(json.likes) + '</td><td><div class="bar green" style="width:' + likes + '%;"></div></td></tr><tr><td><span class="thumbs-down"></span></td><td>' + parseInt(json.dislikes) + '</td><td><div class="bar red" style="width:' + dislikes + '%;"></div></td></tr></table>');

                $(".like .close").on("click", function () {
                    container.find(".stats").slideUp();
                });

            }
        });
    });

});