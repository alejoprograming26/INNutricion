<div align="center">
  <img src="public/assets/logo.png" alt="INNutricion Logo" width="200" height="auto" />
  <h1>INNutricion</h1>
  <p><strong>Sistema Integral de Gestión, Seguimiento y Análisis Estadístico Nutricional</strong></p>
  
  <p>
    Plataforma de alta fidelidad desarrollada para el <strong>Instituto Nacional de Nutrición (Estado Lara, Venezuela)</strong>. Diseñada para la transcripción ágil, seguimiento territorial jerárquico y análisis estadístico en tiempo real de la vulnerabilidad nutricional y otros indicadores clave de salud.
  </p>

  <p>
    <a href="#-características-principales">Características</a> •
    <a href="#-arquitectura-y-tecnologías">Tecnologías</a> •
    <a href="#-estructura-del-proyecto">Estructura del Proyecto</a> •
    <a href="#-estructura-de-datos-core">Base de Datos</a> •
    <a href="#-instalación-y-despliegue">Instalación</a> •
    <a href="#-soporte--contacto">Contacto</a>
  </p>
</div>

---

## 🚀 Características Principales

*   **Estructuración Territorial Jerárquica**: Gestión multinivel robusta (`Municipio` ➔ `Parroquia` ➔ `Comuna` ➔ `Sector`), que permite un análisis demográfico y geográfico altamente granulado.
*   **Módulo de Transcripciones Ágil**: Registro optimizado de más de 10 indicadores vitales, como:
    *   *Plan de Vulnerabilidad Nutricional*
    *   *Sistemas SUGIMA y CPLV*
    *   *Diversidad Dietaria y Lactancia Materna*
    *   *Escuelas 4S y Ferias del Campo Soberano*
*   **Experiencia Single Page Application (SPA)**: Navegación instantánea y fluida sin recargas de página gracias a las capacidades nativas de **Livewire wire:navigate**.
*   **Sistema de Planificación de Metas**: Formulación y control de metas nutricionales anuales distribuidas de manera mensual y municipal.
*   **Dashboard Analítico e Interactivo**: Panel estadístico avanzado con gráficos dinámicos integrados con **Chart.js**, actualizados en tiempo real mediante filtros interactivos de mes, año y área geográfica.
*   **Tematización Dinámica e Interfaz Premium**: Diseño responsivo y estético con temas claro/oscuro que adaptan su paleta de colores dinámicamente según el módulo seleccionado, utilizando **Flux UI** y **Tailwind CSS 4**.
*   **Optimización del Rendimiento**: Caché inteligente en memoria (`Cache::rememberForever`) para mitigar peticiones redundantes y garantizar tiempos de carga inferiores a 200ms en entornos remotos.

---

## 🛠️ Arquitectura y Tecnologías

El sistema implementa el moderno ecosistema **TALL Stack**:

### 💻 Backend
*   **Framework**: [Laravel 12](https://laravel.com/) (PHP 8.2+)
*   **Motor de Base de Datos**: PostgreSQL alojado de forma remota en [Supabase](https://supabase.com/)
*   **Control de Accesos (RBAC)**: [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission) para control de roles y permisos específicos.

### 🎨 Frontend
*   **Interactividad Reactiva**: [Livewire 4](https://livewire.laravel.com/) & [Alpine.js](https://alpinejs.dev/)
*   **Diseño y UI**: [Tailwind CSS 4](https://tailwindcss.com/) & [Flux UI](https://fluxui.dev/)
*   **Reportes Visuales**: [Chart.js](https://www.chartjs.org/)
*   **Alertas de Interfaz**: [SweetAlert2](https://sweetalert2.github.io/)

---

## 📂 Estructura del Proyecto

A continuación se detalla la organización de los directorios y componentes principales de la aplicación:

```text
INNutricion/
├── app/                          # Lógica del Core del Negocio (Backend)
│   ├── Http/                     # Middlewares y controladores auxiliares de la aplicación
│   ├── Livewire/                 # Componentes interactivos SPA y controladores de interfaz
│   │   ├── Actividades/          # Registro de actividades de campo e institucionales
│   │   ├── Meta/                 # Gestión, visualización y control de metas anuales
│   │   ├── AjusteController.php  # Administración de datos institucionales de la sucursal
│   │   ├── DashboardController.php # Lógica central del dashboard interactivo general
│   │   ├── GraficosTranscripcionController.php # Generación de visualizaciones dinámicas
│   │   ├── TranscripcionController.php # Motor unificado para registro de indicadores
│   │   └── ... (otros controladores específicos de módulos)
│   ├── Models/                   # Modelos de datos Eloquent (Integridad y relaciones)
│   │   ├── Transcripcion.php     # Modelo transaccional central del sistema
│   │   ├── Municipio.php         # Representación del primer nivel de división político-territorial
│   │   ├── Parroquia.php         # Representación del segundo nivel territorial
│   │   ├── Comuna.php            # Nivel de organización comunal
│   │   ├── Sector.php            # Nivel de detalle geográfico de campo
│   │   └── ... (modelos específicos de cada indicador y módulo)
│   └── Observers/                # Observadores para limpiar caché de forma automática al guardar cambios
├── bootstrap/                    # Configuración de inicialización y carga de Laravel
├── config/                       # Archivos de configuración de Laravel (Caché, DB, Auth)
├── database/                     # Migraciones y Alimentadores de la base de datos
│   ├── migrations/               # Esquema relacional de tablas PostgreSQL
│   └── seeders/                  # Inyección inicial de datos (Roles, Ajustes y Geografía del Estado Lara)
├── public/                       # Assets estáticos (Imágenes, iconos, etc.)
├── resources/                    # Vistas y archivos fuente de interfaz
│   ├── css/                      # Estilos personalizados integrados con Tailwind CSS 4
│   ├── js/                       # Inicialización y scripts interactivos (Chart.js y Alpine.js)
│   └── views/                    # Plantillas Blade y componentes de vista
│       ├── layouts/              # Plantilla principal y navegación del panel administrativo
│       └── livewire/             # Plantillas asociadas a cada controlador interactivo de Livewire
├── routes/                       # Rutas web del sistema (protegidas por middleware de autenticación)
└── vite.config.js                # Configuración del empaquetador de assets Vite
```

---

## 🏗️ Estructura de Datos (Core)

Para garantizar velocidad instantánea en reportes estadísticos y evitar costosos procesos de unión en SQL con millones de registros, la arquitectura de base de datos implementa una desnormalización controlada:

*   **Tabla Central de Transcripciones**: Cada registro almacena directamente todas las llaves foráneas completas de su ubicación (`municipio_id`, `parroquia_id`, `comuna_id`, `sector_id`). Esto reduce drásticamente las consultas complejas con `JOIN` y maximiza la velocidad de cálculo estadístico.
*   **Reglas de Cascada**: Para mantener la consistencia lógica de la información, se implementan reglas de eliminación y actualización en cascada (`ON DELETE CASCADE`) en todas las llaves foráneas.
*   **Caché Proactiva**: El sistema invalida automáticamente la caché guardada tan pronto como se detecta una escritura, asegurando consistencia de datos sin penalizar la velocidad de lectura.

*(Consulte `DATABASE_GUIDELINES.md` para conocer los estándares detallados sobre la base de datos).*

---

## 📊 Módulo de Analítica y Gráficos

*   **Visualización Multi-Nivel**: Desglose automático de cantidades absolutas por Parroquias, Comunas y Sectores, junto a una línea de tendencia periódica.
*   **Filtrado Inteligente sin Recarga**: Capacidad de cambiar parámetros de filtrado como año, mes y municipio, propagando los datos instantáneamente a los gráficos a través de eventos de Livewire.
*   **Adaptabilidad Visual (Tematización)**: El dashboard adapta dinámicamente sus colores e identidad visual (Rose, Blue, Emerald, Amber, etc.) de acuerdo al indicador nutricional que se esté visualizando.
*   **Prevención de Fugas en SPA**: Integración técnica avanzada mediante directivas `@script` y ganchos de ciclo de vida de Livewire para destruir y reconstruir instancias de Chart.js al navegar con `wire:navigate`.

---

## 💻 Instalación y Despliegue

Siga estos pasos para preparar el entorno de desarrollo local:

### Requisitos Mínimos
*   PHP >= 8.2
*   Composer
*   Node.js (LTS) & NPM
*   Base de datos PostgreSQL (local o instancia de Supabase)

### Pasos de Instalación

1.  **Clonar el repositorio**
    ```bash
    git clone https://github.com/alejoprograming26/INNutricion.git
    cd INNutricion
    ```

2.  **Instalar dependencias de backend**
    ```bash
    composer install
    ```

3.  **Instalar dependencias de frontend**
    ```bash
    npm install
    ```

4.  **Configurar variables de entorno**
    Cree su archivo `.env` basado en la plantilla del proyecto:
    ```bash
    cp .env.example .env
    ```
    Configure las credenciales PostgreSQL correspondientes en las variables de conexión:
    ```env
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1 (o host remoto de Supabase)
    DB_PORT=5432
    DB_DATABASE=nombre_de_bd
    DB_USERNAME=usuario_bd
    DB_PASSWORD=contraseña_bd
    ```

5.  **Generar la clave de encriptación**
    ```bash
    php artisan key:generate
    ```

6.  **Ejecutar migraciones e inyectar datos semilla**
    ```bash
    php artisan migrate:fresh --seed
    ```
    *Nota: Esto poblará la base de datos con la geografía completa del Estado Lara, roles, permisos y el usuario administrador por defecto.*

7.  **Compilar assets y levantar servidor**
    Ejecute el compilador de assets en segundo plano y levante el servidor de desarrollo Laravel:
    ```bash
    # En una terminal:
    npm run dev

    # En otra terminal:
    php artisan serve
    ```

---

## 🔐 Convenciones y Calidad de Código

*   **Estándares de Componentes**: Todos los componentes Livewire deben usar tipado estricto y encapsulación de lógica de negocio en el backend.
*   **Experiencia de Usuario**: Todos los enlaces de navegación interna deben incorporar la directiva `wire:navigate`.
*   **Rendimiento Percibido**: La barra de progreso de carga nativa se oculta intencionalmente con estilos de inyección para mantener una experiencia visual limpia y de alta gama.

---

## 📞 Soporte & Contacto

*   **Organización**: Instituto Nacional de Nutrición (INN) - Dirección Lara.
*   **Ubicación**: Calle 22 entre carrera 28 y 29, Barquisimeto, Estado Lara.
*   **Teléfono**: 0251-2312345
*   **Correo electrónico**: inn.gob.ve@gmail.com

---
*Desarrollado con ❤️ para garantizar la soberanía, control y seguridad alimentaria.*
