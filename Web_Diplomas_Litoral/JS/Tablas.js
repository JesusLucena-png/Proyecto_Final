$(document).ready(function () {

    tabla = $("#miTabla").DataTable({
        paging: true,
        searching: true,
        info: false,
        ordering: false,   // 🔥 DESACTIVAR ORDENAMIENTO
        dom: "t",
        pageLength: 50
    });

    $("#buscador").on("keyup", function () {
        tabla.search(this.value).draw();
        actualizarInfoPaginador();
    });

    $("#filtroDocumento").on("change", function () {
        tabla.column(0).search(this.value).draw();
        actualizarInfoPaginador();
    });

    $("#btnPrev").click(function () {
        tabla.page("previous").draw("page");
        actualizarInfoPaginador();

        // 🔥 REGRESAR AL INICIO
        $(".content_Tablas").scrollTop(0);
    });

    $("#btnNext").click(function () {
        tabla.page("next").draw("page");
        actualizarInfoPaginador();

        // 🔥 REGRESAR AL INICIO
        $(".content_Tablas").scrollTop(0);
    });

        // Filtro tipo programa (columna 3)
    $("#filtroTipoPrograma").on("change", function () {
        tabla.column(3).search(this.value).draw();
        actualizarInfoPaginador();
    });

    // Filtro jornada (columna 6)
    $("#filtroJornada").on("change", function () {
        tabla.column(6).search(this.value).draw();
        actualizarInfoPaginador();
    });

    // Filtro modalidad (columna 5)
    $("#filtroModalidad").on("change", function () {
        tabla.column(5).search(this.value).draw();
        actualizarInfoPaginador();
    });


    function actualizarInfoPaginador() {
        let info = tabla.page.info();
        document.getElementById("infoPagina").innerHTML =
            `${info.page + 1} de ${info.pages}`;
    }

    // ================== NUEVOS FILTROS PARA PROGRAMAS ACADÉMICOS ==================

    // Filtro: Tipo de programa  (columna 2)
    const filtroTipoPrograma2 = document.getElementById("filtroTipoPrograma2");
    filtroTipoPrograma2.addEventListener("change", function () {
        tabla.column(2).search(this.value).draw();
        actualizarInfoPaginador();
    });

    // Filtro: Jornada / Turno  (columna 5)
    const filtroJornada2 = document.getElementById("filtroJornada2");
    filtroJornada2.addEventListener("change", function () {
        tabla.column(5).search(this.value).draw();
        actualizarInfoPaginador();
    });

    // Filtro: Modalidad  (columna 4)
    const filtroModalidad2 = document.getElementById("filtroModalidad2");
    filtroModalidad2.addEventListener("change", function () {
        tabla.column(4).search(this.value).draw();
        actualizarInfoPaginador();
    });

    actualizarInfoPaginador();
});
