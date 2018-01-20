/**
 * Messages slider class
 * @type {Object}
 */
var messageInfoSlider = {
    slider: $("#slider"),
    timeToDisplayMessage: 30,
    interval: null,
    ajaxJsonHashCode: '',
    /**
     *  run message slider
     */
    run: function () {
        var screenWidth = $(window).width();
        var nbSlides = $('#slider li').length;
        $('#slider li').width(screenWidth);

        setTimeout(function () {
            messageInfoSlider.setFontSize();
        }, 300);

        var sliderUlWidth = nbSlides * screenWidth;
        $('#slider ul').css({width: sliderUlWidth});
        clearInterval(messageInfoSlider.interval);
        messageInfoSlider.interval = setInterval(function () {
            if (nbSlides > 1) {
                messageInfoSlider.moveRight();
            }
        }, messageInfoSlider.timeToDisplayMessage * 1000);
    },
    /**
     * Get message from server
     */
    get: function () {
        $.ajax({
            dataType: "json",
            url: messageInfoSlider.slider.data("remote"),
            success: function (data) {
                var dataHashCode = JSON.stringify(data).hashCode();
                if (data.messages.length > 0 && dataHashCode !== messageInfoSlider.ajaxJsonHashCode) {
                    messageInfoSlider.timeToDisplayMessage = data.timeToDisplayMessage;
                    var slide;
                    messageInfoSlider.ajaxJsonHashCode = dataHashCode;
                    var items = [];
                    $.each(data.messages, function (i, message) {
                        slide = '<li>';
                        if (message.image) {
                            slide += '<img src="/upload/images/' + message.image + '"/>';
                        } else {
                            slide += '<div class="title">' + message.title + '</div>' + '<div class="content">' + message.content + '</div>';
                        }
                        slide += '</li>';
                        items.push(slide);

                    });
                    messageInfoSlider.slider.html("<ul>" + items.join("") + "</ul>");
                    messageInfoSlider.run();
                }
            }
        });
    },
    moveRight: function () {
        var screenWidth = $(window).width();
        $('#slider ul').animate({
            left: -screenWidth
        }, 1000, function () {
            $('#slider li:first-child').appendTo('#slider ul');
            $('#slider ul').css('left', '');
        });
    },
    setFontSize: function () {
        var $body = $('body');
        $('#slider li').each(function (i, slide) {
            var $slide = $(slide);
            if ($slide.find("img").length > 0) {
                return true;
            }
            $slide.css('font-size', '130px');
            while ($slide.height() > $body.height() - 20) {
                $slide.css('font-size', (parseInt($slide.css('font-size')) - 5) + "px");
            }
        });
    }
};

messageInfoSlider.get();

/**
 * Check for new slides every 5 min
 */
setInterval(function () {
    messageInfoSlider.get();
}, 5 * 60 * 1000);