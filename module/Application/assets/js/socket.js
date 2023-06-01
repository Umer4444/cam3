var notificationsSocket = null;
var gameConfirmation = false;

$.game = {
    iterations: 30,
    interval: 1500
};

$.socket = {
    connectTo: '/node/notifications/'
};

$(function () {

    if (typeof(io) === 'undefined' || typeof(user) === 'undefined') {
        return false;
    }

    notificationsSocket = io.connect(location.hostname, {'path': $.socket.connectTo + 'socket.io'});

    // Whenever the server emits 'login', log the login message
    notificationsSocket.on('notification', function (data) {

        if (data.message === undefined) {
            return false;
        }

        $.notify(data.message, {
            className: 'info',
            autoHide: false,
            globalPosition: 'bottom right'
        });

        if (data.url != '') {
            $('div.notifyjs-wrapper').on('click', function () {
                window.open(data.url, '_blank');
            });
        }

    });

    notificationsSocket.on('game request', function (game) {

        if (game.opponent.id == user.id && !gameConfirmation) {
            gameConfirmation = true;

            var $dialog = $('<div>You have a new game request from <br>user: '+game.initiator.username+'<br>game: '+game.name+'</div>').uniqueId().dialog({
                resizable: false,
                height:200,
                modal: true,
                title: 'New Game Request',
                buttons: {
                    'Accept': function() {
                        game.response = true;
                        notificationsSocket.emit('game request response', game);
                        $(this).dialog("close");
                    },
                    'Decline': function() {
                        game.response = false;
                        notificationsSocket.emit('game request response', game);
                        $(this).dialog("close");
                    }
                }
            });

            setTimeout(function() {
                $dialog.dialog("close");
            }, $.game.interval * $.game.iterations)

        }

    });

    notificationsSocket.on('game request response', function (game) {

        var gameUrl = '//' + location.hostname + '/node/play/' + game.name;
        var gameWinOptions = 'width=1000, height=800';

        if (game.opponent.id == user.id && !game.id) {
            if (game.response === true) {

                var gameWin = window.open(gameUrl + '/opponent/' + user.id + '/' + user.username + '/initiator/' + game.initiator.id + '/' + game.initiator.username, 'Play Game', gameWinOptions);

                setTimeout(function() {
                    var parts = $(gameWin.location).attr('href').split('/');
                    game.id = parts[parts.length - 1];
                    notificationsSocket.emit('game request response', game);
                }, $.game.interval)

            }
       }
       else if (game.initiator.id == user.id) {
           if (game.response === true && typeof(game.id) !== 'undefined') {

               $.ajax({
                    type: 'POST',
                    contentType: "application/json",
                    url: '/api/tip',
                    data: JSON.stringify({
                        'pk': game.opponent.id,
                        'value': game.price
                    }),
                    complete: function(response) {
                        var url = gameUrl + '/initiator/' + user.id + '/' + user.username + '/opponent/' + game.opponent.id + '/' + game.opponent.username + '/' + game.id;
                        noGame('If the game window doesn\'t open use <a href="'+url+'">this link</a>');
                        window.open(url, 'Play Game', gameWinOptions);
                    }
                });

           }
           if (game.response !== true) {
               noGame('The opponent refused your game request !');
           }
       }

    });

    notificationsSocket.on('private request', function (request) {

        if (request.performer.id == user.id) {

            var $dialog = $('<div>You have a new private request from <br>user: '+request.user.username+'</div>').uniqueId().dialog({
                resizable: false,
                height: 200,
                modal: true,
                title: 'New Private Request',
                buttons: {
                    'Accept': function() {
                        request.response = true;
                        notificationsSocket.emit('private request response', request);
                        $(this).dialog("close");
                    },
                    'Decline': function() {
                        notificationsSocket.emit('private request response', request);
                        $(this).dialog("close");
                    }
                }
            });

            setTimeout(function() {
                $dialog.dialog("close");
            }, App.dialogTimeout * 1000)

        }

    });

    notificationsSocket.on('private request response', function (request) {

        if (request.user.id == user.id && request.response === true) {
            setTimeout(function() {
                location.href = request.url + '/' + request.hash;
            }, 2000);
        }
        else if (request.performer.id == user.id && request.response === true) {
            location.href = '/admin/broadcast/private/' + request.hash + '/' + request.user.id;
       }

    });

});