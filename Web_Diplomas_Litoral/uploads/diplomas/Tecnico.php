<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dibujo grande</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pinyon+Script&display=swap">




</head>
<body>
    <div class="Contenedor">
        <canvas id="miCanvas"></canvas>
    </div>
</body>

<script>

const canvas = document.getElementById("miCanvas");
const ctx = canvas.getContext("2d");

// PRIMERO define el tamaño para que no borre nada después
canvas.width = 3000;
canvas.height = 2000;



// --- DIBUJAR SVG PRIMERO (FONDO) ---
const img = new Image();
img.src = "../diplomas/Group 3.svg";

img.onload = function() {

    // Dibujar fondo SVG
    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

    // --- AHORA sí dibuja los campos de texto ENCIMA ---
    dibujarCampoTexto4(500, 450, 2000, 150, "La Corporacion de Educacion Superior del Litoral");
    dibujarCampoTexto1(500, 590, 2000, 50, "otorga el presente Diploma a:");

    dibujarCampoTexto2(500, 680, 2000, 150, "--Nombre del estudiante--");

    dibujarCampoTexto3(500, 865, 2000, 75, "por haber cumplido satisfactoriamente con todos los requisitos académicos,");
    dibujarCampoTexto3(500, 925, 2000, 75, "administrativos y reglamentarios establecidos para obtener el título de:");
    
    dibujarCampoTexto4_5(500, 1075, 2000, 100, "--Nombre del programa--");

    dibujarCampoTexto1(500, 1250, 2000, 50, "De conformidad con lo dispuesto por la Ley 30 de 1992, el registro calificado vigente y las normas");
    dibujarCampoTexto1(500, 1300, 2000, 50, "institucionales que rigen la formación técnica profesional en Colombia.");
    dibujarCampoTexto1(500, 1350, 2000, 50, "Se expide en la ciudad de Barranquilla, a los [Día] días del mes de [Mes] del año [Año].");
    dibujarCampoTexto5(410, 1750, 680, 50, "Nombre Del rector");
    dibujarCampoTexto5(1910, 1750, 680, 50, "Nombre Del vicerrector rector");
    dibujarCampoTexto6(410, 1800, 680, 50, "Rector");
    dibujarCampoTexto6(1910, 1800, 680, 50, "vicerrector academico");
    
    dibujarCampoTexto6(1350, 1750, 300, 50, "Código del Diploma: []");
    dibujarCampoTexto6(1350, 1800, 300, 50, "Acta de Grado Nº: []");
    dibujarCampoTexto6(1350, 1850, 300, 50, "Folio: []");


};

img.onerror = () => console.log("Error: Ruta del SVG incorrecta");
const font = new FontFace(
    "Pinyon Script",
    "url(https://fonts.gstatic.com/s/pinyonscript/v16/6NUO8FmMKwSEKjnm5-4v-4Jh2dgi.woff2)"
);

font.load().then(loadedFont => {
    document.fonts.add(loadedFont);
});


function dibujarCampoTexto1(x, y, width, height, texto) {
    ctx.font = "40px Arial";
    ctx.fillStyle = "black";
    ctx.textBaseline = "middle";
    ctx.textAlign = "center";
    

    ctx.fillText(texto.toLowerCase(), x + width / 2, y + height / 2);
    ctx.textAlign = "left";
}

function dibujarCampoTexto2(x, y, width, height, texto) {
    ctx.font = "110px 'Pinyon Script'";
    ctx.fillStyle = "black";
    ctx.textBaseline = "middle";
    ctx.textAlign = "center";
    ctx.textTranform = "Upercase";

    ctx.fillText(texto.replace(/\b\w/g, c => c.toUpperCase()), x + width / 2, y + height / 2);
    ctx.textAlign = "left";
}

function dibujarCampoTexto3(x, y, width, height, texto) {
    ctx.font = "50px Arial";
    ctx.fillStyle = "black";
    ctx.textBaseline = "middle";
    ctx.textAlign = "center";

    ctx.fillText(texto.toLowerCase(), x + width / 2, y + height / 2);
    ctx.textAlign = "left";
}

function dibujarCampoTexto4(x, y, width, height, texto) {
    ctx.font = "80px 'Playfair Display'";
    ctx.fillStyle = "black";
    ctx.textBaseline = "middle";
    ctx.textAlign = "center";

    ctx.fillText(texto.replace(/\b\w/g, c => c.toUpperCase()), x + width / 2, y + height / 2);
    ctx.textAlign = "left";
}
function dibujarCampoTexto4_5(x, y, width, height, texto) {
    ctx.font = "80px 'Playfair Display'";
    ctx.fillStyle = "black";
    ctx.textBaseline = "middle";
    ctx.textAlign = "center";

    ctx.fillText(texto.toUpperCase(), x + width / 2, y + height / 2);
    ctx.textAlign = "left";
}

function dibujarCampoTexto5(x, y, width, height, texto) {
    ctx.font = "35px Arial";
    ctx.fillStyle = "black";
    ctx.textBaseline = "middle";
    ctx.textAlign = "center";

    ctx.fillText(texto.replace(/\b\w/g, c => c.toUpperCase()), x + width / 2, y + height / 2);
    ctx.textAlign = "left";
}

function dibujarCampoTexto6(x, y, width, height, texto) {
    ctx.font = "30px Arial";
    ctx.fillStyle = "black";
    ctx.textBaseline = "middle";
    ctx.textAlign = "center";

    ctx.fillText(texto.replace(/\b\w/g, c => c.toUpperCase()), x + width / 2, y + height / 2);
    ctx.textAlign = "left";
}


</script>
</html>
