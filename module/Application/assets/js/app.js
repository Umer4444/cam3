var App = {

    dialogTimeout: 6,

    enableTinyMCE: function(node) {
        try {
            $(node ? node : 'textarea.tinymce').tinymce({
                script_url: '//tinymce.cachefly.net/4.1/tinymce.min.js',
                relative_urls: false,
                remove_script_host: true,
                document_base_url: "/",
                convert_urls: true,
                plugins: [
                    "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                    "save table contextmenu directionality emoticons template paste textcolor "
                ],
                external_plugins: {
                    "moxiemanager": "/moxiemanager/plugin.min.js"
                }
            });
        }
        catch (e) {
            console.log('probably tinymce jquery not loaded');
        }
    },

    saveResourceValue: function(value, group, name, label, context, callback) {

        var data = {'value': value, 'group': group, 'name': name};

        if (typeof context !== "undefined") {
            data.context = context;
        }

        if (typeof label !== "undefined") {
            data.label = label;
        }

        var ajaxOptions = {
            type: 'POST',
            url: '/api/resources',
            contentType: 'application/json',
            data: JSON.stringify(data),
            dataType : 'json'
        };

        if (typeof callback !== 'undefined') {
            ajaxOptions.success = callback;
        }

        $.ajax(ajaxOptions);

    },

    removeResource: function(group, name, context, callback) {

        var data = {'group': group, 'name': name};

        if (typeof context !== "undefined") {
            data.context = context;
        }

        var ajaxOptions = {
            type: 'DELETE',
            url: '/api/resources',
            contentType: 'application/json',
            data: JSON.stringify(data),
            dataType : 'json'
        };

        if (typeof callback !== 'undefined') {
            ajaxOptions.success = callback;
        }

        $.ajax(ajaxOptions);

    },

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
                //App.chat.data.get_requests = true;

                App.working = false;

                App.model.private.getPrivateRequestTimeoutFunction();

            },

            getPrivateRequestTimeoutFunction: function(){
                //if(App.chat.data.get_requests) App.model.private.getPrivateRequest(App.model.private.getPrivateRequestTimeoutFunction);
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
                //App.chat.data.get_requests = true;

                App.working = false;

                App.model.vip.getVipRequestTimeoutFunction();

            },

            getVipRequestTimeoutFunction: function(){
                //if(App.chat.data.get_requests) App.model.vip.getVipRequest(App.model.vip.getVipRequestTimeoutFunction);
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
                //App.chat.data.get_requests = true;

                App.working = false;

                App.model.group.getGroupRequestTimeoutFunction();

            },

            getGroupRequestTimeoutFunction: function(){
                //if(App.chat.data.get_requests) App.model.group.getGroupRequest(App.model.group.getGroupRequestTimeoutFunction);
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
        },

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

    autoresponders : {

        editAutoResponders: function(field_id, id_question){
            console.log(field_id);
            console.log(id_question);
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
                        <a class="savelink btn btn-success question-'+data.id_question+'" href="javascript:;" onclick="autoresponders.editAutoResponders(\'question-'+data.id_question+'\',\'0\')">Save</a>\
                        <div class="field">\
                       <label for="">Auto Response</label> \
                       <div class="field_wrapper">\
                       <input type="text" value="" class="ss_field" id="answer-0-'+App.autoresponders.i+'" name="answer-0-'+App.autoresponders.i+'"></div><a class="savelink btn btn-primary answer-0-'+App.autoresponders.i+'" href="javascript:;" onclick="App.autoresponders.editAutoResponders(\'answer-0-'+App.autoresponders.i+'\','+data.id_question+')">Save</a></div> \
                       <a href="javascript:;" title="Add a new response" class="add_new_answer btn btn-default" id="add_new_answer-'+data.id_question+'" onclick="App.autoresponders.addNewAnswerField('+data.id_question+')">Add Answer</a> \
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
            div.innerHTML = '<div class="field"><label for="">&nbsp;</label> <div class="field_wrapper"><input type="text" value="" class="ss_field" id="answer-0-'+App.autoresponders.i+'" name="answer-0-'+App.autoresponders.i+'"> </div><a class="savelink btn btn-primary answer-0-'+App.autoresponders.i+'" href="javascript:;" onclick="App.autoresponders.editAutoResponders(\'answer-0-'+App.autoresponders.i+'\','+id_question+')">Save</a></div>';

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
                        <input type="text" value="" class="ss_field" id="question-0-'+App.autoresponders.i+'" name="question-0-'+App.autoresponders.i+'"> </div><a class="savelink btn btn-success question-0-'+App.autoresponders.i+'" href="javascript:;" onclick="App.autoresponders.editAutoResponders(\'question-0-'+App.autoresponders.i+'\',\'0\')">Save</a></div><br>';

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

};

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


function updateSiteClock (){
      if (siteTime == undefined) {
          var siteTime = new Date;
      }
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


    jQuery("#site_clock").html('Site time: '+siteTimeTimeString);
    siteTime = siteTime+1000;
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
            App.model.sendTip(modelID);
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

function multipleCheck(checkbox, multiple_select_input, selectall){
    checkbox = typeof checkbox !== 'undefined' ? checkbox : 'case';
    selectall = typeof selectall !== 'undefined' ? selectall : 'selectall';
    multiple_select_input = typeof multiple_select_input !== 'undefined' ? multiple_select_input : 'multiple_select';

     // add multiple select / deselect functionality
    $("#"+selectall).click(function () {
          $('.'+checkbox).attr('checked', this.checked);
          checkSelection(checkbox, multiple_select_input);
    });
    // if all checkbox are selected, check the selectall checkbox
    // and viceversa
    $('.'+checkbox).click(function(){
        checkSelection(checkbox, multiple_select_input);
       // alert(this);
        if($('.'+checkbox).length == $('.'+checkbox+":checked").length) {
            $("#"+selectall).attr("checked", "checked");
        } else {
            $("#"+selectall).removeAttr("checked");
        }
    });
}

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

// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
// infinite scroll
var infiniteNextPage, infiniteStop, infiniteInProgress = false;
var infinitePage = 1;

function refresh(){
    location.href = location.href;
}

$(window).scroll(function () {

    if(
        !infiniteInProgress && infiniteNextPage && !infiniteStop &&
        ($(window).scrollTop() + $(window).height()) >= ($(document).height() - 300)
    ) {

        var infiniteContainer = $('[data-type="infinite"]').append($('#loadingInfinite').show());
        infiniteInProgress = true;

        $.ajax({
            url: infiniteNextPage,
            success: function(res) {

                if (infiniteContainer.data('url')) {
                    infinitePage++;
                    var nextPage = infiniteContainer.data('url')+'/'+infinitePage;
                }
                else {
                    var nextPage = $(res).find('ul.pagination li:not("disabled") a.next').attr('href');
                }

                if (nextPage != infiniteNextPage) {
                    infiniteNextPage = nextPage;
                }
                else {
                    infiniteStop = true;
                }
                $('[data-type="infinite"]').append($(res).find('[data-type="infinite"] > *'));
                $('#loadingInfinite').hide();
                infiniteInProgress = false;

            }
        });

    }

    $('[data-type="infinite"]').closest('body').find('ul.pagination').remove();

});

$(document).ready(function () {


    // required for ^^^^ infinite scroll
    if ($('[data-type="infinite"]').data('url')) {
        infiniteNextPage = $('[data-type="infinite"]').data('url')+'/1';
    }
    else {
        infiniteNextPage = $('[data-type="infinite"]').closest('body').find('ul.pagination li:not(".disabled") a.next').attr('href');
    }

    $('[data-type="infinite"]').append(
        $('<div id="loadingInfinite" class="row"><div class="col-md-12"><div class="bottom-loader">'+
         '<div class="text-center">Loading more ...</div>'+
         '<h4 class="widget-heading double"><span><img src="/assets/images/loader_img.png"></span>'+
         '</h4></div></div></div>').hide()
    )

    FastClick.attach(document.body);

    $.ajaxSetup({
        cache: false,
        processData: false
    });

    $.ajaxPrefilter(function(options, originalOptions, jqXHR) {

        // apply ajax settings for api calls
        if (options.url.indexOf('/api/') > -1) {

            if (typeof options.data != "string") {
                options.data = JSON.stringify(options.data);
            }

            options.contentType = 'application/json';
            options.dataType = 'json';

        }

    });

    // bs3 tooltip
    $.widget.bridge('uitooltip', $.ui.tooltip);
    $('[data-toggle="tooltip"]').tooltip();

    // x-editable
    $.fn.editable.defaults.ajaxOptions = {contentType: "application/json", dataType: 'json'};

    // bs3 date time
    $('[data-type="datetimepicker"], [data-type="datetime"]').datetimepicker({format: 'YYYY-MM-DD HH:mm:ss'});
    $('[data-type="timepicker"], [data-type="time"]').timepicker();

    // counters
    $('[data-type="timer-flip"]').FlipClock();

    $('[data-type="timer"]').countTo({
        to: 50000000,
        formatter: function (value, options) {
            return value.toFixed(options.decimals);
        }
    });

    // fancy box for video
    $('.fancybox.video').fancybox({
        tpl: {
            // wrap template with custom inner DIV: the empty player container
            wrap: '<div class="fancybox-wrap" tabIndex="-1">' +
            '<div class="fancybox-skin">' +
            '<div class="fancybox-outer">' +
            '<div id="player">' + // player container replaces fancybox-inner
            '</div></div></div></div>'
        },
        beforeShow: function () {

            if ($('#player').length == 0) {
                $('<div/>', {id: 'player'}).appendTo('body');
            }

            $f('#player', '/assets/vendor/javascript/flowplayer-flash/flowplayer.swf', $(this).data('url'));

        },
        beforeClose: function () {
            // important! unload the player
            flowplayer("#player").unload();
        }
    });

    // display video
    if(!$('#content .avatar').hasClass('no-hide-on-hover'))
    {
        $("#content .avatar").on("mouseover", function () {
            $(this).children().css("display", "none");
            $(this).children("embed").css("display", "block");
        });
        $("#content .avatar").on("mouseleave", function () {
            $(this).children().css("display", "block");
            $(this).children("embed").css("display", "none");
        });
    }

    // bs3 dropdown menu
    $('.metisMenu ul.dropdown-menu').addClass('nav bg-warning').removeClass('dropdown-menu'); //FIXME quirks mode
    $('.metisMenu').metisMenu();

});

(function($){

  $.extend({
    playSound: function(){
      return $(
        '<audio autoplay="autoplay" style="display:none;">'
          + '<source src="' + arguments[0] + '" />'
          + '<embed src="' + arguments[0] + '" hidden="true" autostart="true" loop="false" class="playSound" />'
        + '</audio>'
      ).appendTo('body');
    }
  });

})(jQuery);
