// TELÉFONO PRINCIPAL
const tel1 = document.querySelector("#telefono_principal");
const msg1 = document.querySelector("#error_tel1");

const iti1 = intlTelInput(tel1, {
    initialCountry: "co",
    separateDialCode: true,
    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
});

// TELÉFONO SECUNDARIO
const tel2 = document.querySelector("#telefono_secundario");
const msg2 = document.querySelector("#error_tel2");

const iti2 = intlTelInput(tel2, {
    initialCountry: "co",
    separateDialCode: true,
    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
});

// VALIDACIÓN AL ENVIAR
document.getElementById("miFormulario").addEventListener("submit", function (e) {

    // Validar teléfono principal obligatorio
    if (!iti1.isValidNumber()) {
        e.preventDefault();
        msg1.style.display = "block";
        msg1.innerText = "❌ Número principal inválido.";
        tel1.style.border = "2px solid red";
        return;
    }

    // Guardar número principal final
    document.getElementById("telefono_principal_final").value = iti1.getNumber();

    // Validar secundario solo si fue escrito
    if (tel2.value.trim() !== "" && !iti2.isValidNumber()) {
        e.preventDefault();
        msg2.style.display = "block";
        msg2.innerText = "❌ Número secundario inválido.";
        tel2.style.border = "2px solid red";
        return;
    }

    if (tel2.value.trim() !== "") {
        document.getElementById("telefono_secundario_final").value = iti2.getNumber();
    }
});
