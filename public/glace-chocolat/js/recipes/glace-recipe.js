$('.owl-carousel').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    autoplay: true,
    autoplayHoverPause: true,
    autoplaySpeed: 1000,
    responsive:{
        0:{
            items:1,
            stagePadding: 0, // Supprime le padding pour les petits écrans
            margin: 0, // Supprime la marge pour les petits écrans
        },
        600:{
            items:2
        },
        1000:{
            items:3
        }
    }
})