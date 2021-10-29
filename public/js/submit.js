$(function(){
    $(document).on('submit', 'form', function() {
        $('.button-prevent-multiple-submits').attr('disabled','disabled');
        $('.spinner').show();
    })
});
