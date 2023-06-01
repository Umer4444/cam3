var DB = null;

/**
 * Validate session data for "Game" page
 * Returns valid data on success or null on failure
 */
var validateGame = function(req) {

  // These must exist
  if (!req.session.gameID)      { return null; }
  if (!req.session.playerColor) { return null; }
  if (!req.session.playerName)  { return null; }
  if (!req.session.playerId)    { return null; }
  if (!req.session.opponentId)  { return null; }
  if (!req.session.initiatorId) { return null; }
  if (!req.params.id)           { return null; }

  // These must match
  if (req.session.gameID !== req.params.id) { return null; }

  return {
    gameID      : req.session.gameID,
    playerColor : req.session.playerColor,
    playerId    : req.session.playerId,
    opponentId  : req.session.opponentId,
    initiatorId : req.session.initiatorId,
    playerName  : req.session.playerName
  };
};

/**
 * Validate "Start Game" form input
 * Returns valid data on success or null on failure
 */
var validateStartGame = function(req) {

  // These must exist
  if (!req.body['player-color']) { return null; }
  if (!req.body['player-id']) { return null; }

  // Player Color must be 'white' or 'black'
  if (req.body['player-color'] !== 'white' && req.body['player-color'] !== 'black') { return null; }

  // If Player Name consists only of whitespace, set as 'Player 1'
  if (/^\s*$/.test(req.body['player-name'])) { req.body['player-name'] = 'Player 1'; }

  return {
    initiatorId : req.body['initiator-id'],
    opponentId  : req.body['opponent-id'],
    playerId    : req.body['player-id'],
    playerColor : req.body['player-color'],
    playerName  : req.body['player-name']
  };
};

/**
 * Validate "Join Game" form input
 * Returns valid data on success or null on failure
 */
var validateJoinGame = function(req) {

  // These must exist
  if (!req.body['game-id']) { return null; }
  if (!req.body['player-id']) { return null; }

  // If Game ID consists of only whitespace, return null
  if (/^\s*$/.test(req.body['game-id'])) { return null; }

  // If Player Name consists only of whitespace, set as 'Player 2'
  if (/^\s*$/.test(req.body['player-name'])) { req.body['player-name'] = 'Player 2'; }

  return {
    gameID      : req.body['game-id'],
    playerName  : req.body['player-name'],
    initiatorId : req.body['initiator-id'],
    opponentId  : req.body['opponent-id'],
    playerId    : req.body['player-id']
  };
};

/**
 * Render "Home" Page
 */
var home = function(req, res) {

    var view = {
        initiatorId: req.param('initiatorId'),
        initiatorUsername: req.param('initiatorUsername'),
        opponentId: req.param('opponentId'),
        opponentUsername: req.param('opponentUsername'),
        playerName: req.param('opponentUsername'),
        playerId: req.param('opponentId'),
        javascript: '$("#startForm").submit()'
    }

    if (req.param('id')) {
        view.gameID = req.param('id');
        view.javascript = '$("#joinForm").submit()';
        view.playerName = req.param('initiatorUsername');
        view.playerId = req.param('initiatorId');
    }

    req.session.playerId = view.playerId;
    req.session.initiatorId = req.param('initiatorId');
    req.session.opponentId = req.param('opponentId');

    // Welcome
    res.render('home', view);
};

var empty = function(req, res) {
    res.render('empty');
};

/**
 * Render "Game" Page (or redirect to home page if session is invalid)
 */
var game = function(req, res) {

  // Validate session data
  var validData = validateGame(req);
  if (!validData) { res.redirect('/'); return; }

  // Render the game page
  res.render('game', validData);
};

/**
 * Process "Start Game" form submission
 * Redirects to game page on success or home page on failure
 */
var startGame = function(req, res) {

  // Create a new session
  req.session.regenerate(function(err) {
    if (err) { res.redirect('/'); return; }

    // Validate form input
    var validData = validateStartGame(req);
    if (!validData) { res.redirect('/'); return; }

    // Create new game
    var gameID = DB.add(validData);

    // Save data to session
    req.session.gameID      = gameID;
    req.session.playerId    = validData.playerId;
    req.session.playerColor = validData.playerColor;
    req.session.playerName  = validData.playerName;
    req.session.opponentId  = validData.opponentId;
    req.session.initiatorId  = validData.initiatorId;

    // Redirect to game page
    res.redirect('/node/play/chess/game/'+req.session.initiatorId+'/'+req.session.opponentId+'/'+gameID);
  });
};

/**
 * Process "Join Game" form submission
 * Redirects to game page on success or home page on failure
 */
var joinGame = function(req, res) {

  // Create a new session
  req.session.regenerate(function(err) {
    if (err) { res.redirect('/'); return; }

    // Validate form input
    var validData = validateJoinGame(req);
    if (!validData) { res.redirect('/'); return; }

    // Find specified game
    var game = DB.find(validData.gameID);
    if (!game) { res.redirect('/'); return;}

    // Determine which player (color) to join as
    var joinColor = (game.players[0].joined) ? game.players[1].color : game.players[0].color;

    // Save data to session
    req.session.gameID      = validData.gameID;
    req.session.playerColor = joinColor;
    req.session.playerName  = validData.playerName;
    req.session.playerId    = validData.playerId;
    req.session.opponentId  = validData.opponentId;
    req.session.initiatorId = validData.initiatorId;

    // Redirect to game page
    res.redirect('/node/play/chess/game/'+req.session.initiatorId+'/'+req.session.opponentId+'/'+validData.gameID);
  });
};

/**
 * Redirect non-existent routes to the home page
 */
var invalid = function(req, res) {
  // Go home HTTP request, you're drunk
  res.redirect('/');
};

/**
 * Attach route handlers to the app
 */
exports.attach = function(app, db) {
  DB = db;

  app.get('/', empty);
  app.get('/initiator/:initiatorId/:initiatorUsername/opponent/:opponentId/:opponentUsername/:id', home);
  app.get('/opponent/:opponentId/:opponentUsername/initiator/:initiatorId/:initiatorUsername', home);
  app.get('/game/:initiatorId/:opponentId/:id', game);
  app.post('/start', startGame);
  app.post('/join', joinGame);
  app.all('*', invalid);
};