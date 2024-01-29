'use strict';
$(document).ready(function () {
	$('.filter_in_btn').on('click', function () {
		$('.category-sidebar').addClass('active');
	})
	$('.close-sidebar').on('click', function () {
		$('.category-sidebar').removeClass('active');
	})
})
// menu options custom affix


// ============== Header Hide Click On Body Js Start ========
$('.navbar-toggler').on('click', function() {
	$('.body-overlay').toggleClass('show-overlay')
  }); 
  $('.body-overlay').on('click', function() {
	$('.navbar-toggler').trigger('click')
	$(this).removeClass('show-overlay');
  }); 
  // =============== Header Hide Click On Body Js End =========

var fixed_top = $(".header__bottom");
$(window).on("scroll", function () {
	if ($(window).scrollTop() > 50) {
		fixed_top.addClass("animated fadeInDown menu-fixed");
	}
	else {
		fixed_top.removeClass("animated fadeInDown menu-fixed");
	}
});


$('.navbar-toggler').on('click', function () {
	$('.header__bottom').toggleClass('active');
});

// mobile menu js
$(".navbar-collapse>ul>li>a, .navbar-collapse ul.sub-menu>li>a").on("click", function () {
	const element = $(this).parent("li");
	if (element.hasClass("open")) {
		element.removeClass("open");
		element.find("li").removeClass("open");
	}
	else {
		element.addClass("open");
		element.siblings("li").removeClass("open");
		element.siblings("li").find("li").removeClass("open");
	}
});

let img = $('.bg_img');
img.css('background-image', function () {
	let bg = ('url(' + $(this).data('background') + ')');
	return bg;
});

$(function () {
	$('[data-toggle="tooltip"]').tooltip()
})

// lightcase plugin init
$('a[data-rel^=lightcase]').lightcase();

/* Get the documentElement (<html>) to display the page in fullscreen */
let elem = document.documentElement;






// banner-Slider-Start//
$('.responsive').slick({
	autoplay: true,
	dots: false,
	arrows: false,
	infinite: true,
	speed: 600,
	slidesToShow: 3,
	slidesToScroll: 1,
	responsive: [{
			breakpoint: 1024,
			settings: {
				slidesToShow: 3,
				slidesToScroll: 3,
				infinite: true,
				dots: false
			}
		},
		{
			breakpoint: 600,
			settings: {
				slidesToShow: 2,
				slidesToScroll: 2
			}
		},
		{
			breakpoint: 480,
			settings: {
				slidesToShow: 1,
				slidesToScroll: 1
			}
		}
	]
});
// banner-Slider-End//

// mainSlider
function mainSlider() {
	var BasicSlider = $('.hero__slider');
	BasicSlider.on('init', function (e, slick) {
		var $firstAnimatingElements = $('.single__slide:first-child').find('[data-animation]');
		doAnimations($firstAnimatingElements);
	});
	BasicSlider.on('beforeChange', function (e, slick, currentSlide, nextSlide) {
		var $animatingElements = $('.single__slide[data-slick-index="' + nextSlide + '"]').find('[data-animation]');
		doAnimations($animatingElements);
	});
	BasicSlider.slick({
		autoplay: false,
		autoplaySpeed: 10000,
		dots: false,
		fade: true,
		arrows: true,
		nextArrow: '<div class="next"><i class="las la-long-arrow-alt-right"></i></div>',
		prevArrow: '<div class="prev"><i class="las la-long-arrow-alt-left"></i></div>',
		responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					infinite: true,
				}
			},
			{
				breakpoint: 991,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					arrows: false
				}
			},
			{
				breakpoint: 767,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					arrows: false
				}
			}
		]
	});
	function doAnimations(elements) {
		var animationEndEvents = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
		elements.each(function () {
			var $this = $(this);
			var $animationDelay = $this.data('delay');
			var $animationType = 'animated ' + $this.data('animation');
			$this.css({
				'animation-delay': $animationDelay,
				'-webkit-animation-delay': $animationDelay
			});
			$this.addClass($animationType).one(animationEndEvents, function () {
				$this.removeClass($animationType);
			});
		});
	}
}
mainSlider();


$('.story-thumb-slider').slick({
	slidesToShow: 1,
	slidesToScroll: 1,
	arrows: false,
	asNavFor: '.story-slider'
});
$('.story-slider').slick({
	slidesToShow: 1,
	slidesToScroll: 1,
	asNavFor: '.story-thumb-slider',
	dots: true,
	arrows: false
});


// Animate the scroll to top
$(".scroll-to-top").on("click", function (event) {
	event.preventDefault();
	$("html, body").animate({ scrollTop: 0 }, 300);
});

//================================= Scroll To Top Icon Js Start =========================
var btn = $('.scroll-top');

$(window).scroll(function () {
	if ($(window).scrollTop() > 300) {
		btn.addClass('show');
	} else {
		btn.removeClass('show');
	}
});


btn.on('click', function (e) {
	e.preventDefault();
	$('html, body').animate({ scrollTop: 0 }, '300');
});
//================================= Scroll To Top Icon Js End ===========================

//preloader js code
$(".preloader").delay(300).animate({
	"opacity": "0"
}, 300, function () {
	$(".preloader").css("display", "none");
});

$(".progressbar").each(function () {
	$(this).find(".bar").animate({
		"width": $(this).attr("data-perc")
	}, 3000);
	$(this).find(".label").animate({
		"left": $(this).attr("data-perc")
	}, 3000);
});

new WOW().init();


//required
$.each($('input, select, textarea'), function (i, element) {
	if (element.hasAttribute('required')) {
	  $(element).closest('.form-group').find('label').first().addClass('required');
	}
  
  });

//data-label of table-dynamic//
Array.from(document.querySelectorAll('table')).forEach(table => {
	let heading = table.querySelectorAll('thead tr th');
	Array.from(table.querySelectorAll('tbody tr')).forEach(row => {
	  Array.from(row.querySelectorAll('td')).forEach((column, i) => {
		(column.colSpan == 100) || column.setAttribute('data-label', heading[i].innerText)
	  });
	});
  });


////...........
$(".progressbar").each(function () {
	$(this).find(".bar").animate({
		"width": $(this).attr("data-perc")
	}, 3000);
	$(this).find(".label").animate({
		"left": $(this).attr("data-perc")
	}, 3000);
});


var x = setInterval(function () {
	var deadline = $('.days-left').each(function (item) {

		var countDownDate = new Date($(this).data('deadline')).getTime();
		// Get today's date and time
		var now = new Date().getTime();

		// Find the distance between now and the count down date
		var distance = countDownDate - now;

		// Time calculations for days, hours, minutes and seconds
		var days = Math.floor(distance / (1000 * 60 * 60 * 24));
		var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		var seconds = Math.floor((distance % (1000 * 60)) / 1000);
		$(this).children('.day').text(days + 'D');
		$(this).children('.hour').text(hours + 'H');
		$(this).children('.minute').text(minutes + 'M');
		$(this).children('.sec').text(seconds + 'S');

		if (distance < 0) {
			clearInterval(x);
			document.getElementById("demo").innerHTML = "EXPIRED";
		}

	});
	// If the count down is finished, write some text

}, 1000);
