$(function () {
    'use strict';

    // Switch Between Login & Singup
    $(".login_background h1 span").click(function () { 
        $(this).addClass("selected").siblings().removeClass("selected");
        $(".login_background form").hide();
        $('.' + $(this).data("class")).fadeIn(100);
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

    // Confirmation Message On Button
    $(".confirm").click(function () {
        return confirm("Are You Sure?");
    });

    // Live Preview
        // $('.live-name').keyup( function () {
        //     $(".live-preview .caption h3").text($(this).val());
        // });
        // $(".live-desc").keyup(function () {
        //     $(".live-preview .caption p").text($(this).val());
        // });
        $(".live").keyup(function () {
            $($(this).data("class")).text($(this).val());
        });

    $(".live-price").keyup(function () {
        $(".live-preview .price").text("pound: " + $(this).val());
    });

});