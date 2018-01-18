var myModule = (function () {
    var didScroll;
    var lastScrollTop = 0;
    var delta = 5;
    var navbarHeight = $('.logo').outerHeight();

    var init = function () {
        _setUpListners();
    };

    var _setUpListners = function () {
        // main page
        $('.locations__list').on('mouseleave', onFadeOutSubMenu);
        $('.locations__list').on('mouseover', showLocationsMenu);
        $('.quote--menu--freight').on('mouseover', showPreviewFormFreight);
        $('.quote--menu--moving').on('mouseover', showPreviewFormMoving);
        $('.menu__item--locations').on('mouseleave', onFadeOutSubMenu);
        $('.menu__item--locations').on('mouseenter touchstart', onFadeInSubMenu);
        $('#quotes, .container--logo').on('click', '.logo__action', onFadeInMenuNavigation);
        $(window).on('scroll', fadeInRibbon);
        $('.back-menu').on('touchstart', onFadeOutSubMenu);
        $('.menu__link--moving').on('mouseenter touchstart', showFormPreviewMoving);
        $('.menu__link--moving').on('mouseleave', hideFormPreviewMoving);
        /*$('.menu__link--freight').on('mouseenter touchstart', showFormPreviewFreight);*/
        $('.menu__link--freight').on('mouseleave', hideFormPreviewFreight);
        $('.menu__link--contact').on('click', showContactForm);
        $('.close-form').on('click', hideContactForm);
        $('.logo__link').on('click', function () { localStorage.clear() });
        $('#contact-form').on('submit', submitContactForm);
        $('#quotes').on('submit', submitAjaxForm);
        // quote page
        $(document).ready(onInitUi);
        $(document).on('scroll', onChangeSubPageTitle);
        $(document).on('scroll', onChangeSubPageTitleAbout);
        $('#side-menu__icon').on('click', onAnimateBurgerIcon);
        $('.side-menu__item').on('click', goToTheSection);
        // moving page
        $('.quote--menu--freight--link, .link-moving').on('click', onGoToMovingSection);
        // about us
        $('.content-link--locations').on('click', onStoreMenu);
    };

    var submitAjaxForm = function (e) {
        e.preventDefault();
        
        if(!window.captchaOK) {
            alert('Fill in the capcha!');
            return false;
        }

        var formData = $(this).serialize();
        console.log(formData);
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: formData
        }).done(function () {
            $.fancybox.open("<div class='success-popup'> \
                    <div class='success-popup-wrapper'> \
                        <h1 class='success-popup__title'>THANK YOU!</h1> \
                        <span class='success-popup__content'>Your Jay's moving consultant will be in touch soon!</span> \
                    </div> \
                </div>");
            setTimeout(function () {
                $.fancybox.close();
            }, 5000);
        });
        return false;
    };

    var submitContactForm = function (e) {
        e.preventDefault();

        if(!window.captchaOK) {
            alert('Fill in the capcha!');
            return false;
        }

        var formData = $(this).serialize();
        console.log(formData);
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: formData
        }).done(function () {
            $.fancybox.open("<div class='success-popup'> \
                <div class='success-popup-wrapper'> \
                    <h1 class='success-popup__title'>THANK YOU!</h1> \
                    <span class='success-popup__content'>Your Jay's moving consultant will be in touch soon!</span> \
                </div> \
            </div>");
            setTimeout(function () {
                $.fancybox.close();
            }, 5000);
        });
        return false;
    };

    var onStoreMenu = function (e) {
        e.preventDefault();
        $('.menu').addClass('active-menu');
        $('.logo__action').text('close');
        $('.locations__list').css("display", "flex")
            .hide()
            .delay(300)
            .stop(true)
            .fadeIn();
        $('.quote--menu').fadeOut(0);
        $(window).scrollTop(0);

        if ($(window).width() < 768) {
            $('.menu__list').fadeOut(0);
            $('.back-menu').fadeIn();
        }
    };

    var onGoToMovingSection = function () {
        $('.menu').removeClass('active-menu');
        $('body').removeClass('froze-scroll');
        $('.logo__action').text('menu');
    };

    // main page

    var hideContactForm = function () {
        $('.contact-form').removeClass('active-form');
        $('.quote--menu').stop(true).fadeIn();
    };

    var showContactForm = function () {
        $('.quote--menu').stop(true).fadeOut(0);
        $('.quote--menu--moving').stop(true).fadeOut(0);
        $('.quote--menu--freight').stop(true).fadeOut(0);
        $('.contact-form').toggleClass('active-form');

        if (!$('.contact-form').hasClass('active-form')) {
            $('.quote--menu').stop(true).fadeIn();
        }

        if ($(window).width() < 768) {
            $('.menu').addClass('active-form-bg');
            $('.menu__list').fadeOut(0);
            $('.back-menu').fadeIn();
        }
    };

    var showLocationsMenu = function () {
        $('.locations__list').css("display", "flex");
        $('.quote--menu').stop(true).fadeOut(0);
    };

    var showPreviewFormFreight = function () {
        $('.quote--menu').stop(true).fadeOut(0);
        $('.quote--menu--freight').css("display", "flex");

        if ($(window).width() < 768) {
            $('.menu').addClass('active-freight-bg');
            $('.menu__list').fadeOut(0);
            $('.back-menu').fadeIn();
        }
    };

    var showPreviewFormMoving = function () {
        $('.quote--menu').stop(true).fadeOut(0);
        $('.quote--menu--moving').css("display", "flex");
    };

    var showFormPreviewMoving = function (e) {
        e.preventDefault();

        $('.contact-form').removeClass('active-form');
        $('.quote--menu').stop(true).fadeOut(0);
        $('.quote--menu--moving').css("display", "flex")
            .hide()
            .delay(200)
            .stop(true)
            .fadeIn();

        if ($(window).width() < 767) {
            $('.menu__list').fadeOut(0);
            $('.back-menu').fadeIn();
        }
    };

    var hideFormPreviewMoving = function () {
        $('.quote--menu').stop(true).fadeIn();
        $('.quote--menu--moving').stop(true).fadeOut(0);
    };


    var hideFormPreviewFreight = function () {
        $('.quote--menu').stop(true).fadeIn();
        $('.quote--menu--freight').stop(true).fadeOut(0);
    };

    var onFadeInSubMenu = function (e) {
        e.preventDefault();
        $('.contact-form').removeClass('active-form');
        $('.quote--menu--moving').stop(true).fadeOut(0);
        $('.quote--menu--freight').stop(true).fadeOut(0);

        $('.quote--menu').stop(true).fadeOut(0);
        $('.locations__list').css("display", "flex")
            .hide()
            .delay(300)
            .stop(true)
            .fadeIn();

        if ($(window).width() < 767) {
            $('.menu__list').fadeOut(0);
            $('.back-menu').fadeIn();
        }
    };

    var onFadeOutSubMenu = function () {
        $('.locations__list--mobile').removeClass('active-mobile-submenu--locations');
        $('.back-menu').fadeOut();
        $('.contact-form').removeClass('active-form');
        $('.locations__list, .quote--menu--freight, .quote--menu--moving, .contact-form').stop(true).fadeOut(0);
        $('.menu').removeClass('active-form-bg');
        $('.menu').removeClass('active-freight-bg');
        $('.quote--menu').stop(true).fadeIn();

        if ($(window).width() < 767) {
            $('.menu__list').stop(true).fadeIn();
            $('.back-menu').fadeOut();
        }
    };

    var onFadeInMenuNavigation = function () {
        $('.menu').toggleClass('active-menu');
        $('body').toggleClass('froze-scroll');

        if ($('.menu').hasClass('active-menu')) {
            $(this).text('close');
        } else {
            $(this).text('menu');
        }

        localStorage.clear();
    };

    var fadeInRibbon = function (e) {
        didScroll = true;
        // animate logo
        function hasScrolled() {
            $('.menu').css({
                'transition': 'all 1s ease-in-out'
            });
            var st = $(this).scrollTop();
            // Make sure they scroll more than delta
            if (Math.abs(lastScrollTop - st) <= delta)
                return;
            // If they scrolled down and are past the navbar, add class .nav-up.
            // This is necessary so you never see what is "behind" the navbar.
            if (st > lastScrollTop && st > navbarHeight) {
                // Scroll Down
                $('.logo').removeClass('nav-down').addClass('nav-up');
            } else {
                // Scroll Up
                if (st + $(window).height() < $(document).height()) {
                    $('.logo').removeClass('nav-up').addClass('nav-down');
                }
            }

            lastScrollTop = st;
        }

        setInterval(function () {
            if (didScroll) {
                hasScrolled();
                didScroll = false;
            }
        }, 250);
    };

    var onInitUi = function () {
        var form = $('#quotes');

        $("#quotes").steps({
            headerTag: "h3",
            bodyTag: "section",
            transitionEffect: "fade",
            labels: {
                next: 'Continue',
                finish: 'ok'
            },
            onStepChanging: function (event, currentIndex, newIndex) {
                if (!form.valid()) { return; }

                switch (newIndex) {
                    case 1:
                        $('#quotes').css('background-image', 'url(./img/bg-quote-2.jpg)');
                        $('.actions ul[role] li:nth-child(2) a').addClass('black-button');
                        break;
                    case 2:
                        $('#quotes').css('background-image', 'url(./img/bg-quote-3.jpg)');
                        $('.actions ul[role] li:nth-child(2) a').addClass('black-button');
                        break;
                    case 3:
                        $('#datepickerPickupLabel').on('click', function () {
                            var dataPicker = $('#datepickerPickup').datepicker().data('datepicker');
                            dataPicker.show();
                        });

                        $('#datepickerDeliveryLabel').on('click', function () {
                            var dataPicker = $('#datepickerDelivery').datepicker().data('datepicker');
                            dataPicker.show();
                        });

                        $('#datepickerLabel').on('click', function () {
                            var dataPicker = $('#datepicker').datepicker().data('datepicker');
                            dataPicker.show();
                        });

                        $('#quotes').css('background-image', 'url(./img/bg-quote-4.jpg)');
                        $('.actions ul[role] li:nth-child(2) a').addClass('black-button');
                        break;
                    case 4:
                        $('#quotes').css('background-image', 'url(./img/bg-quote-2.jpg)');
                        $('.actions ul[role] li:nth-child(3) a').addClass('black-button');
                        break;
                    default:
                        $('#quotes').css('background-image', 'url(./img/bg-quote-1.jpg)');
                        $('.actions ul[role] li:nth-child(2) a').removeClass('black-button');
                }

                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            },
            onFinishing: function (event, currentIndex) {
                form.validate().settings.ignore = ":disabled";
                return form.valid();
            },
            onFinished: function (event, currentIndex) {
                form.submit();
            }
        });

        $('#datepicker').datepicker();
        $('#datepickerPickup').datepicker();
        $('#datepickerDelivery').datepicker();

        // fancybox popup about-us
        $('.i4ewOd-pzNkMb-LgbsSe').fancybox();

        $('.modal-content-about').fancybox();
        $(document).on('click', '#your-button', function () { $.fancybox.close(); parent.$.fancybox.close(); });
        $('.link').click(function (event) {
            event.preventDefault();
            window.open('http://chp.tbe.taleo.net/chp01/ats/careers/jobSearch.jsp?org=MULLEN&cws=1');
            $.fancybox.close();
            parent.$.fancybox.close();
        });
        $('.facebook').click(function (event) {
            event.preventDefault();
            window.open('https://www.facebook.com/JaysTransportationGroup');
            $.fancybox.close();
            parent.$.fancybox.close();
        });
        $('.plus').click(function (event) {
            event.preventDefault();
            window.open('https://plus.google.com/+JaysMovingStorageLtdRegina');
            $.fancybox.close();
            parent.$.fancybox.close();
        });

        // Listnav
        $('#locationsdivList1172').listnav({
            includeNums: false,
            includeAll: true,
            showCounts: false,
            initLetter: 'a',
            noMatchText: 'There are no matching entries.'
        });

        // parallax 
        $(window).stellar();

        if ($('.careers').length && $('.about-us').length) {
            var careersTop = $('.careers').position().top - 500;
            var aboutTop = $('.about-us').position().top;
            checkPosition(careersTop, aboutTop);
        }
    }

    // about-us

    var onChangeSubPageTitleAbout = function () {
        if ($('.careers').length && $('.about-us').length) {
            var careersTop = $('.careers').position().top - 500;
            var aboutTop = $('.about-us').position().top;

            checkPositionAbout(careersTop, aboutTop);
        }
    };

    var checkPositionAbout = function (careersTop, aboutTop) {
        if ($(this).scrollTop() >= careersTop) {
            $('.side-menu__title').text('careers');
            $('.side-menu__list li:nth-child(2) a').addClass('current');
            $('.side-menu__list li:nth-child(1) a').removeClass('current');
        } else if ($(this).scrollTop() >= aboutTop) {
            $('.side-menu__title').text('about us');
            $('.side-menu__list li:nth-child(1) a').addClass('current');
            $('.side-menu__list li:nth-child(2) a').removeClass('current');
        }
    };

    var onAnimateBurgerIcon = function () {
        $(this).toggleClass('open');
        $('.side-menu__list').toggleClass('active-submenu');
    };

    var goToTheSection = function () {
        var sectionName = $(this).data('menu');
        $.scrollTo($("#"+ sectionName +""), 800, {
            offset: 0
        });
    }

    // moving-preview page

    var onChangeSubPageTitle = function () {
        if ($('.moving-preview').length && $('.long-distance').length && $('.speciality').length) {
            var movingPreview = $('.moving-preview').position().top;
            var longDistance = $('.long-distance').position().top - 300;
            var speciality = $('.speciality').position().top - 500;

            checkPosition(movingPreview, longDistance, speciality);
        }
    };


    var checkPosition = function (movingPreview, longDistance, speciality) {
        if ($(this).scrollTop() >= movingPreview) {
            $('.side-menu__list li:nth-child(1) a').addClass('current');
            $('.side-menu__list li:nth-child(2) a').removeClass('current');
            $('.side-menu__list li:nth-child(2) a').removeClass('current');
        }

        if ($(this).scrollTop() >= longDistance) {
            $('.side-menu__list li:nth-child(1) a').removeClass('current');
            $('.side-menu__list li:nth-child(2) a').addClass('current');
            $('.side-menu__list li:nth-child(3) a').removeClass('current');
        }

        if ($(this).scrollTop() >= speciality) {
            $('.side-menu__list li:nth-child(1) a').removeClass('current');
            $('.side-menu__list li:nth-child(2) a').removeClass('current');
            $('.side-menu__list li:nth-child(3) a').addClass('current');
        }
    };




    return {
        init: init
    };

})();
myModule.init();

