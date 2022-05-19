
const phoneInputField = document.querySelector("#phone");
const phoneInput = window.intlTelInput(phoneInputField, {
    formatOnDisplay: true,
    hiddenInput: "full_number",
    preferredCountries: ["br", "us"],
        utilsScript:
        "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
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

$(phoneInputField).on("countrychange", function(event) {
    var selectedCountryData = phoneInput.getSelectedCountryData();
    newPlaceholder = intlTelInputUtils.getExampleNumber(selectedCountryData.iso2, true, intlTelInputUtils.numberFormat.INTERNATIONAL),
    mask = newPlaceholder.replace(/[1-9]/g, "0");

    $(this).mask(mask);
        
    if(phoneInputField.value == ""){
        phoneInput.setNumber("");
    }else{
        $(phoneInputField).val(phoneInputField.value);
        phoneInput.setNumber(phoneInputField.value);
    }
});

phoneInput.promise.then(function() {
    $(phoneInputField).trigger("countrychange");
});