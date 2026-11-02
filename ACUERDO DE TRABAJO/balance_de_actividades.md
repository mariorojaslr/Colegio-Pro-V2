# Balance de Actividades

*Este documento es el registro inalterable y estrictamente seriado de todas las actividades, definiciones y módulos funcionales generados en el proyecto. Ninguna actividad listada aquí puede ser omitida o eliminada en futuras versiones.*

## Actividades Completadas

* **Actividad #1 (10-Jun-2026):** Definición de "Reglas de Juego" y formalización de la Arquitectura Distribuida (Microservicios, SOA, Theming Multiempresa y modelo API-First). Registro de acuerdos en `reglas_y_arquitectura.md`.
* **Actividad #2 (10-Jun-2026):** Construcción Core Terapistas. Landing page dinámica (Theming) con sección Autoridades. Refactorización de Padrón para importación masiva por CSV con manejo de errores y validación. Estructuración pasarela de Mercado Pago (Simulador + Base SDK). Automatización de base de datos.
* **Actividad #3 (17-Jun-2026):** Reestructuración relacional del Organigrama (Autoridades vinculadas a Legajos / Collegiates) con Catálogo de Cargos fijos. Integración de `league/flysystem-aws-s3-v3` para subida de imágenes y archivos pesados directamente a Bunny.net (CDN).
* **Actividad #4 (02-Nov-2026):** Implementación universal del sistema de Validación por PIN de 4 Dígitos para Primer Ingreso y Acceso Rápido diario de Colegiados. Los profesionales pueden activar su cuenta buscando su Legajo/Matrícula en el padrón, definiendo su PIN numérico de 4 dígitos y a partir de ese momento ingresar inmediatamente con su N° de Legajo o Teléfono Celular y su PIN. Se incorporó soporte multi-tenant aislado (Cotolar y demás colegios), pestaña dual en `/login` para colegiados y administradores, relación bidireccional `user()` en el modelo `Collegiate`, y gestión de PIN en `/mi-perfil`.
