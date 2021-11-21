import intlTelInput from "intl-tel-input";

export default function useInitIntlPhone() {

    /**
     * 
     * @param {Ref} inputRef 
     * @param {Ref} intlPhoneRef 
     * @param {String} inputId 
     * @param {String} validatorName 
     */
    const initIntlPhone = (inputRef, intlPhoneRef, inputId, validatorName, message) => {
        inputRef.value = document.querySelector(`#${inputId}`)
        intlPhoneRef.value = intlTelInput(inputRef.value, {
            initialCountry: 'ng',
            separateDialCode: true,
            utilsScript: "/plugins/intl-tel-input/js/utils.js",
        });

        jQuery.validator.addMethod(validatorName || 'intl_phone', function(value, element) {
            return this.optional(element) || intlPhoneRef.value.isValidNumber()
        }, message || "Please enter a valid phone number");
    }

    return {
        initIntlPhone,
    }

}