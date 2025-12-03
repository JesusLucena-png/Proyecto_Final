<?php
/**
 * Sistema de Control de Acceso por Rol
 * Protege páginas según el rol del usuario logueado
 */

session_start();

// Definir mapeo de páginas permitidas por rol
$rol_permisos = [
    'Admin' => [
        'Modulo_Diplomas_Admin.php',
        'Administración_Usuarios.php',
        'Administración_Editar_Usuarios.php',
        'Administración_Ver_Usuarios.php',
        'Administración_Matrículas.php',
        'Programas_Académicos.php',
        'Programa_Ver.php',
        'Validacion_Academica.php',
        'Validar_Validacion_Academica_Admin.php',
        'Usuario_Ver.php',
        'Persona_Crear.php',
        'Persona_Editar.php',
        'Persona_Editar_Guardar.php',
        'access_control.php',
        'Diplomas_Grupo.php',
        'Diploma_Generar.php',
        'Personas.php'
    ],
    'Student' => [
        'Validacion_Academica_Estudiante.php',
        'Validar_Validacion_Academica_Estudiante.php',
        'Usuario_Ver_copy.php'
    
    ],
    'Viewer' => [
        'Validacion_Academica.php',
        'Validar_Validacion_Academica.php',
        'Usuario_Ver.php'
    ]
];

// Definir página de inicio por rol
$rol_inicio = [
    'Admin' => 'Modulo_Diplomas_Admin.php',
    'Student' => 'Validacion_Academica_Estudiante.php',
    'Viewer' => 'Validacion_Academica.php'
];

/**
 * Función para obtener la página de inicio del rol
 * 
 * @param string $rol Rol del usuario
 * @return string Página de inicio
 */
function obtener_pagina_inicio($rol) {
    global $rol_inicio;
    return $rol_inicio[$rol] ?? 'index.php';
}

/**
 * Función para validar acceso a una página
 * 
 * @param string $required_role Role requerido
 * @param string $current_page Página actual (nombre del archivo)
 * @return bool True si tiene acceso, False si no
 */
function validar_acceso($required_role = null, $current_page = null) {
    global $rol_permisos, $rol_inicio;
    
    // Si no hay sesión iniciada, redirigir al login
    if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol'])) {
        header("Location: index.php");
        exit;
    }
    
    $rol_usuario = $_SESSION['rol'];
    $pagina_inicio = obtener_pagina_inicio($rol_usuario);
    
    // Si se especifica un rol requerido, verificar que coincida
    if ($required_role !== null && $rol_usuario !== $required_role) {
        // Rol no coincide - redirigir a página de inicio del rol SIN destruir sesión
        header("Location: $pagina_inicio");
        exit;
    }
    
    // Si se especifica la página actual, verificar que esté en la lista permitida
    if ($current_page !== null) {
        $current_page = basename($current_page);
        
        if (!isset($rol_permisos[$rol_usuario])) {
            // Rol no definido - redirigir
            header("Location: $pagina_inicio");
            exit;
        }
        
        if (!in_array($current_page, $rol_permisos[$rol_usuario])) {
            // Página no permitida para este rol - redirigir
            header("Location: $pagina_inicio");
            exit;
        }
    }
    
    return true;
}

/**
 * Función para verificar si un usuario tiene permisos en una página
 * 
 * @param string $page Nombre del archivo de página
 * @return bool True si tiene acceso
 */
function tiene_acceso_pagina($page) {
    global $rol_permisos;
    
    if (!isset($_SESSION['rol'])) {
        return false;
    }
    
    $rol = $_SESSION['rol'];
    $page = basename($page);
    
    return isset($rol_permisos[$rol]) && in_array($page, $rol_permisos[$rol]);
}

/**
 * Función para obtener las páginas permitidas del rol actual
 * 
 * @return array Lista de páginas permitidas
 */
function obtener_paginas_permitidas() {
    global $rol_permisos;
    
    if (!isset($_SESSION['rol'])) {
        return [];
    }
    
    $rol = $_SESSION['rol'];
    return $rol_permisos[$rol] ?? [];
}

/**
 * Función para obtener la descripción de un rol
 * 
 * @return string Descripción del rol
 */
function obtener_descripcion_rol() {
    $rol = $_SESSION['rol'] ?? 'Desconocido';
    $descripciones = [
        'Admin' => 'Administrador',
        'Student' => 'Estudiante',
        'Viewer' => 'Visualizador'
    ];
    return $descripciones[$rol] ?? $rol;
}

// Incluir la base de datos para operaciones adicionales
require_once 'MySql_php.php';
?>
