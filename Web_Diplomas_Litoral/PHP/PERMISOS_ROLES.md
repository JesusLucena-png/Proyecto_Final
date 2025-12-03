# Sistema de Control de Acceso por Rol

## Descripción General
Este sistema protege todas las páginas según el rol del usuario logueado. Si un usuario intenta acceder a una página que no le corresponde o recarga con otra cuenta, la sesión se cierra automáticamente.

---

## Roles y Permisos

### 1. **Admin** (Administrador)
Rol con acceso completo al sistema.

**Páginas permitidas:**
- `Modulo_Diplomas_Admin.php` - Panel principal de admin
- `Administración_Usuarios.php` - Gestión de usuarios
- `Administración_Editar_Usuarios.php` - Edición de usuarios
- `Administración_Ver_Usuarios.php` - Visualización de usuarios
- `Administración_Matrículas.php` - Gestión de matrículas
- `Programas_Académicos.php` - Gestión de programas
- `Programa_Ver.php` - Detalle de programa
- `Validacion_Academica.php` - Validación académica (revisión)
- `Validar_Validacion_Academica.php` - Detalle de validación
- `Usuario_Ver.php` - Perfil del usuario
- Páginas de personas (crear, editar, listar)

**Redirección al iniciar sesión:** `Modulo_Diplomas_Admin.php`  
**Redirección si acceso no autorizado:** `Modulo_Diplomas_Admin.php` (sin cerrar sesión, solo si es su rol)

---

### 2. **Student** (Estudiante)
Rol con acceso limitado solo a su validación académica.

**Páginas permitidas:**
- `Validacion_Academica_Estudiante.php` - Ver sus validaciones pendientes
- `Validar_Validacion_Academica_Estudiante.php` - Detalles de una validación
- `Usuario_Ver.php` - Ver su perfil

**Redirección al iniciar sesión:** `Validacion_Academica_Estudiante.php`  
**Redirección si acceso no autorizado:** `Validacion_Academica_Estudiante.php` (sesión cerrada)

---

### 3. **Viewer** (Visualizador)
Rol con acceso solo a lectura de validaciones académicas.

**Páginas permitidas:**
- `Validacion_Academica.php` - Ver listado de validaciones (solo lectura)
- `Validar_Validacion_Academica.php` - Ver detalle de validaciones (solo lectura)
- `Usuario_Ver.php` - Ver su perfil

**Redirección al iniciar sesión:** `Validacion_Academica.php`  
**Redirección si acceso no autorizado:** `Validacion_Academica.php` (sesión cerrada)

---

## Cómo Funciona

### Implementación en Páginas

Cada página protegida debe incluir al inicio:

```php
<?php
include "access_control.php";

// Para Admin:
validar_acceso('Admin', __FILE__);

// Para Student:
validar_acceso('Student', __FILE__);

// Para múltiples roles (Admin y Viewer):
validar_acceso(null, __FILE__);

// Resto del código...
?>
```

### Funciones Disponibles

1. **`validar_acceso($required_role, $current_page)`**
   - Valida que el usuario tenga el rol requerido y acceso a la página
   - Si no cumple, cierra la sesión y redirige a `index.php`
   - Parámetros:
     - `$required_role`: Rol específico requerido (ej: 'Admin') o `null` para usar la lista de permisos
     - `$current_page`: Usar `__FILE__` para validar la página actual

2. **`tiene_acceso_pagina($page)`**
   - Retorna `true` si el usuario tiene acceso a una página específica
   - Útil para mostrar/ocultar enlaces en menús

3. **`obtener_paginas_permitidas()`**
   - Retorna array con todas las páginas permitidas para el rol actual

4. **`obtener_descripcion_rol()`**
   - Retorna descripción legible del rol actual

---

## Comportamiento de Seguridad

### Escenario 1: Usuario cambio de rol
1. Usuario "Juan" inicia sesión como **Student**
2. La página `Modulo_Diplomas_Admin.php` está protegida solo para **Admin**
3. Si Juan intenta acceder:
   - ❌ Se valida: Juan es **Student**, la página requiere **Admin**
   - 🔒 Se cierra la sesión de Juan
   - 🔄 Se redirige a `index.php` (login)

### Escenario 2: Recargar página con otra cuenta
1. Usuario "Juan" (**Student**) tiene abierta `Validacion_Academica_Estudiante.php`
2. Cierra el navegador pero deja la sesión abierta en otra pestaña
3. Alguien más intenta recargar esa misma URL (ej: desde historial compartido)
4. Si es **Admin** o **Viewer**:
   - ❌ Se valida: Rol no autorizado para esta página
   - 🔒 Se cierra la sesión
   - 🔄 Se redirige a `index.php`

---

## Páginas Protegidas Actualmente

| Página | Rol Requerido | Protección |
|--------|---------------|-----------|
| Modulo_Diplomas_Admin.php | Admin | ✅ Si |
| Administración_Usuarios.php | Admin | ✅ Si |
| Administración_Editar_Usuarios.php | Admin | ✅ Si |
| Administración_Ver_Usuarios.php | Admin | ✅ Si |
| Administración_Matrículas.php | Admin | ✅ Si |
| Programas_Académicos.php | Admin | ✅ Si |
| Programa_Ver.php | Admin | ✅ Si |
| Validacion_Academica.php | Admin/Viewer | ✅ Si |
| Validar_Validacion_Academica.php | Admin/Viewer | ✅ Si |
| Validacion_Academica_Estudiante.php | Student | ✅ Si |
| Validar_Validacion_Academica_Estudiante.php | Student | ✅ Si |
| Usuario_Ver.php | Todos (Admin/Student/Viewer) | ✅ Si |

---

## Próximos Pasos Recomendados

1. Proteger todas las páginas de Administración restantes
2. Crear redirección para rol **Viewer** al iniciar sesión
3. Agregar middleware para proteger automáticamente todas las páginas
4. Agregar validación en archivos de carga/manipulación de datos (`_Guardar.php`)
5. Registrar intentos de acceso no autorizado en logs

---

## Archivos Modificados

- ✅ `access_control.php` (nuevo)
- ✅ `sign_in.php` - Genera sesión con `rol` almacenado
- ✅ `Modulo_Diplomas_Admin.php`
- ✅ `Administración_Usuarios.php`
- ✅ `Administración_Matrículas.php`
- ✅ `Validacion_Academica.php`
- ✅ `Validacion_Academica_Estudiante.php`
- ✅ `Validar_Validacion_Academica.php`
- ✅ `Validar_Validacion_Academica_Estudiante.php`
