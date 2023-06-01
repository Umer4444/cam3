var path         = require('path')
  , http         = require('http')
  , express      = require('express')
  , socket       = require('socket.io')
  , httpRoutes   = require('./routes/http')
  , socketRoutes = require('./routes/socket')
  , GameStore    = require('./lib/GameStore');

var app    = express()
  , server = http.createServer(app)
  , io     = socket.listen(server);

var DB = new GameStore();

var cookieParser = express.cookieParser('Cookie')
  , sessionStore = new express.session.MemoryStore();

// Settings
app.set('port', process.env.PORT || 3002);
app.set('ipaddr', process.env.OPENSHIFT_NODEJS_IP || "127.0.0.1");
app.set('views', __dirname + '/views');
app.set('view engine', 'jade');

// Middleware
app.use(express.logger('dev'));
app.use(express.bodyParser());
app.use(cookieParser);
app.use(express.session({ store: sessionStore }));
app.use(express.static(path.join(__dirname, 'public')));
app.use(app.router);
if ('development' == app.get('env')) {
  app.use(express.errorHandler());
}

/*
 * Only allow socket connections that come from clients with an established session.
 * This requires re-purposing Express's cookieParser middleware in order to expose
 * the session info to Socket.IO
 */
io.use(function (socket, next) {
  var handshakeData = socket.request;
  cookieParser(handshakeData, {}, function(err) {
    if (err) next(new Error('not authorized'));
    sessionStore.load(handshakeData.signedCookies['connect.sid'], function(err, session) {
      if (err) next(new Error('not authorized'));
      socket.handshake.session = session;
      next((socket.handshake.session) ? '' : new Error('not authorized'));
    });
  });
});

// Attach routes
httpRoutes.attach(app, DB);
socketRoutes.attach(io, DB);

// And away we go
server.listen(app.get('port'), function(){
  console.log('Socket.IO Chess is listening on port ' + app.get('port'));
});