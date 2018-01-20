/**
 * handle weather
 */
var weather = {
    /**
     * get and display temperature
     */
    getTemperature: function () {
        $temperatureEl = $(".temperature");
        $.ajax({
            url: $temperatureEl.data("remote"),
            success: function (resp) {
                if (resp != "") {
                    $temperatureEl.removeClass("blue orange red");
                    if (parseInt(resp) <= 15) {
                        $temperatureEl.addClass("blue");
                    } else if (parseInt(resp) > 15 && parseInt(resp) < 25) {
                        $temperatureEl.addClass("orange");
                    } else if (parseInt(resp) >= 25) {
                        $temperatureEl.addClass("red");
                    }
                    $(".temperature span").text(resp);
                    $temperatureEl.removeClass("hidden");
                }
            },
            error: function () {
                $temperatureEl.addClass("hidden");
            }
        });
    },
    initUpdateTemperature: function () {
        if (prayer.confData.temperatureEnabled === true) {
            weather.getTemperature();
            setInterval(function () {
                weather.getTemperature();
            }, prayer.oneMinute * 60);
        }
    }
};
