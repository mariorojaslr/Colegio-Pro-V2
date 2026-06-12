# Acuerdos de Arquitectura y Reglas de Juego

## 1. Reglas de Trabajo
* **No omitir pasos:** Cada actividad, avance o funcionalidad debe registrarse sin falta en el archivo `balance_de_actividades.md`.
* **Exigencia Total:** Cada etapa que se aborde se debe dejar 100% lista, operativa y sin elementos faltantes antes de pasar a la siguiente.
* **Aprobación de Planes:** No se iniciará la ejecución de código sin que exista una formulación detallada del plan de acción previamente avalada.
* **Balance Positivo:** El recuento de actividades siempre debe ir en aumento, reflejando cada logro y asegurando que ninguna función desarrollada anteriormente "desaparezca" o sea omitida por supuesta simplificación.

---

## 2. Definición Arquitectónica: Ecosistema Orientado a Servicios (SOA)

El desarrollo del software ya no será tratado como un sistema monolítico tradicional, sino como un **Ecosistema de Microservicios Distribuidos y API-First**.

### A. Eje 1: Plataformas "Mono-Empresa" (Single-Tenant)
* El código fuente que se entrega al cliente es autónomo, robusto e independiente.
* **Personalización Institucional:** Cada empresa (colegio/cliente) tendrá una web pública 100% a medida. Mediante un sistema de *Theming* (Gestión de Temas), cada instalación tendrá asignada una plantilla visual, colores, logos, e integrará su propio Gestor Web (CMS) para que sus administradores controlen noticias, banners y menús, sin compartir base de datos con otras empresas.

### B. Eje 2: Dashboard Central del Owner (Panóptico)
* Es un sistema totalmente separado (privado del dueño).
* Se conecta a cada una de las plataformas "Mono-Empresa" a través de **APIs seguras** (Tokens). Permite monitorear, auditar y operar sobre todas las plataformas instaladas sin mezclarse con el código fuente de los clientes.

### C. Eje 3: Módulos como Productos Independientes
* **Sistemas Separados:** La *Escuela Virtual*, el *Sistema de Administración de Colegios* y un eventual *Sistema de Ventas/Gastos (POS)* o *Gestión Médica* son productos de software distintos.
* **Integración Modular (Llaves de Conexión):** El dueño tiene el control absoluto para "encender" llaves. Si a un Colegio se le habilita la Escuela Virtual, se establecerá una conexión API y un **Single Sign-On (SSO)**. El colegio verá un botón en su menú y los usuarios cruzarán de un sistema a otro de manera fluida y transparente, sincronizando datos, sin importar que sean servidores separados.
* **Escalabilidad y Cross-Selling:** Esta separación permite vender módulos adicionales en el futuro (Ventas, Escuelas, etc.) a clientes antiguos, integrándolos a la perfección gracias al enfoque API-First.
