$('.confirm').click(function(){
    return confirm('Are You Sure?');
});

$('[placeholder]').focus(function(){
    $(this).attr('data-text',$(this).attr('placeholder'));
    $(this).attr('placeholder','');
}).blur(function(){
    $(this).attr('placeholder',$(this).attr('data-text'));
})

