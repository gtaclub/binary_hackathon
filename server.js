var express = require('express'),
    app = express(),
    server = require('http').createServer(app),
    io = require('socket.io').listen(server),
    redis = require("redis");

var connection = [];

server.listen(3000);
console.log('Socket.io Connected !');


    io.on('connection', function (socket){

        var redisClient = redis.createClient();
        connection.push(socket);
        redisClient.subscribe('message');

        redisClient.on("message", function(channel, data) {
            var json = JSON.parse(data);
            socket.emit("homepage", json);
        });

        socket.on('disconnect', function(){
            connection.splice(connection.indexOf(socket), 1);
            socket.leave();
            redisClient.quit();
        });

    });

