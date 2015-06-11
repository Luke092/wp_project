$(document).ready(function() {
    $('#login').click(function() {
        showPopUp($(this).attr('value'));
    });
    $('#register').click(function() {
        showPopUp($(this).attr('value'));
    });
    // When clicking on the button close or the mask layer the popup closed
    $('a.close, #mask').bind('click', function() { 
      $('#mask , .login-popup').fadeOut(300 , function() {
            $('#mask').remove();  
        }); 
        return false;
    });
});

function showPopUp(box){
    //Fade in the Popup
    $(box).fadeIn(300);

    //Set the center alignment padding + border see css style
    var popMargTop = ($(box).height() + 24) / 2; 
    var popMargLeft = ($(box).width() + 24) / 2; 

    $(box).css({ 
        'margin-top' : -popMargTop,
        'margin-left' : -popMargLeft
    });

    // Add the mask to body
    $('body').append('<div id="mask"></div>');
    $('#mask').fadeIn(300);
    $('#mask').click(function(){
        $('#mask , .login-popup').fadeOut(300 , function() {
            $('#mask').remove();
        }); 
    });

    return false;
}