jQuery(document).ready(function($) {
    $('a[href*=#]').click(function(event){
        $('html, body').animate({
            scrollTop: $( $.attr(this, 'href') ).offset().top - 30
        }, 500);
        event.preventDefault();
    });
});