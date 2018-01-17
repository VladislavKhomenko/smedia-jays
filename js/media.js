
(function($) {
    var aspect_ratio = $(window).width()/$(window).height();
    var selected_file = 'Jays_cinema_1920x1080';
    var distance = Math.abs((1920/1080) - aspect_ratio);
    var width = 1920;

    if(Math.abs((1280/800) - aspect_ratio) < distance) {
        selected_file = 'Jays_cinema_1280x800';
        distance = Math.abs((1280/800) - aspect_ratio);
        width = 1280;
    }

    if(Math.abs((1280/960) - aspect_ratio) < distance) {
        selected_file = 'Jays_cinema_1280x960';
        distance = Math.abs((1280/960) - aspect_ratio);
        width = 1280;
    }

    if(Math.abs((1280/1024) - aspect_ratio) < distance) {
        selected_file = 'Jays_cinema_1280x1024';
        distance = Math.abs((1280/1024) - aspect_ratio);
        width = 1280;
    }

    var src = "./img/" + selected_file + ".mp4";

    $('#video-background source').attr("src", src);
    $('#video-background').load();
    
    window.vwidth = width;
    
    bgResized();
    
    window.addEventListener("orientationchange", function() {
        bgResized();
    }, false);
    
    window.addEventListener("resize", function() {
        bgResized();
    }, false);
    
    $(window).resize(function(){
        bgResized();
    });
    
    var mql = window.matchMedia("(orientation: portrait)");
    
    mql.addListener(function(m) {
        bgResized();
    });
    
    function bgResized() {
        if(window.vwidth > $(window).width()){
            if($(window).width() > $(window).height()) {
                $('#video-background').css('width', '100vw');
            }
        }
        
        if ($(window).width() < $(window).height()){
            $('#video-background').css('height', '100hw');
        }
    }
    
})( jQuery );