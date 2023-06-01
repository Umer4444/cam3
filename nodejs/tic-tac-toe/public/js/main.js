var $path = "/node/play/tic-tac-toe/js/";

require.config({
	paths: {
		"socketio": $path + "../socket.io/socket.io",
		"underscore": $path + "lib/underscore-1.4.4",
		"backbone": $path + "lib/backbone-0.9.10",
		"jquery": $path + "lib/jquery-1.9.1",
		"text": $path + "lib/text-2.0.5",
		"space-model": $path + "models/space-model",
		"space-view": $path + "views/space-view",
		"status-model": $path + "models/status-model",
		"status-view": $path + "views/status-view",
		"win-model": $path + "models/win-model",
		"win-view": $path + "views/win-view",
		"board-model": $path + "models/board-model",
		"board-view": $path + "views/board-view",
		"app": $path + "app"
	},
	shim: {
		"underscore": {
			exports: "_"
		},
		"backbone": {
			deps: ["underscore", "jquery"],
			exports: "Backbone"
		}
	}
});

// require the app, which starts up our game
require(["app"]);