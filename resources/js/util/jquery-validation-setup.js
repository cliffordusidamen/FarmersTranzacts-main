require('jquery-validation')

(function() {
    /**
     * =============================================================================
     * CUSTOM VALIDATION METHODS
     * =============================================================================
     */
    window.trimNormalizer = function(value) {
        return $.trim(value);
    };

    window.dateValueNormalizer = function(value, element) {
        var valueTrim = $.trim(value);
        if (valueTrim != '' || /[\-]/ig.test(valueTrim) ) {
            var dateParts = valueTrim.split('-');

            return dateParts.reverse().join('-');
        }

        return valueTrim;
    };

    window.timeNormalizer = function(value, element) {
        const normalized = value.replace(/^(0\:)/, '12:')
        return normalized;
    };

    window.submitHandler = function(form) {
        $(form).find('[type="submit"]').attr('disabled', true);
        form.submit();
    };

    function importMomentJS()
    {
        if (undefined == moment) {
            let myScript = document.createElement("script");
            myScript.setAttribute("src", "https://cdnjs.com/libraries/moment.js");
            document.body.appendChild(myScript);
        }
    }

    function setValidationMessage(context, element, validationFunction, message)
    {
        var fieldName = $(element).attr('name');
        var hasCustomMessage = undefined == $(element).data(validationFunction + '-default')
            && undefined != context.settings.messages[fieldName]
            && undefined != context.settings.messages[fieldName][validationFunction];
        
        if (hasCustomMessage) {
            return;
        }
        $(element).data(validationFunction + '-default', '1');

        context.settings.messages[fieldName]
            = undefined != context.settings.messages[fieldName]
            ? context.settings.messages[fieldName]
            : {};

        context.settings.messages[fieldName][validationFunction] = message;
    }

    /** Custom person_name validation method for jquery validate plugin **/
    var person_name = function ( value, element, params ) {
        return this.optional( element ) || !(/[^a-zA-Z\-\s]/ig.test(value));
    };
    $.validator.addMethod('person_name', person_name, "Enter a valid name");


    /** Custom size validation method for jquery validate plugin **/
    var size = function ( value, element, params ) {
        return this.optional( element ) || value.length == parseInt(params);
    };
    $.validator.addMethod('size', size, $.validator.format("Enter exactly {0} characters"));


    /** Custom reference number (alphanumeric, dash, underscore, slashes and/or spaces) input method for jquery validate plugin **/
    var referenceNumber = function ( value, element, params ) {
        // check for start/end with non-alphanum and successive non-alphanum
        return this.optional( element ) || !(/[^a-zA-Z0-9\/\s\-\_\\]/ig.test(value));
    };
    $.validator.addMethod('referenceNumber', referenceNumber, $.validator.format("This field can only contain alphabets, numbers, dash, underscore, slash and space"));

    /** Validate date using moment.js **/
    var dateMoment = function (value, element, params) {
        if (this.optional(element)) {
            return true;
        }
        
        importMomentJS();

        var aYearFromToday = parseInt(moment().add(100, 'years').format('YYYY'));
        var dateEntered = moment(value, params || 'DD-MM-YYYY', true);

        if (!dateEntered.isValid()) {
            return false;
        }

        return parseInt(dateEntered.format('YYYY')) <= aYearFromToday;
    }

    $.validator.addMethod('dateMoment', dateMoment, $.validator.format("Enter a valid date in the format '{0}'."));


    // Date validations based on comparison
    function prepMomentDateFromString(dateString, format)
    {
        format = format || 'YYYY-MM-DD';
        var formatSeparator = format.match(/[^a-z0-9]/i);
        var separator = formatSeparator.length ? formatSeparator[0] : '-';
        var separatorRegEx = new RegExp('[\\' + separator + ']');

        if (dateString == 'yesterday') {
            return moment().subtract(1, 'day');
        }
        
        if (dateString == 'tomorrow') {
            return moment().add(1, 'day');
        }
        
        if (dateString == 'today'){
            return moment();
        }
        
        if (!separatorRegEx.test(dateString)) {
            var otherFieldValue = $(dateString).length > 0 
                && $(dateString).val().trim();
            var otherFieldIsEmpty = otherFieldValue.length == 0
            var otherFieldDate = !otherFieldIsEmpty && moment(otherFieldValue, format, true);
            if (otherFieldIsEmpty || !otherFieldDate.isValid()) {
                return null;
            }
            return otherFieldDate;
        }

        var sampleDate = moment(dateString, format, true);
        return sampleDate.isValid() ? sampleDate : moment();
    }

    function getDateDifference(dateString1, dateString2, format)
    {
        var date1 = prepMomentDateFromString(dateString1, format || 'YYYY-MM-DD');
        var date2 = prepMomentDateFromString(dateString2, format || 'YYYY-MM-DD');
        if (date1 === null || date2 === null) {
            return null;
        }

        return date1.diff(date2);
    }

    function prepRefDateStringAndFormat(params)
    {
        var attributes = {};
        if (typeof params == "string") {
            var dateString = params;
            var format = 'YYYY-MM-DD';
        } else if (Array.isArray(params)) {
            var dateString = params[0] ?? 'today';
            var format = params[1] ?? 'YYYY-MM-DD';
            attributes = params[2] ?? attributes;
        } else {
            var format = params.format ?? 'YYYY-MM-DD';
            var dateString = params.reference ?? moment().format(format);
            attributes = params.attributes ?? attributes;
        }

        return {format, dateString, attributes};
    }
    
    function isValidDateComparison(dateString1, dateString2, comparisonOperator, format)
    {
        var useFormat = undefined == format ? 'YYYY-MM-DD' : format;
        var difference = getDateDifference(dateString1, dateString2, useFormat);

        return difference === null ? true : eval(difference + comparisonOperator + '0');
    }

    function getComparisonFunctionOperator(comparisonFunction)
    {
        if (
            undefined == comparisonFunction
            || comparisonFunction.constructor !== String
        ) {
            return null;
        }

        var comparisonOperator = {
            'dateBefore': '<',
            'dateBeforeOrEqual': '<=',
            'dateAfter': '>',
            'dateAfterOrEqual': '>=',
        };

        return comparisonOperator[comparisonFunction] || null;
    }

    function getDateComparisonMessage(refDate, comparisonFunction)
    {
        if (refDate == 'date') {
            return 'Enter a valid date';
        }

        var operatorMessages = {
            'dateBefore': "Date should be earlier than '{0}'.",
            'dateBeforeOrEqual': "Date should be same as '{0}' or earlier.",
            'dateAfter': "Date should be after '{0}'.",
            'dateAfterOrEqual': "Date should be same as '{0}' or later.",
        };

        return operatorMessages[comparisonFunction].replace('{0}', refDate) || null;
    }

    function validateDateComparisonInput(context, value, element, params, comparisonFunction)
    {
        if (context.optional(element)) {
            return true;
        }

        var data = prepRefDateStringAndFormat(params);
        

        var fieldName = $(element).attr('name');
        var datesToCompare = data.dateString.split(/[\,]/);
        var isValid = true;
        var comparisonOperator = getComparisonFunctionOperator(comparisonFunction);
        var invalidDateCheck = 'date';

        if (!$.validator.methods.dateMoment.call( context, value, element, data.format )) {
            isValid = false;
        } else {

            for (let index = 0; index < datesToCompare.length; index++) {
                isValid = isValidDateComparison(value, datesToCompare[index].trim(), comparisonOperator, data.format);
                if (!isValid) {
                    invalidDateCheck = datesToCompare[index].trim();
                    break;
                }
            };
        }

        var message = getDateComparisonMessage(
            data.attributes[invalidDateCheck] || invalidDateCheck.replace(/[^a-z0-9]/ig, ' '),
            comparisonFunction
        );
        
        if (!isValid) {
            setValidationMessage(context, element, comparisonFunction, message);
        }

        return isValid;
    }

    /** Validate date to be before a particular date, today or yesterday, other fields(separated by comma(s)) **/
    var dateBefore = function (value, element, params) {
        return validateDateComparisonInput(this, value, element, params, 'dateBefore');
    };
    $.validator.addMethod('dateBefore', dateBefore);

    /** Validate date to be before or equal to particular date, today or yesterday **/
    var dateBeforeOrEqual = function (value, element, params) {
        return validateDateComparisonInput(this, value, element, params, 'dateBeforeOrEqual');
    };
    $.validator.addMethod('dateBeforeOrEqual', dateBeforeOrEqual);

    /** Validate date to be after a particular date, today or yesterday, other fields(separated by comma(s)) **/
    var dateAfter = function (value, element, params) {
        return validateDateComparisonInput(this, value, element, params, 'dateAfter');
    };
    $.validator.addMethod('dateAfter', dateAfter);

    /** Validate date to be after or equal to a particular date, today or yesterday, other fields(separated by comma(s)) **/
    var dateAfterOrEqual = function (value, element, params) {
        return validateDateComparisonInput(this, value, element, params, 'dateAfterOrEqual');
    };
    $.validator.addMethod('dateAfterOrEqual', dateAfterOrEqual);

    // VALUE LESS THAN
    $.validator.addMethod( "lessThan", function( value, element, param ) {

        if (this.optional(element) || param === false) {
            return true;
        }

        if (isNaN(Number(value))) {
            return false;
        }

        var limitNumber = Number(param);
        if (limitNumber !== NaN) {
            setValidationMessage(
                this,
                element,
                "lessThan",
                "Enter a number less than "
                    + limitNumber.toFixed(Number.isInteger(limitNumber) ? 0 : 2)
            );
            return Number(value) < limitNumber;
        }

        var target = $( param );
    
        if ( this.settings.onfocusout && target.not( ".validate-lessThan-blur" ).length ) {
            target.addClass( "validate-lessThan-blur" ).on( "blur.validate-lessThan", function() {
                $( element ).valid();
            } );
        }
    
        return value < target.val();
    }, "Please enter a lesser value." );

    // EXTENSION VALIDATION
    $.validator.addMethod( "extension", function( value, element, param ) {
        if (this.optional( element )) {
            return true;
        }
        param = typeof param === "string" ? param.replace( /,/g, "|" ) : "png|jpe?g|gif";
        var isValidExtension = value.match( new RegExp( "\\.(" + param + ")$", "i" ) );

        if (!isValidExtension) {
            setValidationMessage(
                this,
                element,
                "extension",
                "This must be a file of type: "
                    + param.replace(/\|/g, ', ')
            );
        }

        return isValidExtension;
    }, $.validator.format( "Select a file with a valid extension." ) );

    // MINUMUM FILE SIZE
    $.validator.addMethod( "min_file_size", function( value, element, param ) {

        if (element.files.length == 0) {
            return false;
        }

        var minSize = isNaN(Number(param)) ? 0 : Number(param);
        var file = element.files[0];

        return file.size >= (minSize * 1000) ;

    }, $.validator.format( "Minimum file size is {0}Kb." ) );

    // MAXIMUM FILE SIZE
    $.validator.addMethod( "max_file_size", function( value, element, param ) {
        if (this.optional( element )) {
            return true;
        }

        if (element.files.length == 0) {
            return false;
        }

        var maxSize = isNaN(Number(param)) ? 0 : Number(param);
        var file = element.files[0];

        return file.size <= (maxSize * 1000) ;

    }, $.validator.format( "Maximum file size is {0}Kb." ) );

    /** EXACT LENGTH OF CHARACTERS **/
    $.validator.addMethod( "length", function( value, element, param ) {
        return this.optional( element ) || value.trim().length == param;
    }, $.validator.format("Value should have exactly {0} character.") );


    /**
     * =============================================================================
     * Time validation
     * =============================================================================
     */
    window.strToMomentTime = function(timeStr) {
        importMomentJS();
        const hasSpace = /[\s]/ig.test(timeStr)
        const hasAMPM = /[a-z]/ig.test(timeStr)

        var format = hasSpace || hasAMPM
            ? (hasSpace ? 'YYYY-MM-DD hh:mm a' : 'YYYY-MM-DD hh:mma')
            : 'YYYY-MM-DD HH:mm';

        var timeObj = moment(moment().format('YYYY-MM-DD') + ' ' +  timeStr, format, true);

        return timeObj.isValid() ? timeObj : false;
    };

    function getTimeFromString(timeStrOrElemId) {
        const isTimeStr = /[\:]/ig.test(timeStrOrElemId);
        if (timeStrOrElemId === 'now') {
            return moment();
        }
        
        if (isTimeStr) {
            if (!strToMomentTime(timeStrOrElemId)) {
                console.warn('Invalid comparison time "' + timeStrOrElemId + '" for "' + element.getAttribute('name') + '"');
            }
            return strToMomentTime(timeStrOrElemId) || moment();
        }
        
        if (
            !document.getElementById(timeStrOrElemId)
            || !strToMomentTime(document.getElementById(timeStrOrElemId).value)
        ) {
            return null;
        }

        return  strToMomentTime(document.getElementById(timeStrOrElemId).value);
    }

    function validateTimeComparison(context, comparisonFunction, value, element, param)
    {
        if (context.optional(element)) {
            return true;
        }

        if (!strToMomentTime(value)) {
            setValidationMessage(context, element, comparisonFunction, 'Value entered should be a valid time.');
            return false;
        }

        var thisElemTime = strToMomentTime(value);
        var comparisonTime = getTimeFromString(param);

        if (comparisonTime === null) {
            return true;
        }

        return {
            thisElemTime,
            comparisonTime,
            difference: thisElemTime.diff(comparisonTime),
        }
    }

    var timeMoment = function (value, element, params) {
        if (this.optional(element)) {
            return true;
        }

        return strToMomentTime(value);
    }
    $.validator.addMethod('timeMoment', timeMoment, $.validator.format("Value entered should be a valid time."));

    var timeAfter = function (value, element, param) {
        const validationResult = validateTimeComparison(this, 'timeAfter', value, element, param);

        if (validationResult.constructor == Boolean) {
            return validationResult;
        }

        return validationResult.difference > 0;
    };
    $.validator.addMethod('timeAfter', timeAfter, $.validator.format("Value entered should be a time later than {0}."));


    var timeAfterOrEqual = function (value, element, param) {
        const validationResult = validateTimeComparison(this, 'timeAfterOrEqual', value, element, param);

        if (validationResult.constructor == Boolean) {
            return validationResult;
        }

        return validationResult.difference >= 0;
    };
    $.validator.addMethod('timeAfterOrEqual', timeAfterOrEqual, $.validator.format("Value entered should be a time later than or equal to {0}."));

    var timeBefore = function (value, element, param) {
        const validationResult = validateTimeComparison(this, 'timeBefore', value, element, param);

        if (validationResult.constructor == Boolean) {
            return validationResult;
        }

        return validationResult.difference < 0;
    };
    $.validator.addMethod('timeBefore', timeBefore, $.validator.format("Value entered should be a time earlier than {0}."));


    var timeBeforeOrEqual = function (value, element, param) {
        const validationResult = validateTimeComparison(this, 'timeBeforeOrEqual', value, element, param);

        if (validationResult.constructor == Boolean) {
            return validationResult;
        }

        return validationResult.difference <= 0;
    };
    $.validator.addMethod('timeBeforeOrEqual', timeBeforeOrEqual, $.validator.format("Value entered should be a time earlier than or equal to {0}."));


    /**
     * =============================================================================
     * DEFAULT CONFIGURATION
     * =============================================================================
     */
    jQuery.validator.setDefaults({
        onKeyup: true,
        onFocusout: true,
        errorClass: "is-invalid text-danger",
        validClass: "is-valid",
        errorElement: "span",
        normalizer: trimNormalizer,
        errorPlacement: function (error, element) {
            // remove current errors
            $(element).siblings('span').replaceWith('');
            // Add class `is-invalid` to the element
            $(element).addClass('is-invalid');
            // Add the `invalid-feedback` class to the error element
            error.addClass("invalid-feedback").addClass('d-block');

            // Add `has-validation` class to the parent div.form-group
            // in order to add icons to inputs
            element.parents("div").addClass("has-validation");

            if (element.prop("type") === "checkbox" || element.prop("type") === "radio") {
                element.parent("label").parent('div').append(error.addClass('text-center'));
            } else if (element.parent('div').hasClass('input-group')) {
                error.insertAfter(element.parent('div'));
            } else {
                error.insertAfter(element);
            }
        },
    });

})();
