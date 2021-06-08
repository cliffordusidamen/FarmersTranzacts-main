require('jquery-validation')

jQuery.validator.setDefaults({
    onKeyup: true,
    onFocusout: true,
    errorClass: "is-invalid text-danger",
    validClass: "is-valid",
    errorElement: "span",
    normalizer: value => $.trim(value),
});
