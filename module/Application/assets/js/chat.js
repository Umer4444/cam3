$.extend(App, {
    chat: {

        init: function() {

            $('#chatOptions').multiselect({
                inheritClass: true,
                buttonText: function(options, select) {
                    return 'Chat options';
                },
                onChange: function(option, checked, select) {

                    var $option = $(option).val();

                    if ($option == 'guests') {
                        $('#people li[data-user=""]')[checked ? 'hide' : 'show']();
                    }

                    if ($option == 'users') {
                        $('#people')[checked ? 'hide' : 'show']();
                    }

                    if ($option == 'images') {
                        $.stylesheet('#msgs li.image', 'display', checked ? 'none' : '')
                    }

                }
            });

            $('button#chat-image').on('click', function(){

                moxman.browse({
                    view: 'thumbs',
                    extensions:'jpg,gif,png',
                    rootpath: '/Home/chat',
                    oninsert: function(result) {
                        $('#msg').val(result.focusedFile.url);
                        $('#send').click();
                    },
                    upload_auto_close: true,
                    filelist_context_menu: 'view edit download remove',
                    filelist_utils_toolbar: 'refresh viewmode sort filter',
                    filelist_main_toolbar: 'upload',
                    leftpanel: false,
                    title: 'Chat Image Manager',
                    multiple: false
                });

            })

        },

        atNamesChatRoom: function (peopleNames) {

            var emojis = $.map(emojione.emojioneList, function(i, value) {return {key: i, name: value, image: emojione.shortnameToImage(value)}});

            $("#msg").atwho({
                at: "@",
                limit: 20,
                data: peopleNames
            })
            .atwho({
                at: ":",
                data: emojis,
                limit: 20,
                displayTpl: "<li>${name} ${image}</li>",
                insertTpl: '${name}'
            });

        },

        processMessage: function (msTime, person, msg) {

            var urlRegex =/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|]\.(jpg|png|gif|jpeg))/ig;
            msg = msg.replace(urlRegex, function(url) {
                return '<a href="' + url + '" class="fancybox"><img src="' + url + '" width="100" class="img-thumbnail"></a>';
            });

            message = "<li>";
            if (!msTime && !person) {
                message += "<span class='text-warning'>" + msg + "</span>";
            }
            else {
                message += "<strong><span class='text-success'>" + timeFormat(msTime) + person.name + "</span></strong>: " + msg;
            }
            message += "</li>";

            $("#msgs").append(emojione.toImage(message)).find('a img').closest('li').addClass('image');

            $('div #main-chat-screen a.fancybox').fancybox({
                margin: 150,
                type: 'image'
            });

        }
    }
});

var myRoomID = null;
var socket = io.connect(location.hostname, {'path': '/node/chat/socket.io', 'force new connection': true});

//WebSpeech API
var final_transcript = '';
var recognizing = false;
var last10messages = []; //to be populated later

if (!('webkitSpeechRecognition' in window)) {
    console.log("webkitSpeechRecognition is not available");
}
else {
    var recognition = new webkitSpeechRecognition();
    recognition.continuous = true;
    recognition.interimResults = true;

    recognition.onstart = function () {
        recognizing = true;
    };

    recognition.onresult = function (event) {
        var interim_transcript = '';
        for (var i = event.resultIndex; i < event.results.length; ++i) {
            if (event.results[i].isFinal) {
                final_transcript += event.results[i][0].transcript;
                $('#msg').addClass("final");
                $('#msg').removeClass("interim");
            } else {
                interim_transcript += event.results[i][0].transcript;
                $("#msg").val(interim_transcript);
                $('#msg').addClass("interim");
                $('#msg').removeClass("final");
            }
        }
        $("#msg").val(final_transcript);
    };
}

function startButton(event) {
    if (recognizing) {
        recognition.stop();
        recognizing = false;
        $("#start_button").prop("value", "Record");
        return;
    }
    final_transcript = '';
    recognition.lang = "en-GB"
    recognition.start();
    $("#start_button").prop("value", "Recording ... Click to stop.");
    $("#msg").val();
}
//end of WebSpeech

/*
 Functions
 */
function toggleNameForm() {
    $("#login-screen").toggle();
}

function toggleChatWindow() {
    $("#main-chat-screen").toggle();
}

// Pad n to specified size by prepending a zeros
function zeroPad(num, size) {
    var s = num + "";
    while (s.length < size)
        s = "0" + s;
    return s;
}

// Format the time specified in ms from 1970 into local HH:MM:SS
function timeFormat(msTime) {
    var d = new Date(msTime);
    return zeroPad(d.getHours(), 2) + ":" +
        zeroPad(d.getMinutes(), 2) + ":" +
        zeroPad(d.getSeconds(), 2) + " ";
}

$(document).ready(function () {

    App.chat.init();

    $("#main-chat-screen form").submit(function (event) {
        event.preventDefault();
    });

    $("#conversation").bind("DOMSubtreeModified", function () {
        $("#conversation").animate({
            scrollTop: $("#conversation")[0].scrollHeight
        });
    });

    $("#errors").hide();
    $("#name").focus();

    if (typeof user === "undefined") {
        return ;
    }

    var name = user.username;
    var device = "desktop";
    if (navigator.userAgent.match(/Android|BlackBerry|iPhone|iPad|iPod|Opera Mini|IEMobile/i)) {
        device = "mobile";
    }
    socket.emit("joinserver", name, device, user);
    /*toggleNameForm();
    toggleChatWindow();*/
    $("#msg").focus();

    $("#name").keypress(function (e) {
        var name = $("#name").val();
        if (name.length < 2) {
            $("#join").attr('disabled', 'disabled');
        } else {
            $("#errors").empty();
            $("#errors").hide();
            $("#join").removeAttr('disabled');
        }
    });

    // main chat screen
    $("#chatForm").submit(function () {
        var msg = $("#msg").val();
        if (msg !== "") {

            // send only to performer if the option is selected
            if ($('#chatOptions option:selected[value="only-performer"]').length) {
                msg = 'w:' + $('#people li:nth-child(2) > span').html() + ':' + msg;
            }

            socket.emit("send", new Date().getTime(), msg);
            $("#msg").val("");
        }
    });

    //'is typing' message
    var typing = false;
    var timeout = undefined;

    function timeoutFunction() {
        typing = false;
        socket.emit("typing", false);
    }

    $("#msg").keypress(function (e) {
        if (e.which !== 13) {
            if (typing === false && myRoomID !== null && $("#msg").is(":focus")) {
                typing = true;
                socket.emit("typing", true);
            } else {
                clearTimeout(timeout);
                timeout = setTimeout(timeoutFunction, 5000);
            }
        }
    });

    socket.on("isTyping", function (data) {
        if (data.isTyping) {
            if ($("#" + data.person + "").length === 0) {
                $("#updates").append("<li id='" + data.person + "'><span class='text-muted'><small><i class='fa fa-keyboard-o'></i> " + data.person + " is typing.</small></li>");
                timeout = setTimeout(timeoutFunction, 5000);
            }
        } else {
            $("#" + data.person + "").remove();
        }
    });

    socket.on("kick", function (data) {

        if (data.userId == user.id) {

            var cookies = $.cookie('bannedRooms') ? $.cookie('bannedRooms') : [];
            cookies.push(data.room);
            $.cookie('bannedRooms', cookies);

            location.href = location.href;

        }

    });


    /*
     $("#msg").keypress(function(){
     if ($("#msg").is(":focus")) {
     if (myRoomID !== null) {
     socket.emit("isTyping");
     }
     } else {
     $("#keyboard").remove();
     }
     });

     socket.on("isTyping", function(data) {
     if (data.typing) {
     if ($("#keyboard").length === 0)
     $("#updates").append("<li id='keyboard'><span class='text-muted'><i class='fa fa-keyboard-o'></i>" + data.person + " is typing.</li>");
     } else {
     socket.emit("clearMessage");
     $("#keyboard").remove();
     }
     console.log(data);
     });
     */

    $("#showCreateRoom").click(function () {
        $("#createRoomForm").toggle();
        $("#createRoomName").focus();
    });

    $("#createRoomBtn").click(function () {
        var roomExists = false;
        var roomName = $("#createRoomName").val();
        socket.emit("check", roomName, function (data) {
            roomExists = data.result;
            if (roomExists) {
                $("#errors").empty();
                $("#errors").show();
                $("#errors").append("Room <i>" + roomName + "</i> already exists");
            } else {
                if (roomName.length > 0) { //also check for roomname
                    socket.emit("createRoom", roomName);
                    $("#errors").empty();
                    $("#errors").hide();
                }
            }
        });
    });

    $("#rooms").on('click', '.joinRoomBtn', function () {
        var roomName = $(this).siblings("span").text();
        var roomID = $(this).attr("id");
        socket.emit("joinRoom", roomID);
    });

    $("#rooms").on('click', '.removeRoomBtn', function () {
        var roomName = $(this).siblings("span").text();
        var roomID = $(this).attr("id");
        socket.emit("removeRoom", roomID);
        $("#createRoom").show();
    });

    $("#leave").click(function () {
        var roomID = myRoomID;
        socket.emit("leaveRoom", roomID);
        $("#createRoom").show();
    });

    $("#people").on('click', '.whisper', function () {
        var name = $(this).siblings("span").text();
        $("#msg").val("w:" + name + ":");
        $("#msg").focus();
    });
    /*
     $("#whisper").change(function() {
     var peopleOnline = [];
     if ($("#whisper").prop('checked')) {
     console.log("checked, going to get the peeps");
     //peopleOnline = ["Tamas", "Steve", "George"];
     socket.emit("getOnlinePeople", function(data) {
     $.each(data.people, function(clientid, obj) {
     console.log(obj.name);
     peopleOnline.push(obj.name);
     });
     console.log("adding typeahead")
     $("#msg").typeahead({
     local: peopleOnline
     }).each(function() {
     if ($(this).hasClass('input-lg'))
     $(this).prev('.tt-hint').addClass('hint-lg');
     });
     });

     console.log(peopleOnline);
     } else {
     console.log('remove typeahead');
     $('#msg').typeahead('destroy');
     }
     });
     // $( "#whisper" ).change(function() {
     //   var peopleOnline = [];
     //   console.log($("#whisper").prop('checked'));
     //   if ($("#whisper").prop('checked')) {
     //     console.log("checked, going to get the peeps");
     //     peopleOnline = ["Tamas", "Steve", "George"];
     //     // socket.emit("getOnlinePeople", function(data) {
     //     //   $.each(data.people, function(clientid, obj) {
     //     //     console.log(obj.name);
     //     //     peopleOnline.push(obj.name);
     //     //   });
     //     // });
     //     //console.log(peopleOnline);
     //   }
     //   $("#msg").typeahead({
     //         local: peopleOnline
     //       }).each(function() {
     //         if ($(this).hasClass('input-lg'))
     //           $(this).prev('.tt-hint').addClass('hint-lg');
     //       });
     // });
     */

//socket-y stuff
    socket.on("exists", function (data) {
        $("#errors").empty();
        $("#errors").show();
        $("#errors").append(data.msg + " Try <strong>" + data.proposedName + "</strong>");
        toggleNameForm();
        //toggleChatWindow();
    });

    socket.on("joined", function () {

        $("#errors").hide();
        if (navigator.geolocation) { //get lat lon of user
            navigator.geolocation.getCurrentPosition(positionSuccess, positionError, {enableHighAccuracy: true});
        } else {
            $("#errors").show();
            $("#errors").append("Your browser is ancient and it doesn't support GeoLocation.");
        }
        function positionError(e) {
            console.log(e);
        }

        function positionSuccess(position) {
            var lat = position.coords.latitude;
            var lon = position.coords.longitude;
            //consult the yahoo service
            $.ajax({
                type: "GET",
                url: "http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20geo.placefinder%20where%20text%3D%22" + lat + "%2C" + lon + "%22%20and%20gflags%3D%22R%22&format=json",
                dataType: "json",
                success: function (data) {
                    socket.emit("countryUpdate", {country: data.query.results.Result.countrycode});
                }
            });
        }
    });

    socket.on("history", function (data) {
        if (data.length !== 0) {
            $("#msgs").append("<li><strong><span class='text-warning'>Last 10 messages:</li>");
            $.each(data, function (data, msg) {
                App.chat.processMessage(false, false, msg);
            });
        } else {
            $("#msgs").append("<li><strong><span class='text-warning'>No past messages in this room.</li>");
        }
    });

    socket.on("update", function (msg) {
        $("#msgs").append("<li>" + msg + "</li>");
    });

    socket.on("update-people", function (data) {

        var peopleNames = [];
        var peopleOnlineHtml = '';

        $.each(data.people, function (a, obj) {
            if (!("country" in obj)) {
                html = "";
            } else {
                html = "<img class=\"flag flag-" + obj.country + "\"/>";
            }

            peopleOnlineHtml += "<li class=\"list-group-item context-menu\" data-user=\""+obj.user.id+"\"><span>" + obj.name + "</span> <i class=\"fa fa-" + obj.device + "\"></i> " + html + " <a href=\"#chat\" class=\"whisper btn btn-xs\">whisper</a></li>";
            peopleNames.push(obj.name);
        });

        var oldPeopleCount = parseInt($('#people li span.badge').html());

        if (peopleNames.length > oldPeopleCount && user.sounds.userIn) {
            $.playSound(user.sounds.userIn);
        }

        if (peopleNames.length < oldPeopleCount && user.sounds.userOut) {
            $.playSound(user.sounds.userOut);
        }

        $('#people').empty().append("<li class=\"list-group-item active\">People online <span class=\"badge\">" + peopleNames.length + "</span></li>" + peopleOnlineHtml);

        App.chat.atNamesChatRoom(peopleNames);
        App.context.applyContextMenu();

        /*var whisper = $("#whisper").prop('checked');
         if (whisper) {
         $("#msg").typeahead({
         local: peopleOnline
         }).each(function() {
         if ($(this).hasClass('input-lg'))
         $(this).prev('.tt-hint').addClass('hint-lg');
         });
         }*/
    });

    socket.on("chat", function (msTime, person, msg) {

        App.chat.processMessage(msTime, person, msg);

        //clear typing field
        $("#" + person.name + "").remove();
        clearTimeout(timeout);
        timeout = setTimeout(timeoutFunction, 0);
    });

    socket.on("whisper", function (msTime, person, msg) {
        if (person.name === "You") {
            s = "whisper"
        } else {
            s = "whispers"
        }
        $("#msgs").append("<li><strong><span class='text-muted'>" + timeFormat(msTime) + person.name + "</span></strong> " + s + ": " + msg + "</li>");
    });

    socket.on("roomList", function (data) {
        $("#rooms").text("");
        $("#rooms").append("<li class=\"list-group-item active\">List of rooms <span class=\"badge\">" + data.count + "</span></li>");
        if (!jQuery.isEmptyObject(data.rooms)) {
            $.each(data.rooms, function (id, room) {
                if (room.name == 'Lobby') {
                    return false;
                }
                var html = "<button id=" + id + " class='joinRoomBtn btn btn-default btn-xs' >Join</button>" + " " + "<button id=" + id + " class='removeRoomBtn btn btn-default btn-xs'>Remove</button>";
                $('#rooms').append("<li id=" + id + " class=\"list-group-item\"><span>" + room.name + "</span> " + html + "</li>");
            });
        } else {
            $("#rooms").append("<li class=\"list-group-item\">There are no rooms yet.</li>");
        }
    });

    socket.on("sendRoomID", function (data) {
        myRoomID = data.id;
    });

    socket.on("callback", function (callback) {

        var fn = window[callback];

        if(typeof fn === 'function') {
            setTimeout(function(){ fn(); }, 1000);
        }

    });

    socket.on("disconnect", function () {

        $("#msgs").append("<li><strong><span class='text-warning'>The server is not available</span></strong></li>");
        $("#msg").attr("disabled", "disabled");
        $("#send").attr("disabled", "disabled");
    });



});
