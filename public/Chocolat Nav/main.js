$(function(){
    const $nav = $('nav');
    let scrollNav = false;
    let menuNavVisible = false;
    let menuIsOpening = false;
    addEventListener('scroll', function(e){
        if(!scrollNav && window.scrollY > 100 * 3){
            scrollNav = true;
            $nav.addClass('nav-fixed');
        }else if(scrollNav && window.scrollY <= 100 * 3){
            scrollNav = false;
            $nav.removeClass('nav-fixed');
        }
    });
    $('#nav-recipes-link').on('click', function(){
        if(menuIsOpening) return;
        menuNavVisible = !menuNavVisible;
        menuIsOpening = true;
        $('#nav-recipes').slideToggle(2000, function(){
            menuIsOpening = false;
            $('.drip__drop').toggle();
        });
        $('#nav-choco-open-menu').animate({
            'top': !menuNavVisible ? '-150px' : '0px'
        }, 2000);
    });
    $('#nav-choco').animate({
        'top': '75px'
    }, 10000);
})