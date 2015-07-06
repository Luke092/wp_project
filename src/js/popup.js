$(document).ready(function(){
    $('#login').click(function() {
        showPopUp($(this).attr('value'));
    });
    $('#register').click(function() {
        showPopUp($(this).attr('value'));
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
    $('a.close, #mask').click(function(){
        $('#mask, .access-popup').fadeOut(300 , function(){
            $('#mask').remove();            
        });
        cleanPopUp(box);
    });

    return false;
}

function cleanPopUp(box){
    $(box + " > .error").html("");
}