// Only God and me know what i'm doing
$(function(){
    const $nav = $('nav');
    let scrollNav = false;
    let menuNavVisible = false;
    let menuIsOpening = false;
    let linksIsOpening = false;
    let phoneMode = window.innerWidth < 600;
    let linksIsVisible = !phoneMode;
    checkPhoneMode();
    addEventListener('scroll', function(e){
        if(!scrollNav && window.scrollY > 100 * 3){
            scrollNav = true;
            $nav.addClass('nav-fixed');
        }else if(scrollNav && window.scrollY <= 100 * 3){
            scrollNav = false;
            $nav.removeClass('nav-fixed');
        }
    });
    addEventListener('resize', function(){
        phoneMode = window.innerWidth < 600;
        linksIsVisible = !phoneMode;
        checkPhoneMode();
    });
    function checkPhoneMode(){
        if(phoneMode){
            if(menuNavVisible){
                $('#links').show();
                $('#nav-choco-open-menu').css('top', '155px');
            } 
            else{
                $('#links').hide();
                $('#nav-choco-open-menu').hide();
            } 
        }else{
            if(menuNavVisible) $('#nav-choco-open-menu').css('top', '0px');
            else $('#nav-choco-open-menu').css('top', '-150px');
            $('#nav-choco-open-menu').show();
            $('#links').show();
        }
    }
    function showNavMenu(){
        if(menuIsOpening) return;
        menuIsOpening = true;
        menuNavVisible = !menuNavVisible;
        if(phoneMode && menuNavVisible){
            $('#nav-choco-open-menu').show();
        }
        if(!menuNavVisible && phoneMode) setTimeout(() => {
            $('#nav-choco-open-menu').hide();
        }, 1500);
        $('#nav-recipes').slideToggle(2000, function(){
            menuIsOpening = false;
        });
        $('#nav-choco-open-menu').animate({
            'top': !menuNavVisible ? (phoneMode ? '-40px' : '-150px') : (phoneMode ? '155px' : '0px')
        }, 2000);
    }
    function showLinks(){
        if(linksIsOpening) return;
        linksIsOpening = true;
        if(menuNavVisible){
            showNavMenu();
            setTimeout(() => {
                $('#links').slideToggle(function(){
                    linksIsOpening = false;
                });
            }, 2000);
        }else $('#links').slideToggle(function(){
            linksIsOpening = false;
        });
    }
    $('#nav-recipes-link').click(showNavMenu);
    $('#container-burger img').click(showLinks);
    $('#nav-choco').animate({
        'top': '75px'
    }, 10000);
});

// Now only God knows.