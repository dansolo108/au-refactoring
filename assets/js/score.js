
var settings = {
    need: 30,
    checkTime: 10,
    IDmetrika: 86113805,
}


var metricsFn = function() {
    console.log(ActiveScore.timer);
    console.log(ActiveScore.need);
    var c1 = this.getCookie(this.cookieName);
    console.log(c1);
    if (ActiveScore.timer >= ActiveScore.need) {
        console.log("событие отправилось");
        ym(settings.IDmetrika, "reachGoal", this.cookieName.slice(0, -3));
    }
};

var ActiveScore = {
    need: settings.need,
    checkTime: settings.checkTime,
    loop: true,
    counter: 0,
    cookieName: "30sec_ap",
    sendFn: null,
    parts: 0,
    active_parts: 0,
    timer: 0,
    events: [
        "touchmove",
        "blur",
        "focus",
        "focusin",
        "focusout",
        "load",
        "resize",
        "scroll",
        "unload",
        "click",
        "dblclick",
        "mousedown",
        "mouseup",
        "mousemove",
        "mouseover",
        "mouseout",
        "mouseenter",
        "mouseleave",
        "change",
        "select",
        "submit",
        "keydown",
        "keypress",
        "keyup",
        "error",
    ],

    setEvents: function() {
        for (var index = 0; index < this.events.length; index++) {
            var eName = this.events[index];
            window.addEventListener(eName, function(e) {
                if (e.isTrusted && ActiveScore.period.events == false) {
                    ActiveScore.period.events = true;
                }
            });
        }
    },

    period: {
        start: 0,
        end: 0,
        events: false,
    },

    init: function(fn) {
        this.calcParts();
        this.setEvents();
        this.setStartCounter();
        if (this.checkCookie()) {
            this.sendFn = fn;
            this.start();
        }
    },

    readLastCookie: function() {
        var absurdlyLarge = 100000;
        for (var i = 1; i < absurdlyLarge; i++) {
            var cookie = this.getCookie(i * this.need + 'sec_ap');
            if (cookie != this.parts * this.parts) return { i: i, cookie: cookie };
        }
        return { i: 1, cookie: 0 };
    },

    setStartCounter: function() {
        var lastCookie = this.readLastCookie();
        this.counter = lastCookie.i - 1;
        this.active_parts = Number(lastCookie.cookie);
        this.cookieName = (this.counter + 1) * this.need + "sec_ap";
    },

    calcParts: function() {
        this.parts = Math.ceil(this.need / this.checkTime);
    },

    setPeriod: function() {
        this.period.start = this.microtime();
        this.period.end = this.period.start + this.checkTime;
        this.period.events = false;
    },

    microtime: function() {
        var now = new Date().getTime() / 1000;
        var s = parseInt(now);
        return s;
    },

    start: function() {
        this.setPeriod();
        this.runPeriod();
    },

    timeoutId: null,

    checkPeriod: function() {
        if (this.period.events == true) {
            this.active_parts = this.active_parts + 1;
            // console.log('В этой секции были действия');
        } else {
            // console.log('В этой секции НЕБЫЛО действия');
        }
        this.timer = this.active_parts * this.checkTime;
        console.log(
            this.active_parts + " / " + this.parts + " [" + this.timer + "]"
        );

        if (this.checkSecs()) {} else {
            this.start();
        }
        this.setCookie(this.cookieName, this.active_parts);
    },

    checkSecs: function() {
        if (this.timer >= this.need) {
            this.send();
            if (this.loop == true) {
                this.counter++;
                this.timer = 0;
                this.active_parts = 0;
                this.cookieName = (this.counter + 1) * this.need + "sec_ap";
                return false;
            } else {
                // console.log('Завершили проверку активности');
                return true;
            }
        }
        return false;
    },

    timeoutFn: function() {
        ActiveScore.checkPeriod();
    },

    runPeriod: function() {
        this.timeoutId = setTimeout(this.timeoutFn, this.checkTime * 1000);
    },

    send: function() {
        if (this.getCookie(this.cookieName) == this.parts * this.parts) {
            this.setStartCounter();
        } else {
            this.setCookie(this.cookieName, this.active_parts * this.active_parts);
        }
        this.sendFn();
    },

    checkCookie: function() {
        var c = this.getCookie(this.cookieName);
        if (c == null) {
            return true;
        } else {
            if (c == '') return true;
            c = parseInt(c);
            if (c >= this.parts) {
                // console.log('Скрипт даже не запустился...');
                if (this.loop == true) {
                    return true;
                }
                return false;
            } else {
                this.active_parts = c;
                return true;
            }
        }
    },

    setCookie: function(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    },
    getCookie: function(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(";");
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == " ") c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    },
    eraseCookie: function(name) {
        document.cookie =
            name + "=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;";
    },
};

ActiveScore.init(metricsFn);

var settings60 = {
    need: 30,
    checkTime: 10,
    IDmetrika: 86113805,
}


var metricsFn60 = function() {
    console.log(ActiveScore60.timer);
    console.log(ActiveScore60.need);
    var c160 = this.getCookie(this.cookieName);
    console.log(c160);
    if (ActiveScore60.timer >= ActiveScore60.need) {
        console.log("событие отправилось");
        ym(settings60.IDmetrika, "reachGoal", this.cookieName.slice(0, -3));
    }
};

var ActiveScore60 = {
    need: settings60.need,
    checkTime: settings60.checkTime,
    loop: true,
    counter: 0,
    cookieName: "60sec_ap",
    sendFn: null,
    parts: 0,
    active_parts: 0,
    timer: 0,
    events: [
        "touchmove",
        "blur",
        "focus",
        "focusin",
        "focusout",
        "load",
        "resize",
        "scroll",
        "unload",
        "click",
        "dblclick",
        "mousedown",
        "mouseup",
        "mousemove",
        "mouseover",
        "mouseout",
        "mouseenter",
        "mouseleave",
        "change",
        "select",
        "submit",
        "keydown",
        "keypress",
        "keyup",
        "error",
    ],

    setEvents: function() {
        for (var index = 0; index < this.events.length; index++) {
            var eName = this.events[index];
            window.addEventListener(eName, function(e) {
                if (e.isTrusted && ActiveScore60.period.events == false) {
                    ActiveScore60.period.events = true;
                }
            });
        }
    },

    period: {
        start: 0,
        end: 0,
        events: false,
    },

    init: function(fn) {
        this.calcParts();
        this.setEvents();
        this.setStartCounter();
        if (this.checkCookie()) {
            this.sendFn = fn;
            this.start();
        }
    },

    readLastCookie: function() {
        var absurdlyLarge = 100000;
        for (var i = 1; i < absurdlyLarge; i++) {
            var cookie = this.getCookie(i * this.need + 'sec_ap');
            if (cookie != this.parts * this.parts) return { i: i, cookie: cookie };
        }
        return { i: 1, cookie: 0 };
    },

    setStartCounter: function() {
        var lastCookie = this.readLastCookie();
        this.counter = lastCookie.i - 1;
        this.active_parts = Number(lastCookie.cookie);
        this.cookieName = (this.counter + 1) * this.need + "sec_ap";
    },

    calcParts: function() {
        this.parts = Math.ceil(this.need / this.checkTime);
    },

    setPeriod: function() {
        this.period.start = this.microtime();
        this.period.end = this.period.start + this.checkTime;
        this.period.events = false;
    },

    microtime: function() {
        var now = new Date().getTime() / 1000;
        var s = parseInt(now);
        return s;
    },

    start: function() {
        this.setPeriod();
        this.runPeriod();
    },

    timeoutId: null,

    checkPeriod: function() {
        if (this.period.events == true) {
            this.active_parts = this.active_parts + 1;
            // console.log('В этой секции были действия');
        } else {
            // console.log('В этой секции НЕБЫЛО действия');
        }
        this.timer = this.active_parts * this.checkTime;
        console.log(
            this.active_parts + " / " + this.parts + " [" + this.timer + "]"
        );

        if (this.checkSecs()) {} else {
            this.start();
        }
        this.setCookie(this.cookieName, this.active_parts);
    },

    checkSecs: function() {
        if (this.timer >= this.need) {
            this.send();
            if (this.loop == true) {
                this.counter++;
                this.timer = 0;
                this.active_parts = 0;
                this.cookieName = (this.counter + 1) * this.need + "sec_ap";
                return false;
            } else {
                // console.log('Завершили проверку активности');
                return true;
            }
        }
        return false;
    },

    timeoutFn: function() {
        ActiveScore60.checkPeriod();
    },

    runPeriod: function() {
        this.timeoutId = setTimeout(this.timeoutFn, this.checkTime * 1000);
    },

    send: function() {
        if (this.getCookie(this.cookieName) == this.parts * this.parts) {
            this.setStartCounter();
        } else {
            this.setCookie(this.cookieName, this.active_parts * this.active_parts);
        }
        this.sendFn();
    },

    checkCookie: function() {
        var c = this.getCookie(this.cookieName);
        if (c == null) {
            return true;
        } else {
            if (c == '') return true;
            c = parseInt(c);
            if (c >= this.parts) {
                // console.log('Скрипт даже не запустился...');
                if (this.loop == true) {
                    return true;
                }
                return false;
            } else {
                this.active_parts = c;
                return true;
            }
        }
    },

    setCookie: function(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    },
    getCookie: function(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(";");
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == " ") c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    },
    eraseCookie: function(name) {
        document.cookie =
            name + "=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;";
    },
};

ActiveScore60.init(metricsFn60);