/* global prayer, getConfFromLocalStorage */

/**
 * class handling date and time
 * @type object
 */
var dateTime = {
    getCurrentMinute: function () {
        var date = new Date();
        return addZero(date.getMinutes());
    },
    getCurrentHour: function () {
        var date = new Date();
        return addZero(date.getHours());
    },
    /**
     * get day of month ex: 0, 1 ... 30
     * 0 is the first day
     */
    getCurrentDay: function () {
        var date = new Date();
        return date.getDate();
    },
    /**
     * get tomorrow day ex: 0, 1 ... 30
     * 0 is the first day
     */
    getTomorrowDay: function () {
        var date = this.tomorrow();
        return date.getDate();
    },
    /**
     * get current month numbre 0, 1, 2 ...
     */
    getCurrentMonth: function () {
        var date = new Date();
        return date.getMonth();
    },
    /**
     * get current month numbre 01, 02 ... 12
     */
    getCurrentMonthText: function () {
        var date = new Date();
        return addZero(date.getMonth() + 1);
    },
    /**
     * get tomorrow month 01, 02 ... 12
     */
    getTomorrowMonth: function () {
        var date = this.tomorrow();
        return date.getMonth();
    },
    /**
     * get full current year ex: 2017
     */
    getCurrentYear: function () {
        var date = new Date();
        return date.getFullYear();
    },
    /**
     * get current time in hh:ii format or hh:ii:ss format depends on withSeconds arg
     * @param {bool} withSeconds
     * @returns {String}
     */
    getCurrentTime: function (withSeconds) {
        var date = new Date();
        var second = addZero(date.getSeconds());
        var time = this.getCurrentHour() + ':' + this.getCurrentMinute();
        if (withSeconds === true) {
            time += ':' + second;
        }
        return  time;
    },
    /**
     * get current gregorian date ex: Vendredi 26/05/2017
     * @returns {String}
     */
    getCurrentDate: function (lang) {
        var date = new Date();
        if (lang === 'ar') {
            return addZero(this.getCurrentDay()) + "/" + this.getCurrentMonthText() + "/" + date.getFullYear();
        }
        var options = {weekday: "long", year: "numeric", month: "short", day: "numeric"}
        try {
            return date.toLocaleString(lang, options).firstCapitalize();
        } catch (e) {
            options.timeZone = "Europe/Paris";
            return date.toLocaleString(lang, options).firstCapitalize();
        }

    },
    getLastSundayOfMonth: function (month) {
        var date = new Date();
        date.setMonth(month);
        date.setDate(31);
        date.setDate(date.getDate() - date.getDay());
        return date.getDate();
    },
    /**
     * true if date between last sunday of march and last sunday of october
     * @returns {Boolean}
     */
    isDst: function () {
        var date  = new Date();
        date.setHours(4);
        return date.dst();
    },
    /**
     * true if date is last sunday of march or october
     * @returns {Boolean}
     */
    isLastSundayDst: function () {
        var date = new Date();
        var currentMonth = date.getMonth();
        var currentDay = date.getDate();
        if ($.inArray(currentMonth, [2, 9]) !== -1) {
            if (currentDay >= this.getLastSundayOfMonth(currentMonth)) {
                return true;
            }
        }
        return false;
    },
    /**
     * @returns {Date}
     */
    tomorrow: function () {
        var date = new Date();
        date.setDate(date.getDate() + 1);
        date.setHours(3);
        return date;
    }
};