/*jslint vars: false, browser: true, nomen: true, regexp: true */
/*global jQuery */

/*
* jQuery Password Strength plugin for Twitter Bootstrap
*
* Copyright (c) 2008-2013 Tane Piper
* Copyright (c) 2013 Alejandro Blanco
* Dual licensed under the MIT and GPL licenses.
*
*/

(function ($) {
    "use strict";

    var options = {
            errors: [],
            minChar: 6,
            errorMessages: {
                password_too_short: "The Password is too short",
                email_as_password: "Do not use your email as your password",
                same_as_username: "Your password cannot contain your username",
                two_character_classes: "Use different character classes",
                repeated_character: "Too many repetitions",
                sequence_found: "Your password contains sequences"
            },
            scores: [17, 50, 70],
            verdicts: ["Weak", "Average", "Strong"],
            showVerdicts: true,
            showErrors: false,
            raisePower: 1.4,
            usernameField: undefined,
            onLoad: undefined,
            onKeyUp: undefined,
            viewports: {
                progress: "#password",
                verdict: undefined,
                errors: undefined
            },
            // Rules stuff
            ruleScores: {
                wordNotEmail: -100,
                wordLength: -20,
                wordSimilarToUsername: -100,
                wordLowercase: 2,
                wordUppercase: 5,
                wordOneNumber: 4,
                wordThreeNumbers: 7,
                wordOneSpecialChar: 4,
                wordTwoSpecialChar: 7,
                wordUpperLowerCombo: 3,
                wordLetterNumberCombo: 3,
                wordLetterNumberCharCombo: 3,
                wordContainSpace: 4
            },
            rules: {
                wordNotEmail: true,
                wordLength: true,
                wordSimilarToUsername: false,
                wordLowercase: true,
                wordUppercase: true,
                wordOneNumber: true,
                wordThreeNumbers: true,
                wordOneSpecialChar: true,
                wordTwoSpecialChar: true,
                wordUpperLowerCombo: true,
                wordLetterNumberCombo: true,
                wordLetterNumberCharCombo: true,
                wordContainSpace: true
            },
            validationRules: {
                wordNotEmail: function (options, word, score) {
                    return word.match(/^([\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+\.)*[\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+@((((([a-z0-9]{1}[a-z0-9\-]{0,62}[a-z0-9]{1})|[a-z])\.)+[a-z]{2,6})|(\d{1,3}\.){3}\d{1,3}(\:\d{1,5})?)$/i) && score;
                },
                wordLength: function (options, word, score) {
                    var wordlen = word.length,
                        lenScore = Math.pow(wordlen, options.raisePower);
                    if (wordlen < options.minChar) {
                        lenScore = (lenScore + score);
                        options.errors.push(options.errorMessages.password_to_short);
                    }
                    return lenScore;
                },
                wordSimilarToUsername: function (options, word, score) {
                    var username = $(options.usernameField).val();
                    if (username && word.toLowerCase().match(username.toLowerCase())) {
                        options.errors.push(options.errorMessages.same_as_username);
                        return score;
                    }
                    return true;
                },
                wordLowercase: function (options, word, score) {
                    return word.match(/[a-z]/) && score;
                },
                wordUppercase: function (options, word, score) {
                    return word.match(/[A-Z]/) && score;
                },
                wordOneNumber : function (options, word, score) {
                    return word.match(/\d+/) && score;
                },
                wordThreeNumbers : function (options, word, score) {
                    return word.match(/(.*[0-9].*[0-9].*[0-9])/) && score;
                },
                wordOneSpecialChar : function (options, word, score) {
                    return word.match(/.[!,@,#,$,%,\^,&,*,?,_,~]/) && score;
                },
                wordTwoSpecialChar : function (options, word, score) {
                    return word.match(/(.*[!,@,#,$,%,\^,&,*,?,_,~].*[!,@,#,$,%,\^,&,*,?,_,~])/) && score;
                },
                wordUpperLowerCombo : function (options, word, score) {
                    return word.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/) && score;
                },
                wordLetterNumberCombo : function (options, word, score) {
                    return word.match(/([a-zA-Z])/) && word.match(/([0-9])/) && score;
                },
                wordLetterNumberCharCombo : function (options, word, score) {
                    return word.match(/([a-zA-Z0-9].*[!,@,#,$,%,\^,&,*,?,_,~])|([!,@,#,$,%,\^,&,*,?,_,~].*[a-zA-Z0-9])/) && score;
                },
                wordContainSpace : function (options, word, score) {
                    return word.match(/([a-zA-Z0-9]*. .*[a-zA-Z0-9])/) && score;
                }
            }
        },

        setProgressBar = function ($el, score) {
            var options = $el.data("pwstrength"),
                progressbar = options.progressbar,
                $verdict;

            if (options.showVerdicts) {
                if (options.viewports.verdict) {
                    $verdict = $(options.viewports.verdict).find(".password-verdict");
                } else {
                    $verdict = progressbar.find(".password-verdict");
                    if ($verdict.length === 0) {
                        $verdict = $('<span class="password-verdict weak"></span>');
                        $verdict.insertAfter($el);
                    }
                }
            }
            if(score < 0) {
                progressbar.find(".progress-bar").css("width", "1.75%");
                progressbar.find(".sr-only").css("width", "1.75%");
            } else {
                if(score > 70) {
                    score += 15;
                }
                progressbar.find(".progress-bar").css("width", score+"%");
                progressbar.find(".sr-only").css("width", score+"%");
            }
            
            if (score < options.scores[0]) {
                progressbar.find(".progress-bar").addClass("progress-bar-danger").removeClass("progress-bar-info").removeClass("progress-bar-success");
                if (options.showVerdicts) {
                    $verdict.addClass('weak');
                    $verdict.removeClass('strong');
                    $verdict.removeClass('hidden');
                    $verdict.removeClass('medium');
                    $verdict.text(options.verdicts[0]);
                }
            }  else if (score >= options.scores[0] && score < options.scores[1]) {
                progressbar.find(".progress-bar").addClass("progress-bar-info").removeClass("progress-bar-danger").removeClass("progress-bar-success");
                if (options.showVerdicts) {
                    $verdict.removeClass('hidden');
                    $verdict.addClass('medium');
                    $verdict.removeClass('weak');
                    $verdict.removeClass('strong');
                    $verdict.text(options.verdicts[1]);
                }
            } else if (score >= options.scores[1]) {
                progressbar.find(".progress-bar").addClass("progress-bar-success").removeClass("progress-bar-danger").removeClass("progress-bar-info");
                if (options.showVerdicts) {
                    $verdict.removeClass('hidden');
                    $verdict.addClass('strong');
                    $verdict.removeClass('weak');
                    $verdict.removeClass('medium');
                    $verdict.text(options.verdicts[2]);
                }
            } 
        },

        calculateScore = function ($el) {
            var self = this,
                word = $el.val(),
                totalScore = 0,
                options = $el.data("pwstrength");

            $.each(options.rules, function (rule, active) {
                if (active === true) {
                    var score = options.ruleScores[rule],
                        result = options.validationRules[rule](options, word, score);
                    if (result) {
                        totalScore += result;
                    }
                }
            });
            setProgressBar($el, totalScore);
            return totalScore;
        },

        progressWidget = function () {
            return '<div id="progress" class="row"><div class="progress col-lg-10"><div class="progress-bar"><span class="sr-only"></span></div></div></div>';
        },

        methods = {
            init: function (settings) {
                var self = this,
                    allOptions = $.extend(options, settings);

                return this.each(function (idx, el) {
                    var $el = $(el),
                        progressbar,
                        verdict;

                    $el.data("pwstrength", allOptions);

                    $el.on("keyup", function (event) {
                        var options = $el.data("pwstrength");
                        options.errors = [];
                        calculateScore.call(self, $el);
                        if ($.isFunction(options.onKeyUp)) {
                            options.onKeyUp(event);
                        }
                    });

                    progressbar = $(progressWidget());
                    if (allOptions.viewports.progress) {
                        $(allOptions.viewports.progress).append(progressbar);
                    } else {
                        progressbar.insertAfter($el);
                    }
                    progressbar.find(".progress-bar").css("width", "0%");
                    progressbar.find(".sr-only").css("width", "0%");
                    $el.data("pwstrength").progressbar = progressbar;

                    if (allOptions.showVerdicts) {
                        verdict = $('<div class="password-verdict hidden col-lg-2">' + allOptions.verdicts[0] + '</div>');
                        if (allOptions.viewports.progressbar) {
                            $('#progress').append(verdict);
//                            $(allOptions.viewports.progressbar).append(verdict);
                        } else {
                            $('#progress').append(verdict);
//                            verdict.insertAfter(progressbar);
                        }
                    }

                    if ($.isFunction(allOptions.onLoad)) {
                        allOptions.onLoad();
                    }
                });
            },

            destroy: function () {
                this.each(function (idx, el) {
                    var $el = $(el);
                    $el.parent().find("span.password-verdict").remove();
                    $el.parent().find("div.progress").remove();
                    $el.parent().find("ul.error-list").remove();
                    $el.removeData("pwstrength");
                });
            },

            forceUpdate: function () {
                var self = this;
                this.each(function (idx, el) {
                    var $el = $(el),
                        options = $el.data("pwstrength");
                    options.errors = [];
                    calculateScore.call(self, $el);
                });
            },

            outputErrorList: function () {
                this.each(function (idx, el) {
                    var output = '<ul class="error-list">',
                        $el = $(el),
                        errors = $el.data("pwstrength").errors,
                        viewports = $el.data("pwstrength").viewports,
                        verdict;
                    $el.parent().find("ul.error-list").remove();

                    if (errors.length > 0) {
                        $.each(errors, function (i, item) {
                            output += '<li>' + item + '</li>';
                        });
                        output += '</ul>';
                        if (viewports.errors) {
                            $(viewports.errors).html(output);
                        } else {
                            output = $(output);
                            verdict = $el.parent().find("span.password-verdict");
                            if (verdict.length > 0) {
                                el = verdict;
                            }
                            output.insertAfter(el);
                        }
                    }
                });
            },
            
            addRule: function (name, method, score, active) {
                this.each(function (idx, el) {
                    var options = $(el).data("pwstrength");
                    options.rules[name] = active;
                    options.ruleScores[name] = score;
                    options.validationRules[name] = method;
                });
            },

            changeScore: function (rule, score) {
                this.each(function (idx, el) {
                    $(el).data("pwstrength").ruleScores[rule] = score;
                });
            },

            ruleActive: function (rule, active) {
                this.each(function (idx, el) {
                    $(el).data("pwstrength").rules[rule] = active;
                });
            }
        };

    $.fn.pwstrength = function (method) {
        var result;
        if (methods[method]) {
            result = methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === "object" || !method) {
            result = methods.init.apply(this, arguments);
        } else {
            $.error("Method " +  method + " does not exist on jQuery.pwstrength");
        }
        return result;
    };
}(jQuery));
