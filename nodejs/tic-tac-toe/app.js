var express = require("express"),
	http = require("http"),
	fs = require("fs"),
	app, server;

app = express();

server = http.createServer(app);

app.configure(function() {
	app.set('port', process.env.PORT || 3003);
    app.set('ipaddr', process.env.OPENSHIFT_NODEJS_IP || "127.0.0.1");
	app.set("views", __dirname + "/views");
	app.engine('html', require('ejs').renderFile);
	app.use(express.bodyParser());
    require("./controller.js")(app, server);
	app.use(app.router);
	app.use(express.static(__dirname + "/public"));
});

app.configure("development", function() {
	app.use(express.errorHandler());
});

server.listen(app.get("port"), function() {
	console.log("Express server listening on port " + app.get("port"));
});