$(document).ready(function () {
    $("#apply-filter").click(function () {
        id = $("#multi_select").val();
        categories = $("#multi_categories").val();
        hair_type = $("#multi_hair_type").val();
        languages = $("#multi_languages").val();
        orientation = $("#multi_orientation").val();
        gender = $("#multi_gender").val();
        eye_color = $("#multi_eye_color").val();
        age = $("#multi_age").val();
        weight = $("#multi_weight").val();
        sorting = $("#multi_sorting").val();
        if ($('#hidden-models').is(':checked')) {

            var hidden = $('#hidden-models').val();
        }

        // if(id.length > 0)
        //    window.location = "/models/filter/"+id ;
        // else
        ///  window.location = "/models";
        url = "/<?=($this->return_to ? $this->return_to : $to_)?>/filter";
        if (categories.length > 0)
            url = url + "/categories/" + categories;
        if (hair_type.length > 0)
            url = url + "/hair_type/" + hair_type;
        if (languages.length > 0)
            url = url + "/languages/" + languages;
        if (orientation.length > 0)
            url = url + "/orientation/" + orientation;
        if (gender.length > 0)
            url = url + "/gender/" + gender;
        if (eye_color.length > 0)
            url = url + "/eye_color/" + eye_color;
        if (age.length > 0)
            url = url + "/age/" + age;
        if (weight.length > 0)
            url = url + "/weight/" + weight;
        if (sorting.length > 0)
            url = url + "/sort/" + sorting;
        if (hidden) {
            url = url + "/" + hidden + "/true";
        }
        window.location = url;
    });
});

/* multiple selection */
function check(ul_name) {
    var ids = '';
    $("." + ul_name + " .case:checkbox:checked").each(function () {
        if (typeof $(this).val() != 'undefined') {
            ids += $(this).val() + ',';
        }
    });
    $('#multi_' + ul_name).val(ids);
}

$(function () {

    $(".case").click(function () {
        ul_name = $(this).parent().parent().attr("class");
        check(ul_name);
        // alert(this);
        if ($(".case").length == $(".case:checked").length) {
            $("#selectall").attr("checked", "checked");
        } else {
            $("#selectall").removeAttr("checked");
        }

    });

    /* make checkbox act like radio */
    $(".checkradio").each(function () {
        $(this).change(function () {
            $(".checkradio").attr('checked', false);
            $(this).attr('checked', true);
        });
    });
});

$(function () {
    /**
     * the element
     */
    var $ui = $('#ui_element');
    /* hide on load */
    $ui.find('.sb_up')
        .addClass('sb_down')
        .removeClass('sb_up')
        .andSelf()
        .find('.sb_dropdown')
        .hide();

    /**
     * on focus and on click display the dropdown,
     * and change the arrow image
     */
    $ui.find('.box-title').bind('focus click', function () {
        $ui.find('.sb_down')
            .addClass('sb_up')
            .removeClass('sb_down')
            .andSelf()
            .find('.sb_dropdown')
            .show();
    });

    /**
     * on mouse leave hide the dropdown,
     * and change the arrow image
     */
    $ui.bind('mouseleave', function () {
        $ui.find('.sb_up')
            .addClass('sb_down')
            .removeClass('sb_up')
            .andSelf()
            .find('.sb_dropdown')
            .hide();
    });
});

    $(document).ready(function () {
        $("#apply-filter").click(function () {
            id = $("#multi_select").val();
            categories = $("#multi_categories").val();
            hair_type = $("#multi_hair_type").val();
            languages = $("#multi_languages").val();
            orientation = $("#multi_orientation").val();
            gender = $("#multi_gender").val();
            eye_color = $("#multi_eye_color").val();
            age = $("#multi_age").val();
            weight = $("#multi_weight").val();
            sorting = $("#multi_sorting").val();
            if ($('#hidden-models').is(':checked')) {
                var hidden = $('#hidden-models').val();
            }

            url = "/<?=($this->return_to ? $this->return_to : $to_)?>/filter";
            if (categories.length > 0)
                url = url + "/categories/" + categories;
            if (hair_type.length > 0)
                url = url + "/hair_type/" + hair_type;
            if (languages.length > 0)
                url = url + "/languages/" + languages;
            if (orientation.length > 0)
                url = url + "/orientation/" + orientation;
            if (gender.length > 0)
                url = url + "/gender/" + gender;
            if (eye_color.length > 0)
                url = url + "/eye_color/" + eye_color;
            if (age.length > 0)
                url = url + "/age/" + age;
            if (weight.length > 0)
                url = url + "/weight/" + weight;
            if (sorting.length > 0)
                url = url + "/sort/" + sorting;
            if (hidden) {

                url = url + "/" + hidden + "/true";
            }

            window.location = url;
        });

    });

/* multiple selection */
function check(ul_name) {
    var ids = '';
    $("." + ul_name + " .case:checkbox:checked").each(function () {
        if (typeof $(this).val() != 'undefined') {
            ids += $(this).val() + ',';
        }
    });
    $('#multi_' + ul_name).val(ids);
}

$(function () {

    $(".case").click(function () {
        ul_name = $(this).parent().parent().attr("class");
        check(ul_name);
        // alert(this);
        if ($(".case").length == $(".case:checked").length) {
            $("#selectall").attr("checked", "checked");
        } else {
            $("#selectall").removeAttr("checked");
        }

    });

    /* make checkbox act like radio */
    $(".checkradio").each(function () {
        $(this).change(function () {
            $(".checkradio").attr('checked', false);
            $(this).attr('checked', true);
        });
    });
});

$(function () {
    /**
     * the element
     */
    var $ui = $('#ui_element');
    /* hide on load */
    $ui.find('.sb_up')
        .addClass('sb_down')
        .removeClass('sb_up')
        .andSelf()
        .find('.sb_dropdown')
        .hide();

    /**
     * on focus and on click display the dropdown,
     * and change the arrow image
     */
    $ui.find('.box-title').bind('focus click', function () {
        $ui.find('.sb_down')
            .addClass('sb_up')
            .removeClass('sb_down')
            .andSelf()
            .find('.sb_dropdown')
            .show();
    });

    /**
     * on mouse leave hide the dropdown,
     * and change the arrow image
     */
    $ui.bind('mouseleave', function () {
        $ui.find('.sb_up')
            .addClass('sb_down')
            .removeClass('sb_up')
            .andSelf()
            .find('.sb_dropdown')
            .hide();
    });

});
