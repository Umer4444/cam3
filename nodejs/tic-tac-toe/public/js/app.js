/*jshint browser:true */
/*global define:true */
define([
	"jquery",
	"board-model",
	"board-view"
],
function($, BoardModel, BoardView) {
	"use-strict";

	$(function() {

		// render the game board
		var boardModel = new BoardModel();

        if (players.game.length > 2) {
            boardModel.set("playerName", players.initiatorUsername);
            boardModel.set("initiatorId", players.initiatorId);
            boardModel.set("opponentId", players.opponentId);
        }
        else {
            boardModel.set("playerName", players.opponentUsername);
            boardModel.set("initiatorId", players.opponentId);
            boardModel.set("opponentId", players.initiatorId);
        }

		var boardView = new BoardView({model: boardModel});
		boardView.render();
	});
});