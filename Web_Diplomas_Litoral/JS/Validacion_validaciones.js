function mostrarDocumento(ruta) {
    document.getElementById("visorFrame").src = "../uploads/" + ruta;
}

function enviarAccion(idValidacion, estado) {
    const form = new FormData();
    form.append("validacion_id", idValidacion);
    form.append("estado", estado);

    fetch("Procesar_Validacion.php", {
        method: "POST",
        body: form
    })
    .then(r => r.text())
    .then(r => alert(r));
}
