/**
 * form validation erorrs for bootstrap form plus bubble insertion
 *
 * @param response
 */
function formDone(response) {
    if (response.status == 'success') {

        $(".control-group.error span[class*='help-']").remove();
        $(".control-group.error").removeClass('error');

    } else if (response.status == 'fail') {


        $(".control-group.error span[class*='help-']").remove();
        $(".control-group.error").removeClass('error');
        if (response.hasOwnProperty("errors")) {
            for (key in response.errors) {
                insertedMessages = '<span class="help-block"><ul>';
                if (response.errors.hasOwnProperty(key)) {
                    messages = response.errors[key];
                    $.each(messages, function (key_m, value_m) {
                        if (messages.hasOwnProperty(key_m)) {
                            insertedMessages = insertedMessages + '<li>' + value_m + '</li>';
                        }
                    });
                }
                insertedMessages = insertedMessages + '</ul></span>';

                $('#' + key).after(insertedMessages);
                $('#' + key).parents().eq(1).addClass("error");
            }
        }
    }
}


/**
 * spinner  add overlay for ajax submit
 * @param spinner
 */
var spinner = {
    "options": {
        'template': '<div id="facebookG"><div id="blockG_1" class="facebook_blockG"></div> <div id="blockG_2" class="facebook_blockG"></div><div id="blockG_3" class="facebook_blockG"></div></div>',
        'defaultClass' : '.jquery-modal.blocker.spinner'
    },
    "appendOverlay": function (spinner) {
        $(this.options.defaultClass).remove();

        $('<div/>', {
            class: this.options.defaultClass.split(".").join(" ")
        }).appendTo('body');

        if (typeof spinner != 'undefined') {
            this.appendSpinner(this.options.defaultClass);
        }
    },
    "removeOverlay": function () {
        $('.spin').remove();
        $(this.options.defaultClass).remove();
    },
    "appendSpinner": function (element) {

        $('<div/>', {
            class: 'spin',
            html: this.options.template
        }).appendTo(element);
    }

}

function submitItemsForModeration(url) {

    $(".submit").removeAttr('disabled');

    if (typeof url == 'undefined') return;
        //var str = $('#photos-form').serialize();

        $('.submit').click(function () {
            if( $("#pending").val().length < 1
            && $("#denied").val().length < 1
            && $("#approved").val().length < 1 ) {

                return;
            }

            //var str = $('#photos-form').serialize();
            $(".submit").attr('disabled', true);
            spinner.appendOverlay(true);
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {
                    "pendingItems": $("#pending").val(),
                    "deniedItems": $("#denied").val(),
                    "approvedItems": $("#approved").val()
                },
                success: function (response) {
                    $(".submit").attr('disabled', false);
                    spinner.removeOverlay();

                    for (var key in response.approved) {
                        $('#' + response.approved[key]).remove();
                    }
                }
            });
        });

}
    function initModerationButtons() {
       // $('.btn-group').button();

        /*$(".moderate-items .btn-group input[type='radio']").on('click', function () {*/
        $(".moderate-items.btn-group button").on('click', function () {

            itemId = $(this).data('id');

            $(this).parent().children('button').removeClass('active');
            $(this).addClass('active');

            $("#item_" + itemId + ' .a_cover').data("active", $(this).val());

            pendingItems = $.trim($("#pending").val()).split(',').filter(Boolean);
            approvedItems = $.trim($("#approved").val()).split(',').filter(Boolean);
            deniedItems = $.trim($("#denied").val()).split(',').filter(Boolean);

            pendingItems = $.grep(pendingItems, function (n, i) {
                return ( n != itemId);
            });
            deniedItems = $.grep(deniedItems, function (n, i) {
                return ( n != itemId);
            });
            approvedItems = $.grep(approvedItems, function (n, i) {
                return ( n != itemId);
            });

            if ($(this).val() == "0") { //pending
                pendingItems.push(itemId);
            } else if ($(this).val() == "1") { //approved
                approvedItems.push(itemId);
            } else if ($(this).val() == "-1") { //denied
                deniedItems.push(itemId);
            }

            $("#pending").val(pendingItems.join(','));
            $("#approved").val(approvedItems.join(','));
            $("#denied").val(deniedItems.join(','));
        });
    }

    function initFancyboxWithModeration() {
        $("a[rel=group]").on("click", function () {
            el = $(this);
            $.fancybox({
                href: this.href,
                type: $(this).data("type"),
                'transitionIn': 'none',
                'transitionOut': 'none',
                'titlePosition': 'inside',
                'title': $(this).data("caption"),
                'titleFormat': function (title, currentArray, currentIndex, currentOpts) {

                    /* return  '<span id="fancybox-title-inside">' +    title + '</span>';*/

                    return  $('<span/>', {
                        id: 'fancybox-title-inside',
                        html: title
                    }).after(
                            $('<div/>', {
                                class: 'show-status',
                                html:  el.parents().eq(1).children(".item-status")[0].innerHTML
                            })
                        );

                },
                "onComplete": function () {
                    //set tooltip for a inside caption
                    $(".fancybox-title-inside .tooltip").tooltip({placement: 'right'});
                    initOptionsMenu();
                    return;
                }

            }); // fancybox
            return false
        }); // on
    }

    function initOptionsMenu(url) {

        if (typeof url == 'undefined' || url.length < 1) return;
        $(".item-options button").unbind("click");
        $(".item-options button").on("click", function (e) {
            e.preventDefault();
            if($(this).hasClass('disabled')) return;

            var listElement = $(this).parent().children('ul.item-options-list');

            if(listElement.hasClass('active')) {
                listElement.removeClass("active");
                listElement.hide();
             } else {
                //clean previous opened element
                $('ul.item-options-list.active').removeClass('active');
                $('ul.item-options-list').hide();

                listElement.addClass("active");
                listElement.show();

            }

            //listElement.toggle();
            var id = $(this).data("id");

            listElement.children('li').unbind('click');


            listElement.children('li').on('click', function (e) {
                e.preventDefault();
                if ($(this).children().hasClass('hidden') === false) {

                    $(this).children().addClass('hidden');

                } else {

                    $(this).children().removeClass('hidden');
                }

                el = $(this);
                if(el.hasClass('disabled')) return;
                listElement.children('li').addClass('disabled');
                spinner.appendSpinner(el);

                var action = el.data('action');

                var jqxhr = $.ajax(url, {
                    type: 'POST',
                    dataType: "json",
                    data: {
                        'id': id,
                        'action': action
                    }
                }).done(function (response) {
                    // response = JSON.parse(response);

                }).fail(function (response) {
                    //response = JSON.parse(response);

                }).always(function () {
                    //listElement.hide();
                    spinner.removeOverlay();
                    listElement.children('li').removeClass('disabled');
                    //listElement.removeClass("active");
                });
            })
        });
    }

    $(document).ready(function () {
        $(".secondary-nav a").on("click", function () {
            $.each($(".account-menu-arrow"), function () {
                if ($(this).hasClass('hide')) {
                    $(this).removeClass('hide');
                } else if (!$(this).hasClass('hide')) {
                    $(this).addClass('hide');
                }
            })
        })
    });


$(document).ready(function () {

    //select all input content - used for link selection
    $(".selectOnClick").on("click", function () {
        $(this).select();
    });

    chatRating();
})
window.modalShow = true;
function chatRating() {
    $.cookie.json = true;

    if (!window.modalShow) return false;

    if ($.cookie('chatsession')) {
        var data = JSON.parse($.cookie('chatsession'));

        now = new Date();
        $.each(data, function (k, v) {
            /********************************
             *** payed session only
             *************************************/

            if (/*v.chat_type == 'normal' &&*/ (now.getTime() - v.last_activity > 30000)) {
                model_id = k.replace('chat_', '');

                if (!window.modalShow) return false;
                //display modal for ratings
                $(".rating-modal").dialog("destroy");
                $(".rating-modal").remove();

                $('<div/>').addClass("rating-modal").dialog({
                    modal: true,
                    spinnerHtml: spinner.options.template,      // HTML appended to the default spinner during AJAX requests.
                    showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
                    create: function (event, ui) {
                        $(".rating-modal").append(spinner.options.template);
                    },
                    open: function () {

                        window.modalShow = false;

                        $(this).load(
                            '/process/rate-session',
                            {'model': model_id},
                            function () {
                                $(".rating-modal").data("session-id", k);
                                $(".stars-model").each(function () {
                                    if ($(this).hasClass("disableRate")) {
                                        disabled = true;
                                    } else {
                                        disabled = false;
                                    }

                                    $(this).jRating({
                                        phpPath: '/process/rate-session',
                                        action: 'rating',
                                        isDisabled: disabled,
                                        bigStarsPath: '/scripts/jrating/icons/stars.png', // path of the icon stars.png
                                        smallStarsPath: '/scripts/jrating/icons/small.png', // path of the icon small.png
                                        type: 'big', // type of the rate.. can be set to 'small' or 'big'
                                        length: 5, // nb of stars
                                        decimalLength: 0, // number of decimal in the rate
                                        step: true, //fil stats full
                                        rateMax: 5
                                    });
                                });
                            });
                    },
                    beforeClose: function (event, ui) {
                        sess = $(".rating-modal").data("session-id");

                        window.modalShow = true;

                        if (typeof data[sess] != 'undefined') {
                            delete data[sess];
                            //data[sess]['voted'] = true;
                        }

                        $.cookie('chatsession', JSON.stringify(data), {
                            path: '/'
                        });
                    },
                    height: 200,
                    width: 400,
                    title: 'Rate chat session'
                });
                //logout user if needed
                //unset value from cookie
            }
        });

    }
    /*now = new Date();

    <script src="/js/custom.js"></script>
     $.cookie.json = true;
     var data = {};
     if($.cookie('chatsession')){
     data = JSON.parse($.cookie('chatsession'));
     data['chat_' + App.chat.data.model_id ] = now.getTime();
     } else {
     data['chat_' + App.chat.data.model_id] = now.getTime();
     }

     $.cookie('chatsession', JSON.stringify(data), {path: '/',  expire: new Date(now.getTime() + (4 * 60 * 1000))});*/

    setTimeout(function () {
        chatRating();
    }, 30000);

}
