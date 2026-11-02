# COLEGIO-PRO / SAAS MULTIEMPRESA
## Guía del Colegiado de Demostración (Uso del OWNER Mario Rojas)

---

### 1. Propósito y Filosofía
Este mecanismo fue diseñado especialmente para permitir que el OWNER (**Mario Rojas**) pueda ingresar a la plataforma y vivir la experiencia exacta como un **Colegiado Socio** desde su teléfono celular o computadora, **sin ensuciar ni alterar la base de datos de los profesionales reales** del cliente (Cotolar o cualquier otra institución).

---

### 2. Datos de Acceso del Usuario de Demostración

* **Colegio asignado:** CoTOLaR (Colegio de Terapistas Ocupacionales de La Rioja)
* **Nombre Completo:** Mario Rojas (Demostración)
* **N° de Legajo:** `DEMO`
* **Teléfono Celular:** `3804000000`
* **Código PIN de 4 Dígitos:** `1234`
* **Correo Electrónico:** `demo.mario@cotolar.com`
* **Contraseña tradicional:** `1234`

---

### 3. FORMA A: Cómo GENERAR o REGENERAR el usuario de prueba

Para crear el usuario demo por primera vez o reponerlo cuando desees hacer una demostración o reunión comercial, ejecuta directamente en la consola del servidor (SSH):

```bash
php artisan demo:collegiate --school=cotolar
```

#### ¿Qué hace este comando internamente?
1. Crea o actualiza en la tabla `users` al usuario con rol `COLEGIADO` y PIN encriptado `1234`.
2. Crea la ficha del colegiado en `collegiates` vinculada al colegio Cotolar con Legajo `DEMO` y celular `3804000000`.
3. Genera 2 cuotas arancelarias de ejemplo (1 pagada y 1 pendiente) para que en el dashboard se aprecien los **Vu-Meters** circulares de estado de cuenta en vivo.

---

### 4. FORMA B: Cómo BORRAR y LIMPIAR el usuario de prueba

Cuando hayas terminado tu presentación, reunión o verificación y quieras dejar la base de datos 100% limpia sin el usuario de prueba, ejecuta en la consola (SSH):

```bash
php artisan demo:collegiate --action=delete
```

#### ¿Qué hace este comando de borrado?
1. Elimina el registro del usuario en la tabla `users`.
2. Elimina la ficha de matriculado en la tabla `collegiates`.
3. Elimina las cuotas de prueba generadas en `collegiate_dues`.
4. **No toca, no modifica y no compromete a ningún colegiado real de Cotolar.**

---

### 5. Alternativa: Visión Omnisciente (Sin crear usuarios)

Si estás logueado como OWNER o Administrador del Colegio y quieres ver la plataforma exactamente como la ve cualquiera de los terapeutas reales que ya activaron cuenta (ej. Karina Arias o Pablo Barrionuevo):
1. Ingresa a **Padrón de Matriculados** (`/colegiados`).
2. Haz clic en el ícono del ojo 👁️ para simular la sesión de ese profesional.
3. Para salir y volver a tu cuenta de Administrador / OWNER, haz clic en el botón superior dorado/rojo **"Salir de Simulación"**.
