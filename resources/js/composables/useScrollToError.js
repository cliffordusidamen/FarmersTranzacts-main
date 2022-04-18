export default function useScrollToError(formControlsSelector) {

    const scrollToFieldError = (e) => {
        setTimeout(() => {
            const containerSelector = !!formControlsSelector
                ? `${formControlsSelector} `
                : '';

            const errorClass =
                (`
                    ${containerSelector}.field-error,
                    ${containerSelector}.alert,
                    ${containerSelector}.text-red-500.text-sm
                `).trim()

            const elementIsFocused = document.querySelectorAll('.form-control:focus,textarea:focus').length > 0
            if (elementIsFocused) {
                return;
            }
        
            console.log('scrolling...', document.querySelectorAll(errorClass).length)
        
            if (document.querySelectorAll(errorClass).length > 0) {
                window.scrollTo(0, document.querySelector(errorClass).parentElement.offsetTop - 100)
            }
        }, 200)
    }

    return {
        scrollToFieldError
    }

}