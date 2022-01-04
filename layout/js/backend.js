$(function() {
    'use strict';

    //Hide Placeholder on form focus

    $('[placeholder]').focus(function() {
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    }).blur(function() {
        $(this).attr('placeholder', $(this).attr('data-text'));
    });

    // Add Asterisk On Required Field

    $('input').each(function() {
        if ($(this).attr('required') === 'required') {
            $(this).after('<span class="asterisk">*</span>');
        }
    });

    // Convert password to text on hover

    var passField = $('.password');

    $('.show-pass').hover(function() {
        passField.attr('type', 'text');
    }, function() {
        passField.attr('type', 'password');
    });

    //Confirmation message on button

    $('.confirm').click(function() {
        return confirm('Are You Sure?');
    });

    // Category view option
    /*   $(".cat h3").on(function() {
        $(this).next('.full-view').fadeOut(100);
    });

*/
    $('.cat h3').click(function() {
        $(this).next('.full-view').fadeToggle(0.000001);
    });
    $(".cat h3").trigger("click");

    /*    $('.categories .panel .ordering .sort').click(function() {
            $(this).attr('data-text', $(this).after('<span class="sort"></span>'));
            $(this).attr($(this).after('<span class="sort"></span>'), "< a href = '?sort=DESC' > < i class = 'glyphicon glyphicon-sort' > < /i></a >");
        });

    */
});