var App = {

    request : {},

    model : {

        status : {

            // data holds variables for use in the class:
            data : {
                id : '',
                status : '',
                status_profile : ''
            },

            // Init binds event listeners and sets up timers:
            init : function(model_id){

                App.model.status.data.id = model_id;

                App.working = false;

                // Submitting a new status:

                $('#status').blur(function(){

                    if(App.working) return false;
                    App.working = true;

                    App.model.status.data.status = $('#status').val();

                    App.request.submitstatus_status =  App.model.status.data.status
                    App.request.submitStatus = true;

                    App.working = false;

                    return false;
                });

                // Submitting a new status for profile:

                $('#status_profile').blur(function(){

                    if(App.working) return false;
                    App.working = true;

                    App.model.status.data.status_profile = $('#status_profile').val();

                    App.request.submitstatus_status_profile =  App.model.status.data.status_profile
                    App.request.submitStatusProfile = true;

                    App.working = false;

                    return false;
                });
            }

        },

        private : {

            // data holds variables for use in the class:
            data : {
                id : '',
                user_id : '',
                status : false
            },

            // Init binds event listeners and sets up timers:
            init : function(model_id){

                App.model.private.data.id = model_id;
                App.chat.data.get_requests = true;

                App.working = false;

                App.model.private.getPrivateRequestTimeoutFunction();

            },

            getPrivateRequestTimeoutFunction: function(){
                if(App.chat.data.get_requests) App.model.private.getPrivateRequest(App.model.private.getPrivateRequestTimeoutFunction);
            },

            getPrivateRequest: function(callback){

                if(App.working) return false;
                App.working = true;

                App.request.returnRequest = true;
                App.request.returnrequest_request = 'private';
                App.request.returnrequest_type = 'model';

                App.working = false;

                nextRequest = 4000;
                setTimeout(callback, nextRequest);

                return false;
            },

            acceptPrivateRequest: function(){

                if(App.working) return false;
                App.working = true;

                App.request.acceptrequest_user_id = App.model.private.data.user_id;
                App.request.acceptrequest_request = 'private';
                App.request.acceptRequest = true;



                App.working = false;

                return false;
            },

            denyPrivateRequest: function(){

                if(App.working) return false;
                App.working = true;

                App.request.denyrequest_user_id = App.model.private.data.user_id;
                App.request.denyrequest_request = 'private';
                App.request.denyRequest = true;

                App.working = false;

                return false;
            }

        },

        vip : {

            // data holds variables for use in the class:
            data : {
                id : '',
                user_id : '',
                status : false
            },

            // Init binds event listeners and sets up timers:
            init : function(model_id){

                App.model.vip.data.id = model_id;
                App.chat.data.get_requests = true;

                App.working = false;

                App.model.vip.getVipRequestTimeoutFunction();

            },

            getVipRequestTimeoutFunction: function(){
                if(App.chat.data.get_requests) App.model.vip.getVipRequest(App.model.vip.getVipRequestTimeoutFunction);
            },

            getVipRequest: function(callback){

                if(App.working) return false;
                App.working = true;

                App.request.returnRequest = true;
                App.request.returnrequest_request = 'vip';
                App.request.returnrequest_type = 'model';

                App.working = false;

                nextRequest = 5500;
                setTimeout(callback, nextRequest);

                return false;
            },

            acceptVipRequest: function(){

                if(App.working) return false;
                App.working = true;

                App.request.acceptrequest_user_id = App.model.vip.data.user_id;
                App.request.acceptrequest_request = 'vip';
                App.request.acceptRequest = true;



                App.working = false;

                return false;
            },

            denyVipRequest: function(){

                if(App.working) return false;
                App.working = true;

                App.request.denyrequest_user_id = App.model.vip.data.user_id;
                App.request.denyrequest_request = 'vip';
                App.request.denyRequest = true;

                App.working = false;

                return false;
            }

        },

        group : {

            // data holds variables for use in the class:
            data : {
                id : '',
                user_id : '',
                status : false
            },

            // Init binds event listeners and sets up timers:
            init : function(model_id){

                App.model.group.data.id = model_id;
                App.chat.data.get_requests = true;

                App.working = false;

                App.model.group.getGroupRequestTimeoutFunction();

            },

            getGroupRequestTimeoutFunction: function(){
                if(App.chat.data.get_requests)
                    App.model.group.getGroupRequest(App.model.group.getGroupRequestTimeoutFunction);
            },

            getGroupRequest: function(callback){

                if(App.working) return false;
                App.working = true;

                App.request.returnRequest = true;
                App.request.returnrequest_request = 'group';
                App.request.returnrequest_type = 'model';

                App.working = false;

                nextRequest = 6700;
                setTimeout(callback, nextRequest);

                return false;
            },

            acceptGroupRequest: function(){

                if(App.working) return false;
                App.working = true;

                App.request.acceptrequest_user_id = App.model.group.data.user_id;
                App.request.acceptrequest_request = 'group';
                App.request.acceptRequest = true;



                App.working = false;

                return false;
            },

            denyGroupRequest: function(){

                if(App.working) return false;
                App.working = true;

                App.request.denyrequest_user_id = App.model.group.data.user_id;
                App.request.denyrequest_request = 'group';
                App.request.denyRequest = true;

                App.working = false;

                return false;
            }

        },

        setGalleryCover: function(p_id){
            $.ajax({
              type: "POST",
              cache: false,
              url: "/process",
              data: {
                p_id : p_id,
                action : 'setGalleryCover'
              },
              dataType : "json",
              success: function(data){
                $('#c_'+p_id).removeAttr('onclick');
                $('#c_'+p_id).text('Cover');
              }
            });

        },

        deletePhoto: function(p_id){

            if (!confirm("Are you sure you want to delete this photo?")) return false;

            $.ajax({
              type: "POST",
              cache: false,
              url: "/process",
              data: {
                p_id : p_id,
                action : 'deletePhoto'
              },
              dataType : "json",
              success: function(data){
                  if(data == 1){
                      $('#p_'+p_id).remove();
                  }
              }
            });
        } ,

        addPhotoCaption: function(p_id) {
            caption = $("#pc_"+p_id).data("caption");
            $("body").append('<div class="captionAdd"></div>');
            $(".captionAdd").append('<input type="text" value="'+caption+'" id="caption_text">');
            $(".captionAdd").dialog({
                modal: true,
                resizable: false,
                title: "Edit caption",
                buttons: [
                    {
                        text: "Save",
                        click: function(){
                            $.ajax({
                              type: "POST",
                              cache: false,
                              url: "/process",
                              data: {
                                action  : 'photoCaption',
                                pid     : p_id,
                                caption : $("#caption_text").val()
                              },
                              dataType : "json",
                              success: function(response){
                                $(".captionAdd p").remove();

                                if(response.status = "success"){
                                    $("#caption_text").before('<p style="color:green">Caption saved!</p>');
                                    $("#img_"+p_id).attr("title", caption);
                                } else {
                                    $("#caption_text").before('<p style="color:red">Fail!</p>');
                                }
                              }
                            });
                        }

                    },
                    {
                        text: "Cancel",
                        click: function(){
                            $(".captionAdd").dialog("destroy");
                            $(".captionAdd").empty();
                            $(".captionAdd").remove();
                        }
                    }
                ],
                close: function(event, ui){
                    $(".captionAdd").dialog("destroy")
                    $(".captionAdd").empty();
                    $(".captionAdd").remove();
                }
            });
            $(".captionAdd").dialog("open");
        }

    },

    user : {

        follow:{
            add: function(id_model, type){
                $.ajax({
                  type: "POST",
                  cache: false,
                  url: "/process",
                  data: {
                    id_model : id_model,
                    action   : 'addFollow',
                    type     : type
                  },
                  dataType : "json",
                  success: function(data){
                    //$('#path_follow'+id_model+' input').prop('checked', true);
                    $('#path_follow'+id_model+' a').text('Unfollow');
                    $('#path_follow'+id_model+' a').attr('onclick','App.user.follow.remove('+id_model+')');
                    if(type) {
                        $('#path_follow'+id_model+' #'+type+id_model).attr('onclick','App.user.follow.remove('+id_model+',"'+type+'")');
                        $('#path_follow'+id_model+' #'+type+id_model).prop('checked', true);
                    }
                  }
                });
            },
            remove: function(id_model, type){
                $.ajax({
                  type: "POST",
                  cache: false,
                  url: "/process",
                  data: {
                    id_model : id_model,
                    action : 'removeFollow',
                    type:   type
                  },
                  dataType : "json",
                  success: function(data){
                    //$('#path_follow'+id_model).html('<a href="javascript:;" class="button-round-small blue right" onclick="App.user.follow.add('+id_model+')">Follow</a>');
                    //$('#path_follow'+id_model+' .follow_options').hide();
                    //$('#path_follow'+id_model+' input').prop('checked', false);
                    if(!type)  {
                        $('#path_follow'+id_model+' a').text('Follow');
                        $('#path_follow'+id_model+' input').prop('checked', false);
                    }
                    //$('#more_follow_'+id_model).remove();
                    $('#path_follow'+id_model+' a').attr('onclick','App.user.follow.add('+id_model+')');
                    if(type) {
                        $('#path_follow'+id_model+' #'+type+id_model).attr('onclick','App.user.follow.add('+id_model+',"'+type+'")');
                        $('#path_follow'+id_model+' #'+type+id_model).prop('checked', false);
                    }
                  }
                });
            }

        },

        my_favorites:{

            remove_my_favorites: function(model_id1){
                $.ajax({
                  type: "POST",
                  cache: false,
                  url: "/process",
                  data: {
                    model_id : model_id1,
                    action : 'removeFavorite'
                  },
                  dataType : "json",
                  success: function(data){
                    window.location.reload();
                  }
                });
            }

        },

        favorites : {

            // data holds variables for use in the class:
            data : {
                id : '',
                user_id : '', // eject
                status : false // eject
            },

            // Init binds event listeners and sets up timers:
            init : function(model_id, user_id){

                App.user.favorites.data.id = model_id;
                App.user.favorites.data.user_id = user_id;
                if($('#favorites').attr('class') == 'add') App.user.favorites.data.status = false;
                else App.user.favorites.data.status = true;

                App.working = false;

                // Add/remove favorite model - chat page
                $('#favorites').click(function(){
                    if($('#favorites').attr('class') == 'add') App.user.favorites.data.status = false;
                    else App.user.favorites.data.status = true;

                    if(App.working) return false;
                    App.working = true;

                    params = {
                            model_id      : App.user.favorites.data.id,
                            user_id       : App.user.favorites.data.user_id
                        };

                    // Using our tzPOST wrapper method to send the status
                    // via a POST AJAX request:
                    if(!App.user.favorites.data.status){

                        //save changes to db
                        App.tzPOST('addFavorite',params, function(r){
                            if(r[0].count == 1){
                                //change image src + change label content to remove favorite
                                $('#favorites_label').attr('class','remove');
                                $('#favorites_label').text('Remove from favorites!');
                                $('#favorites').attr('class','remove');
                                $('#favorites').attr('alt', 'Remove from favorites!');
                                $('#favorites').attr('src', '/images/remove_favorite.png');

                                /* modificare obiect models pentru context menu */
                                if(typeof models != "undefined"){
                                    eval("models.model_"+App.user.favorites.data.id+".favorite = 1;") ;
                                    $.contextMenu( 'destroy', $("#model_"+App.user.favorites.data.id) );
                                    generateUserMenu($("#model_"+App.user.favorites.data.id));
                                }
                            }
                            App.working = false;

                        });
                    }else{
                        //save changes to db
                        App.tzPOST('removeFavorite',params, function(r){
                            if(r[0].count == 0){
                                //change image src + change label content to add favorite
                                $('#favorites_label').attr('class','add');
                                $('#favorites_label').text('Add to favorites!');
                                $('#favorites').attr('class','add');
                                $('#favorites').attr('alt', 'Add to favorites!');
                                $('#favorites').attr('src', '/images/add_favorite.png');

                                 /* modificare obiect models pentru context menu */
                                if(typeof models != "undefined"){
                                    eval("models.model_"+App.user.favorites.data.id+".favorite = 0;") ;
                                    $.contextMenu( 'destroy', $("#model_"+App.user.favorites.data.id) );
                                    generateUserMenu($("#model_"+App.user.favorites.data.id));
                                }
                            }
                            App.working = false;

                        });
                    }
                    return false;
                });

                $('#favorites').mouseover(function(){
                    //show label
                    $('#favorites_label').css('visibility', 'visible');
                });
                $('#favorites').mouseout(function(){
                   //hide label
                   $('#favorites_label').css('visibility', 'hidden');
                });


                // Add/remove favorite model -  model profile page
                $('#favorites_btn').click(function(){
                    if($('#favorites_btn').attr('status') == 'add') App.user.favorites.data.status = false;
                    else App.user.favorites.data.status = true;

                    if(App.working) return false;
                    App.working = true;

                    params = {
                            model_id      : App.user.favorites.data.id,
                            user_id       : App.user.favorites.data.user_id
                        };

                    // Using our tzPOST wrapper method to send the status
                    // via a POST AJAX request:
                    if(!App.user.favorites.data.status){
                        //save changes to db
                        App.tzPOST('addFavorite',params, function(r){
                            if(r[0].count == 1){
                                $('#favorites_btn').attr('status','remove');
                                $('#favorites_btn').attr('alt', 'Remove from favorites!');
                                $('#favorites_btn').text('Remove Favorite');
                            }
                            App.working = false;

                        });
                    }else{
                        //save changes to db
                        App.tzPOST('removeFavorite',params, function(r){
                            if(r[0].count == 0){
                                //change image src + change label content to add favorite
                                $('#favorites_btn').attr('status','add');
                                $('#favorites_btn').attr('alt', 'Add to favorites!');
                                $('#favorites_btn').text('Add Favorite');
                            }
                            App.working = false;

                        });
                    }
                    return false;
                });
            }

        },

        status : {

            // data holds variables for use in the class:
            data : {
                id : '',
                status : ''
            },

            // Init binds event listeners and sets up timers:
            init : function(model_id){

                App.user.status.data.id = model_id;

                App.working = false;

                App.user.status.data.status = $('#status').text();

                App.user.status.getStatusTimeoutFunction();

            },

            getStatusTimeoutFunction : function(){
                    App.user.status.getStatus(App.user.status.getStatusTimeoutFunction);
            },

            getStatus: function(callback){

                if(App.working) return false;
                App.working = true;

                App.request.getStatus = true;

                App.working = false;

                nextRequest = 10000;
                setTimeout(callback, nextRequest);

                return false;
            }

        },

        private : {

            // data holds variables for use in the class:
            data : {
                id : '',
                user_id : '',
                status : false,
                requested : false,
                counter : 30
            },

            // Init binds event listeners and sets up timers:
            init : function(model_id, user_id){

                App.user.private.data.id = model_id;
                App.user.private.data.user_id = user_id;
                App.user.private.data.requested = false;
                App.user.private.data.counter = 30;

                App.working = false;

                App.user.private.getPrivateRequest();

                // Submitting a new private request:

                $('#request').click(function(){
                    if(!alertFee($(this)))
                        return false;
                    //open modal to request access to camera
                    $("#share_cam").dialog();
                    $("#share_cam").dialog({modal: true});


                    $('#continue_camera').click(function(){
                        $("#share_cam").dialog('close');

                        if(App.working) return false;
                            App.working = true;

                        App.request.submitrequest_memberCamera = 1;

                        App.request.submitRequest = true;
                        App.request.submitrequest_user_id = App.user.private.data.user_id;
                        App.request.submitrequest_request = 'private';

                        App.working = false;

                        return false;
                    });

                   $('#continue_no_camera').click(function(){
                        $("#share_cam").dialog('close');

                        if(App.working) return false;
                            App.working = true;

                        App.request.submitrequest_memberCamera = 0;

                        App.request.submitRequest = true;
                        App.request.submitrequest_user_id = App.user.private.data.user_id;
                        App.request.submitrequest_request = 'private';

                        App.working = false;

                        return false;
                    });


                    return false;
                });


            },

            getPrivateRequestTimeoutFunction: function(){
                if(App.user.private.data.requested) App.user.private.getPrivateRequest(App.user.private.getPrivateRequestTimeoutFunction);
            },

            getPrivateRequest: function(callback){

                if(App.working) return false;
                App.working = true;

                App.request.returnRequest = true;
                App.request.returnrequest_user_id = App.user.private.data.user_id;
                App.request.returnrequest_request = 'private';
                App.request.returnrequest_type = 'user';

                if(callback) App.user.callback = true;
                else App.user.callback = false;

                App.working = false;

                nextRequest = 1000;
                if(callback) setTimeout(callback, nextRequest);

                return false;
            }

        },


        vip : {

            // data holds variables for use in the class:
            data : {
                id : '',
                user_id : '',
                status : false,
                requested : false,
                counter : 30
            },

            // Init binds event listeners and sets up timers:
            init : function(model_id, user_id){

                App.user.vip.data.id = model_id;
                App.user.vip.data.user_id = user_id;
                App.user.vip.data.requested = false;
                App.user.vip.data.counter = 30;

                App.working = false;

                App.user.vip.getVipRequest();

                // Submitting a new private request:

/*                $('#request_vip').click(function(){

                    if(App.working) return false;
                        App.working = true;

                    App.request.submitRequest = true;
                    App.request.submitrequest_user_id = App.user.vip.data.user_id;
                    App.request.submitrequest_request = 'vip';

                    App.working = false;

                    return false;
                });*/

                $('#request_vip').click(function(){
                    if(!alertFee($(this)))
                        return false;
                    //open modal to request access to camera
                    $("#share_cam").dialog('open');
                    $("#share_cam").dialog({modal: true});


                    $('#continue_camera').click(function(){
                        $("#share_cam").dialog('close');

                        if(App.working) return false;
                            App.working = true;

                        App.request.submitrequest_memberCamera = 1;

                        App.request.submitRequest = true;
                        App.request.submitrequest_user_id = App.user.vip.data.user_id;
                        App.request.submitrequest_request = 'vip';

                        App.working = false;

                        return false;
                    });

                   $('#continue_no_camera').click(function(){
                        $("#share_cam").dialog('close');

                        if(App.working) return false;
                            App.working = true;

                        App.request.submitrequest_memberCamera = 0;

                        App.request.submitRequest = true;
                        App.request.submitrequest_user_id = App.user.vip.data.user_id;
                        App.request.submitrequest_request = 'vip';

                        App.working = false;

                        return false;
                    });


                    return false;
                });


            },

            getVipRequestTimeoutFunction: function(){
                if(App.user.vip.data.requested) App.user.vip.getVipRequest(App.user.vip.getVipRequestTimeoutFunction);
            },

            getVipRequest: function(callback){

                if(App.working) return false;
                App.working = true;

                App.request.returnRequest = true;
                App.request.returnrequest_user_id = App.user.vip.data.user_id;
                App.request.returnrequest_request = 'vip';
                App.request.returnrequest_type = 'user';

                if(callback) App.user.callback = true;
                else App.user.callback = false;

                App.working = false;

                nextRequest = 1000;
                if(callback) setTimeout(callback, nextRequest);

                return false;
            }

        },


        group : {

            // data holds variables for use in the class:
            data : {
                id : '',
                user_id : '',
                status : false,
                requested : false,
                counter : 60
            },

            // Init binds event listeners and sets up timers:
            init : function(model_id, user_id){

                App.user.group.data.id = model_id;
                App.user.group.data.user_id = user_id;
                App.user.group.data.requested = false;
                App.user.group.data.counter = 60;

                App.working = false;

                App.user.group.getGroupRequest();

                // Submitting a new private request:

                $('#request_group').click(function(){
                    if(!alertFee($(this)))
                        return false;
                    if(App.working) return false;
                        App.working = true;

                    App.request.submitRequest = true;
                    App.request.submitrequest_user_id = App.user.group.data.user_id;
                    App.request.submitrequest_request = 'group';

                    App.working = false;

                    return false;
                });


            },

            getGroupRequestTimeoutFunction: function(){
                if(App.user.group.data.requested) App.user.group.getGroupRequest(App.user.group.getGroupRequestTimeoutFunction);
            },

            getGroupRequest: function(callback){

                if(App.working) return false;
                App.working = true;

                App.request.returnRequest = true;
                App.request.returnrequest_user_id = App.user.group.data.user_id;
                App.request.returnrequest_request = 'group';
                App.request.returnrequest_type = 'user';

                if(callback) App.user.callback = true;
                else App.user.callback = false;

                App.working = false;

                nextRequest = 1000;
                if(callback) setTimeout(callback, nextRequest);

                return false;
            }

        },


        spy : {

            // data holds variables for use in the class:
            data : {
                id : '',
                user_id : '',
                status : false,
                requested : false
            },

            // Init binds event listeners and sets up timers:
            init : function(model_id, user_id){

                App.user.spy.data.id = model_id;
                App.user.spy.data.user_id = user_id;
                App.user.spy.data.requested = false;

                App.working = false;

                // Submitting a new private request:

                $('#request_spy').click(function(){

                    if(App.working) return false;
                        App.working = true;

                    App.chat.data.stop = false;
                    App.request.submitRequest = true;
                    App.request.submitrequest_user_id = App.user.spy.data.user_id;
                    App.request.submitrequest_request = 'spy';

                    App.chat.init(App.user.spy.data.id, 'spy', null);

                    App.working = false;

                    return false;
                });


            },

            getSpyRequestTimeoutFunction: function(){
                if(App.user.spy.data.requested) App.user.spy.getSpyRequest(App.user.spy.getSpyRequestTimeoutFunction);
            },

            getSpyRequest: function(callback){

                if(App.working) return false;
                App.working = true;

                App.request.returnRequest = true;
                App.request.returnrequest_user_id = App.user.spy.data.user_id;
                App.request.returnrequest_request = 'spy';
                App.request.returnrequest_type = 'user';

                if(callback) App.user.callback = true;
                else App.user.callback = false;

                App.working = false;

                nextRequest = 1000;
                if(callback) setTimeout(callback, nextRequest);

                return false;
            }

        }

    },

    chat : {

        // data holds variables for use in the class:
        data : {
            lastID         : 0,
            noActivity    : 0,
            model_id : '',
            user_id : '',
            first_time : true,
            disable_line_sound : true,
            disable_user_sound : true,
            chat_type : 'normal',
            stop : false,
            chips : 0,
            spy_users : 0,
            tax : false,
            request_type: false,
            get_requests: false,
            pending_requests: 0
        },

        linesLoaded: false,

        sounds :{},

        users : {},

        userNames : [],

        // Init binds event listeners and sets up timers:
        init : function(model_id, chat_type, r){

            App.chat.data.model_id = model_id;
            App.chat.data.stop = false;
            App.chat.users = {};
            if(r){
                if(r.getChats) App.chat.data.first_time = false;
            }
            else App.chat.data.first_time = true;


            if(chat_type) App.chat.data.chat_type = chat_type;

            if(chat_type != 'spy'){
                // Using the defaultText jQuery plugin, included at the bottom:
                $('#name').defaultText('Nickname');
                $('#email').defaultText('Email (Gravatars are Enabled)');

                // Converting the #chatLineHolder and #chatUsers divs into jScrollPanes,
                // and saving the plugin's API in chat.data:
                App.chat.data.jspAPI = $('#chatLineHolder').jScrollPane({
                    verticalDragMinHeight: 12,
                    verticalDragMaxHeight: 12,
                    contentWidth: '0px'
                }).data('jsp');

                App.chat.data.jspAPI_users = $('#chatUsers').jScrollPane({
                    verticalDragMinHeight: 12,
                    verticalDragMaxHeight: 12,
                    contentWidth: '0px'
                }).data('jsp');


                // We use the working variable to prevent
                // multiple form submissions:

                App.working = false;

                // Emoticons button click:

                $('#smileyButton').click(function(e){
                    App.chat.hideStyles();
                    if($('.tipEmoticons').css('visibility')=='visible') App.chat.hideEmoticons();
                    else App.chat.showEmoticons();
                    e.stopPropagation();

                });

                $('#fontButton').click(function (e) {
                    App.chat.hideEmoticons();

                    if ($('#fontStyleContainer').css('visibility') == 'visible') App.chat.hideStyles();
                    else App.chat.showStyles();
                    e.stopPropagation();

                });
                //Emoticon click:

                $('.insert_emoticon').click(function(){
                    current = $('#chatText').val();
                    $('#chatText').focus();
                    switch ($(this).attr('id')){
                        case "smile":
                            $('#chatText').val(current+":) ");
                        break;
                        case "neutral":
                            $('#chatText').val(current+":| ");
                        break;
                        case "frown":
                            $('#chatText').val(current+":( ");
                        break;
                        case "wink":
                            $('#chatText').val(current+";) ");
                        break;
                        case "lol":
                            $('#chatText').val(current+":)) ");
                        break;
                        case "grin":
                            $('#chatText').val(current+":D ");
                        break;
                        case "tongue":
                            $('#chatText').val(current+":P ");
                        break;
                        case "sad":
                            $('#chatText').val(current+":(( ");
                        break;
                        case "surprised":
                            $('#chatText').val(current+":() ");
                        break;
                        case "confused":
                            $('#chatText').val(current+":-\\ ");
                        break;
                    }
                    $('#chatText').focus();
                    App.chat.hideEmoticons();
                });

                $('body').click(function(event){
                    if($('#emoticonsContainer').css('visibility')=='visible') if(!$(event.target).is('#emoticonsContainer')) {
                        App.chat.hideEmoticons();
                    }
                });

                $('#chatText').val();

                // Logging a person in the chat:

                $('#loginForm').submit(function(){

                    if(App.working) return false;
                    App.working = true;


                    App.request.login_name = $('#name').val();
                    App.request.login_email = $('#email').val();
                    App.request.login_user_id = 'guest_' + Math.floor((Math.random() * 99999) + 10000);
                    App.request.login = true;
                    App.request.login_chat_type = 'normal';

                    App.working = false;

                    return false;
                });

                // Submitting a new chat entry:

                $('#submitForm').submit(function(){

                    var text = $('#chatText').val();

                    if(text.length == 0){
                        return false;
                    }

                    if(App.working) return false;
                    App.working = true;

                    App.request.submitchat_autoresponse = '0';
                    App.request.submitchat_text = text.replace(/</g,'&lt;').replace(/>/g,'&gt;');
                    App.request.submitChat = true;
                    App.request.submitchat_line_type = 'normal';
                    $('#chatText').val('');
                    App.working = false;

                    return false;
                });

                //tip model

                $('#tip').click(function(){

                    text = $('#tip_value').val();

                    if(App.working) return false;
                    App.working = true;
                    if(App.chat.data.chips >= text){ //tip only if sufficient tips are available
                        App.request.submitchat_autoresponse = '0';
                        App.request.submitchat_text = text;
                        App.request.submitChat = true;
                        App.request.submitchat_line_type = 'tip';
                    }else{
                        alert("You don't have enough chips!");
                        App.chat.openLink("/purchase-chips/");
                    }
                    App.working = false;

                    return false;
                });

                // Checking whether the user is already logged (browser refresh)


                // Self executing timeout functions

                // reinitialize chat lines and chat users
                App.chat.data.jspAPI.reinitialise();
                App.chat.data.jspAPI.scrollToBottom(true);

                App.chat.data.jspAPI_users.reinitialise();
                App.chat.data.jspAPI_users.scrollToBottom(true);

                $(window).bind('resize', function() {
                  App.chat.data.jspAPI.reinitialise();
                  App.chat.data.jspAPI.scrollToBottom(true);
                });

            }

            App.working = true;

            App.request.checkLogged = true;
            App.chat.data.first_time = true;

            App.working = false;

            //init request
            App.chat.sendRequestTimeoutFunction();

        },

        //callbacks for functions
        sendRequestTimeoutFunction : function(){
            if(!App.chat.data.stop) App.chat.sendRequest(App.chat.sendRequestTimeoutFunction);
        },

        //send general request
        sendRequest : function(callback){

            App.request.model_id = App.chat.data.model_id;

            //allways check if user still has the right to wiew the chat/broadcast
            if(App.request){

                //get nr of chips
                seconds = new Date().getSeconds();
                if((seconds == 0 || seconds == 30 ) && !App.chat.data.tax){
                    App.chat.data.tax = true;
                    //alert(seconds+" : "+App.chat.data.tax);
                }
            }

            App.tzPOST('request', App.request, function(r){

                if(App.working) return false;
                    App.working = true;

                //set activity time in cookie
                now = new Date();
                $.cookie.json = true;

                var data = {};
                if ($.cookie('chatsession')) {
                    data = JSON.parse($.cookie('chatsession'));
                }
                data['chat_' + App.chat.data.model_id] = {};
                data['chat_' + App.chat.data.model_id]['last_activity'] = now.getTime();
                data['chat_' + App.chat.data.model_id]['chat_type'] = App.chat.data.chat_type;


                $.cookie('chatsession', JSON.stringify(data), {
                    path: '/',
                    expire: new Date(now.getTime() + (4 * 60 * 1000))
                });




                //reinitialize taxing (once request sent)


                seconds_after = new Date().getSeconds();
                if (App.chat.data.tax && ((seconds_after > 10 && seconds_after < 30)|| (seconds_after > 40 && seconds_after <= 59))) {
                    App.chat.data.tax = false;
                    //alert(seconds_after+" : "+App.chat.data.tax);
                }

               //  get spy users in private chat
               if(r.spy_users){
                   $("#spy_users").html("Spy users: "+r.spy_users);
               }

                //redirect banned users
                if(r.user_ban){
                    //alert(r.user_ban);
                    App.chat.displayError(r.user_ban);
                    window.onbeforeunload = function(){};
                    $(window).unbind('unload');
                    setTimeout(function(){
                        //window.location= '/live/';
                        location.reload(true);
                    },300);


                    //location.reload(true);

                }

                // if users left chat play sound
                if(r.chat_no_users) {

                    if(App.chat.sounds.container && App.chat.sounds.no_users && !App.chat.data.disable_user_sound){
                        setTimeout(function(){
                            $("#"+App.chat.sounds.container).attr("src", App.chat.sounds.no_users);
                            thissound = document.getElementById(App.chat.sounds.container);
                            thissound.play();
                        }, 2000);

                    }
                }

                //deal with response
                if(r.login){

                    delete App.request.login;
                    App.chat.data.first_time = true;
                    App.working = false;
                    if(r.login.error){
                        App.chat.displayError(r.login.error);
                    }
                    else App.chat.login(r.login.name, r.login.gravatar, r.login.model_id, r.login.user_id);

                    delete App.request.login_name;
                    delete App.request.login_email;
                    delete App.request.login_user_id;
                    delete App.request.login_chat_type;

                    App.working = true;
                }

                if(r.logout){

                    delete App.request.logout;
                    if(App.chat.data.chat_type != 'spy' && App.chat.data.user_id.substr(0, App.chat.data.user_id.indexOf("_")) != 'model'){
                        App.chat.data.jspAPI.getContentPane().empty();
                        App.chat.data.jspAPI_users.getContentPane().empty();
                        App.chat.data.jspAPI.reinitialise();
                        App.chat.data.jspAPI_users.reinitialise();
                        App.chat.data.jspAPI.scrollToBottom(true);
                        App.chat.data.jspAPI_users.scrollToBottom(true);

                        App.chat.data.stop = true;

                        $("#chatBottomBar").empty();
                        $("#chatTopBar .count").empty();
                        temp = $("#chatTopBar .count");
                        $("#chatTopBar").html(temp);
                        //$(".requestContainer").empty();
                    }
                    if (App.chat.data.user_id.substr(0, App.chat.data.user_id.indexOf("_")) == 'model'){

                        /*
                        //stop model streams
                        swfobject.getObjectById('playerID').sendEvent('STOP');
                        for(i=0; (i<window.nr_cams-1 && i<3);i++){
                            swfobject.getObjectById('playerID'+(i+2)).sendEvent('STOP');
                        }
                        */
                        window.onbeforeunload = function(){};
                        $(window).unbind('unload');

                        if(App.chat.data.chat_type != 'normal') location.reload(true);
                        else window.location= '/model/';

                    }
                    if (App.chat.data.user_id.substr(0, App.chat.data.user_id.indexOf("_")) == 'user'){
                        /*
                        //stop user stream
                        $f('player1').stop();
                        */
                        window.onbeforeunload = function(){};
                        $(window).unbind('unload');
                        window.location= '/';

                    }

                    delete callback;
                }



                if(r.checkLogged){

                    delete App.request.checkLogged;
                    App.chat.data.first_time = true;

                    if(r.checkLogged.logged){
                        App.working = false;

                        App.chat.login(r.checkLogged.loggedAs.name, r.checkLogged.loggedAs.gravatar, r.checkLogged.loggedAs.model_id, r.checkLogged.loggedAs.user_id, r.checkLogged.loggedAs.chat_type);

                        App.working = true;
                    }

                }

                //display message
                if(r.chips_message ){
                    //alert(r.chips_message);
                    App.chat.displayError(r.chips_message)
                }

                if(typeof(r.getChips) != 'undefined'){

                    App.working = false;
                    //use response to show chips
                    App.chat.data.chips = r.getChips;
                    $('.chips_number').text("Chips: "+parseInt(r.getChips)+" ");
                    App.working = true;
                }

                if(r.submitChat){

                    delete App.request.submitChat;
                    delete App.request.submitchat_autoresponse;
                    delete App.request.submitchat_text;
                    delete App.request.submitchat_line_type;

                }

                if(r.getNotes){

                    delete App.request.getNotes;

                    $("#notes").val(r.getNotes.notes);
                    $("#user_id").val(App.request.getnotes_user_id);

                    delete App.request.getnotes_user_id;

                    $("#dialog-form").dialog( "open" );

                }

                if(r.submitNotes){

                    delete App.request.submitNotes;
                    delete App.request.submitnotes_notes;
                    delete App.request.submitnotes_user_id;

                }

                if(r.getUsers){

                    App.working = true;
                    App.chat.processUsers(r);



                }

                if(r.getChats){

                    App.chat.processChats(r);

                }

                if(r.getStatus){

                    delete App.request.getStatus;

                    App.user.status.data.status = r.getStatus.status;

                    if($('#status').text() != App.user.status.data.status){
                        $('#status').text(App.user.status.data.status);

                        $('#status').animate( {color: 'green'}, 2500, function(){
                            $('#status').animate( {color: '#777'}, 2500);
                        } );
                    }

                }

                if(r.submitStatus){

                    delete App.request.submitStatus;
                    delete App.request.submitstatus_status;
                }
                if(r.submitStatusProfile){

                    delete App.request.submitStatusProfile;
                    delete App.request.submitstatus_status_profile;
                }

                if(r.removeRequest){

                    delete App.request.removeRequest;
                    delete App.request.removerequest_user_id;
                    delete App.request.removerequest_request;
                }

                if(r.pending_requests > 0 && $('.requestContainer').length == 1){

                     App.chat.data.pending_requests = 1;



                      $("#waiting_label").hide().val('');
                      if(App.user.group.data.requested) {
                            message = "Your request has been approved. Waiting for other users to join!";
                      } else {
                           if(r.pending_requests == 1)
                                message = "1 user pending group chat.";
                            else
                                message = r.pending_requests + " users pending group chat.";

                            $("#request_group").val("Join group!");
                      }
                      $('.requestContainer').after('<div class="requestContainer"><p style="text-align: center">'+message+' </p></div>');


                }

                if(r.returnRequest){

                    delete App.request.returnRequest;

                    if(r.returnRequest.length>0){

                        if(App.request.returnrequest_type == 'model'){

                            if(r.returnRequest[0].count>0 && r.returnRequest[0].status == 'pending'){
                                if(r.returnRequest[0].type == 'private'){
                                    //console.log(r.returnRequest);
                                    App.chat.data.request_type = 'private';
                                    App.model.private.data.id = r.returnRequest[0].id_model;
                                    App.model.private.data.user_id = r.returnRequest[0].id_user;

                                    App.chat.data.get_requests = false;
                                    App.working = true;
                                    //show dialog - request to accept
                                    $("#user_id").val(r.returnRequest[0].id_user);
                                    $("#user_name").text('');
                                    $("#user_name").text('User: "'+r.returnRequest[0].nickname+'" requests private chat');
                                    $("#dialog-form-request").dialog( "open" );
                                }

                                if(r.returnRequest[0].type == 'vip'){
                                    App.chat.data.request_type = 'vip';
                                    App.model.vip.data.id = r.returnRequest[0].id_model;
                                    App.model.vip.data.user_id = r.returnRequest[0].id_user;

                                    App.chat.data.get_requests = false;
                                    App.working = true;
                                    //show dialog - request to accept
                                    $("#user_id").val(r.returnRequest[0].id_user);
                                    $("#user_name").text('');
                                    $("#user_name").text('User: "'+r.returnRequest[0].nickname+'" requests VIP private chat');
                                    $("#dialog-form-request").dialog( "open" );
                                }
                                if(r.returnRequest[0].type == 'group'){
                                    App.chat.data.request_type = 'group';
                                    App.model.group.data.id = r.returnRequest[0].id_model;
                                    App.model.group.data.user_id = r.returnRequest[0].id_user;

                                    App.chat.data.get_requests = true;
                                    App.working = true;
                                    //show dialog - request to accept
                                    $("#user_id").val(r.returnRequest[0].id_user);
                                    $("#user_name").text('');
                                    $("#user_name").text('User: "'+r.returnRequest[0].nickname+'" requests Group chat');
                                    $("#dialog-form-request").dialog( "open" );
                                }

                            }
                        }

                        if(App.request.returnrequest_type == 'user'){

                            if(r.returnRequest.length == 1) {
                                if(r.returnRequest[0].count>0){

                                    if(r.returnRequest[0].status == 'accepted') {
                                        if(r.returnRequest[0].type == 'private'){
                                            if(!App.user.private.data.status){//private request
                                                App.user.private.data.status = true;
                                                App.chat.data.chat_type = r.returnRequest[0].type;
                                                $('#go_private').empty();
                                                $("#stop_chat").css("visibility", "visible");
                                                alert('Click to start Private chat!');

                                                /*reset user counter for private mode*/
                                                var date = new Date();
                                                var minutes = 3;
                                                date.setTime(date.getTime() + (minutes * 60 * 1000));
                                                document.cookie = "reset_counter=true; expires:"+ date ;

                                                App.user.private.data.requested = false;
                                                source = $('#img_logo').attr('src');
                                                source = source.replace('normal', 'private_chat');
                                                $('#img_logo').attr('src', source);
                                                $('#chat_label').text('Private Chat');
                                                $('#chat_logo').css('display', 'block');
                                                $("a").attr('target','_blank');
                                                $(".header_controls a").removeAttr('target');

                                                App.chat.initExit();
                                            }
                                        }
                                        //spy request
                                        if(r.returnRequest[0].type == 'spy'){
                                            App.user.spy.data.status = true;
                                            App.chat.data.chat_type = r.returnRequest[0].type;
                                            $('#go_spy').empty();
                                            $("#stop_chat").css("visibility", "visible");



                                            //alert('Click to start Spy mode!');
                                            App.user.spy.data.requested = false;
                                            window.onbeforeunload = function(){};
                                            $(window).unbind('unload');

                                             setTimeout(function(){
                                                    location.reload(true);
                                            }, 3000);
                                        }

                                        if(r.returnRequest[0].type == 'vip'){
                                            if(!App.user.vip.data.status){//private request
                                                App.user.vip.data.status = true;
                                                App.chat.data.chat_type = r.returnRequest[0].type;
                                                $('#go_private').empty();
                                                $("#stop_chat").css("visibility", "visible");
                                                alert('Click to start VIP Private chat!');

                                                /*reset user counter for vip mode*/
                                                var date = new Date();
                                                var minutes = 3;
                                                date.setTime(date.getTime() + (minutes * 60 * 1000));
                                                document.cookie = "reset_counter=true; expires:"+ date ;

                                                App.user.vip.data.requested = false;
                                                source = $('#img_logo').attr('src');
                                                source = source.replace('normal', 'private_chat');
                                                $('#img_logo').attr('src', source);
                                                $('#chat_label').text('VIP Private Chat');
                                                $('#chat_logo').css('display', 'block');
                                                $("a").attr('target','_blank');
                                                $(".header_controls a").removeAttr('target');

                                                App.chat.initExit();
                                            }
                                        }

                                        if(r.returnRequest[0].type == 'group'){
                                            if(!App.user.group.data.status){//private request
                                                App.user.group.data.status = true;
                                                App.chat.data.chat_type = r.returnRequest[0].type;
                                                $('#go_private').empty();
                                                $("#stop_chat").css("visibility", "visible");
                                                alert('Click to start Group chat!');

                                                /*reset user counter for vip mode*/
                                                var date = new Date();
                                                var minutes = 3;
                                                date.setTime(date.getTime() + (minutes * 60 * 1000));
                                                document.cookie = "reset_counter=true; expires:"+ date ;

                                                App.user.vip.data.requested = false;
                                                source = $('#img_logo').attr('src');
                                                source = source.replace('normal', 'group_chat');
                                                $('#img_logo').attr('src', source);
                                                $('#chat_label').text('Group Chat');
                                                $('#chat_logo').css('display', 'block');
                                                $("a").attr('target','_blank');
                                                $(".header_controls a").removeAttr('target');

                                                App.chat.initExit();
                                            }
                                        }


                                    }

                                    //delete entry from db if necessary
                                    if(r.returnRequest[0].status == 'accepted' || r.returnRequest[0].status == 'denied') {

                                        App.request.removeRequest = true;
                                        if(r.returnRequest[0].type == 'private'){
                                            App.request.removerequest_user_id = App.user.private.data.user_id;
                                            App.request.removerequest_request = 'private';
                                        }
                                        if(r.returnRequest[0].type == 'vip'){
                                            App.request.removerequest_user_id = App.user.vip.data.user_id;
                                            App.request.removerequest_request = 'vip';
                                        }
                                        if(r.returnRequest[0].type == 'group'){
                                            App.request.removerequest_user_id = App.user.group.data.user_id;
                                            App.request.removerequest_request = 'group';
                                        }
                                        if(r.returnRequest[0].type == 'spy'){
                                            App.request.removerequest_user_id = App.user.spy.data.user_id;
                                            App.request.removerequest_request = 'spy';
                                        }
                                    }

                                    if(r.returnRequest[0].status == 'denied') {
                                        if(r.returnRequest[0].type == 'private'){
                                            $('#request').removeAttr("disabled");
                                            $('#request').val('Go Private!');
                                            $('#waiting_label').text('The model denied your request!');
                                            $('#waiting_label').animate( {color: 'green'}, 2500, function(){

                                                $('#waiting_label').animate( {color: '#777'}, 2500, function(){
                                                        $('#waiting_label').css('display', 'none');
                                                        $('#waiting_label').text('Waiting for model response.');

                                                } );

                                            } );
                                            $('#request').css('background', "url('/images/button_blue.png') no-repeat");
                                            $('#request').css('cursor', 'pointer');
                                            App.user.private.data.counter = 30;
                                            App.user.private.data.requested = false;
                                        }

                                        if(r.returnRequest[0].type == 'vip'){
                                            $('#request_vip').removeAttr("disabled");
                                            $('#request_vip').val('VIP Private!');
                                            $('#waiting_label').text('The model denied your request!');
                                            $('#waiting_label').animate( {color: 'green'}, 2500, function(){

                                                $('#waiting_label').animate( {color: '#777'}, 2500, function(){
                                                        $('#waiting_label').css('display', 'none');
                                                        $('#waiting_label').text('Waiting for model response.');

                                                } );

                                            } );
                                            $('#request').css('background', "url('/images/button_blue.png') no-repeat");
                                            $('#request').css('cursor', 'pointer');
                                            App.user.vip.data.counter = 30;
                                            App.user.vip.data.requested = false;
                                        }
                                        if(r.returnRequest[0].type == 'group'){

                                            $('#request_group').removeAttr("disabled");
                                            $('#request_group').val('Group chat!');
                                            $('#waiting_label').text('The model denied your request!');
                                            $('#waiting_label').animate( {color: 'green'}, 2500, function(){

                                                $('#waiting_label').animate( {color: '#777'}, 2500, function(){
                                                        $('#waiting_label').css('display', 'none');
                                                        $('#waiting_label').text('Waiting for model response.');

                                                } );

                                            } );
                                            $('#request').css('background', "url('/images/button_blue.png') no-repeat");
                                            $('#request').css('cursor', 'pointer');
                                            App.user.vip.data.counter = 30;
                                            App.user.vip.data.requested = false;
                                        }

                                    }

                                    if(r.returnRequest[0].status == 'pending') {
                                        if(!App.request.removeRequest){
                                            if(r.returnRequest[0].type == 'private'){ //private request
                                                $('#waiting_label').css('display', 'inline-block');
                                                $('#waiting_label').css({color: '#777'});
                                                /*$('#request').attr("disabled","disabled");
                                                $('#request').css('background', "url('/images/button_blue_disabled.png') no-repeat");
                                                $('#request').css('cursor', 'default');*/

                                                App.user.private.data.counter--;
                                                $('#request_counter').show().html(App.user.private.data.counter);

                                                if(App.user.private.data.counter == 0){
                                                    $('#request_counter').hide().html('');
                                                    App.user.private.data.counter = 30;
                                                    App.user.private.data.requested = false;

                                                    App.request.removeRequest = true;
                                                    App.request.removerequest_user_id = App.user.private.data.user_id;
                                                    App.request.removerequest_request = 'private';

                                                }
                                                if(App.user.group.data.counter == 0){
                                                    $('#request_counter').hide().html('');
                                                    App.user.group.data.counter = 30;
                                                    App.user.group.data.requested = false;

                                                    App.request.removeRequest = true;
                                                    App.request.removerequest_user_id = App.user.group.data.user_id;
                                                    App.request.removerequest_request = 'private';

                                                }
                                            }
                                            if(r.returnRequest[0].type == 'vip'){ //vip private request
                                                $('#waiting_label').css('display', 'inline-block');
                                                $('#waiting_label').css({color: '#777'});
                                                /*$('#request_vip').attr("disabled","disabled");
                                                $('#request_vip').css('background', "url('/images/button_blue_disabled.png') no-repeat");
                                                $('#request_vip').css('cursor', 'default');*/

                                                App.user.vip.data.counter--;
                                                //$('#request_vip').val(App.user.vip.data.counter);
                                                $('#request_counter').show().html(App.user.vip.data.counter);

                                                if(App.user.vip.data.counter == 0){
                                                    $('#request_counter').hide().html('');
                                                    App.user.vip.data.counter = 30;
                                                    App.user.vip.data.requested = false;

                                                    App.request.removeRequest = true;
                                                    App.request.removerequest_user_id = App.user.vip.data.user_id;
                                                    App.request.removerequest_request = 'vip';

                                                }
                                            }
                                            if(r.returnRequest[0].type == 'group'){ //vip private request
                                                $('#waiting_label').css('display', 'inline-block');
                                                $('#waiting_label').css({color: '#777'});
                                                /*$('#request_vip').attr("disabled","disabled");
                                                $('#request_vip').css('background', "url('/images/button_blue_disabled.png') no-repeat");
                                                $('#request_vip').css('cursor', 'default');*/

                                                App.user.group.data.counter--;
                                                //$('#request_vip').val(App.user.vip.data.counter);
                                                $('#request_counter').show().html(App.user.group.data.counter);

                                                if(App.user.group.data.counter == 0){
                                                    $('#request_counter').hide().html('');
                                                    App.user.group.data.counter = 30;
                                                    App.user.group.data.requested = false;

                                                    App.request.removeRequest = true;
                                                    App.request.removerequest_user_id = App.user.group.data.user_id;
                                                    App.request.removerequest_request = 'group';

                                                }
                                            }

                                            if(!App.user.callback){
                                                App.user.private.data.requested = true;

                                                App.working = false;

                                                if(r.returnRequest[0].type == 'private') App.user.private.getPrivateRequestTimeoutFunction();
                                                if(r.returnRequest[0].type == 'spy') App.user.spy.getSpyRequestTimeoutFunction();
                                                if(r.returnRequest[0].type == 'vip') App.user.vip.getVipRequestTimeoutFunction();
                                                if(r.returnRequest[0].type == 'group') App.user.group.getGroupRequestTimeoutFunction();

                                                App.working = true;
                                            }
                                        }
                                        if(App.request.removeRequest){
                                            if(r.returnRequest[0].type == 'private'){
                                                /*$('#request').val('Go Private!');
                                                $('#request').removeAttr("disabled");
                                                $('#request').css('background', "url('/images/button_blue.png') no-repeat");
                                                $('#request').css('cursor', 'pointer');*/

                                                $('#waiting_label').text('The model did not respond. Please try again later!');
                                                    $('#waiting_label').animate( {color: 'green'}, 2500, function(){
                                                        $('#waiting_label').animate( {color: '#777'}, 2500, function(){
                                                        $('#waiting_label').css('visibility', 'hidden');
                                                        $('#waiting_label').text('Waiting for model response.');
                                                        $("#go_private .btn_request").show();
                                                    } );
                                                } );

                                            }
                                            if(r.returnRequest[0].type == 'vip'){
                                                /*$('#request_vip').val('VIP Private!');
                                                $('#request_vip').removeAttr("disabled");
                                                $('#request_vip').css('background', "url('/images/button_blue.png') no-repeat");
                                                $('#request_vip').css('cursor', 'pointer');*/
                                                $("#go_private .btn_request").show();
                                                $('#waiting_label').text('The model did not respond. Please try again later!');
                                                    $('#waiting_label').animate( {color: 'green'}, 2500, function(){
                                                        $('#waiting_label').animate( {color: '#777'}, 2500, function(){
                                                        $('#waiting_label').css('visibility', 'hidden');
                                                        $('#waiting_label').text('Waiting for model response.');
                                                        $("#go_private .btn_request").show();
                                                    } );
                                                } );
                                            }
                                            if(r.returnRequest[0].type == 'group'){
                                                $("#go_private .btn_request").show();
                                                $('#waiting_label').text('The model did not respond. Please try again later!');
                                                    $('#waiting_label').animate( {color: 'green'}, 2500, function(){
                                                        $('#waiting_label').animate( {color: '#777'}, 2500, function(){
                                                        $('#waiting_label').css('visibility', 'hidden');
                                                        $('#waiting_label').text('Waiting for model response.');
                                                        $("#go_private .btn_request").show();
                                                    } );
                                                } );
                                            }
                                        }
                                    }
                                }else{
                                    $('#request_counter').hide().html('');
                                    if(r.returnRequest[0].type == 'private'){
                                        App.user.private.data.status = false;
                                        $('#request').val('Go Private!');
                                        $('#request').removeAttr("disabled");
                                        $('#waiting_label').css('visibility', 'hidden');
                                        $('#request').css('background', "url('/images/button_blue.png') no-repeat");
                                        $('#request').css('cursor', 'pointer');
                                        App.user.private.data.counter = 30;
                                    }
                                    if(r.returnRequest[0].type == 'vip'){
                                        App.user.vip.data.status = false;
                                        $('#request_vip').val('VIP Private!');
                                        $('#request_vip').removeAttr("disabled");
                                        $('#waiting_label').css('visibility', 'hidden');
                                        $('#request_vip').css('background', "url('/images/button_blue.png') no-repeat");
                                        $('#request_vip').css('cursor', 'pointer');
                                        App.user.vip.data.counter = 30;
                                    }
                                    if(r.returnRequest[0].type == 'group'){
                                        App.user.group.data.status = false;
                                        $('#request_vip').val('Group chat!');
                                        $('#request_vip').removeAttr("disabled");
                                        $('#waiting_label').css('visibility', 'hidden');
                                        $('#request_vip').css('background', "url('/images/button_blue.png') no-repeat");
                                        $('#request_vip').css('cursor', 'pointer');
                                        App.user.vip.data.counter = 30;
                                    }
                                }
                            }else {
                                if(r.returnRequest[0].type == 'private'){
                                    App.user.private.data.status = false;
                                    $('#request').val('Go Private!');
                                    $('#request').removeAttr("disabled");
                                    $('#waiting_label').css('visibility', 'hidden');
                                    $('#request').css('background', "url('/images/button_blue.png') no-repeat");
                                    $('#request').css('cursor', 'pointer');
                                    App.user.private.data.counter = 30;
                                }
                                if(r.returnRequest[0].type == 'vip'){
                                    App.user.vip.data.status = false;
                                    $('#request_vip').val('VIP Private!');
                                    $('#request_vip').removeAttr("disabled");
                                    $('#waiting_label').css('visibility', 'hidden');
                                    $('#request_vip').css('background', "url('/images/button_blue.png') no-repeat");
                                    $('#request_vip').css('cursor', 'pointer');
                                    App.user.vip.data.counter = 30;
                                }
                                if(r.returnRequest[0].type == 'group'){
                                    App.user.group.data.status = false;
                                    $('#request_vip').val('Group chat!');
                                    $('#request_vip').removeAttr("disabled");
                                    $('#waiting_label').css('visibility', 'hidden');
                                    $('#request_vip').css('background', "url('/images/button_blue.png') no-repeat");
                                    $('#request_vip').css('cursor', 'pointer');
                                    App.user.vip.data.counter = 30;
                                }
                            }

                            if(App.user.private.data.status == true) {

                            }

                        }

                    }

                    delete App.request.returnrequest_user_id;
                    delete App.request.returnrequest_request;
                    delete App.request.returnrequest_type;

                }

                if(r.submitRequest){

                    delete App.request.submitRequest;
                    delete App.request.submitrequest_user_id;
                    delete App.request.submitrequest_request;

                    if(r.submitRequest.length == 1){

                        if(r.submitRequest[0].count > 0 ){

                            if(r.submitRequest[0].type == 'private'){
                                App.user.private.data.counter = 30;
                                /*$('#request').val(App.user.private.data.counter);
                                $('#request').attr("disabled","disabled");
                                $('#request').css('background', "url('/images/button_blue_disabled.png') no-repeat");
                                $('#request').css('cursor', 'default');*/
                                $("#go_private .btn_request").hide();
                                $('#waiting_label').css('visibility', 'visible');


                                //start getPrivateRequest calls if succesfull
                                App.user.private.data.requested = true;

                                App.working = false;

                                App.user.private.getPrivateRequestTimeoutFunction();

                                App.working = true;
                            }

                            if(r.submitRequest[0].type == 'vip'){
                                App.user.vip.data.counter = 30;
/*                                $('#request_vip').val(App.user.vip.data.counter);
                                $('#request_vip').attr("disabled","disabled");
                                $('#request_vip').css('background', "url('/images/button_blue_disabled.png') no-repeat");
                                $('#request_vip').css('cursor', 'default');*/
                                $("#go_private .btn_request").hide();
                                $('#waiting_label').css('visibility', 'visible');


                                //start getPrivateRequest calls if succesfull
                                App.user.vip.data.requested = true;

                                App.working = false;

                                App.user.vip.getVipRequestTimeoutFunction();

                                App.working = true;
                            }

                            if(r.submitRequest[0].type == 'group'){
                                App.user.group.data.counter = 60;
/*                                $('#request_vip').val(App.user.vip.data.counter);
                                $('#request_vip').attr("disabled","disabled");
                                $('#request_vip').css('background', "url('/images/button_blue_disabled.png') no-repeat");
                                $('#request_vip').css('cursor', 'default');*/
                                $("#go_private .btn_request").hide();
                                $('#waiting_label').css('visibility', 'visible');


                                //start getPrivateRequest calls if succesfull
                                App.user.group.data.requested = true;

                                App.working = false;

                                App.user.group.getGroupRequestTimeoutFunction();

                                App.working = true;
                            }

                            if(r.submitRequest[0].type == 'spy'){

                                //start getSpyRequest calls if succesfull
                                App.user.spy.data.requested = true;
                                App.working = false;

                                App.user.spy.getSpyRequestTimeoutFunction();

                                App.working = true;
                            }

                        }else{
                            if(r.submitRequest[0].status == 'not enough chips'){
                                alert("You don't have enough chips!");
                                App.chat.openLink("/purchase-chips/");
                                App.chat.data.stop = true;
                            }
                        }

                    }
                }

                if(r.acceptRequest){

                    delete App.request.acceptRequest;
                    delete App.request.acceptrequest_user_id;
                    delete App.request.acceptrequest_request;

                    if(r.acceptRequest.length>0){

                        if(r.acceptRequest[0].count>0 && r.acceptRequest[0].status == 'accepted'){
                            if(r.acceptRequest[0].type == 'private'){
                                App.model.private.data.status = true;
                                App.chat.data.get_requests = false;
                                App.chat.data.chat_type = r.acceptRequest[0].type;
                                $("a").attr('target','_blank');
                                $(".header_controls a").removeAttr('target');

                                alert("Private chat started!");
                                $("#stop_chat").css("visibility", "visible");
                                source = $('#img_logo').attr('src');
                                source = source.replace('normal', 'private_chat');
                                $('#img_logo').attr('src', source);
                                $('#chat_label').text('Private Chat');
                                $('#chat_logo').css('display', 'block');
                                $("a").attr('target','_blank');
                                $(".header_controls a").removeAttr('target');
                            }

                            if(r.acceptRequest[0].type == 'vip'){
                                App.model.vip.data.status = true;
                                App.chat.data.get_requests = false;
                                App.chat.data.chat_type = r.acceptRequest[0].type;
                                $("a").attr('target','_blank');
                                $(".header_controls a").removeAttr('target');

                                alert("VIP Private chat started!");
                                $("#stop_chat").css("visibility", "visible");
                                source = $('#img_logo').attr('src');
                                source = source.replace('normal', 'vip_chat');
                                $('#img_logo').attr('src', source);
                                $('#chat_label').text('VIP Chat');
                                $('#chat_logo').css('display', 'block');
                                $("a").attr('target','_blank');
                                $(".header_controls a").removeAttr('target');
                            }

                            if(r.acceptRequest[0].type == 'group'){
                                App.model.group.data.status = true;
                                App.chat.data.get_requests = true;
                                App.model.group.getGroupRequestTimeoutFunction();
                                App.chat.data.chat_type = r.acceptRequest[0].type;
                                $("a").attr('target','_blank');
                                $(".header_controls a").removeAttr('target');

                                alert("Group chat started!");
                                $("#stop_chat").css("visibility", "visible");
                                source = $('#img_logo').attr('src');
                                source = source.replace('normal', 'group_chat');
                                $('#img_logo').attr('src', source);
                                $('#chat_label').text('Group Chat');
                                $('#chat_logo').css('display', 'block');
                                $("a").attr('target','_blank');
                                $(".header_controls a").removeAttr('target');
                            }

                        }
                        else {
                            if(r.acceptRequest[0].type == 'private'){
                                App.model.private.data.status = false;
                            }
                            if(r.acceptRequest[0].type == 'vip'){
                                App.model.vip.data.status = false;
                            }
                            if(r.acceptRequest[0].type == 'group'){
                                App.model.group.data.status = false;
                            }

                        }
                    }
                }

                if(r.denyRequest){

                    delete App.request.denyRequest;
                    delete App.request.denyrequest_user_id;
                    delete App.request.denyrequest_request;

                    if(r.denyRequest.length>0){

                        if(r.denyRequest[0].count>0 && r.denyRequest[0].status == 'denied') {

                            if(r.returnRequest[0].type == 'private'){

                                App.model.private.data.status = true;
                                App.chat.data.get_requests = true;
                                App.model.private.getPrivateRequestTimeoutFunction();

                            }
                            if(r.returnRequest[0].type == 'vip'){

                                App.model.vip.data.status = true;
                                App.chat.data.get_requests = true;
                                App.model.vip.getVipRequestTimeoutFunction();

                            }
                            if(r.returnRequest[0].type == 'group'){

                                App.model.group.data.status = true;
                                App.chat.data.get_requests = true;
                                App.model.group.getGroupRequestTimeoutFunction();

                            }

                        }
                        else {
                            if(r.returnRequest[0].type == 'private'){
                                App.model.private.data.status = false;
                            }
                            if(r.returnRequest[0].type == 'vip'){
                                App.model.vip.data.status = false;
                            }
                            if(r.returnRequest[0].type == 'group'){
                                App.model.group.data.status = false;
                            }
                        }
                    }
                }

                if(r.checkUserAccess){



                    if(r.checkUserAccess.stream){
                        //if(r.checkUserAccess.status == 'authorized'){
                           // if(r.checkUserAccess.type =='model'){//change model streams

                                //swfobject.getObjectById('playerID').sendEvent('LOAD', {file: r.checkUserAccess.stream, streamer : r.checkUserAccess.streamer});
                                //swfobject.getObjectById('playerID').sendEvent('PLAY');
                                //for(i=0; (i<window.nr_cams-1 && i<3);i++){
                                    //swfobject.getObjectById('playerID'+(i+2)).sendEvent('LOAD', {file: r.checkUserAccess.stream+""+(i+2), streamer : r.checkUserAccess.streamer});
                                    //swfobject.getObjectById('playerID'+(i+2)).sendEvent('PLAY');
                                //}

                           // }
                           // if(r.checkUserAccess.type =='user'){//change user stream


                               // $f('player1').getClip().update(
                                //{
                                //           url: r.checkUserAccess.stream,
                                //           live: true,
                                //           provider: 'rtmp',
                                //           autoPlay: true,
                                //           autoBuffering: true,
                                //          scaling : "scale"
                                //    }
                               // );

                           // }

                        //}

                        //refresh page - to switch stream
                        window.onbeforeunload = function(){};
                        $(window).unbind('unload');
                        location.reload(true);

                    }

                    if(r.checkUserAccess.status != 'authorized'){
                       window.onbeforeunload = function(){};
                        $(window).unbind('unload');
                        if(App.chat.data.user_id == "model_"+App.chat.data.model_id && App.chat.data.chat_type == 'normal'){
                            location.href = "/model/index/";

                        }else{
                            setTimeout(function(){
                                    location.reload(true);
                            }, 5000);

                        }
                    }
                }

                if(r.openLink){

                    App.chat.openLink(r.openLink);

                }


                if(r.getNotes) App.working = true;
                else App.working = false;


                //set next request in 1s
                nextRequest = 500;

                if(callback) setTimeout(callback,nextRequest);

            });
        },

        openLink : function(open_link){
            if(typeof App.chat.data.winRef == 'undefined' || App.chat.data.winRef == null){
              //create new
              show = false;
              if(typeof App.chat.data.winRef == 'undefined') show = true;
              else{
                  if(App.chat.data.winRef == null) show = true;
                  else{
                      if(App.chat.data.winRef.closed) show = true;
                  }
              }
              if(show) eval("App.chat.data.winRef = window.open('"+open_link+"', '_blank')");
            } else {
              //give it focus (in case it got burried)
              App.chat.data.winRef.focus();
            }

        },

        processUsers : function(r){

            //usernames for autocompete
            App.chat.userNames = [];

            //add new users
            for(var i=0; i< r.getUsers.users.length;i++){



                if(r.getUsers.users[i]){
                    App.chat.addChatUser(r.getUsers.users[i]);
                }
            }

            //remove inactive users (logged_out)
            temp_user_list = App.chat.users;
            for(i in temp_user_list){
                    App.chat.removeChatUser(eval('temp_user_list.'+i), r.getUsers.users);

            }
            var message = '';

            cont=0;
            for(temp in App.chat.users){
                cont++;
            }

            if(cont<1){
                message = 'No one is online';
            }
            else {
                message = cont+' '+(cont == 1 ? 'person':'people')+' online';
            }

            $('.count').html(message);

            $(".notes").click(function() {
                    App.chat.getNotes($(this).attr('id').substr($(this).attr('id').indexOf("_")+1));

            });

            if(App.chat.data.disable_user_sound) App.chat.data.disable_user_sound = false;

            App.chat.atNamesChatRoom();

        },

        processChats: function(r){
            if(!r.getChats.chats) {
                App.chat.linesLoaded = true;
                return null;
            }
            for(var i=0;i<r.getChats.chats.length;i++){
                App.chat.addChatLine(r.getChats.chats[i]);
                if(i+1 == r.getChats.chats.length) App.chat.linesLoaded = true;
            }

            if(r.getChats.chats.length){
                App.chat.data.noActivity = 0;
                App.chat.data.lastID = r.getChats.chats[i-1].id;
            }
            else{
                // If no chats were received, increment
                // the noActivity counter.

                App.chat.data.noActivity++;
            }

            if(!App.chat.data.lastID){
                App.chat.data.jspAPI.getContentPane().html('<p class="noChats">No chats yet</p>');
            }

            if(App.chat.data.disable_line_sound) App.chat.data.disable_line_sound = false;
        },

        login : function(name, gravatar, model_id, user_id, chat_type){

            App.chat.data.name = name;
            App.chat.data.gravatar = gravatar;
            App.chat.data.model_id = model_id;
            App.chat.data.user_id = user_id;
            App.chat.data.chat_type = chat_type;
            App.chat.data.first_time = true;

            if(chat_type != 'spy'){
                $('#chatTopBar').html(App.chat.render('loginTopBar',App.chat.data));

                if($('#loginForm')){
                    $('#loginForm').fadeOut(function(){
                        $('#submitForm').fadeIn();
                        $('#chatText').focus();
                    });
                }else{
                    $('#submitForm').fadeIn();
                    $('#chatText').focus();
                }
            }
        },

        // The render method generates the HTML markup
        // that is needed by the other methods:
        render : function(template,params){

            var arr = [];
            switch(template){
                case 'loginTopBar':
                    arr = [
                    '<span><img src="',params.gravatar,'" width="23" height="23" />',
                    '<span class="name">',params.name,
                    '</span></span><span class="count"></span><span class="timer"></span>'];
                break;

                case 'chatLine':

                    //add username link
                    if (params.text.indexOf('@') != -1) {
                        right = params.text.substring(params.text.indexOf('@'), params.text.length)
                        lastChar = right.indexOf(' ')
                        if (lastChar == -1) lastChar = right.length;
                        username = right.substring(1, lastChar)

                        if(username) {
                            foundUser = App.chat.userNames.map(function (user, i) {
                                if (user.name == username)return user;
                                return null;

                            }).filter(function (item) {
                                return item;
                            })
                            if(foundUser.length > 0 && foundUser[0]) {
                                if(foundUser[0].profile_url) {
                                    urlProfile = '<a href="' + foundUser[0].profile_url + '" target="_new">@' + username + '</a>';
                                    params.text = params.text.replace('@' + username, urlProfile);
                                }
                                //if chat loaded and is mentioned ring a bell
                                if(App.chat.linesLoaded && username == App.chat.data.name) {


                                    //play sound
                                    console.log('im mentioned');
                                    //@todo: create new sound for mentions
                                    if (App.chat.sounds.container && App.chat.sounds.mentioned /*&& !App.chat.data.disable_user_sound*/) {
                                        setTimeout(function () {
                                            $("#" + App.chat.sounds.container).attr("src", App.chat.sounds.mentioned);
                                            thissound = document.getElementById(App.chat.sounds.container);
                                            thissound.play();
                                        }, 10);

                                    }


                                }

                            }
                        }
                    }
                    //console.log(template)
                    //console.log(params)

                    autoresponder = '';
                    if (App.chat.data.user_id.substr(0, App.chat.data.user_id.indexOf("_")) == 'model' && params.autoresponse == 1) {
                        autoresponder =' <i>[autoresponder]</i>';
                    }
                    tip_type = '';
                    tip_text = '';
                    text = params.text;
                    if (params.type == 'tip') {
                        tip_type = ' user_tip';
                        text = '';
                        tip_text = ' '+params.text;
                    }

                    sylesObject = {
                        user_id : ''
                    };

                    if(params.chat_font)
                        sylesObject = JSON.parse(params.chat_font);

                    if(params.id_user) {
                        sylesObject.user_id = params.id_user;
                        $('#chat-user-id').val(sylesObject.user_id)
                    }


                    arr = [
                        '<div class="chat user-' + sylesObject.user_id + ' chat-',params.id,' rounded"><span class="gravatar"><img src="',params.gravatar,
                        '" width="23" height="23" onload="this.style.visibility=\'visible\'" />',
                        '</span><span class="author user' + params.user_id,tip_type,'">',
                        params.author,
                        ':',autoresponder,tip_text,'</span><span class="text">',text,'</span><span class="time">',params.time,'</span></div>'];
                break;

                case 'user':
                    //alert(params.id_user.substr(0, params.id_user.indexOf("_")));


                    favorite = notes = '';
                    if(params.id_user.substring(0, 6) == 'guest_')
                        link = params.name;
                    else if (params.id_user.substr(0, params.id_user.indexOf("_")) == 'user') {

                        if(params.favorite == true && App.chat.data.user_id==('model_'+App.chat.data.model_id))
                            favorite = '<img class="favorite" src="/images/favorite.png">';
                        link = '<a class="user_name_wrapper user" data-chips="'+ params.chips +'" id="user_'+(params.id_user.substr(params.id_user.indexOf("_")+1))+'" target="_blank" href="/user/profile/'+(params.id_user.substr(params.id_user.indexOf("_")+1))+"/"+params.name+'" title="'+params.name+'">'+params.name+'</a>';
                        if(App.chat.data.user_id==('model_'+App.chat.data.model_id)) notes = '<img class="notes" id="notes_'+(params.id_user.substr(params.id_user.indexOf("_")+1))+'" src="/images/notes.png">';

                    } else {
                        if (App.chat.data.user_id.substr(0, App.chat.data.user_id.indexOf("_")) == 'model'){

                            link = '<a class="user_name_wrapper chips_number" href="javascript:void(0);" class="chips_number" title="My chips"></a>';

                        } else {

                            link = '<a class="user_name_wrapper model" id="model_'+App.chat.data.model_id+'" target="_blank" href="/model/'+(params.id_user.substr(params.id_user.indexOf("_")+1))+'/'+params.name+'/profile" title="'+params.name+'">'+params.name+'\'s Chat</a>';

                        }

                    }

                    favorite = "</span><div class='favorite_icon_wrapper'>"+favorite+"</div>";
                    notes = '<div class="notes_icon_wrapper">'+notes+'</div>';
                    if (App.chat.data.user_id.substr(0, App.chat.data.user_id.indexOf("_")) == 'model'){
                        link = favorite+notes+link;
                    }
                    arr = [
                        '<div class="user" id="user-'+params.id_user+'" data-chips="'+params.chips+'" title="',params.name,'">',link,'</div>'
                    ];
                break;
            }

            // A single array join is faster than
            // multiple concatenations

            return arr.join('');

        },

        // The addChatLine method ads a chat entry to the page
        addChatLine : function(params){

            // All times are displayed in the user's timezone

            var d = new Date();
            if(params.time) {

                // PHP returns the time in UTC (GMT). We use it to feed the date
                // object and later output it in the user's timezone. JavaScript
                // internally converts it for us.

                d.setUTCHours(params.time.hours,params.time.minutes);
            }

            params.time = (d.getHours() < 10 ? '0' : '' ) + d.getHours()+':'+
                          (d.getMinutes() < 10 ? '0':'') + d.getMinutes();

            if(!App.chat.data.lastID){
                // If this is the first chat, remove the
                // paragraph saying there aren't any:

                $('#chatLineHolder p').remove();
            }

            exists = $('#chatLineHolder .chat-'+params.id);
            if(!exists.length){
                markup = App.chat.render('chatLine',params);
                App.chat.data.jspAPI.getContentPane().append(markup);
                //new line added - play sound if defined

                if(App.chat.sounds.container && App.chat.sounds.new_chat && !App.chat.data.disable_line_sound){

                    $("#"+App.chat.sounds.container).attr("src", App.chat.sounds.new_chat);
                    thissound = document.getElementById(App.chat.sounds.container);
                    thissound.play();


                }
                // As we added new content, we need to
                // reinitialise the jScrollPane plugin:

                App.chat.data.jspAPI.reinitialise();
                App.chat.data.jspAPI.scrollToBottom(true);
            }

            App.chat.setUserFontStyle(params.chat_font)
        },

        addChatUser : function(params){

            App.chat.setUserFontStyle(params.chat_font);

            if(params.chat_type != 'spy'){


                //add user for usernames
                App.chat.userNames.push(params);

                if(eval('App.chat.users.'+params.id_user)){//user exists at position exists_in_array

                    if(eval('App.chat.users.'+params.id_user+'.favorite!=params.favorite')){//refresh info

                        //if is user - do not show self in chat users list
                        if((App.chat.data.user_id == "model_"+ App.chat.data.model_id) && (App.chat.data.user_id!=params.id_user)){
                            $('#chatUsers #user-'+params.id_user).remove();
                            markup = App.chat.render('user',params);
                            App.chat.data.jspAPI_users.getContentPane().append(markup);
                        }

                        eval('delete App.chat.users.'+params.id_user+';');
                        eval('App.chat.users.'+params.id_user+'=params;');

                    }
                }else{

                    if(eval('App.chat.users.'+params.id_user)){
                        eval('delete App.chat.users.'+params.id_user+';');
                        $('#chatUsers #user-'+params.id_user).remove();
                    }
                }
              if(!eval('App.chat.users.'+params.id_user)){

                    //if is user - do not show self in chat users list
                    if(((App.chat.data.user_id != "model_"+App.chat.data.model_id)
                        && (App.chat.data.user_id!=params.id_user))
                        ||(App.chat.data.user_id == "model_"+App.chat.data.model_id)
                    ){
                        markup = App.chat.render('user',params);
                        App.chat.data.jspAPI_users.getContentPane().append(markup);;
                    }

                    App.chat.users[params.id_user] = params;
                     //eval('App.chat.users.'+params.id_user+'=params;');
                    //new user in - play sound if defined



                    if(App.chat.sounds.container && App.chat.sounds.user_in && !App.chat.data.disable_user_sound){

                        $("#"+App.chat.sounds.container).attr("src", App.chat.sounds.user_in);
                        thissound = document.getElementById(App.chat.sounds.container);
                        thissound.play();

                    }

                }

                if(eval('App.chat.users.'+params.id_user)
                    && ((App.chat.data.user_id != "model_"+App.chat.data.model_id)
                    && (App.chat.data.user_id!=params.id_user)
                    || (App.chat.data.user_id == "model_"+App.chat.data.model_id))
                ){
                    // As we added new content / modified existing content, we need to
                    // reinitialise the jScrollPane plugin:

                    App.chat.data.jspAPI_users.reinitialise();
                    App.chat.data.jspAPI_users.scrollToBottom(true);

                }
            }
        },

        setUserFontStyle : function (chat_font) {

            if (!chat_font ||  typeof chat_font == 'undefined' ) return false;
            fontStyle = JSON.parse(chat_font);

            if (fontStyle.hasOwnProperty('user_id')) {
                $('.chat.user-' + fontStyle.user_id + ' .text').css("font-family", fontStyle.fontName);
            }
            if(fontStyle.hasOwnProperty('color'))
            {
                $('.chat.user-' + fontStyle.user_id + ' .text').css("color", fontStyle.color);
            }
            if (fontStyle.hasOwnProperty('fontWeight')) {
                $('.chat.user-' + fontStyle.user_id + ' .text').css("font-weight", fontStyle.fontWeight);
            }
            if (fontStyle.hasOwnProperty('fontItalic')) {
                $('.chat.user-' + fontStyle.user_id + ' .text').css("font-style", fontStyle.fontItalic);
            }

        },
        removeChatUser : function(params, user_list){

            exists = -1;
            for (j=0; j<user_list.length; j++){
                if(params.id_user == user_list[j].id_user){
                    exists = j;
                    break;
                }
            }

            if(exists == -1){

                $('#chatUsers #user-'+params.id_user).remove();
                eval('delete App.chat.users.'+params.id_user+';')//user out - play sound if defined

                if(App.chat.sounds.container && App.chat.sounds.user_out && !App.chat.data.disable_user_sound){

                    $("#"+App.chat.sounds.container).attr("src", App.chat.sounds.user_out);
                    thissound = document.getElementById(App.chat.sounds.container);
                    thissound.play();

                }
                // As we added new content, we need to
                // reinitialise the jScrollPane plugin:

                App.chat.data.jspAPI_users.reinitialise();
                App.chat.data.jspAPI_users.scrollToBottom(true);

            }
        },

        // This method displays an error message on the top of the page:
        displayError : function(msg){
            var elem = $('<div>',{
                id        : 'chatErrorMessage',
                html    : msg
            });

            elem.click(function(){
                $(this).fadeOut(function(){
                    $(this).remove();
                });
            });

            setTimeout(function(){
                elem.click();
            },5000);

            elem.hide().appendTo('body').slideDown();
        },

        showEmoticons: function(){
            App.chat.hideStyles()
            $('#emoticonsContainer').css('visibility','visible');
            $('.tipEmoticons').css('visibility','visible').css("right", "132px");
            $('.smileyButton').css('background-position','left bottom');
        },

        hideEmoticons: function(){
            $('#emoticonsContainer').css('visibility','hidden');
            $('.tipEmoticons').css('visibility','hidden');
            $('.smileyButton').css('background-position','left top');

        },
        showStyles: function () {
            App.chat.hideEmoticons()
            $('#fontStyleContainer').css('visibility', 'visible');
            $('.tipEmoticons').css('visibility', 'visible').css("right", "165px");
            //  $('.smileyButton').css('background-position', 'left bottom');
        },
        hideStyles: function () {
            $('#fontStyleContainer').css('visibility', 'hidden');
            $('.tipEmoticons').css('visibility', 'hidden');
            //$('.smileyButton').css('background-position','left top');

        },
        submitNotes : function(notes, user){

            if(App.working) return false;
                App.working = true;

            App.request.submitnotes_user_id = user;
            App.request.submitnotes_notes = notes;
            App.request.submitNotes = true;

            App.working = false;

        },

        getNotes : function(user){

            if(App.working) return false;
                App.working = true;

            App.request.getnotes_user_id = user;
            App.request.getNotes = true;

            App.working = false;

        },

        initNotes : function(){

            // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
            $( "#dialog:ui-dialog" ).dialog( "destroy" );

            var notes = $( "#notes" ),
                id_user = $("#user_id"),
                allFields = $( [] ).add( notes ).add( id_user ),
                tips = $( ".validateTips" );

            function updateTips( t ) {
                tips
                    .text( t )
                    .addClass( "ui-state-highlight" );
                setTimeout(function() {
                    tips.removeClass( "ui-state-highlight", 1500 );
                }, 500 );
            }

            function checkLength( o, n, min) {
                if (o.val().length < min ) {
                    o.addClass( "ui-state-error" );
                    updateTips( "Length of " + n + " must be > " +
                        min);
                    return false;
                } else {
                    return true;
                }
            }

            function checkRegexp( o, regexp, n ) {
                if ( !( regexp.test( o.val() ) ) ) {
                    o.addClass( "ui-state-error" );
                    updateTips( n );
                    return false;
                } else {
                    return true;
                }
            }

            $( "#dialog-form" ).dialog({
                autoOpen: false,
                height: 300,
                width: 350,
                modal: true,
                zIndex: 9999,
                buttons: {
                    "Save notes": function() {
                        var bValid = true;
                        allFields.removeClass( "ui-state-error" );

                        bValid = bValid && checkLength( notes, "text", 1);

                        if ( bValid ) { //save notes (using ajaxcall)
                            App.working = false;
                            App.chat.submitNotes(notes.val(), id_user.val());
                            $( this ).dialog( "close" );

                        }
                    },
                    Cancel: function() {
                        $( this ).dialog( "close" );
                    }
                },
                close: function() {
                    allFields.val( "" ).removeClass( "ui-state-error" );
                    App.working = false;
                    App.chat.sendRequestTimeoutFunction();
                }
            });


        },

        initRequests : function(){

            // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
            $( "#dialog:ui-dialog" ).dialog( "destroy" );

            var id_user = $("#user_id_req"),
                allFields = $( [] ).add( id_user ),
                tips = $( ".validateTips" );

            function updateTips( t ) {
                tips
                    .text( t )
                    .addClass( "ui-state-highlight" );
                setTimeout(function() {
                    tips.removeClass( "ui-state-highlight", 1500 );
                }, 500 );
            }

            function checkLength( o, n, min) {
                if (o.val().length < min ) {
                    o.addClass( "ui-state-error" );
                    updateTips( "Length of " + n + " must be > " +
                        min);
                    return false;
                } else {
                    return true;
                }
            }

            function checkRegexp( o, regexp, n ) {
                if ( !( regexp.test( o.val() ) ) ) {
                    o.addClass( "ui-state-error" );
                    updateTips( n );
                    return false;
                } else {
                    return true;
                }
            }

            $( "#dialog-form-request" ).dialog({
                autoOpen: false,
                height: 150,
                width: 350,
                modal: true,
                zIndex: 9999,
                buttons: {
                    "Accept request": function() {
                        var bValid = true;
                        allFields.removeClass( "ui-state-error" );

                        if ( bValid ) { //save request notes (using ajaxcall)
                            if(App.chat.data.request_type == 'private')
                                App.model.private.acceptPrivateRequest();
                            else if(App.chat.data.request_type == 'vip'){
                                App.model.vip.acceptVipRequest();
                            }
                            else if(App.chat.data.request_type == 'group'){
                                App.model.group.acceptGroupRequest();
                            }

                            $( this ).dialog( "close" );
                        }
                    },
                    "Deny request": function() {
                        var bValid = true;
                        allFields.removeClass( "ui-state-error" );

                        if ( bValid ) { //save request notes (using ajaxcall)

                            if(App.chat.data.chat_type == 'private')
                                App.model.private.denyPrivateRequest();
                            else if(App.chat.data.chat_type == 'vip')
                                App.model.vip.denyVipRequest();
                            else if(App.chat.data.chat_type == 'group')
                                App.model.group.denyGroupRequest();

                            $( this ).dialog( "close" );
                        }
                    }
                },
                dialogClass: 'no-close'

            });

        },

        initExit : function(){

            $('#stop_chat').click(function(){

                    if(App.working) return false;
                        App.working = true;

                    App.request.logout = true;
                    App.working = false;

                    return false;
                });

            window.onbeforeunload = function(event){

                if(App.chat.data.model_id == 'model_'+App.chat.data.user_id) {
                    message = "Leaving page ... broadcast will be stopped";
                }
                else {
                    message = 'Leaving page ... chat session will be stopped';
                }
                event = event || window.event;


                    if(event){
                            event.returnValue = message;
                    }
                    return message;

            };


            /*
                $(window).bind('unload', function(event){
                    setTimeout(
                    $.ajax({
                    type: "POST",
                    cache: false,
                    url: "/process",
                    data: {logout : true, model_id : App.chat.data.model_id, action : 'request'},
                    dataType: "json",
                    async: false
                }), 500);

                });
              */

        },

        initSounds : function(sounds_container, sounds_list){

            App.chat.sounds = sounds_list;
            App.chat.sounds.container = sounds_container;

        },

        saveStyles : function(){
            spinner.appendSpinner($('#save-font-btn').parent());

            $("#save-font-btn").prop('disabled', true);

            fontStyle = $("#fontStyleForm").serializeObject();
            $.cookie.json = true;
            $.cookie("chat_style", JSON.stringify(fontStyle), { path: '/', expires: 365});

            fontName = "Arial";
            if(fontStyle.hasOwnProperty('font')) {
                switch(fontStyle.font) {
                    case 'courier':
                        fontName = 'Courier New';
                        break;
                    case 'arial':
                        fontName = 'Arial';
                        break;
                    case 'comic':
                        fontName = 'Comic Sans';
                        break;
                }

                $('.chat.user-' + fontStyle.user_name + ' .text').css("font-family", fontName);
            }
            if(fontStyle.hasOwnProperty('color')) {

                $('.chat.user-' + fontStyle.user_name + ' .text').css("color", fontStyle.color);
            }
            if(fontStyle.hasOwnProperty('fontWeight')) {
                $('.chat.user-' + fontStyle.user_name + ' .text').css("font-weight", fontStyle.fontWeight);
            }
            if(fontStyle.hasOwnProperty('fontItalic')) {
                $('.chat.user-' + fontStyle.user_name + ' .text').css("font-style", fontStyle.fontItalic);
            }
            fontStyle.fontName =  fontName;
            // Assign handlers immediately after making the request,
// and remember the jqXHR object for this request

            var jqxhr = $.ajax({
                url : '/processAjax/saveStyle',
                method: 'POST',
                data: fontStyle
            })
                .done(function () {
                   App.chat.hideStyles();
                })
                .fail(function () {
                   // alert("error");
                })
                .always(function () {
                    spinner.removeOverlay();
                    App.chat.hideStyles();
                    $("#save-font-btn").prop('disabled', false);
                   // alert("complete");
                });
        },
        atNamesChatRoom: function () {

            $("#chatText")
                .atwho({
                    at: "@",
                    insertTpl: '@${name} ',
                    displayTpl: '<li data-id="${id_user}">${name}</li>',
                    data: App.chat.userNames
                })

            $("#chatText").keypress(function (e) {

                var code = (e.keyCode ? e.keyCode : e.which);

                if (code == 13) {
                    e.preventDefault();
                    $("#submitForm").submit();
                    $("#chatText").val('');
                    return true;
                }
            });
        }
    },

    autoresponders : {

        editAutoResponders: function(field_id,id_question){

            $.ajax({
              type: "POST",
              cache: false,
              url: "/process",
              data: {
                message: $("#"+field_id).val(),
                id_question: id_question,
                field_id : field_id,
                action : 'submitAutoResponders'
              },

              dataType : "json",
              success: function(data){
                if(data.response == 'ok'){
                    $('.'+field_id).html("Saved");
                    $('.'+field_id).addClass("saved");

                    if( field_id.indexOf("answer-0-") > -1){
                        new_field_id = "answer-"+data.answer_id;
                        $("#"+field_id).attr('name',new_field_id);
                        $("#"+field_id).attr('id',new_field_id);
                        on = $(".savelink."+field_id).attr("onclick");
                        on_new = on.replace(field_id, new_field_id);
                        $(".savelink."+field_id).attr("onclick", on_new);
                        $(".savelink."+field_id).removeClass(field_id).addClass(new_field_id);
                        field_id = new_field_id;
                    }

                    if( field_id.indexOf("question-0-") > -1){
                        new_field_id =  "question-"+data.id_question;
                        $("#"+field_id).attr('name',new_field_id);
                        $("#"+field_id).attr('id',new_field_id);
                        on = $(".savelink."+field_id).attr("onclick");
                        on_new = on.replace(field_id, new_field_id);
                        $(".savelink."+field_id).attr("onclick", on_new);
                        $(".savelink."+field_id).removeClass(field_id).addClass(new_field_id);
                        //$("#form-"+field_id).attr("id", new_field_id);
                        field_id = new_field_id;
                    }
                    setTimeout('App.autoresponders.reset_save_btn("'+field_id+'")', 3000);
                    return true;
                }
                if(data.response == 'deleted'){
                    //$('.'+field_id).after('<span class="savelink_delete">deleted</span>');
                     if( field_id.indexOf("answer-") > -1){
                        $('#'+field_id).parent().parent().remove();
                     } else if( field_id.indexOf("question-") > -1) {
                         $('#'+field_id).parents().eq(3).remove();
                     }
                    return true;
                }

                if(data.response == 'question_adeed' && data.id_question>0){
                    //rebuild form with id_question and add answers fields

                    $('#form-'+field_id).remove();

                    var form = document.createElement('form');
                    form.setAttribute("class", "form_autoresponders");
                    form.innerHTML = '<br><div class="join-box-fields"> \
                        <div class="field">\
                        <label for="question-'+data.id_question+'">Question</label> \
                        <div class="field_wrapper">\
                        <input type="text" value="'+data.message+'" class="ss_field" id="question-'+data.id_question+'" name="question-'+data.id_question+'"></div></div> \
                        <a class="savelink question-'+data.id_question+'" href="javascript:;" onclick="App.autoresponders.editAutoResponders(\'question-'+data.id_question+'\',\'0\')">Save</a>\
                        <div class="field">\
                       <label for="">Auto Response</label> \
                       <div class="field_wrapper">\
                       <input type="text" value="" class="ss_field" id="answer-0-'+App.autoresponders.i+'" name="answer-0-'+App.autoresponders.i+'"></div><a class="savelink answer-0-'+App.autoresponders.i+'" href="javascript:;" onclick="App.autoresponders.editAutoResponders(\'answer-0-'+App.autoresponders.i+'\','+data.id_question+')">Save</a></div> \
                       <a href="javascript:;" title="Add a new response" class="add_new_answer" id="add_new_answer-'+data.id_question+'" onclick="App.autoresponders.addNewAnswerField('+data.id_question+')">Add Answer</a> \
                       </div><input type="hidden" value="'+data.id_question+'" name="id_question">';

                    $('#add_new_question_form_mark').after(form);

                    App.autoresponders.setAutoComplete('#question-'+data.id_question);
                    App.autoresponders.setAutoComplete('#answer-0-'+App.autoresponders.i);
                }
             }

            });
            return false;
        },



        reset_save_btn: function(field_id){
            $('.'+field_id).html("Save");
            $('.'+field_id).removeClass("saved");
        },

        i : 100,

        addNewAnswerField: function(id_question){
            App.autoresponders.i++;

            var div = document.createElement('div');
            div.innerHTML = '<div class="field"><label for="">&nbsp;</label> <div class="field_wrapper"><input type="text" value="" class="ss_field" id="answer-0-'+App.autoresponders.i+'" name="answer-0-'+App.autoresponders.i+'"> </div><a class="savelink answer-0-'+App.autoresponders.i+'" href="javascript:;" onclick="App.autoresponders.editAutoResponders(\'answer-0-'+App.autoresponders.i+'\','+id_question+')">Save</a></div>';

            $('#add_new_answer-'+id_question).before(div);

            App.autoresponders.setAutoComplete('#answer-0-'+App.autoresponders.i);
        },

        addNewQuestionForm: function(){
            App.autoresponders.i++;

            var form = document.createElement('form');
            form.setAttribute("class", "form_autoresponders");
            form.setAttribute("id", "form-question-0-"+App.autoresponders.i);
            form.innerHTML = '<br><div class="join-box-fields"> \
                        <div class="field">\
                        <label for="question-0-'+App.autoresponders.i+'">Question</label> \
                        <div class="field_wrapper">\
                        <input type="text" value="" class="ss_field" id="question-0-'+App.autoresponders.i+'" name="question-0-'+App.autoresponders.i+'"> </div><a class="savelink question-0-'+App.autoresponders.i+'" href="javascript:;" onclick="App.autoresponders.editAutoResponders(\'question-0-'+App.autoresponders.i+'\',\'0\')">Save</a></div><br>';

            $('#add_new_question_form_mark').after(form);

            App.autoresponders.setAutoComplete('#question-0-'+App.autoresponders.i);
        },

        setAutoComplete: function(obj){
            $(obj).autocomplete({
                serviceUrl:'/process',
                minChars:2,
                delimiter: /(,|;)\s*/, // regex or character
                maxHeight:400,
                //width:480,
                width:$(obj).width()+20,
                zIndex: 9999,
                deferRequestBy: 10, //miliseconds
                noCache: false, //default is false, set to true to disable caching
                params: { ttype:'a',action: 'suggestAutoResponders' } //aditional parameters

                // callback function:
                //onSelect: function(value, data){ alert('You selected: ' + value + ', ' + data); },
              });
        }
    },

    notes : {

        editNotes: function(user_id){

            $.ajax({
              type: "POST",
              cache: false,
              url: "/process",
              data: {
                message: $("#"+user_id).val(),
                id_user: user_id.substr(user_id.indexOf("_")+1),
                type: user_id.substr(0, user_id.indexOf("_")),
                action : 'saveNotes'
              },

              dataType : "json",
              success: function(data){
                if(data.response == 'ok'){
                    $('.'+user_id).html("Saved");
                    $('.'+user_id).addClass("saved");
                    $('#last_edit_'+user_id).html("just now");
                    setTimeout('App.notes.reset_save_btn("'+user_id+'")', 3000);
                    return true;
                }
                if(data.response == 'deleted'){
                    $('#form_'+user_id).remove();
                    $('.'+user_id).after('<span class="savelink_notes_delete">deleted</span>');
                    $('.'+user_id).remove();
                    return true;
                }

             }

            });
            return false;
        },

        reset_save_btn: function(field_id){
            $('.'+field_id).html("Save");
            $('.'+field_id).removeClass("saved");
        }

    },

    // Custom GET & POST wrappers:

    tzPOST : function(action,data,callback){
        data.action = action;
        $.post('/process', data, callback, 'json');
    },

    tzGET : function(action,data,callback){
        data.action = action;
        $.get('/process', data, callback, 'json');
    },


    analytics: {
        /**
        * trackRole -  urmareste visitatori si membri
        *
        * @param role
        */
        trackUserRole: function(role, name){
            _gaq.push(['_setCustomVar',
              1,             // This custom var is set to slot #1.  Required parameter.
              role,   // The name of the custom variable.  Required parameter.
              name,      // Sets the value of "User Type" to "Member" or "Visitor" depending on status.  Required parameter.
               2             // Sets the scope to session-level.  Optional parameter.
           ]);
        },
         /**
         * trackplay  - urmareste timpul pentru chat broadcast
         *
         * @param userRole      : visitator, member, model, admin, studio
         * @param chatType      :  normal, spy, private, free
         * @param actionType    : play/stop
         * @param label         : model name
         * @param time          : time in seconds
         */
        trackBroadcast: function(userRole, chatType, actionType, label, timeSpent){

            //_gaq.push(['_trackEvent', 'Videos', 'Video Load Time', 'Gone With the Wind', downloadTime]);
            //disabled for test anak// _gaq.push(['_trackEvent', userRole+' '+chatType+' Chat', actionType, 'Model: ' + label, timeSpent]);
        }

    }
};

// A custom jQuery method for placeholder text:
$.fn.defaultText = function(value){

            var element = this.eq(0);
            element.data('defaultText',value);

            element.focus(function(){
                if(element.val() == value){
                    element.val('').removeClass('defaultText');
                }
            }).blur(function(){
                if(element.val() == '' || element.val() == value){
                    element.addClass('defaultText').val(value);
                }
            });

            return element.blur();
        }

$(document).ready(function() {

    //image zoom on hover
    $(function(){
       $('.zoom').hover(
           function(){

               $(this).css('z-index','10').stop().animate({
                   marginTop: '-20px',
                   marginLeft: '-20px',
                   width: '200px',
                   height: '150px'
               }, 200);
           },
           function() {
               $(this).stop().animate({
                   marginTop: '0px',
                   marginLeft: '0px',
                   height: '105px',
                   width: '144px'

               }, 200).css('z-index','0');
          });
     });


    //for autoresponders
    $('.ss_field').each(function(index, obj){
         App.autoresponders.setAutoComplete(obj);
    });

    $("#sendto").autocomplete({
        serviceUrl:'/process',
        minChars:2,
        delimiter: /(,|;)\s*/, // regex or character
        maxHeight:400,
        width:493,
        zIndex: 9999,
        deferRequestBy: 10, //miliseconds
        noCache: false, //default is false, set to true to disable caching
        params: { ttype:'a',action: 'suggestSendToModel' } //aditional parameters
    });

    $("#sendtouser").autocomplete({
        serviceUrl:'/process',
        minChars:2,
        delimiter: /(,|;)\s*/, // regex or character
        maxHeight:400,
        width:493,
        zIndex: 9999,
        deferRequestBy: 10, //miliseconds
        noCache: false, //default is false, set to true to disable caching
        params: { ttype:'a',action: 'suggestSendToUser' } //aditional parameters
    });

    //forms validation
    $.validator.setDefaults({
    });

    $.validator.addMethod("loginRegex", function(value, element) {
        return this.optional(element) || /^[a-z0-9\-_]+$/i.test(value);
    }, "Username must contain only letters, numbers, dashes or underscore.");

    $.validator.addMethod("nickRegex", function(value, element) {
        return this.optional(element) || /^[a-z0-9\-_ ]+$/i.test(value);
    }, "Nickname must contain only letters, numbers, dashes, spaces or underscore.");

    $("#form_profile_settings").validate({
        groups: {
            dateOfBirth: "birthday_month birthday_day birthday_year"
        },
        rules: {
            screen_name: {
                required: true,
                remote: {
                  type: "POST",
                  cache: false,
                  url: "/process",
                  data: {
                    action : 'checkScreenName',
                    id : ($("#model_id") ? $("#model_id").val() : null)

                  },
                  complete: function(data){
                    if(data.responseText == 'false') {
                        screenNameSuggest('#screen_name');
                    }else{
                        $('#theresults').remove();
                    }
                  }
                },
                minlength: 3,
                maxlength: 25,
                loginRegex: true
            },
            about_me:{
                required: true
            },
            hair_color:{
                required: true
            },
            orientation:{
                required: true
            },
            birthday_month: {
                required: true
            },
            birthday_day: {
                required: true
            },
            birthday_year: {
                required: true
            },
            country: {
                required: true
            }

        },
        messages: {
            screen_name: {
                required: "Please enter a screen name",
                minlength: "Your screen name must consist of at least 3 characters",
                maxlength: "Your screen name must be max 15 characters",
                remote : "This screen name is already in use ! We have some suggestion for you."
            },
            birthday_month : {
                required: "Please enter a valid date"
            },
            birthday_day : {
                required: "Please enter a valid date"
            },
            birthday_year : {
                required: "Please enter a valid date"
            },
            country : {
                required: "Please choose your country"
            }
        },
        errorPlacement: function(error, element) {
         if (element.attr("name") == "birthday_month" || element.attr("name") == "birthday_day" || element.attr("name") == "birthday_year") error.insertAfter("#Birth");
         else error.insertAfter(element);
        }
    });

    $("#form_profile_settings_user").validate({
        groups: {
            dateOfBirth: "birthday_month birthday_day birthday_year"
        },
        rules: {
            nickname: {
                required: true,
                remote: {
                  type: "POST",
                  cache: false,
                  url: "/process",
                  data: {
                    action : 'checkNickName'
                  },
                  complete: function(data){
                    if(data.responseText == 'false') {
                        nickNameSuggest('#nickname');
                    }else{
                        $('#theresults').remove();
                    }
                  }
                },
                minlength: 3,
                maxlength: 25,
                nickRegex: true
            },

            birthday_month: {
                required: true
            },
            birthday_day: {
                required: true
            },
            birthday_year: {
                required: true
            },
            country: {
                required: true
            }

        },
        messages: {
            nickname: {
                required: "Please enter a nickname",
                minlength: "Your nickname must consist of at least 3 characters",
                maxlength: "Your nickname must be max 15 characters",
                remote : "This nickname is already in use ! We have some suggestion for you."
            },
            birthday_month : {
                required: "Please enter a valid date"
            },
            birthday_day : {
                required: "Please enter a valid date"
            },
            birthday_year : {
                required: "Please enter a valid date"
            },
            country : {
                required: "Please choose your country"
            }
        },
        errorPlacement: function(error, element) {
         if (element.attr("name") == "birthday_month" || element.attr("name") == "birthday_day" || element.attr("name") == "birthday_year") error.insertAfter("#Birth");
         else error.insertAfter(element);
        }
    });

    $("#form_profile_settings_admin_user").validate({
        groups: {
            dateOfBirth: "birthday_month birthday_day birthday_year"
        },
        rules: {
            nickname: {
                required: true,
                remote: {
                  type: "POST",
                  cache: false,
                  url: "/process",
                  data: {
                    action : 'checkNickName',
                    id : ($("#user_id") ? $("#user_id").val() : null)

                  },
                  complete: function(data){
                    if(data.responseText == 'false') {
                        nickNameSuggest('#nickname');
                    }else{
                        $('#theresults').remove();
                    }
                  }
                },
                minlength: 3,
                maxlength: 25,
                nickRegex: true
            },

            birthday_month: {
                required: true
            },
            birthday_day: {
                required: true
            },
            birthday_year: {
                required: true
            },
            country: {
                required: true
            }

        },
        messages: {
            nickname: {
                required: "Please enter a nickname",
                minlength: "Your nickname must consist of at least 3 characters",
                maxlength: "Your nickname must be max 15 characters",
                remote : "This nickname is already in use ! We have some suggestion for you."
            },
            birthday_month : {
                required: "Please enter a valid date"
            },
            birthday_day : {
                required: "Please enter a valid date"
            },
            birthday_year : {
                required: "Please enter a valid date"
            },
            country : {
                required: "Please choose your country"
            }
        },
        errorPlacement: function(error, element) {
         if (element.attr("name") == "birthday_month" || element.attr("name") == "birthday_day" || element.attr("name") == "birthday_year") error.insertAfter("#Birth");
         else error.insertAfter(element);
        }
    });

    $.validator.addMethod('SSNChecker', function(value) {
            ssn_addr = "^(?!000)(?!666)(?!9)[0-9]{3}[ -]?(?!00)[0-9]{2}[ -]?(?!0000)[0-9]{4}$";
                if(($('#country').children()[parseInt($('#country').val())].label == 'United States')) return value.match(ssn_addr);
                else return true;
            }, 'SSN Invalid');

    $("#form_account_settings").validate({
        rules: {
            first_name: {
                required: true
            },
            name: {
                required: true
            },
            password: {
                minlength: 4
            },
            confirm_password: {
                minlength: 4,
                equalTo: "#password"
            },
            email: {
                required: true,
                email: true,
                remote: {
                  type: "POST",
                  cache: false,
                  url: "/process",
                  data: {
                    action : 'checkUniqueEmail',
                    id : ($("#model_id") ? $("#model_id").val() : null)
                  }
                }
            },
            ssn:{
                SSNChecker:true
            }
        },
        messages: {
            email: {
                required: "Please enter a valid email address",
                email: "Please enter a valid email address",
                remote : "This email address is already in use"
            },
            ssn: {
                SSNChecker: "Please enter a SSN."
            }
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        }
    });

    $("#form_add_model").validate({
        rules: {
            screen_name: {
                required: true,
                remote: {
                  type: "POST",
                  cache: false,
                  url: "/process",
                  data: {
                    action : 'checkScreenName'

                  },
                  complete: function(data){
                    if(data.responseText == 'false') {
                        screenNameSuggest('#screen_name');
                    }else{
                        $('#theresults').remove();
                    }
                  }
                },
                minlength: 3,
                maxlength: 25,
                loginRegex: true
            },
            password: {
                minlength: 4
            },
            confirm_password: {
                minlength: 4,
                equalTo: "#password"
            },
            email: {
                required: true,
                email: true,
                remote: {
                  type: "POST",
                  cache: false,
                  url: "/process",
                  data: {
                    action : 'checkUniqueEmail',
                    id : ($("#model_id") ? $("#model_id").val() : null)
                  }
                }
            }
        },
        messages: {
            email: {
                required: "Please enter a valid email address",
                email: "Please enter a valid email address",
                remote : "This email address is already in use"
            },
            screen_name: {
                required: "Please enter a screen name",
                minlength: "Your screen name must consist of at least 3 characters",
                maxlength: "Your screen name must be max 15 characters",
                remote : "This screen name is already in use ! We have some suggestion for you."
            }
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        }
    });

    $("#form_account_settings_admin").validate({
        rules: {
            first_name: {
                required: true
            },
            password: {
                minlength: 4
            },
            confirm_password: {
                minlength: 4,
                equalTo: "#password"
            },
            name: {
                required: true
            },
            email: {
                required: true,
                email: true,
                remote: {
                  type: "POST",
                  cache: false,
                  url: "/process",
                  data: {
                    action : 'checkUniqueEmail',
                    id : ($("#model_id") ? $("#model_id").val() : null)
                  }
                }
            },
            ssn:{
                SSNChecker:true
            }
        },
        messages: {
            email: {
                required: "Please enter a valid email address",
                email: "Please enter a valid email address",
                remote : "This email address is already in use"
            },
            ssn: {
                SSNChecker: "Please enter a SSN."
            }
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        }
    });

    $("#form_account_settings_admin_user").validate({
        rules: {
            email: {
                required: true,
                email: true,
                remote: {
                  type: "POST",
                  cache: false,
                  url: "/process",
                  data: {
                    action : 'checkUserUniqueEmail',
                    id : ($("#user_id") ? $("#user_id").val() : null)
                  }
                }
            }
        },
        messages: {
            email: {
                required: "Please enter a valid email address",
                email: "Please enter a valid email address",
                remote : "This email address is already in use"
            }
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        }
    });

     $("#form_add_user").validate({
        rules: {
            username: {
                required: true,
                minlength: 3,
                maxlength: 25,
                loginRegex: true,
                remote: {
                  type: "POST",
                  cache: false,
                  url: "/process",
                  data: {
                    action : 'checkUserName'
                  }
                }
            },
            password: {
                minlength: 4
            },
            confirm_password: {
                minlength: 4,
                equalTo: "#password"
            },
            email: {
                required: true,
                email: true,
                remote: {
                  type: "POST",
                  cache: false,
                  url: "/process",
                  data: {
                    action : 'checkUserUniqueEmail'
                  }
                }
            }
        },
        messages: {
            username: {
                required: "Please enter a username",
                minlength: "Your username must consist of at least 3 characters",
                maxlength: "Your username must be max 15 characters",
                remote : "This username is already in use"
            },
            email: {
                required: "Please enter a valid email address",
                email: "Please enter a valid email address",
                remote : "This email address is already in use"
            }
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        }
    });

    $("#form_add_moderator").validate({
        rules: {
            username: {
                required: true,
                minlength: 3,
                maxlength: 25,
                loginRegex: true,
                remote: {
                  type: "POST",
                  cache: false,
                  url: "/process",
                  data: {
                    action : 'checkModeratorUserName'
                  }
                }
            },
            password: {
                minlength: 4
            },
            confirm_password: {
                minlength: 4,
                equalTo: "#password"
            },
            email: {
                required: true,
                email: true,
                remote: {
                  type: "POST",
                  cache: false,
                  url: "/process",
                  data: {
                    action : 'checkModeratorUniqueEmail',
                    ignore_logged : true
                  }
                }
            }
        },
        messages: {
            email: {
                required: "Please enter a valid email address",
                email: "Please enter a valid email address",
                remote : "This email address is already in use"
            },
            username: {
                required: "Please enter a username",
                minlength: "Your username must consist of at least 3 characters",
                maxlength: "Your username must be max 15 characters",
                remote : "This username is already in use"
            }
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        }
    });

    $("#form_account_settings_admin_moderator").validate({
        rules: {
            email: {
                required: true,
                email: true,
                remote: {
                  type: "POST",
                  cache: false,
                  url: "/process",
                  data: {
                    action : 'checkModeratorUniqueEmail',
                    id : ($("#moderator_id") ? $("#moderator_id").val() : null)
                  }
                }
            }
        },
        messages: {
            email: {
                required: "Please enter a valid email address",
                email: "Please enter a valid email address",
                remote : "This email address is already in use"
            }
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        }
    });

    $.validator.addMethod("checkRates", function(value, element) {

        if(parseInt($("#special_rate").val())!=0 && value != "") return $("#special_rate").children()[$("#special_rate").val()].getAttribute("min")<=parseInt(value);
        else return true;

    }, "The requested rate must be greater than the previous maximum limit.");

    $("#form_model_rates").validate({
        rules: {
            special_rate_value: {
                digits: true,
                checkRates: true

            }

        },
        messages: {
            special_rate_value: {
                checkRates: "The requested rate must be greater than the previous maximum limit.",
                digits: "Please enter a valid number."
            }
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        }
    });

    $.validator.addMethod('IP4Checker', function(value) {
            ip_addr = "^([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])$";
                if(value) return value.match(ip_addr);
                else return true;
            }, 'Invalid IP address');

    $("#form_system_logs").validate({
        rules: {

            date: {
                date: true
            },
            ip:{
                IP4Checker:true
            }

        },
        messages: {
            date: {
                date: "Please enter a valid date: d/m/Y."
            },
            ip: {
                IP4Checker: "Please enter a valid ip address."
            }
        },
        errorPlacement: function(error, element) {
            error.insertAfter($(".filter_button"));
        }
    });

//    $("#form_signup").validate({
//        groups: {
//            dateOfBirth: "birthday_month birthday_day birthday_year"
//        },
//        rules: {
//            username: {
//                required: true,
//                minlength: 3,
//                maxlength: 25,
//                loginRegex: true,
//                remote: {
//                  type: "POST",
//                  cache: false,
//                  url: "/process",
//                  data: {
//                    action : 'checkUserName'
//                  }
//                }
//            },
//            password: {
//                required: true,
//                minlength: 4
//            },
//            confirm_password: {
//                required: true,
//                minlength: 4,
//                equalTo: "#password"
//            },
//            email: {
//                required: true,
//                email: true,
//                remote: {
//                  type: "POST",
//                  cache: false,
//                  url: "/process",
//                  data: {
//                    action : 'checkUserUniqueEmail'
//                  }
//                }
//            },
//            confirm_email: {
//                equalTo: "#email"
//            },
//            birthday_month: {
//                required: true
//            },
//            birthday_day: {
//                required: true
//            },
//            birthday_year: {
//                required: true
//            },
//            country: {
//                required: true
//            }
//        },
//        messages: {
//            username: {
//                required: "Please enter a username",
//                minlength: "Your username must consist of at least 3 characters",
//                maxlength: "Your username must be max 15 characters",
//                remote : "This username is already in use"
//            },
//            email: {
//                required: "Please enter a valid email address",
//                email: "Please enter a valid email address",
//                remote : "This email address is already in use"
//            },
//            birthday_month : {
//                required: "Please enter a valid date"
//            },
//            birthday_day : {
//                required: "Please enter a valid date"
//            },
//            birthday_year : {
//                required: "Please enter a valid date"
//            },
//            country : {
//                required: "Please choose your country"
//            }
//        },
//        errorPlacement: function(error, element) {
//         if (element.attr("name") == "birthday_month" || element.attr("name") == "birthday_day" || element.attr("name") == "birthday_year") error.insertAfter("#Birth");
//         else error.insertAfter(element);
//        }
//    });
//
    $("#pwreset").validate({
        rules: {
            password: {
                required: true,
                minlength: 4
            },
            confirm_password: {
                required: true,
                minlength: 4,
                equalTo: "#password"
            }
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        }
    });

    $("#form_signup_step1").validate({
        rules: {
            first_name: {
                required: true,
                minlength: 2,
                maxlength: 25
            },
            name: {
                required: true,
                minlength: 2,
                maxlength: 25
            },
            password: {
                required: true,
                minlength: 4
            },
            confirm_password: {
                minlength: 4,
                equalTo: "#password"
            },
            email: {
                required: true,
                email: true,
                remote: {
                  type: "POST",
                  cache: false,
                  url: "/process",
                  data: {
                    action : 'checkUniqueEmail'
                  }
                }
            },
            terms: {
                required: true
            },
            confirm_email: {
                equalTo: "#email"
            }
        },
        messages: {
            email: {
                required: "Please enter a valid email address",
                email: "Please enter a valid email address",
                remote : "This email address is already in use"
            },
            terms: {
                required: "You must agree to the terms and conditions!"
            }
        },
        errorPlacement: function(error, element) {
         if (element.attr("name") == "terms") error.insertAfter("#Terms");
         else error.insertAfter(element);
        }
    });


    $("#form_contact").validate({
        rules: {
            message: {
                required: true,
                minlength: 5
            },
            username: {
                required: true,
                minlength: 2
            },
            captcha: {
                required: true,
                minlength: 6,
                maxlength: 6,
                remote: {
                  type: "POST",
                  cache: false,
                  url: "/process",
                  data: {
                    action : 'checkCapchaContact'
                  }
                }
            },
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            email: {
                required: "Please enter an email address",
                email: "Please enter a valid email address"
            },
            message: {
                required: "Please enter a message"
            },
            username: {
                required: "Please enter a valid username"
            },
            captcha: {
                required: "You must provide the verification!",
                remote : "Incorrect catcha, please try again"
            }
        },
        errorPlacement: function(error, element) {
         if (element.attr("name") == "captcha") error.insertAfter("#captcha_img");
         else error.insertAfter(element);
        }
    });


    $("#messages_compose").validate({
        rules: {
            to: {
                required: true
            },
            subject: {
                required: true
            },
            message: {
                required: true
            }
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        }
    });

    $("#form_rule_edit").validate({
        rules: {
            title: {
                required: true,
                minlength: 4

            },
            fine : {
                digits: true,
                required: false
            }

        },
        messages: {
            title: {
                required: 'Please enter a title',
                minlength: 'Please enter a title'

            },
            fine : {
                digits: 'Pease enter a valid number'
            }
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        }
    });

    $("#form_rule_add").validate({
        rules: {
            title: {
                required: true,
                minlength: 4

            },
            fine : {
                digits: true,
                required: false
            }

        },
        messages: {
            title: {
                required: 'Please enter a title',
                minlength: 'Please enter a title'

            },
            fine : {
                digits: 'Pease enter a valid number'
            }
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        }
    });

   //clock pe footer
   setInterval('updateClock()', 1000);
   setInterval('updateSiteClock()', 1000);

});

// screen name suggest alternate name
function screenNameSuggest(obj){
    $.ajax({
        type: "POST",
        cache: false,
        url: "/process",
        data: {
            query : $(obj).val(),
            action : 'suggestScreenName'
        },
        dataType : "json",
        success: function(data){
            $('#scrname').after(data.suggestions);

        }
    });
}

// nickname suggest alternate name
function nickNameSuggest(obj){
    $.ajax({
        type: "POST",
        cache: false,
        url: "/process",
        data: {
            query : $(obj).val(),
            action : 'suggestNickName'
        },
        dataType : "json",
        success: function(data){
            $('#nick_name').after(data.suggestions);

        }
    });
}

function addNickname(screen_name){
    $('#nickname').val(screen_name);
    $('#theresults').remove();
}

function addScreenname(screen_name){
    $('#screen_name').val(screen_name);
    $('#theresults').remove();
}

function onWithdrawTypeChanged(withdrawTypeSelect,rowName){
    var rows=document.getElementsByName(rowName);
    var withdrawType=withdrawTypeSelect.options[withdrawTypeSelect.selectedIndex].value;
    var i;
    for(i=0;i<rows.length;i++){
        var withdraw=rows[i].getAttribute("withdraw");
        if(withdraw!=null){
            if(withdraw==withdrawType){
                rows[i].style.display="";
            }else{
                rows[i].style.display="none"
            }
        }
    }
}

function isNumeric(evt){
     var charCode = (evt.which) ? evt.which : event.keyCode
     if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;

     return true;
}

function updateClock (){
    var currentTime = new Date ( );
    var currentHours = currentTime.getHours ( );
    var currentMinutes = currentTime.getMinutes ( );
    var currentSeconds = currentTime.getSeconds ( );

    // Pad the minutes and seconds with leading zeros, if required
    currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
    currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;

    // Choose either "AM" or "PM" as appropriate
    var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";

    // Convert the hours component to 12-hour format if needed
    currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;

    // Convert an hours component of "0" to "12"
    currentHours = ( currentHours == 0 ) ? 12 : currentHours;

    // Compose the string for display
    var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;


    $("#clock").html('Your time: '+currentTimeString);

 }

  function updateSiteClock (){
    var siteTimeTime = new Date (siteTime);
    var siteTimeHours = siteTimeTime.getHours ( );
    var siteTimeMinutes = siteTimeTime.getMinutes ( );
    var siteTimeSeconds = siteTimeTime.getSeconds ( );

    // Pad the minutes and seconds with leading zeros, if required
    siteTimeMinutes = ( siteTimeMinutes < 10 ? "0" : "" ) + siteTimeMinutes;
    siteTimeSeconds = ( siteTimeSeconds < 10 ? "0" : "" ) + siteTimeSeconds;

    // Choose either "AM" or "PM" as appropriate
    var timeOfDay = ( siteTimeHours < 12 ) ? "AM" : "PM";

    // Convert the hours component to 12-hour format if needed
    siteTimeHours = ( siteTimeHours > 12 ) ? siteTimeHours - 12 : siteTimeHours;

    // Convert an hours component of "0" to "12"
    siteTimeHours = ( siteTimeHours == 0 ) ? 12 : siteTimeHours;

    // Compose the string for display
    var siteTimeTimeString = siteTimeHours + ":" + siteTimeMinutes + ":" + siteTimeSeconds + " " + timeOfDay;


    $("#site_clock").html('Site time: '+siteTimeTimeString);
    siteTime = siteTime+1000;
 }


/*

    Functii pentru right click context menu
*/


function popitup(url, modelID) {

    LeftPosition = (screen.width) ? (screen.width - 600) / 2 : 0;
    TopPosition = (screen.height) ? (screen.height - 500) / 2 : 0;
    var sheight = 700;
    var swidth = 1024;

    settings = 'height=' + sheight + ', width=' + swidth + ',';
    settings += ' directories=no, fullscreen=no, location=no, menubar=no, resizable=no, scrollbars=no, status=no, titlebar=no, toolbar=no';

    newwindow = window.open(url, '', settings);

    if (window.focus) {
        newwindow.focus()
    }

/*    var timer = setInterval(function () {
        if (newwindow.closed) {
            clearInterval(timer);
            removeModelIntoCookie(modelID);
        }
    }, 100);*/


    return false;
}

 function makeCookieForPopup(modelID) {
     var timer = setInterval(function () {
         if (typeof $.cookie("popup_" + modelID) == "undefined") {
             exp = new Date();
             exp = new Date(exp.getTime() + 3102);
             $.cookie("popup_" + modelID, " ", {expires: exp, path: "/"});

         }
     }, 100);
 }

function userOptionSelected(key, options){

    var modelID = options.selector.replace("#model_","");
    switch (key){

        case 'popup':
            popitup(eval(" models.model_" + modelID + ".popuplink;"), modelID);

            break;
        case 'favorite':
            var fav = eval("models.model_"+modelID+".favorite");
            params = { model_id      : modelID };

            if(fav == 1) {
                //remove
                App.tzPOST('removeFavorite',params, null);

                eval("models.model_"+modelID+".favorite = 0;");

                /* modificare icoana deasupra imagine video pe pagina live */

                if($('#favorites_label').length && $('#favorites').length) {
                    $('#favorites_label').attr('class','add');
                    $('#favorites_label').text('Add to favorites!');
                    $('#favorites').attr('class','add');
                    $('#favorites').attr('alt', 'Add to favorites!');
                    $('#favorites').attr('src', '/images/add_favorite.png');
                }

            } else {
                //add
                App.tzPOST('addFavorite',params, null);
                eval("models.model_"+modelID+".favorite = 1;");

                /* modificare icoana deasupra imagine video pe pagina live */

                if($('#favorites_label').length && $('#favorites').length) {
                    $('#favorites_label').attr('class','remove');
                    $('#favorites_label').text('Remove from favorites!');
                    $('#favorites').attr('class','remove');
                    $('#favorites').attr('alt', 'Remove from favorites!');
                    $('#favorites').attr('src', '/images/remove_favorite.png');
                }
            }
            $.contextMenu( 'destroy', options.selector );
            generateUserMenu($(options.selector));
        break;

        case "message":
             window.open( eval(" models.model_"+modelID+".message_link;"), '_blank');
             window.focus();
        break;

        case "tip":
            console.log('send tip');
        break;

        case "request":
            console.log('send request');
        break;

        case "follow":
            var followed = eval("models.model_"+modelID+".follow");

            if(followed == 1) {
                App.user.follow.remove(modelID);
                eval("models.model_"+modelID+".follow = 0;");
            } else {
                App.user.follow.add(modelID);
                eval("models.model_"+modelID+".follow = 1;");
            }
            $.contextMenu( 'destroy', options.selector );
            generateUserMenu($(options.selector));
        break;

        case "hideModel":
            var followed = eval("models.model_" + modelID + ".hide");

            if (followed == 1) {
                App.user.follow.remove(modelID);
                eval("models.model_" + modelID + ".hide = 0;");
            } else {
                App.user.follow.add(modelID);
                eval("models.model_" + modelID + ".hide = 1;");
            }

            $.ajax({
                url: "/hideModel",
                type: "POST",
                data: {"for": null, "model": modelID},
                success: function (data, textStatus, jqXHR) {
                    $('#model_'+modelID).parent().remove();
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });

        break;
    }
}


function generateUserMenu(elem){

    var divID = elem.attr("id");
    id = divID.replace("model_", "");

    var favorites =  {name: "Add to favorites",    icon: "add_favorite"};
    var following =  {name: "Follow model",        icon: "follow" }

    if(typeof models != 'undefined') {
        if(eval("models."+divID+".favorite") == 1) {
            favorites =   {name: "Remove from favorites",    icon: "remove_favorite"};
        }
        if(eval("models."+divID+".follow") == 1) {
            following =   {name: "Unfollow",    icon: "unfollow"};
        }
    }
    var hideModel = {name: "Hide model", icon: "ban"};
    $.contextMenu({
        selector: '#'+divID,
        build: function($trigger, e) {
            // this callback is executed every time the menu is to be shown
            // its results are destroyed every time the menu is hidden
            // e is the original contextmenu event, containing e.pageX and e.pageY (amongst other data)
            return {
                callback: userOptionSelected ,
                items: {
                    "popup":    {name: "Open chat in popup", icon:"open-popup"},
                    "favorite":    favorites,
                    "follow":      following,
                    "message":     {name: "Send message",        icon: "message"},
                    "hideModel" : hideModel
                    //,
                    //"tip":         {name: "Send tip",            icon: "tip"},
                    //"request":     {name: "Special request",     icon: "request"}
                }
            };
        }
    });
}


/* meniu click dreapta model  in broadcast*/

function modelOptionSelected(key, options){

    var userID = options.selector.replace("#user_","");

    switch (key){


        case "ban":
            console.log('banned');
        break;

        case "kick":
            console.log('kicked');
        break;

        case "note":
            App.chat.getNotes(userID);
        break;
    }
}

function generateModelMenu(elem){

    var divID = elem.attr("id");

    id = divID.replace("user_", "");
    var chips1 = $('#'+divID).data('chips');


    $.contextMenu({
        selector: '#'+divID,
        build: function($trigger, e) {
            // this callback is executed every time the menu is to be shown
            // its results are destroyed every time the menu is hidden
            // e is the original contextmenu event, containing e.pageX and e.pageY (amongst other data)
            return {
                callback: modelOptionSelected ,
                items: {
                    "kick" :     {name: "Kick",       icon: "kick"},
                    "ban"  :     {name: "Ban",        icon: "ban"},
                    "note" :     {name: "Add note",   icon: "note"},
                    "chips" :     {name: chips1+" ",   icon: "tip"}
                    //"tip":         {name: "Send tip",            icon: "tip"},
                    //"request":     {name: "Special request",     icon: "request"}
                }
            };
        }
    });
}


function parseUrl(url) {
       var cleanUrl = (url + " ").replace(/[^a-zA-Z0-9]+/g, "-");
       return cleanUrl.slice(0, cleanUrl.length -1).toLowerCase();
}

/**
* preg_replace -  copie pentru preg_replace din php
*
* @param replace - array cu cuvinte de cautat
* @param by - cuvintele de inlocuit
* @param str - stringul in care se cauta
*/
function preg_replace(replace, by, str) {

  for (var i=0; i<replace.length; i++) {
     str = str.replace(replace[i], by[i]);
  }
  return str;
}




/**
*   MULTI CHECBOX SELECT
*
* @checkbox - clasa pentru checkbox
* @multiple_select_input - id pentru textinput hidden in care vor fi stocate valorile
* @selectall - id checkbox pentru select all checkboxes
*/


$(document).ready(function(){
    //report init
    $(".report").on("click", function(){
        spinner.appendOverlay();
        reportContent($(this).data("type"), $(this).data("id"));
    });
});

function reportContent(type, id_content){

    report_for = ["inappropriate", "underage", "copyright", "not working", "other"];


    $("body").append('<div id="reportContent"></div>');
    $("#reportContent").append('<form id="reportContentForm"></from>');
    $("#reportContentForm").append('<input type="hidden" name="content_type" id="content_type"/>');
    $("#reportContentForm").append('<input type="hidden" name="id_content" id="id_content"/>');
    $("#reportContentForm").append('<select name="reported_for" id="reported_for"></select>');
    for(i=0 ; i< report_for.length; i++){
        $("#reported_for").append('<option value="'+report_for[i]+'">'+report_for[i]+'</option>');
    }
    $("#reportContentForm").append('<textarea cols="10" style="width:450px" rows"15" name="report_reason" id="report_reason"></textarea>');
    //$("#reportContentForm").append('<button>Report</button>');
    $("#content_type").val(type);
    $("#id_content").val(id_content);
    $('#reportContent').dialog({
        title: "Report reason",
        minWidth: '500',
        model: true,
        buttons: [{
            text: "Report",
            click: function(){
                $('.errmsg').remove();
                if($("#report_reason").val().length < 1) {
                    $("#report_reason").after('<div class="errmsg ui-widget "><div class="ui-state-error ui-corner-all "><p>Add a reason</p></div></div>');
                    return;
                }
                 data = {
                    action: "reportcontent",
                    id_content: id_content,
                    content_type: type,
                    report_reason: $("#report_reason").val(),
                    reported_for: $("#reported_for").val()
                 };

                 $.ajax({
                  type: "POST",
                  url: "/process",
                  data: data,
                   beforeSend: function() {
                       $(".ui-dialog-buttonset").append('<div class="spin"><div id="facebookG"><div id="blockG_1" class="facebook_blockG"></div><div id="blockG_2" class="facebook_blockG"></div><div id="blockG_3" class="facebook_blockG"></div></div></div>');
                       $(".ui-dialog-buttonset button").prop('disabled', true);
                     }
                })
                  .done(function( msg ) {
                        msg = $.parseJSON(msg);
                        if(msg.status == "success") {
                            $('#reportContent').html('<p style="color: green">'+msg.message+'</p>');
                        }
                        if(msg.status == "fail") {
                            $('#reportContent').html('<p style="color: green">'+msg.message+'</p>');
                        }
                        $('#reportContent').dialog({
                                buttons: [{
                                    text: "Ok",
                                    click: function(){
                                        $('#reportContent').dialog('close');
                                    }
                                }]
                        });

                         $(".ui-dialog-buttonset .spin").remove();
                         $(".ui-dialog-buttonset button").prop('disabled', false);
                  })
                  .fail(function( msg ) {
                        $('#reportContent').html('<p style="color: red">Report not sent!</p>');
                        $('#reportContent').dialog({
                            buttons: [{
                                text: "Ok",
                                click: function(){
                                    $('#reportContent').dialog('close');
                                }
                            }]
                        });

                });
            }
        },
        {
            text: "Cancel",
            click: function(){
                $(this).dialog('close');
            }
        }],
        close: function(){
            $(this).dialog("destroy");
            $(this).remove();
            spinner.removeOverlay();
        }
    });
}

/**
* verifica selectia si adauga valorile in multiple select input /\
*/
function checkSelection(checkbox, multiple_select_input){
    checkbox = typeof checkbox !== 'undefined' ? checkbox : 'case';
    multiple_select_input = typeof multiple_select_input !== 'undefined' ? multiple_select_input : 'multiple_select';
    var ids = '';
    $('.'+checkbox+':checkbox:checked').each(function() {
        if(typeof $(this).val() !=  'undefined'){
            ids += $(this).val()+',';
        }
    });
    $('#'+multiple_select_input).val(ids);
}

/* fix jquery deprecated $.browser in new 1.9 */

if(typeof jQuery.browser == "undefined") {
        jQuery.browser={};
        jQuery.browser.mozilla=/mozilla/.test(navigator.userAgent.toLowerCase())&&!/webkit/.test(navigator.userAgent.toLowerCase());
        jQuery.browser.webkit=/webkit/.test(navigator.userAgent.toLowerCase());
        jQuery.browser.opera=/opera/.test(navigator.userAgent.toLowerCase());
        jQuery.browser.msie=/msie/.test(navigator.userAgent.toLowerCase());
}




$(document).ready(function(){

    /* disable right click browser - disable context menu */
      /*$(document).bind("contextmenu", function(e) {
           return false;
       });*/
});



function alertFee(element){
    return confirm("Fee: " + element.data("fee") + " chips") ;
}

function viewItem(id, type){

    data = {
        id: id,
        type: type,
        action: 'viewitem'
    };

    $.ajax( {
      type: "POST",
      url: "/process",
      data: data,
      dataType: 'json',
      success: function(ret){
       // ret = jQuery.parseJSON(ret);

        if(ret.status == "ok")
             $("#viewCount"+data.id).html(parseInt( $("#viewCount"+data.id).html())+1);
      }
    } )
   .done(function() {
    });

}

// function lastActivity(){
//        var date = new Date();
//        var last_move = new Date();
//        var minutes = 1;
//        date.setTime(date.getTime() + (minutes * 60 * 1000));
//        last_time =  parseInt( Math.floor(last_move.getTime()/1000) );
//        document.cookie = "last_activity="+last_time+"; expires:"+ date ;
// }
//
// $(document).ready(function(){
//     lastActivity();
//
//     $("body").bind( "keyup", function(){
//        lastActivity();
//     })
// });


// Open modal in AJAX callback
function disclaimerModal() {

    if ($.cookie("over18")) {
        return;
    } else {
        $.ajax({
            url: "/processAjax/disclaimer",
            beforeSend: function () {
                spinner.appendOverlay(true);
            }

        }).done(function (response) {
            $(".jquery-modal.blocker.spinner").remove();
            var modal = $('<div/>', {
                class: 'modal disclaimer',
                html: response.content
            }).appendTo('body').modal({
                overlay: "#000",        // Overlay color
                opacity: 0.45,          // Overlay opacity
                zIndex: 1001,              // Overlay z-index.
                escapeClose: false,      // Allows the user to close the modal by pressing `ESC`
                clickClose: false,       // Allows the user to close the modal by clicking the overlay
                closeText: 'Close',     // Text content for the close <a> tag.
                closeClass: '',         // Add additional class(es) to the close <a> tag.
                showClose: false,        // Shows a (X) icon/link in the top-right corner
                modalClass: "modal",    // CSS class added to the element being displayed in the modal.
                spinnerHtml: null,      // HTML appended to the default spinner during AJAX requests.
                showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
                fadeDuration: 200,     // Number of milliseconds the fade transition takes (null means no transition)
                fadeDelay: 1.0          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
            });
            $('<div/>', {
                class: 'modal-footer'
            }).appendTo('.modal');

            $('<button/>', {
                class: 'below18',
                text: "No, I'm under 18 years old"
            }).appendTo('.modal-footer').on("click", function (e) {
                e.preventDefault();
                $.removeCookie('over18');
                window.location = "http://google.com";
                $(".modal.disclaimer").css("z-index", 0);
            });

            $('<button/>', {
                class: 'above18 btn btn-magenta',
                text: "Yes, I'm over 18 years old"
            }).appendTo('.modal-footer').on("click", function (e) {
                e.preventDefault();
                $.cookie("over18", "true", {path: "/", expires: 365});
                $.modal.close();
            });

        });
    }

}


//instantiate tooltip
jQuery(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
})

