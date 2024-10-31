jQuery(document).ready(function($) {

    if($('.color-picker').length) {
        $('.color-picker').spectrum({
            type: "component",
            hideAfterPaletteSelect: true,
            showButtons: false,
            allowEmpty: false
        });
    }

    $("body").on("click","a.change-table",function(){
        var table = $(this).data('table');

        $('.change-table').removeClass('active');
        $(this).addClass('active');

        $('.select-table').hide();
        $('#'+table).show();
    });

    (function() {
        $(function() {
            $.tips({
                action: 'focus',
                element: '.error',
                tooltipClass: 'error'
            });
            $.tips({
                action: 'click',
                element: '.clicktips',
                preventDefault: false
            });
            return $.tips({
                action: 'hover',
                element: '.hover',
                preventDefault: false,
                html5: false
            });
        });
    }).call(this);
});