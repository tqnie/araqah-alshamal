$(document).ready(function () {
	
    $('.gallery .small-images  img').on('click', function() {
        $(this).addClass('selected').siblings().removeClass("selected")

        $('.gallery .master-img img').hide().attr('src', $(this).attr('src')).fadeIn(500);

        console.log($(this).attr('src'))
    });
	
	
	
    function first_slider() {
        var owl = $(".first-slider");
        owl.owlCarousel({
            loop: true,
            margin: 0,
            navigation: false,
            items: 1,
            smartSpeed: 1000,
            dots: false,
            nav: false,
            autoplay: true,
            center: true,
            autoplayTimeout: 4000,
            dotsEach: false,
        });
	}
    first_slider();
	
    function screen_slider() {
        var owl = $(".screen-slider");
        owl.owlCarousel({
            loop: true,
            margin: 30,
            navigation: true,
            items: 4,
            smartSpeed: 1000,
            dots: true,
            nav: true,
            autoplay: true,
            center: false,
            autoplayTimeout: 2000,
            dotsEach: true,
            responsive: {
                0: {
                    items: 1,
					margin: 0
                },
                480: {
                    items: 1,
					margin: 0
                },
                767: {
                    items: 2,
					margin: 30
                },
                992: {
                    items: 4
                },
                1920: {
                    items: 4
                }
            }
        });
		 $( ".owl-prev").html('<span class="arrow-left"><i class="fa fa-arrow-left" aria-hidden="true"></i></span>');
		 $( ".owl-next").html('<span class="arrow-right"><i class="fa fa-arrow-right" aria-hidden="true"></i></span>');
	}
    screen_slider();
	
	
//in case js in turned off
   $(window).on('load', function () {
        $("#header-scroll").removeClass("h-fixed")
  });

$(window).scroll(function () {
     var sc = $(window).scrollTop()
    if (sc > 1) {
        $("#header-scroll").addClass("h-fixed")
    } else {
        $("#header-scroll").removeClass("h-fixed")
    }
});

//scrollspy

	
    const video = document.getElementById("myVideo");
    const playButton = document.getElementById("playBtn");
    if(playButton){
    playButton.addEventListener("click", () => {
        if (video.paused) {
            video.play();
            playButton.classList.add("hide"); // إخفاء زر التشغيل بعد البدء
        }
    });
}
if(video){
    video.addEventListener("click", () => {
        if (!video.paused) {
            video.pause();
            playButton.classList.remove("hide"); // إعادة زر التشغيل عند الإيقاف
        }
    });
}
	


	
		var menu = document.getElementById("main_menu");
		var btnico = document.getElementById("nav-trigger");
	$('#nav-trigger').on('click', function() {
		
		menu.classList.toggle("active");
		btnico.classList.toggle("cansel");
		$(".overlapblackbg").toggleClass('active');
		
	});
    $(".overlapblackbg ").on('click', function() {
		
		menu.classList.toggle("active");
		btnico.classList.toggle("cansel");
		$(".overlapblackbg").toggleClass('active');

    });	
    $(".menu-link ").on('click', function() {
		
		menu.classList.toggle("active");
		btnico.classList.toggle("cansel");
		$(".overlapblackbg").toggleClass('active');

    });	
	
});

  	/*====================================
    WOW JS
    ======================================*/	

	new WOW().init();

