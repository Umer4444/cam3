var express = require('express')
  , app = express()
  , server = require('http').Server(app)
  , io = require('socket.io')(server)
  , net = require('net');

app.set('port', process.env.PORT || 3004);
app.set('ipaddr', process.env.OPENSHIFT_NODEJS_IP || "127.0.0.1");
app.engine('html', require('ejs').renderFile);
app.set('views', __dirname + '/views');

var home = function(req, res) {

    var view = {
        initiatorId: req.param('initiatorId'),
        initiatorUsername: req.param('initiatorUsername'),
        opponentId: req.param('opponentId'),
        opponentUsername: req.param('opponentUsername'),
        playerUsername: req.param('opponentUsername'),
        playerId: req.param('opponentId')
    }

    if (req.param('id')) {
        view.playerUsername = req.param('initiatorUsername');
        view.playerId = req.param('initiatorId');
    }
    else {
        view.opponentId = req.param('initiatorId');
    }

    res.render('index.html', view);
};

app.get('/initiator/:initiatorId/:initiatorUsername/opponent/:opponentId/:opponentUsername/:id', home);
app.get('/opponent/:opponentId/:opponentUsername/initiator/:initiatorId/:initiatorUsername', home);

app.use(express.static(__dirname + '/static'));

io.on('connection', function(socket) {

   function status_handler(msg) {
      var STATUS = 'STATUS';
      var status_types = ['GAME_ID', 'BOARD', 'MOVED', 'CAPTURED', 'WINNER', 'TURN', 'KING', 'LIST SPECTATE', 'LIST', 'YOU_ARE'];
      for (var i=0; i<status_types.length; i++) {
         type = status_types[i];
         statusPrefix = STATUS + ' ' + type;
         prefixIndex = msg.indexOf(statusPrefix);
         if (prefixIndex >= 0) {
            content = msg.substring(prefixIndex + statusPrefix.length).trim();
            socket.emit(type, content);
         }
      }
   }

   var game = checkers(status_handler, 5000);

   socket.on('commands', function(data) {
      game.send(data, function(cmdResult) {
         socket.emit('commands', cmdResult);
      });
   });

   socket.on('disconnect', function() {
      game.send('QUIT');
   });

});

server.listen(app.get('port'), function() {
  console.log('Checkers port ' + app.get('port'));
});

function checkers(handler, port) {

   var result = {

      'data': '',

      'commands': [],

      'read':  function(data) {
         result.data += data;
         lineTerminator = '\r\n';
         var i = result.data.indexOf(lineTerminator);
         while (i >= 0) {
            line = result.data.substring(0, i);
            result.data = result.data.substring(i+lineTerminator.length)
               i = result.data.indexOf(lineTerminator);
            if (line.indexOf('OK') >= 0 || line.indexOf('ERROR') >= 0) {
               if (result.commands.length > 0) {
                  handler = result.commands.shift();
                  handler(line);
               }
            } else {
               result.status(line);
            }
         }
      },

      'end': function() {
         result.client.end();
      },

      'client':  net.connect({port: port}, function() {
            result.client.on('data', result.read);
            result.client.on('end', result.end);
            }),

      'send': function(line, responseHandler) {
         if (responseHandler) {
            result.commands.push(responseHandler);
         } else {
            result.commands.push(function(response) {});
         }
         result.client.write(line + '\r\n');
      },

      'status': function(line) {},
   };

   if (handler) {
      result.status = handler;
   }

   return result;
}