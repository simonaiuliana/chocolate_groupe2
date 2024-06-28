// Only God and me know what i'm doing
$(function(){
    const $nav = $('nav');
    let scrollNav = false;
    let menuNavVisible = false;
    let menuIsOpening = false;
    let linksIsOpening = false;
    let phoneMode = window.innerWidth < 1000;
    checkPhoneMode();
    fixNav(true);
    addEventListener('scroll', fixNav);
    addEventListener('resize', function(){
        phoneMode = window.innerWidth < 1000;
        checkPhoneMode();
    });
    function fixNav(firstTime){
        if(!scrollNav && window.scrollY > $('.header-text').height() + 100){
            scrollNav = true;
            $('#place-holder-nav').show();
            $nav.addClass('nav-fixed');
            hideMenuNav(firstTime === true);
        }else if(scrollNav && window.scrollY <= $('.header-text').height() + 100){
            scrollNav = false;
            $('#place-holder-nav').hide();
            $nav.removeClass('nav-fixed');
        }
    }
    function hideMenuNav(firstTime){
        menuNavVisible = false;
        if(!firstTime)
            $('#nav-choco-open-menu').stop().css('top', phoneMode ? '-45px' : '-150px').hide();
        $('#nav-recipes').hide();
        if(phoneMode)
            $('#links').slideToggle(function(){
                linksIsOpening = false;
            });
    }
    function checkPhoneMode(){
        if(phoneMode){
            if(menuNavVisible){
                $('#links').show();
                $('#nav-choco-open-menu').css('top', '153.64px');
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
        $('#nav-choco-open-menu').show();
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
            'top': !menuNavVisible ? (phoneMode ? '-45px' : '-150px') : (phoneMode ? '153.64px' : '0px')
        }, 2000);
    }
    function showLinks(){
        if(linksIsOpening) return;
        linksIsOpening = true;
        if(menuNavVisible){
            hideMenuNav();
        }else $('#links').slideToggle(function(){
            linksIsOpening = false;
        });
    }
    $('#nav-recipes-link').click(showNavMenu);
    $('#nav-recipes').on('mouseleave', ()=>$('#previews').hide());
    $('#container-burger img').click(showLinks);
    $('#nav-choco').animate({
        'top': '75px'
    }, 10000);

    $('#nav-recipes').children().each(function(i){
        let url;
        const basePath = "../../public/assets/image\ seb/";
        switch(i){
            case 0:
                url = basePath+'Mousse\ au\ chocolat/13630550-Mousse-au-chocolat.jpg';
                break;
            case 1:
                url = basePath+'Cake\ tout\ chocolat/14633_3-2_1560-1040.jpg';
                break;
            case 2:
                url = basePath+'fondant\ au\ chocolat/1575898110_fondant-au-chocolat.jpg';
                break;
            case 3:
                url = basePath+'Tarte\ au\ chocolat/chocolate-cake-wood.jpg';
                break;
            case 4:
                url = basePath+'cookies\ chocolat/13370901-Cookies-with-chocolate-chips-and-almonds-on-baking-sheet.jpg';
                break;
            case 5:
                url = basePath+'Glace\ au\ chocolat/cock.jpeg';
                break;
            case 6:
                url = basePath+'Bûche\ de\ Noël\ chocolat/14175853-Bûche-de-Noël-made-from-chocolate-sponge-cake-with-quark-filling.jpg';
                break;
            case 7:
                url = basePath+'Moelleux\ chocolat/histoire_moelleux_au_chocolat.webp';
                break;
            case 8:
                url = basePath+'Truffes\ au\ chocolat/front-view-composition-delicious-chocolate-goodies.jpg';
                break;
            case 9:
                url = basePath+'macarons\ au\ chocolat/0a80c4a2-8ce0-4491-a5e8-e903fdb1fafd_eyqtYhH.jpg';
                break;
        }
        $(this).on('mouseover', ()=>{
            if(!menuNavVisible || phoneMode) return;
            $('#previews').show()
            $('#previews').css('background-image', `url('${url}')`)
        });
    })

});

// Now only God knows.