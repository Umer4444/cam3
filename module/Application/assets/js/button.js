App.button = {

    default: function(arg) {

        if (arg.length > 1) {
            var key = arg[1];
            var options = arg[2];
            options.commands[key].$node.data($('<p '+options.items[key].data+'></p>').data());
        }

    },

    call: function(id){

        App.button.default(arguments);

        $('.'+id).unbind('click');
        $('.'+id).on('click', function (e) {
            e.preventDefault();
            var url = $(this).prop('href') ? $(this).prop('href') : $(this).data('href');
            if (url) {
                window.open(url, "_blank", "titlebar=yes,status=no,height=800,width=600,resizable=no,left=50,top=50,toolbar=no,menubar=no,scrollbars=no,location=no,directories=no");
            }
        });

    },

    watchPopup: function(id){

        App.button.default(arguments);

        $('.'+id).unbind('click');
        $('.'+id).on('click', function (e) {
            e.preventDefault();
            var url = $(this).prop('href') ? $(this).prop('href') : $(this).data('href');
            if (url) {
                window.open(url, "_blank", "titlebar=yes,status=no,height=800,width=990,resizable=no,left=50,top=50,toolbar=no,menubar=no,scrollbars=no,location=no,directories=no");
                socket.destroy();
                location.href = '/';
            }
        });

    },

    kick: function(userId){

        if (typeof(socket) === 'undefined') {
            return false;
        }

        socket.emit('kick', {userId: userId});

        $.ajax({
            type: 'PUT',
            url: '/api/kick/'+userId,
        })

    },

    friend: function(id) {

        App.button.default(arguments);

        var selector = '.'+id+'[href!="/account/login"]';
        $(selector).unbind('click');
        $(selector).on('click', function (e) {

            var $button = $(this);
            var btn_text = 'UnFriend';
            var method = 'POST';
            var action = 'removeFriend';
            var url = '/api/friends';

            if (!$button.data('friend') || !$button.data('user')) {
                e.preventDefault();
                return console.log('#button friend req: performer or user is missing');
            }

            // if is friend, setup action and text to remove friendship
            if($(this).data('action') == 'removeFriend') {
                btn_text = 'Friend Req';
                method = 'DELETE';
                action = 'addFriend';
            }

            $.ajax({
                type: method,
                url: url,
                data: {
                    user: $button.data('user'),
                    friend: $button.data('friend')
                },
                complete: function(response) {
                    $(selector + (arguments.length > 1 ? ' span, '+selector : '')).html(btn_text);
                    $(selector).data('action', action);
                }
            });

        });

    },

    favorite: function(id) {

        App.button.default(arguments);

        $('.'+id).on('click', function(){

           var $button = $(this);

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: $button.data('url'),
                data: {
                   user: $button.data('user')
                }

           }).done(function (response) {
               if (response.status == 'success') {
                   $button.html("Favorited")
               }
           });
       });

    },

    tip: function(id) {

        var params = {
            /*params: function(params) {
                return JSON.stringify(params);
            }*/
        };

        if (arguments.length > 1) {
            App.button.default(arguments);
            arguments[2].commands[arguments[1]].$node.addClass('xeditable');
            params.display = false;
            params.placement = 'right';
        }

        $('.'+id).editable(params);

    },

    private: function(id, price, performer, url) {

        var $dialog = $("<div>The performer's fee is $"+price+" per minute</div>").uniqueId().dialog({
            resizable: false,
            height: 150,
            width: 360,
            modal: true,
            title: 'Private fee',
            buttons: {
                'Accept': function() {

                    notificationsSocket.emit('private request', {user: user, performer: performer, buttonId: id, url: url});
                    $(this).text('').append('<div data-type="timer-flip"></div>');
                    $(this).dialog('option', 'title', 'Waiting for the performer to answer ...')
                    $(this).dialog('option', 'height', 270);

                    var counter = $('[data-type="timer-flip"]').FlipClock(App.dialogTimeout, {
                        countdown: true,
                        clockFace: 'MinuteCounter'
                    });

                    setTimeout(function(){
                        $dialog.dialog({
                            buttons: {
                                'Close': function() {
                                    $dialog.dialog("close");
                                }
                            }
                        });
                        $dialog.text('We are sorry, the model seems busy !');
                    }, App.dialogTimeout * 1000)

                    $(this).dialog({buttons:{}});

                },
                'Decline': function() {
                    $(this).dialog("close");
                }
            }
        });

    }

}