( function($){
    $('.loginsubmit').on('click', function(){
        $('.loginsubmit').parents('form').submit();
    });
    $('.saveblog').on('click', function(){
        $('.saveblog').parents('form').submit();
    });
})( jQuery );