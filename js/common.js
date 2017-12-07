var myModule = (function (){
    var didScroll;
    var lastScrollTop = 0;
    var delta = 5;
    var navbarHeight = $('.logo').outerHeight();
    
    var init = function () {
        _setUpListners();
    };

    var _setUpListners = function () {
        // main page
        $('.menu__item--locations').on('mouseenter touchstart', onFadeInSubMenu);
        $('.locations__list').on('mouseleave', onFadeOutSubMenu);
        $('.menu__item--locations').on('mouseleave', onFadeOutSubMenu);
        $('.menu__item--locations').on('touchstart', function() {$(this).unbind('mouseleave'); $(this).unbind('mouseenter');});
        $('.locations__link').on('touchstart', function() {$(this).unbind('mouseleave'); $(this).unbind('mouseenter');});
        $('.menu__link').on('touchstart', function() {$(this).unbind('mouseleave'); $(this).unbind('mouseenter');});
        $('.wrapper').on('click', '.logo__action', onFadeInMenuNavigation);
        $(window).on('scroll', fadeInRibbon);
        $('.back-menu').on('touchstart', onFadeOutSubMenu);
        // quote page
        $(document).ready(onInitUi);
        $(document).on('scroll', onChangeSubPageTitle);
        $('#side-menu__icon').on('click', onAnimateBurgerIcon);
        $('.side-menu__item').on('click', goToTheSection);
    };

    // main page

    var onFadeInSubMenu = function() {
        $('.locations__list--mobile').addClass('active-mobile-submenu--locations');
        $('.back-menu').fadeIn();
        $('.menu__list').addClass('active-mobile-submenu');
        $('.quote--menu').stop(true).fadeOut();
        $('.locations__list').css("display", "flex")
        .hide()
        .delay(200)
        .stop(true)
        .fadeIn();
    };
    
    var onFadeOutSubMenu = function() {
        $('.locations__list--mobile').removeClass('active-mobile-submenu--locations');
        $('.back-menu').fadeOut();
        $('.menu__list').removeClass('active-mobile-submenu');
        $('.locations__list').stop(true).fadeOut(300);
        $('.quote--menu').fadeIn();
    };

    var onFadeInMenuNavigation = function() {
        $('.menu').toggleClass('active-menu');
        if($('.menu').hasClass('active-menu')) {
            $(this).text('close');
        } else {
            $(this).text('menu');
        }
    };

    var fadeInRibbon = function(e) {
        didScroll = true;
        // animate logo
        function hasScrolled() {
            $('.menu').css({
                'transition': 'all 1s ease-in-out'
            });
            var st = $(this).scrollTop();
            // Make sure they scroll more than delta
            if(Math.abs(lastScrollTop - st) <= delta)
                return;
            // If they scrolled down and are past the navbar, add class .nav-up.
            // This is necessary so you never see what is "behind" the navbar.
            if (st > lastScrollTop && st > navbarHeight){
                // Scroll Down
                $('.logo').removeClass('nav-down').addClass('nav-up');
            } else {
                // Scroll Up
                if(st + $(window).height() < $(document).height()) {
                    $('.logo').removeClass('nav-up').addClass('nav-down');
                }
            }
            
            lastScrollTop = st;
        }

        setInterval(function() {
            if (didScroll) {
                hasScrolled();
                didScroll = false;
            }
        }, 250);
    };

    var onInitUi = function() {
        
        var form = $('#quotes');

        $("#quotes").steps({
            headerTag: "h3",
            bodyTag: "section",
            transitionEffect: "fade",
            labels: {
                next: 'Continue',
                finish: 'send'
            },
            onStepChanging: function(event, currentIndex, newIndex) {
                if(!form.valid()) {return;} 

                switch(newIndex) {
                    case 1:
                        $('#quotes').css('background-image', 'url(./img/bg-quote-2.png)');
                        $('.actions ul[role] li:nth-child(2) a').addClass('black-button');
                        break;
                    case 2:
                        $('#quotes').css('background-image', 'url(./img/bg-quote-3.png)');
                        $('.actions ul[role] li:nth-child(2) a').addClass('black-button');
                        break;
                    case 3:
                        $('#quotes').css('background-image', 'url(./img/bg-quote-4.jpg)');
                        $('.actions ul[role] li:nth-child(2) a').addClass('black-button');
                        break;
                    case 4:
                        $('#quotes').css('background-image', 'url(./img/bg-quote-1.png)');
                        break;
                    default: 
                        $('#quotes').css('background-image', 'url(./img/bg-quote-1.png)');
                        $('.actions ul[role] li:nth-child(2) a').removeClass('black-button');
                }

                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            },
            onFinishing: function (event, currentIndex)
            {
                form.validate().settings.ignore = ":disabled";
                return form.valid();
            }
        });

        $('#datepickerPickup').datepicker();
        $('#datepickerDelivery').datepicker();

        // parallax 
        $(window).stellar();

        if ($('.careers').length && $('.about-us').length) {
            var careersTop = $('.careers').position().top - 500;
            var aboutTop = $('.about-us').position().top;
            checkPosition(careersTop, aboutTop); 
        }
    }

    // about-us

    var onChangeSubPageTitle = function() {
        if ($('.careers').length && $('.about-us').length) {
            var careersTop = $('.careers').position().top - 500;
            var aboutTop = $('.about-us').position().top;
    
            checkPosition(careersTop, aboutTop); 
        }
    };

    var checkPosition = function(careersTop, aboutTop) {
        if($(this).scrollTop() >= careersTop) {
            $('.side-menu__title').text('careers');
            $('.side-menu__list li:nth-child(2) a').addClass('current');
            $('.side-menu__list li:nth-child(1) a').removeClass('current');
        } else if($(this).scrollTop() >= aboutTop)  {
            $('.side-menu__title').text('about us');
            $('.side-menu__list li:nth-child(1) a').addClass('current');
            $('.side-menu__list li:nth-child(2) a').removeClass('current');
        }  
    };

    var onAnimateBurgerIcon = function() {
        $(this).toggleClass('open');
        $('.side-menu__list').toggleClass('active-submenu');
    };

    var goToTheSection = function() {
        var sectionName = $(this).data('menu');
        $.scrollTo($(`#${sectionName}`), 800, {
			offset: 0
        });
    }
        
        return {
            init: init
        };

})();
myModule.init();