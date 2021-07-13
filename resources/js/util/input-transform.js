/** Transform input to uppercase **/
$.fn.transform_uppercase = function () {
    $(this).css({ "text-transform": "uppercase" });
    this.on("keyup change", function (event) {
        this.value = this.value.toLocaleUpperCase();
    });

    return this;
};

/** Transform input to lowercase **/
$.fn.transform_lowercase = function () {
    $(this).css({ "text-transform": "lowercase" });
    this.on("keyup change", function () {
        this.value = this.value.toLocaleLowerCase();
    });

    return this;
};

/** Transform input to capitalize **/
$.fn.transform_capitalize = function () {
    $(this).css({ "text-transform": "capitalize" });
    this.on("keyup change", function () {
        if ($(this).val().length > 0) {
            const words = $(this).val().split(" ");

            for (let i = 0; i < words.length; i++) {
                if (words[i].trim().length > 0) {
                    words[i] = words[i][0].toUpperCase() + words[i].substr(1);
                }
            }

            $(this).val(words.join(' '));
        }
    });

    return this;
};

window.initInputTransforms = function() {
    /** Input transforms (Applied by element attributes) **/
    $(':input[data-ctransform="uppercase"]').transform_uppercase();
    $(':input[data-ctransform="lowercase"]').transform_lowercase();
    $(':input[data-ctransform="capitalize"]').transform_capitalize();
};

window.initInputTransforms(); 
