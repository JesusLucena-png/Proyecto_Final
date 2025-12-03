document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("toggleSidebar");

    // Leer estado del sidebar en localStorage
    const estadoGuardado = localStorage.getItem("sidebarEstado");

    if (estadoGuardado === "cerrado") {
        sidebar.classList.add("open");  // tu clase open = cerrado
    }

    // Click para abrir/cerrar
    toggleBtn.addEventListener("click", () => {
        sidebar.classList.toggle("open");

        // Guardar el nuevo estado
        if (sidebar.classList.contains("open")) {
            localStorage.setItem("sidebarEstado", "cerrado");
        } else {
            localStorage.setItem("sidebarEstado", "abierto");
        }
    });
});
