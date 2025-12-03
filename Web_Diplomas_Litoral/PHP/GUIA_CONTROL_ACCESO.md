# 🔒 Control de Acceso por Rol - Implementado

## ✅ Resumen de Cambios

Se ha implementado un **sistema automático de control de acceso** que cierra sesiones y redirige cuando:
- Un usuario intenta acceder a una página que no le corresponde
- Se recarga una página con un rol diferente
- Se intenta cambiar de cuenta en la misma sesión

**Nuevo en esta versión:**
- 🔄 Redirecciones a la página de inicio de cada rol (NO a index.php)
- 🎯 Admin → Modulo_Diplomas_Admin.php
- 👤 Student → Validacion_Academica_Estudiante.php
- 👁️ Viewer → Validacion_Academica.php

---

## 🎯 Flujo por Rol

```
LOGIN → SIGN_IN → VALIDAR CREDENCIALES → ASIGNAR ROL → REDIRIGIR
                                                    ↓
                                            ┌───────┴────────┐
                                            ↓                ↓
                                    (Admin Rol)      (Student/Viewer)
                                            ↓                ↓
                            Modulo_Diplomas_Admin  Validacion_Academica_*
                                            ↓                ↓
                                       ┌────────────────────┴─────┐
                                       ↓                          ↓
                               (Intenta acceder              (Intenta acceder
                                a otra página)               a otra página)
                                       ↓                          ↓
                        BLOQUEADO ←─────── VALIDAR ACCESO ──────→ OK
                       DESTROY SESSION    (access_control.php)
                       REDIRIGE A INICIO
                       (su página principal)
```

---

## 📋 Matriz de Permisos

### **Admin** ✅
```
✓ Puede acceder a TODAS las páginas del sistema
✓ Redirección: Modulo_Diplomas_Admin.php
✓ Cierre automático si intenta otra página
```

### **Student** ✅
```
✓ Validacion_Academica_Estudiante.php
✓ Validar_Validacion_Academica_Estudiante.php
✓ Usuario_Ver.php
✓ Redirección: Validacion_Academica_Estudiante.php
✗ BLOQUEADO: Admin, Viewer y cualquier otra página
```

### **Viewer** ✅
```
✓ Validacion_Academica.php (solo lectura)
✓ Validar_Validacion_Academica.php (solo lectura)
✓ Usuario_Ver.php
✓ Redirección: Validacion_Academica.php
✗ BLOQUEADO: Student y Admin, y cualquier otra página
```

---

## 🛡️ Ejemplos de Protección

### Caso 1: Estudiante intenta acceder a Admin
```
Usuario: Juan (Student)
URL: http://localhost/.../Modulo_Diplomas_Admin.php

→ Validación: rol ≠ Admin
→ Resultado: BLOQUEADO
→ Acción: Session destroy + Redirect a Validacion_Academica_Estudiante.php
```

### Caso 2: Cambio de cuenta en misma navegador
```
Escenario:
- Admin abierto en tab 1
- Student recarga tab 1 desde historial

→ Validación: Student NO tiene acceso a Modulo_Diplomas_Admin.php
→ Resultado: BLOQUEADO
→ Acción: Session destroy + Redirect a Validacion_Academica_Estudiante.php
```

### Caso 3: Viewer intenta acceder a Student
```
Usuario: María (Viewer)
URL: http://localhost/.../Validacion_Academica_Estudiante.php

→ Validación: Página no en lista de permisos
→ Resultado: BLOQUEADO
→ Acción: Session destroy + Redirect a Validacion_Academica.php
```

---

## 📦 Archivos Implementados/Modificados

| Archivo | Tipo | Cambio |
|---------|------|--------|
| `access_control.php` | 🆕 NUEVO | Sistema de validación centralizado |
| `sign_in.php` | 📝 MODIFICADO | Redirecciones correctas por rol |
| `Modulo_Diplomas_Admin.php` | 🔒 PROTEGIDO | Requiere rol Admin |
| `Administración_Usuarios.php` | 🔒 PROTEGIDO | Requiere rol Admin |
| `Administración_Matrículas.php` | 🔒 PROTEGIDO | Requiere rol Admin |
| `Validacion_Academica.php` | 🔒 PROTEGIDO | Admin/Viewer |
| `Validar_Validacion_Academica.php` | 🔒 PROTEGIDO | Admin/Viewer |
| `Validacion_Academica_Estudiante.php` | 🔒 PROTEGIDO | Requiere Student |
| `Validar_Validacion_Academica_Estudiante.php` | 🔒 PROTEGIDO | Requiere Student |
| `PERMISOS_ROLES.md` | 📖 DOCUMENTACIÓN | Guía completa de permisos |

---

## 🔧 Técnicamente ¿Cómo Funciona?

### Archivo Principal: `access_control.php`

```php
// Al inicio de cada página protegida:
include "access_control.php";

// Validar que sea Admin
validar_acceso('Admin', __FILE__);

// O para múltiples roles
validar_acceso(null, __FILE__);
```

**La función valida:**
1. ¿Existe sesión iniciada?
2. ¿El rol coincide con el requerido?
3. ¿La página actual está en la lista permitida para ese rol?

**Si algo falla:**
- 🔒 Destruye la sesión
- 🔄 Redirige a `index.php` (login)
- El usuario ve: "Credenciales incorrectas o página no encontrada"

---

## ✨ Ventajas del Sistema

✅ **Centralizado**: Un solo archivo controla todo  
✅ **Automático**: No requiere lógica manual en cada página  
✅ **Seguro**: Cierra sesiones inmediatamente  
✅ **Escalable**: Fácil agregar nuevas páginas o roles  
✅ **Mantenible**: Cambios en un solo lugar  
✅ **Documentado**: Incluye guía en PERMISOS_ROLES.md  

---

## 🚀 Próximas Mejoras (Opcionales)

1. **Registrar intentos fallidos** en tabla `audit_logs`
2. **Proteger archivos de procesamiento** (`*_Guardar.php`)
3. **Middleware automático** para todas las páginas
4. **Logs de acceso** por usuario y página
5. **Notificaciones** de accesos no autorizados
6. **Rate limiting** para evitar ataques de fuerza bruta

---

## 📞 Soporte

**Si una página no funciona correctamente:**
1. Verificar que incluya: `include "access_control.php";`
2. Verificar que use: `validar_acceso('RolRequerido', __FILE__);`
3. Revisar `PERMISOS_ROLES.md` para confirmar permisos

