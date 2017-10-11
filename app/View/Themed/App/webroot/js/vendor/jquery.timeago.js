/**
 * Timeago is a jQuery plugin that makes it easy to support automatically
 * updating fuzzy timestamps (e.g. "4 minutes ago" or "about 1 day ago").
 *
 * @name timeago
 * @version 1.3.0
 * @requires jQuery v1.2.3+
 * @author Ryan McGeary
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 *
 * For usage and examples, visit:
 * http://timeago.yarp.com/
 *
 * Copyright (c) 2008-2013, Ryan McGeary (ryan -[at]- mcgeary [*dot*] org)
 */

(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery'], factory);
    } else {
        // Browser globals
        factory(jQuery);
    }
}(function ($) {
    $.timeago = function(timestamp) {
        if (timestamp instanceof Date) {
            return inWords(timestamp);
        } else if (typeof timestamp === "string") {
            return inWords($.timeago.parse(timestamp));
        } else if (typeof timestamp === "number") {
            return inWords(new Date(timestamp));
        } else {
            return inWords($.timeago.datetime(timestamp));
        }
    };
    var $t = $.timeago;

    var weekMilliSeconds = 7 * 24 * 60 * 60 * 1000;

    $.extend($.timeago, {
        settings: {
            refreshMillis: 60000,
            allowFuture: false,
            localeTitle: false,
            cutoff: weekMilliSeconds,
            strings: {
                prefixAgo: null,
                prefixFromNow: null,
                suffixAgo: "ago",
                suffixFromNow: "from now",
                second: "just now",
                seconds: "%d seconds",
                minute: "%d minute",
                minutes: "%d minutes",
                hour: "%d hour",
                hours: "%d hours",
                day: "%d day",
                days: "%d days",
                month: "about a month",
                months: "%d months",
                year: "about a year",
                years: "%d years",
                wordSeparator: " ",
                numbers: []
            }
        },
        inWords: function(distanceMillis) {
            var $l = this.settings.strings;
            var prefix = $l.prefixAgo;
            var suffix = $l.suffixAgo;
            if (this.settings.allowFuture) {
                if (distanceMillis < 0) {
                    prefix = $l.prefixFromNow;
                    suffix = $l.suffixFromNow;
                }
            }

            $diff = Math.abs(distanceMillis) / 1000;

            $years = $months = $weeks = 0;
            $days = Math.floor($diff / 86400);

            $diff = $diff - ($days * 86400);

            $hours = Math.floor($diff / 3600);
            $diff = $diff - ($hours * 3600);

            $minutes = Math.floor($diff / 60);
            $diff = $diff - ($minutes * 60);

            $seconds = Math.round($diff);

            function substitute(stringOrFunction, number) {
                var string = $.isFunction(stringOrFunction) ? stringOrFunction(number, distanceMillis) : stringOrFunction;
                var value = ($l.numbers && $l.numbers[number]) || number;
                return string.replace(/%d/i, value);
            }

            /**
             * JavaScript equivalent of PHP str_replace
             */
            function str_replace(search, replace, subject, count) {
                var i = 0,
                        j = 0,
                        temp = '',
                        repl = '',
                        sl = 0,
                        fl = 0,
                        f = [].concat(search),
                        r = [].concat(replace),
                        s = subject,
                        ra = Object.prototype.toString.call(r) === '[object Array]',
                        sa = Object.prototype.toString.call(s) === '[object Array]';
                s = [].concat(s);
                if (count) {
                    this.window[count] = 0;
                }

                for (i = 0, sl = s.length; i < sl; i++) {
                    if (s[i] === '') {
                        continue;
                    }
                    for (j = 0, fl = f.length; j < fl; j++) {
                        temp = s[i] + '';
                        repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
                        s[i] = (temp).split(f[j]).join(repl);
                        if (count && s[i] !== temp) {
                            this.window[count] += (temp.length - s[i].length) / f[j].length;
                        }
                    }
                }
                return sa ? s : s[0];
            }

            $accuracy = new Array();
            $accuracy['year'] = "day";
            $accuracy['month'] = "day";
            $accuracy['week'] = "day";
            $accuracy['day'] = "day";
            $accuracy['hour'] = "minute";
            $accuracy['minute'] = "minute";
            $accuracy['second'] = "second";

            $fWord = $accuracy['second'];
            if ($years > 0) {
                $fWord = $accuracy['year'];
            } else if (Math.abs($months) > 0) {
                $fWord = $accuracy['month'];
            } else if (Math.abs($weeks) > 0) {
                $fWord = $accuracy['week'];
            } else if (Math.abs($days) > 0) {
                $fWord = $accuracy['day'];
            } else if (Math.abs($hours) > 0) {
                $fWord = $accuracy['hour'];
            } else if (Math.abs($minutes) > 0) {
                $fWord = $accuracy['minute'];
            }

            $fNum = str_replace(new Array('year', 'month', 'week', 'day', 'hour', 'minute', 'second'), new Array(1, 2, 3, 4, 5, 6, 7), $fWord);

            relativeDate = '';
            if ($fNum >= 4 && $days > 0) {
                words = ($days === 1) ? substitute($l.day, 1) : substitute($l.days, $days);
                relativeDate += (relativeDate ? ', ' : '') + words;
            }
            if ($fNum >= 5 && $hours > 0) {
                words = ($hours === 1) ? substitute($l.hour, 1) : substitute($l.hours, $hours);
                relativeDate += (relativeDate ? ', ' : '') + words;
            }
            if ($fNum >= 6 && $minutes > 0) {
                words = ($minutes === 1) ? substitute($l.minute, 1) : substitute($l.minutes, $minutes);
                relativeDate += (relativeDate ? ', ' : '') + words;
            }
            if ($fNum >= 7 && $seconds > 0) {
                if ($seconds === 1) {
                    words = substitute($l.second, 1);
                    suffix = null;
                }
                else {
                    words = substitute($l.seconds, $seconds);
                }
                relativeDate += (relativeDate ? ', ' : '') + words;
            }

            var separator = $l.wordSeparator || "";
            if ($l.wordSeparator === undefined) { separator = " "; }
            return $.trim([prefix, relativeDate, suffix].join(separator));
        },
        parse: function(iso8601) {
            var s = $.trim(iso8601);
            s = s.replace(/\.\d+/,""); // remove milliseconds
            s = s.replace(/-/,"/").replace(/-/,"/");
            s = s.replace(/T/," ").replace(/Z/," UTC");
            s = s.replace(/([\+\-]\d\d)\:?(\d\d)/," $1$2"); // -04:00 -> -0400
            return new Date(s);
        },
        datetime: function(elem) {
            var iso8601 = $(elem).attr("datetime") ? $(elem).attr("datetime") : $(elem).attr("title");
            return $t.parse(iso8601);
        },
        isTime: function(elem) {
            // jQuery's `is()` doesn't play well with HTML5 in IE
            return $(elem).get(0).tagName.toLowerCase() === "time"; // $(elem).is("time");
        }
    });

    // functions that can be called via $(el).timeago('action')
    // init is default when no action is given
    // functions are called with context of a single element
    var functions = {
        init: function(){
            var refresh_el = $.proxy(refresh, this);
            refresh_el();
            var $s = $t.settings;
            if ($s.refreshMillis > 0) {
                setInterval(refresh_el, $s.refreshMillis);
            }
        },
        update: function(time){
            $(this).data('timeago', { datetime: $t.parse(time) });
            refresh.apply(this);
        },
        updateFromDOM: function(){
            $(this).data('timeago', { datetime: $t.parse( $(this).attr("datetime") ? $(this).attr("datetime") : $(this).attr("title") ) });
            refresh.apply(this);
        }
    };

    $.fn.timeago = function(action, options) {
        var fn = action ? functions[action] : functions.init;
        if(!fn){
            throw new Error("Unknown function name '"+ action +"' for timeago");
        }
        // each over objects here and call the requested function
        this.each(function(){
            fn.call(this, options);
        });
        return this;
    };

    function refresh() {
        var data = prepareData(this);
        var $s = $t.settings;

        if (!isNaN(data.datetime)) {
            if ( $s.cutoff == 0 || distance(data.datetime) < $s.cutoff) {
                $(this).text(inWords(data.datetime));
            }
        }
        return this;
    }

    function prepareData(element) {
        element = $(element);
        if (!element.data("timeago")) {
            element.data("timeago", { datetime: $t.datetime(element) });
            var text = $.trim(element.text());
//            if ($t.settings.localeTitle) {
//                element.attr("title", element.data('timeago').datetime.toLocaleString());
//            } else if (text.length > 0 && !($t.isTime(element) && element.attr("title"))) {
//                element.attr("title", text);
//            }
        }
        return element.data("timeago");
    }

    function inWords(date) {
        return $t.inWords(distance(date));
    }

    function distance(date) {
		var currentDateTimeUTC;
		if ($appDateTime) {
			currentDateTimeUTC = $appDateTime;
		}
		else {
			currentDateTimeUTC = new Date();
		}
		return (currentDateTimeUTC.getTime() - date.getTime());
	}

    // fix for IE6 suckage
    document.createElement("abbr");
    document.createElement("time");
}));