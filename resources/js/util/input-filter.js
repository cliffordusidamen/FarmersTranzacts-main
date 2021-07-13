(function() {
    const MAX_NAME_LENGTH = 15;

    const FILTER_REGEX = {
        alpha: "a-zA-Z",
        alphabet: "a-zA-Z",
        alphabets: "a-zA-Z",
        digit: "0-9",
        digits: "0-9",
        dot: "\\.",
        dots: "\\.",
        space: "\\s",
        spaces: "\\s",
        dash: "\\-",
        dashes: "\\-",
        underscore: "\\_",
        underscores: "\\_",
        slash: "\\/\\\\",
        slashes: "\\/\\\\",
        bracket: "\\(\\)",
        brackets: "\\(\\)",
        parenthesis: "\\(\\)",
        parentheses: "\\(\\)",
        symbol_at: "\\@",
        email: "a-zA-Z\\@0-9\\-\\_\\.",
        ref_number: "a-zA-Z0-9\\-\\/\\\\",
        "@": "\\@",
    };

    /** Check for non-essential entry key code **/
    function isNonCharacterKey(key) {
        const nonCharacterKeys = [
            'Meta', // Start key on windows
            'Tab',
            'Escape',
            'Enter',
            'F5',
            'Alt',
            'Shift',
            'Backspace',
            'Control',
            'CapsLock',
            'F1',
            'F2',
            'F3',
            'F4',
            'F5',
            'F6',
            'F7',
            'F8',
            'F9',
            'F10',
            'F11',
            'F12',
            'Delete',
            'Home',
            'ArrowLeft',
            'ArrowUp',
            'ArrowRight',
            'ArrowDown',
            'PageUp',
            'PageDown',
            'Insert',
        ];

        return nonCharacterKeys.indexOf(key) >= 0;
    }

    function getCharacterPrecedingCaret(el) {
        var start = 0, end = 0, normalizedValue, range,
            textInputRange, len, endRange;
    
        if (typeof el.selectionStart == "number" && typeof el.selectionEnd == "number") {
            start = el.selectionStart;
            end = el.selectionEnd;
        } else {
            range = document.createRange();
            console.log(range)
    
            if (range) {
                len = el.value.length;
                normalizedValue = el.value.replace(/\r\n/g, "\n");
    
                // Create a working TextRange that lives only in the input
                textInputRange = el.createTextRange();
                textInputRange.moveToBookmark(range.getBookmark());
    
                // Check if the start and end of the selection are at the very end
                // of the input, since moveStart/moveEnd doesn't return what we want
                // in those cases
                endRange = el.createTextRange();
                endRange.collapse(false);
    
                if (textInputRange.compareEndPoints("StartToEnd", endRange) > -1) {
                    start = end = len;
                } else {
                    start = -textInputRange.moveStart("character", -len);
                    start += normalizedValue.slice(0, start).split("\n").length - 1;
    
                    if (textInputRange.compareEndPoints("EndToEnd", endRange) > -1) {
                        end = len;
                    } else {
                        end = -textInputRange.moveEnd("character", -len);
                        end += normalizedValue.slice(0, end).split("\n").length - 1;
                    }
                }
            }
        }
    
        return {
            start: start,
            end: end
        };
    }

    /** Allow only listed characters **/
    $.fn.allowListed = function (whitelistArg) {
        function prepBlackListRegEx(whitelist) {
            whitelist = whitelist.split(",");
            var whitelistRegExString = "";
            $.each(whitelist, function (index, item) {
                if (undefined != FILTER_REGEX[item.trim()]) {
                    whitelistRegExString += FILTER_REGEX[item.trim()];
                }
            });

            return whitelistRegExString;
        }

        const whitelistAsParam = undefined != whitelistArg  && whitelistArg != ''

        var whitelistRegExString = prepBlackListRegEx(
            whitelistAsParam ? whitelistArg : ($(this).attr("data-cfilter-allow") || "")
        );

        if (
            whitelistAsParam
            || (undefined != this.data("cfilter-allow") && this.data("cfilter-allow").trim() != "")
        ) {
            this.on("keydown", function (e) {
                if (!isNonCharacterKey(e.key)) {
                    if (whitelistRegExString.trim().length > 0) {
                        var blacklistRegEx = new RegExp(
                            "[^" + whitelistRegExString + "]"
                        );
                        var isBlackListedKey;
                        isBlackListedKey = blacklistRegEx.test(e.key);

                        if (isBlackListedKey) {
                            e.preventDefault();
                        }
                    }
                }
            });

            this.on("change paste blur", function (e) {
                if (whitelistRegExString.trim().length > 0) {
                    var blacklistRegEx = new RegExp(
                        "[^" + whitelistRegExString + "]",
                        "g"
                    );
                    filteredValue = $(this)
                        .val()
                        .replace(blacklistRegEx, "");
                    $(this).val(filteredValue);
                }
            });
        }

        return this;
    };

    $.fn.checkNonAlphaNumeric = function () {

        // Prevent consecutive non-alphanumeric keys
        this.on("keyup", function (evt) {
            console.log('Position of cursor--->', evt.target.selectionStart);
            var preceedingCharacter =
                $(this).val().length < 2
                    ? ""
                    : $(this).val().charAt($(this).selectionStart - 1);
            var preceedingCharIsAlphaNumeric = preceedingCharacter == ""
                || /[a-z0-9]/i.test(preceedingCharacter);

            const isAlphaNumKey =  /[a-z0-9]/i.test(evt.key);

            if (!isNonCharacterKey(evt.key) && !preceedingCharIsAlphaNumeric && !isAlphaNumKey) {
                console.log('Bad');
                evt.preventDefault();
                return;
            }
            console.log('Good');
        });

        // On change: remove non-alphanum from begining and end
        // remove occurences of consecutive non-alnum 
        
        return this;
    };

    window.initInputFilters = function() {

        $(':input[data-cfilter="alphanumeric"]')
            .allowListed('alpha, digits');

        $(':input[data-cfilter="name"]')
            .allowListed('alpha');

        $(':input[data-cfilter="full-name"]')
            .allowListed('alpha, space');

        $(':input[data-cfilter="email"]')
            .allowListed('alpha, dash, digits, dot, underscore, symbol_at');

        $(':input[data-cfilter="reference-number"]')
            .allowListed('alpha, digits, slash, dash')
            .checkNonAlphaNumeric();

        $(':input[data-cfilter-allow]').allowListed();
    };
})();
