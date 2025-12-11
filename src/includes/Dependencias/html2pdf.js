async function generarPDF(nombreEmpleado, fechaActual, elementoId = 'contenido-pdf') {

    const botones = document.querySelectorAll('.btn');
    const canvas = document.getElementById("horasChart");
    const elementoOriginal = document.getElementById(elementoId);

    // 🔹 Ocultar botones
    botones.forEach(btn => btn.style.visibility = 'hidden');

    // 🔹 Ocultar gráfico para evitar PDFs enormes
    if (canvas) canvas.style.display = "none";

    await new Promise(res => setTimeout(res, 80));

    // 🔹 Crear contenedor temporal para incluir encabezado con fecha
    const contenedorTemporal = document.createElement("div");

    contenedorTemporal.innerHTML = `
        <div style="
            text-align: right;
            font-size: 14px;
            color: #444;
            margin-bottom: 10px;
        ">
            <strong>Fecha de generación:</strong> ${fechaActual}
        </div>
    `;

    // Clonar el contenido real sin mover el original
    const copiaContenido = elementoOriginal.cloneNode(true);
    contenedorTemporal.appendChild(copiaContenido);

    // 🔹 Generar nombre seguro
    const nombreSeguro = nombreEmpleado
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .replace(/[^a-zA-Z0-9]/g, "_")
        .toLowerCase();

    // 🔹 Opciones optimizadas
    const opciones = {
        margin: [10, 10, 10, 10],
        filename: `asistencias_${nombreSeguro}_${fechaActual}.pdf`,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 1.2, useCORS: true, logging: false },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };

    // 🔹 Generar PDF SEGURO (evita el error de "examen de virus")
    html2pdf()
        .set(opciones)
        .from(contenedorTemporal)
        .outputPdf('blob')
        .then(blob => {

            // Descargar de forma manual (Chrome lo permite escanear)
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = opciones.filename;
            document.body.appendChild(a);
            a.click();
            URL.revokeObjectURL(url);

            // Restaurar
            if (canvas) canvas.style.display = "";
            botones.forEach(btn => btn.style.visibility = 'visible');
        })
        .catch(err => {
            console.error("Error al generar el PDF:", err);
            
            if (canvas) canvas.style.display = "";
            botones.forEach(btn => btn.style.visibility = 'visible');
        });
}
