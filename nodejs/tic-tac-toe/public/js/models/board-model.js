/* jshint browser:true */
/* global define:true */
define([
	"backbone"
],
function (Backbone) {
	"use strict";

	return Backbone.Model.extend({

		defaults: {
			playerNumber: 1,
			playerName: "X",
			opponentName: "X",
			opponentNumber: 2,
			playerXO: "X"
		}
	});
});