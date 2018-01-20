/* ##### string ##### */
String.prototype.firstCapitalize = function () {
    return this.charAt(0).toUpperCase() + this.slice(1);
};

/**
 * check if string match time hh:mm
 * @returns {Boolean}
 */
String.prototype.matchTime = function () {
    var regex = /^\d{2}:\d{2}$/g;
    return regex.test(this);
};

String.prototype.hashCode = function() {
    var hash = 0;
    if (this.length == 0) return hash;
    for (i = 0; i < this.length; i++) {
        char = this.charCodeAt(i);
        hash = ((hash<<5)-hash)+char;
        hash = hash & hash; // Convert to 32bit integer
    }
    return hash;
}


/* ##### time ##### */
Date.prototype.stdTimezoneOffset = function () {
    var jan = new Date(this.getFullYear(), 0, 1);
    var jul = new Date(this.getFullYear(), 6, 1);
    return Math.max(jan.getTimezoneOffset(), jul.getTimezoneOffset());
}

Date.prototype.dst = function () {
    return this.getTimezoneOffset() < this.stdTimezoneOffset();
}


/* ##### others ##### */
/**
 * add zero to number if < to 10, ex : 1 becomes 01
 * @param {integer} value
 * @returns {String}
 */
function addZero(value) {
    if (value < 10) {
        value = '0' + value;
    }
    return value;
}

/**
 * Reload page if internet connection is available
 */
function reloadIfConnected() {
    $.ajax({
        url: window.location.href,
        success: function () {
            location.reload();
        }
    });
}
