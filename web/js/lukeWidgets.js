/*jslint browser: true*/
/*global $, jQuery, alert, console*/

function lukeWidgets() {
    "use strict";
    
    function slideShow(slider) {
        var current = $(slider).find('.show'),
            next = current.next().length ? current.next() : current.parent().children(':first'),
            h = $(next).height();
        next.parent().css('height', h);
        current.fadeOut(2000).removeClass('show');
        next.fadeIn(2000).addClass('show');
    }
    
    function fadeInOut(element) {
        var current = $(element).children('.active'),
            next = current.next().length ? current.next() : current.parent().children(':first');
        current.fadeOut(8000).removeClass('active');
        next.fadeIn(8000).addClass('active');
    }
    
    $(document).ready(function () {
        
        //menu wysuwane
        $('.verticalMenu li').hover(function () {
            $(this).animate({paddingLeft: '+=10px'}, {queue: false}, 400);
        }, function () {
            $(this).animate({paddingLeft: '-=10px'}, {queue: false}, 50);
        });
        
        //slidery
        $.each($('.slider'), function (key, value) {
            $(value).css('height', $(value).children(':first').height());
            setInterval(function () {
                slideShow(value);
            }, 6000);
        });
        var element = $('#imgWrapper');
        if ($('#imgWrapper').children('img.active').is(':hidden')) {
            $('#imgWrapper').children('img.active').show();
        }
        setInterval(function () {
            fadeInOut(element);
        }, 12000);
 
    });
}
        