// adds the visual chat message to the message list
function addChatBox(data) {

    var $box = $('#im_' + data.user.id);

    // create new chat box
    if ($box.length == 0) {

        $box = $('<div/>', {id: 'im_' + data.user.id}).appendTo('body').chatbox({
            id: 'im_' + data.user.id,
            title: data.user.username,
            user: user.username,
            offset: 200,
            messageSent: function(id, user, message) {
                sendMessage(message, this.title);
                $box.chatbox("option", "boxManager").addMsg(user, message);
            },
            boxClosed: function(id) {
                $("#" + id).remove()
            }
        });

    }

    $($box.parent().find('textarea')[0]).focus();

    if (data.message) {
        $box.chatbox("option", "boxManager").addMsg(data.user.username, data.message);
    }

}

// sends a chat message
function sendMessage(message, to) {

    // prevent markup from being injected into the message
    message = cleanInput(message);

    // if there is a non-empty message and a socket connection
    if (message) {

        // tell server to execute 'new message' and send along one parameter
        notificationsSocket.emit('new message', {message: message, to: to});

    }

}

// prevents input from having injected markup
function cleanInput(input) {
    return $('<div/>').text(input).text();
}

$(document).ready(function () {

    if (typeof(io) === 'undefined' || typeof(user) === 'undefined' || typeof(notificationsSocket) === 'undefined') {
        return false;
    }

    $('[data-type="im"]').on('click', function() {
        notificationsSocket.emit('subscribe', $(this).data('username'));
        addChatBox({
            user: {
                id: $(this).data('id'),
                username: $(this).data('username')
            }
        })
    });

    notificationsSocket.emit('add user', user);
    notificationsSocket.emit('subscribe', user.username);

    // whenever the server emits 'new message', update the chat body
    notificationsSocket.on('new message', function (data) {
        addChatBox(data);
    });

});