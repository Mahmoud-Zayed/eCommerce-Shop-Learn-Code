$(function () {
    'use strict';

    // Dashboard 
    $(".toggle-info").click(function () { 
        $(this).toggleClass("selected").parent().next(".panel-body").fadeToggle(100);
        if ($(this).hasClass("selected")) {
            $(this).html('<i class="fa fa-minus fa-lg"></i>');
        } else {
            $(this).html('<i class="fa fa-plus fa-lg"></i>');

        }
    });

    // Hide Placeholder On Form Foucs
    $("[placeholder]").focus(function () {
        $(this).attr("data-text", $(this).attr("placeholder"));
        $(this).attr("placeholder", "");
    }).blur(function () {
        $(this).attr("placeholder", $(this).attr("data-text"));
    });

    // Add Asterisk On Required Field
    $('input').each(function () {
        if ($(this).attr("required") === "required") {
            $(this).after("<span class='asterisk'>*</span>");
        }
    });

    // Convert Password Field To Text Field On Hover 
    var passFeild = $(".password");
    $(".show-pass").hover(function () {
        passFeild.attr("type", "text");
    }, function () {
        passFeild.attr("type", "password");
    });

    // Confirmation Message On Button
    $(".confirm").click(function () {
        return confirm("Are You Sure?");
    });

    // Category View Option
    $(".categories .cat h3").click(function () {
        $(this).next(".categories .full-view").fadeToggle(200);
    });
    $(".categories .orrr span").click(function () {
        $(this).addClass("active").siblings("span").removeClass("active");
        if ($(this).data('view') === "full") {
            $(".categories .full-view").fadeIn(200);
        } else {
            $(".categories .full-view").fadeOut(200);
        }
    });

});