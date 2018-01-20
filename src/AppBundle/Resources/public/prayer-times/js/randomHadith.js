/* global prayer */

/**
 * get and display a random hadith from server
 * It will be shwon every 5 min, except in prayer moment
 */
var randomHadith = {
    init: function () {
        if (prayer.confData.randomHadithEnabled) {
            setInterval(function () {
                if (randomHadith.isAllowed()) {
                    randomHadith.get();
                    setTimeout(function () {
                        randomHadith.hide();
                    }, 2 * prayer.oneMinute);
                }
            }, 5 * prayer.oneMinute);
        }
    },
    /**
     * check if hadith displaying is allowed, 2 conditions
     * 1- not prayer moment
     * 2- not disabling hadith moment
     * @returns {boolean}
     */
    isAllowed: function () {
        if (prayer.isPrayingMoment()) {
            return false;
        }

        if (/^\d\-\d$/.test(prayer.confData.randomHadithIntervalDisabling)) {
            var prayers = prayer.confData.randomHadithIntervalDisabling.split("-");
            var prayer1 = prayer.getTimeByIndex(parseInt(prayers[0]));
            var prayer2 = prayer.getTimeByIndex(parseInt(prayers[1]));

            var prayer1DateTime = prayer.getCurrentDateForPrayerTime(prayer1);
            var prayer2DateTime = prayer.getCurrentDateForPrayerTime(prayer2);
            var date = new Date();
            if (date > prayer1DateTime && date < prayer2DateTime) {
                return false;
            }
        }

        return true;
    },
    get: function () {
        if ($(".main").is(":visible")) { // condition to bypass a display bug
            $randomHadithEl = $(".random-hadith");
            $.ajax({
                url: $randomHadithEl.data("remote"),
                success: function (resp) {
                    if (resp.text !== "") {
                        $(".random-hadith .text div").text(resp.text);
                        randomHadith.show(randomHadith.setFontSize, resp.lang);
                    }
                },
                error: function () {
                    randomHadith.hide();
                    var hadith = $(".random-hadith .text div").text();
                    if (hadith != "") {
                        randomHadith.show();
                    }
                }
            });
        }
    },
    show: function (callback, lang) {
        prayer.nextPrayerCountdown();
        $(".top-content").fadeOut(1000, function () {
            $(".footer").hide();
            $(".random-hadith").fadeIn(1000);
            if (typeof callback !== 'undefined' && typeof lang !== 'undefined') {
                callback(lang);
            }
        });
    },
    hide: function () {
        $(".random-hadith").fadeOut(1000, function () {
            $(".footer").show();
            $(".top-content").fadeIn(1000);
        });
    },
    setFontSize: function (lang) {
        var $textContainer = $('.random-hadith .text');
        var $textContainerDiv = $('.random-hadith .text div');
        $textContainerDiv.css('font-size', '150px');
        $textContainerDiv.css('line-height', '300px');
        if (lang !== "ar") {
            $textContainerDiv.css('line-height', '140%');
        }
        while ($textContainerDiv.height() > $textContainer.height()) {
            $textContainerDiv.css('font-size', (parseInt($textContainerDiv.css('font-size')) - 1) + "px");
            if (lang === "ar") {
                $textContainerDiv.css('line-height', (parseInt($textContainerDiv.css('line-height')) - 2) + "px");
            }
        }
    }
};
