$(function () {
   'use strict';

   // hide placeholder on focus
    $('[placeholder]').focus(function () {
        $(this).attr('data-text',$(this).attr('placeholder'));
        $(this).attr('placeholder','');

    }).blur(function () {
        $(this).attr('placeholder',$(this).attr('data-text'));
    });
// add star to required fields
    $('input').each(function () {
       if ($(this).attr('required') ==='required')
       {
           $(this).attr('<span class="asterisk">*</span>')
       }
    });

    // add eye to passwords inputs to see on hover as numbers

    var passfield = $('.password');
    $('.show-pass').hover(function () {
        passfield.attr('type','text');
    },function () {
        passfield.attr('type','password');
    });

    /*category style */

    $('.cat h3').click(function () {
       $(this).next('full-view').fadeToggle(200);
    });

    $('.option span').click(function () {
       $(this).addClass('active'),siblings('span').removeClass('active');
       if ($(this).data('view') == 'full')
       {
           $('.cat .full-view').fadeIn(200);
       }
       else
       {
           $('.cat .full-view').fadeOut(200);
       }
    });

    $(".live").keyup(function () {
        $($(this).data('class')).text($(this).val());
    });



});