// Only God and me know what i'm doing
$(function(){
    const $nav = $('nav');
    let scrollNav = false;
    let menuNavVisible = false;
    let menuIsOpening = false;
    let linksIsOpening = false;
    let phoneMode = window.innerWidth < 1000;
    checkPhoneMode();
    addEventListener('scroll', function(e){
        if(!scrollNav && window.scrollY > $('.header-text').height() + 100){
            scrollNav = true;
            $('#place-holder-nav').show();
            $nav.addClass('nav-fixed');
        }else if(scrollNav && window.scrollY <= $('.header-text').height() + 100){
            scrollNav = false;
            $('#place-holder-nav').hide();
            $nav.removeClass('nav-fixed');
        }
    });
    addEventListener('resize', function(){
        phoneMode = window.innerWidth < 1000;
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
        if(!menuNavVisible) $('#previews').css('background-image', `url('')`)
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
            menuNavVisible = false;
            $('#nav-choco-open-menu').css('top', phoneMode ? '-40px' : '-150px').hide();
            $('#nav-recipes').hide();
            $('#links').slideToggle(function(){
                linksIsOpening = false;
            });
        }else $('#links').slideToggle(function(){
            linksIsOpening = false;
        });
    }
    $('#nav-recipes-link').click(showNavMenu);
    $('#container-burger img').click(showLinks);
    $('#nav-choco').animate({
        'top': '75px'
    }, 10000);

    $('#nav-recipes').children().each(function(i){
        let url;
        switch(i){
            case 0:
                url = '../assets/image\ seb/Mousse\ au\ chocolat/13630550-Mousse-au-chocolat.jpg';
                break;
            case 1:
                url = '../assets/image\ seb/Cake\ tout\ chocolat/14633_3-2_1560-1040.jpg';
                break;
            case 2:
                url = '../assets/image\ seb/fondant\ au\ chocolat/1575898110_fondant-au-chocolat.jpg';
                break;
            case 3:
                url = '../assets/image\ seb/Tarte\ au\ chocolat/chocolate-cake-wood.jpg';
                break;
            case 4:
                url = '../assets/image\ seb/cookies\ chocolat/13370901-Cookies-with-chocolate-chips-and-almonds-on-baking-sheet.jpg';
                break;
            case 5:
                url = '../assets/image\ seb/Glace\ au\ chocolat/cock.jpeg';
                break;
            case 6:
                url = '../assets/image\ seb/Bûche\ de\ Noël\ chocolat/14175853-Bûche-de-Noël-made-from-chocolate-sponge-cake-with-quark-filling.jpg';
                break;
            case 7:
                url = '../assets/image\ seb/Moelleux\ chocolat/histoire_moelleux_au_chocolat.webp';
                break;
            case 8:
                url = '../assets/image\ seb/Truffes\ au\ chocolat/front-view-composition-delicious-chocolate-goodies.jpg';
                break;
            case 9:
                url = '../assets/image\ seb/macarons\ au\ chocolat/0a80c4a2-8ce0-4491-a5e8-e903fdb1fafd_eyqtYhH.jpg';
                break;
        }
        $(this).on('mouseover', ()=>{
            if(!menuNavVisible) return;
            $('#previews').css('background-image', `url('${url}')`)
        });
    })

});

// Now only God knows.