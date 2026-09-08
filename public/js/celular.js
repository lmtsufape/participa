
const phoneInputField = document.querySelector("#celular");
const phoneInput = window.intlTelInput(phoneInputField, {
    formatOnDisplay: true,
    hiddenInput: "full_number",
    preferredCountries: ["br", "us"],
        utilsScript:
        "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",

    customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
        if (selectedCountryData.iso2 === 'br') {
            return '(00) 00000-0000';
        }
        return selectedCountryPlaceholder;
    }
});
const info = document.querySelector(".alert-info");
const error = document.querySelector("#celular-invalido");

function process(event) {
    event.preventDefault();

    const phoneNumber = phoneInput.getNumber();

    info.style.display = "none";
    error.style.display = "none";

    if (phoneInput.isValidNumber()) {
        //info.style.display = "";
        //info.innerHTML = `Número válido: <strong>${phoneNumber}</strong>`;
    } else {
        //error.style.display = "";
        //error.innerHTML = `Número inválido.`;
    }
}

function applyPhoneMask() {
    const country = phoneInput.getSelectedCountryData();
    const field = $(phoneInputField);

    field.unmask();

    if (country.iso2 === 'br') {
        const brazilianMask = function(value) {
            return value.replace(/\D/g, '').length > 10
                ? '(00) 00000-0000'
                : '(00) 0000-00009';
        };

        field.mask(brazilianMask, {
            onKeyPress: function(value, event, input, options) {
                input.mask(brazilianMask(value), options);
            }
        });
    } else if (window.intlTelInputUtils) {
        const placeholder = intlTelInputUtils.getExampleNumber(
            country.iso2, true, intlTelInputUtils.numberFormat.NATIONAL
        );
        if (placeholder) {
            field.mask(placeholder.replace(/[1-9]/g, '0'));
        }
    }
}

$(phoneInputField).on('countrychange', applyPhoneMask);
applyPhoneMask();

phoneInput.promise.then(function() {
    applyPhoneMask();
});
