// JavaScript Document
jQuery(document).ready(function(){
    $(".dropdown").hover(
        function() { $('.dropdown-menu', this).stop().fadeIn("fast");
        },
        function() { $('.dropdown-menu', this).stop().fadeOut("fast");
        });
});
jQuery(document).ready(function(){

    $('.collapse').on('shown.bs.collapse', function(){
        $(this).parent().find("span").removeClass("glyphicon-plus").addClass("glyphicon-minus");
    });

    $('.collapse').on('hidden.bs.collapse', function(){
        $(this).parent().find("span").removeClass("glyphicon-minus").addClass("glyphicon-plus");
    });
});
jQuery(document).ready(function(){
    var ypos;
    function parallex (){
    }
    window.addEventListener('scroll', parallex);
    $(window).scroll(function(){
        if($(document).scrollTop() > 0) {
            $('#login').addClass('none');
        } else {
            $('#login').removeClass('none');
        }
        if($(document).scrollTop() > 0) {
            $('#nav-right').addClass('nav-right-small');
        } else {
            $('#nav-right').removeClass('nav-right-small');
        }
        if($(document).scrollTop() > 0) {
            $('#logo').addClass('logo-small');
        } else {
            $('#logo').removeClass('logo-small');
        }
    }); });
$('.navigation__toogle').click(function() {
    $(this).toggleClass( 'active' );
});

//pop over js start//
var originalLeave = $.fn.popover.Constructor.prototype.leave;
$.fn.popover.Constructor.prototype.leave = function(obj){
    var self = obj instanceof this.constructor ?
        obj : $(obj.currentTarget)[this.type](this.getDelegateOptions()).data('bs.' + this.type)
    var container, timeout;

    originalLeave.call(this, obj);

    if(obj.currentTarget) {
        container = $(obj.currentTarget).siblings('.popover')
        timeout = self.timeout;
        container.one('mouseenter', function(){
            //We entered the actual popover  call off the dogs
            clearTimeout(timeout);
            //Let's monitor popover content instead
            container.one('mouseleave', function(){
                $.fn.popover.Constructor.prototype.leave.call(self, self);
            });
        })
    }
};


$('body').popover({ selector: '[data-popover]', trigger: 'click hover', placement: 'top', delay: {show: 50, hide: 200}});

// pop over js end //
 $(document).ready(function() {
    $('.carousel.pledge-slide').carousel({
        interval: false
    })
});
jQuery(document).ready(function(){
    $('.pledge-slide').carousel({
        interval: false

    })
});

$('input[type=range]').on('input', function(e){
    var min = e.target.min,
        max = e.target.max,
        val = e.target.value;

    $(e.target).css({
        'backgroundSize': (val - min) * 100 / (max - min) + '% 100%'
    });
}).trigger('input');


jQuery(document).ready(function(){
    jQuery('.chat_header').on('click',function(){
        if(jQuery(this).hasClass('minimize')){
            jQuery(this).removeClass('minimize');
            jQuery(this).parent().find('.chat_body').css('height','225px');
            jQuery(this).parent().find('.chat_type').show();
            jQuery(this).parent().css('margin-top','0px');
        }
        else{
            jQuery(this).addClass('minimize');
            jQuery(this).parent().find('.chat_body').css('height','0px');
            jQuery(this).parent().find('.chat_type').hide();
            jQuery(this).parent().css('margin-top','257px');
        }

    });

    jQuery( window ).resize(function() {

        //location.reload();

    });

    /***************************/
    $('.slaide-arrow-right').on('click', function(){
        var leftwidth=jQuery('.left_side').width();
        var addwidth=jQuery('.add_width').width()+60;
        var rightwidth=jQuery('.right_side').width()+30;
        var winwidth=jQuery(window).width();

        if(getCookie('chat') == "closed" || getCookie('chat') == ""){

            setCookie('chat', 'open', 1)
            console.log(getCookie('chat'));

            $('.slaide-arrow-right').removeClass('right_reduce');
            $('.slaide-arrow-right').removeClass('right_icon');
            $('.tab_class').show();
            $('.chat-container').css('border','1px solid');

            jQuery(this).removeClass('right_reduce');
            jQuery('.chat-container').css('border','1px solid');

            if (jQuery('.slaide-arrow').hasClass('left_reduce')) {
                jQuery('.productdiv').addClass('height_25');
                jQuery('.productdiv').removeClass('height_20');
            }
            else {
                jQuery('.productdiv').removeClass('height_25');
                jQuery('.productdiv').removeClass('height_20');
            }

            if(winwidth<630){
                jQuery('.rightblock').show();
                jQuery('.tab_class').show();
            }
            else{
                jQuery('.right_side').animate({
                    marginRight:0
                }, 1500, function() {
                });
                jQuery('.add_width').animate({
                    width:addwidth-leftwidth-60
                }, 300, function() {
                });

                jQuery('.rightblock').show();
                jQuery('.tab_class').show();
                jQuery('.slaide-arrow-right').removeClass('right_icon');
            }
        } else if (getCookie('chat') == "open"){
            setCookie('chat', 'closed', 1)
            console.log(getCookie('chat'));

            $('.slaide-arrow-right').addClass('right_reduce');
            $('.slaide-arrow-right').addClass('right_icon');
            $('.tab_class').hide();
            $('.chat-container').css('border','0px solid');

            jQuery(this).addClass('right_reduce');
            jQuery('.chat-container').css('border','0px solid');

            if (jQuery('.slaide-arrow').hasClass('left_reduce')) {
                jQuery('.productdiv').addClass('height_20');
                jQuery('.productdiv').removeClass('height_25');
            }
            else {
                jQuery('.productdiv').addClass('height_25');
                jQuery('.productdiv').removeClass('height_20');
            }

            if(winwidth<630){
                jQuery('.rightblock').hide();
                jQuery('.tab_class').hide();
            }
            else{
                jQuery('.right_side').animate({
                    marginRight:'-'+leftwidth
                }, 1500, function() {
                });
                jQuery('.add_width').animate({
                    width:addwidth+leftwidth
                }, 300, function() {
                });

                jQuery('.rightblock').hide();
                jQuery('.tab_class').hide();
                jQuery('.right_reduce').addClass('right_icon');
            }
        }
    });

    /***************************/
    jQuery('.user_wrap').on('mouseenter',function(){
        var image_path=jQuery(this).find('.img_attr').attr('src');
        jQuery(this).find('.img_wrap img').attr('src',image_path);
        jQuery('.tool_tip').css('display','none');
        jQuery(this).find('.tool_tip').css('display','block');
    });
    jQuery('.user_wrap').on('mouseleave',function(){
        jQuery('.tool_tip').css('display','none');
    });

    /*********************/

    jQuery('.perform_wrap').on('mouseenter',function(){
        var image_path=jQuery(this).find('.img_attr').attr('src');
        jQuery(this).find('.img_wrap img').attr('src',image_path);
        jQuery('.tool_tip').css('display','none');
        jQuery(this).find('.tool_tip').css('display','block');
    });
    jQuery('.perform_wrap').on('mouseleave',function(){
        jQuery('.tool_tip').css('display','none');
    });

    /*********************/
    jQuery('.user_wrap1').on('mouseenter',function(){
        var image_path=jQuery(this).find('.img_attr').attr('src');
        jQuery(this).find('.img_wrap img').attr('src',image_path);
        jQuery('.tool_tip').css('display','none');
        jQuery(this).find('.tool_tip').css('display','block');
    });
    jQuery('.user_wrap1').on('mouseleave',function(){
        jQuery('.tool_tip').css('display','none');
    });

    /*********************/

    jQuery('.post_wrap').on('mouseenter',function(){
        var image_path=jQuery(this).find('.img_attr').attr('src');
        jQuery(this).find('.img_wrap img').attr('src',image_path);
        jQuery('.tool_tip').css('display','none');
        jQuery(this).find('.tool_tip').css('display','block');
    });
    jQuery('.post_wrap').on('mouseleave',function(){
        jQuery('.tool_tip').css('display','none');
    });

    /*********************/

    jQuery('.like_wrapper').on('mouseenter',function(){
//        var image_path=jQuery(this).find('.img_attr').attr('src');
//        jQuery(this).find('.img_wrap img').attr('src',image_path);
        jQuery('.tool_tip').css('display','none');
        jQuery(this).find('.tool_tip').css('display','block');
    });
    jQuery('.like_wrapper').on('mouseleave',function(){
        jQuery('.tool_tip').css('display','none');
    });

    //end doc
});

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}

$(document).ready(function() {

    if(getCookie('chat') == "open") {
        $('.slaide-arrow-right').removeClass('right_reduce');
        $('.slaide-arrow-right').removeClass('right_icon');
        $('.tab_class').show();
        $('.chat-container').css('border','1px solid');
    } else {
        $('.slaide-arrow-right').addClass('right_reduce');
        $('.slaide-arrow-right').addClass('right_icon');
        $('.tab_class').hide();
        $('.chat-container').css('border','0px solid');
    }
});
