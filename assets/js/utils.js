function scrollToAnchor(tag){
    $('html,body').animate({scrollTop: ($(tag).offset().top + 500)},'slow');
}