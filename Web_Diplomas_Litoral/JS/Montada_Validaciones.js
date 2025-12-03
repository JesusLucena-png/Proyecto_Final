const canvas = document.getElementById('canvasDocumento');
const ctx = canvas.getContext('2d');

function mostrarDocumento(rutaArchivo) {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    if(!rutaArchivo) return alert("No hay documento para mostrar");

    if(rutaArchivo.endsWith(".pdf")) {
        const loadingTask = pdfjsLib.getDocument(rutaArchivo);
        loadingTask.promise.then(pdf => {
            pdf.getPage(1).then(page => {
                const viewport = page.getViewport({ scale: 1.5 });
                canvas.width = viewport.width;
                canvas.height = viewport.height;

                page.render({ canvasContext: ctx, viewport: viewport });
            });
        }).catch(err => { console.error(err); alert("Error al cargar PDF"); });
    } else {
        const img = new Image();
        img.onload = () => { 
            canvas.width = img.width; 
            canvas.height = img.height; 
            ctx.drawImage(img, 0, 0); 
        };
        img.src = rutaArchivo;
    }
}

function notificarEnvio(validacionId) {
    if(!confirm("¿Deseas notificar al administrador que enviaste este documento?")) return;

    fetch('../PHP/Notificar_Envio.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ validacion_id: validacionId })
    })
    .then(resp => resp.json())
    .then(data => {
        if(data.success) {
            alert("Documento enviado al administrador.");
        } else {
            alert("Error: " + data.error);
        }
    })
    .catch(err => console.error(err));
}

// Subida de archivos
document.querySelectorAll('.form_subir_archivo').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const validacionId = this.dataset.validacionId;
        const inputArchivo = this.querySelector('input[name="archivo"]');
        if(inputArchivo.files.length === 0) return alert("Selecciona un archivo.");

        const formData = new FormData();
        formData.append('archivo', inputArchivo.files[0]);
        formData.append('validacion_id', validacionId);

        fetch('../PHP/Subir_Validacion.php', { method: 'POST', body: formData })
        .then(resp => resp.json())
        .then(data => {
            if(data.success) {
                alert("Archivo subido correctamente");
                // Mostrar el archivo inmediatamente sin recargar
                mostrarDocumento(inputArchivo.files[0] instanceof File ? URL.createObjectURL(inputArchivo.files[0]) : data.file_path);
            } else {
                alert("Error al subir el archivo: " + data.error);
            }
        })
        .catch(err => console.error(err));
    });
});
