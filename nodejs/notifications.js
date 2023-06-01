var app = require('express')();
var server = require('http').createServer(app);
var io = require('socket.io')(server);

// Settings
app.set('port', process.env.PORT || 3001);
app.set('ipaddr', "0.0.0.0");

server.listen(app.get('port'), function () {
    console.log('Server listening at port %d', app.get('port'));
});

io.on('connection', function(socket) {

    socket.on('subscribe', function(room) {
        socket.join(room);
    });

    socket.on('unsubscribe', function(room) {
        delete socket.room;
        socket.leave(room);
    });

    // when the client emits 'new message', this listens and executes
    socket.on('new message', function(data) {

        // we tell the client to execute 'new message'
        socket.to(data.to).emit('new message', {
            user: socket.user,
            message: data.message
        });
        console.log(socket.user, data, data.to);

    });

    // when the client emits 'new message', this listens and executes
    socket.on('notification', function(data) {
        socket.broadcast.emit('notification', data);
    });

    // when the client emits 'add user', this listens and executes
    socket.on('add user', function(user) {
        socket.user = user;
        console.log(socket.user);
    });

    // send new game notification
    socket.on('game request', function(data) { socket.broadcast.emit('game request', data); });
    socket.on('game request response', function(data) { io.sockets.emit('game request response', data); });

    // private notifications
    socket.on('private request', function(data) { socket.broadcast.emit('private request', data); });
    socket.on('private request response', function(data) {
        data.hash = Math.floor((Math.random() * 1000000000000) + 1);
        io.sockets.emit('private request response', data);
    });

});