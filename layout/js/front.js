$('.confirm').click(function(){
    return confirm('Are You Sure?');
});

$('[placeholder]').focus(function(){
    $(this).attr('data-text',$(this).attr('placeholder'));
    $(this).attr('placeholder','');
}).blur(function(){
    $(this).attr('placeholder',$(this).attr('data-text'));
});

$('.login-page h1 span').click(function(){
    $(this).addClass('selected').siblings().removeClass('selected');
    $('.login-page form').hide();
    $('.' + $(this).data('class')).fadeIn(100);
});

$('.live-name').keyup(function () {
    $('.live-preview .caption h3').text($(this).val());
});
$('.live-desc').keyup(function () {
    $('.live-preview .caption p').text($(this).val());
});
$('.live-price').keyup(function () {
    $('.live-preview .price-tag').text('$'+$(this).val());
});