<div align="center">
  <img src="public/assets/logo.png" alt="INNutricion Logo" width="200" height="auto" />
  <h1>INNutricion - Sistema de Gestión y Análisis Nutricional</h1>
  
  <p>
    Plataforma integral desarrollada para el Instituto Nacional de Nutrición (Estado Lara). Diseñada para el seguimiento, transcripción y análisis estadístico de la vulnerabilidad nutricional y otros indicadores clave de salud estructurados jerárquicamente.
  </p>

  <p>
    <a href="#características-principales">Características</a> •
    <a href="#arquitectura-y-tecnologías">Tecnologías</a> •
    <a href="#estructura-de-datos">Estructura</a> •
    <a href="#instalación-y-despliegue">Instalación</a>
  </p>
</div>

---

## 🚀 Características Principales

- **Gestión Jerárquica Avanzada**: Administración de datos estructurados multinivel (`Municipio` ➔ `Parroquia` ➔ `Comuna` ➔ `Sector`), permitiendo análisis granulado.
- **Módulo de Transcripciones**: Registro ágil de más de 10 indicadores, incluyendo Vulnerabilidad, SUGIMA, CPLV, Lactancia Materna, entre otros.
- **Navegación SPA (Single Page Application)**: Interfaz de usuario fluida sin recargas de página mediante integraciones avanzadas de Livewire (`wire:navigate`).
- **Sistema de Metas**: Planeación anual de metas nutricionales, desglosadas mensual y municipalmente.
- **Roles y Permisos**: Control de acceso robusto basado en roles y permisos específicos para diferentes niveles operativos.
- **Optimización de Rendimiento**: Uso crítico de un sistema de caché en memoria (`Cache::rememberForever`) para mitigar la latencia de peticiones hacia la base de datos remota.
- **Dashboard Analítico Interactivo**: Visualización avanzada de datos mediante gráficos dinámicos que se adaptan en tiempo real al filtrar por tipo de reporte, municipio, mes y año, permitiendo una toma de decisiones basada en datos.
- **Interfaz Moderna y Responsiva**: Diseño estético con **Tematización Dinámica** (colores que cambian según el módulo), temas claro/oscuro integrados y una experiencia UX de alto nivel impulsada por Flux UI.

---

## 🛠️ Arquitectura y Tecnologías

El sistema está construido bajo la arquitectura **TALL Stack** más moderna:

### Backend
- **Framework**: [Laravel 12](https://laravel.com/)
- **Lenguaje**: PHP 8.2+
- **Base de Datos**: PostgreSQL alojado remotamente en [Supabase](https://supabase.com/)
- **Gestor de Permisos**: [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)

### Frontend (TALL)
- **Componentes Dinámicos**: [Livewire 4](https://livewire.laravel.com/)
- **UI Kit**: [Flux UI](https://fluxui.dev/) - Sistema de diseño oficial del ecosistema Livewire
- **Estilos**: [Tailwind CSS 4](https://tailwindcss.com/)
- **Interactividad local**: [Alpine.js](https://alpinejs.dev/)
- **Alertas y Feedbacks**: [SweetAlert2](https://sweetalert2.github.io/)
- **Visualización de Datos**: [Chart.js](https://www.chartjs.org/) (Integración SPA via Livewire)

---

## 🏗️ Estructura de Datos (Core)

Para garantizar la inmediatez en los reportes estadísticos a gran escala, la base de datos utiliza una desnormalización controlada en su tabla transaccional central:

- **Transcripciones**: Cada registro almacena directamente las llaves foráneas completas de su ubicación (`municipio_id`, `parroquia_id`, `comuna_id`, `sector_id`). Esto evita múltiples uniones complejas en SQL, proporcionando resultados estadísticos instantáneos.
- **Cascade Rules**: Todas las dependencias estructurales aseguran integridad referencial absoluta (`ON DELETE CASCADE`).

*(Para más detalles técnicos sobre base de datos, referirse a `DATABASE_GUIDELINES.md` en la raíz del proyecto).*

---

## 📊 Módulo de Analítica y Gráficos

El sistema cuenta con un motor de reportes gráficos de alto rendimiento diseñado para proporcionar una visión clara de los indicadores nutricionales:

- **Visualización Multi-Nivel**: Desglose automático de cantidades por Parroquias, Comunas y Sectores, además de una línea de tendencia diaria.
- **Filtrado Inteligente**: Capacidad de cambiar el mes y el año directamente desde el dashboard con actualización instantánea de los gráficos mediante eventos de Livewire.
- **Motor de Tematización**: El dashboard adapta sus colores (Rose, Blue, Emerald, Amber, etc.) de forma dinámica según el tipo de transcripción consultada para mantener la coherencia visual con el resto del sistema.
- **Optimización SPA**: Integración técnica mediante `@script` y `wire:navigate` para asegurar que los gráficos se destruyan y reconstruyan correctamente durante la navegación entre módulos, evitando fugas de memoria o errores de renderizado.

---

## 💻 Instalación y Despliegue

Sigue estos pasos para levantar el entorno de desarrollo en tu máquina local:

### Requisitos Previos
- PHP >= 8.2
- Composer
- Node.js & NPM
- Conexión PostgreSQL (Credenciales de Supabase)

### Pasos

1. **Clonar el repositorio**
   ```bash
   git clone <URL_DEL_REPOSITORIO>
   cd INNutricion
   ```

2. **Instalar dependencias de PHP**
   ```bash
   composer install
   ```

3. **Instalar dependencias de Frontend**
   ```bash
   npm install
   ```

4. **Configurar el entorno**
   Copia el archivo de prueba `.env.example` y renómbralo a `.env`:
   ```bash
   cp .env.example .env
   ```
   **Importante**: Configura las variables de conexión a base de datos (`DB_CONNECTION=pgsql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) proporcionadas por Supabase.

5. **Generar la llave de la aplicación**
   ```bash
   php artisan key:generate
   ```

6. **Estructurar la base de datos (Migraciones)**
   ```bash
   php artisan migrate:fresh --seed
   ```
   *(Nota: El seeder base `DatabaseSeeder` inyectará configuraciones por defecto, superadministradores y la estructura territorial base de Lara).*

7. **Compilar y Ejecutar**
   Levanta ambos servidores (Backend y Frontend) simultáneamente:
   ```bash
   npm run dev
   php artisan serve
   ```
   *(También puedes utilizar `npm run build` si deseas compilar de manera definitiva los assets).*

---

## 🔐 Convenciones y Calidad de Código

- **Livewire**: Todos los componentes siguen directrices de tipado fuerte y validación directa (`php artisan make:livewire ...`).
- **Navegación**: Los enlaces del sistema requieren la directiva `wire:navigate` para mantener la experiencia SPA.
- **Barra de Carga**: Se ha suprimido intencionalmente la barra de progreso (NProgress / Livewire bar) vía inyección CSS para maximizar la percepción visual premium interactiva.

---

## 📞 Soporte & Contacto
**Instituto Nacional de Nutrición - Sede Lara**
- **Dirección**: Calle 22 entre carrera 28 y 29, Barquisimeto, Estado Lara.
- **Teléfono**: 0251-2312345
- **Email Institucional**: inn.gob.ve@gmail.com

---
*Desarrollado con ❤️ para fortalecer la seguridad y soberanía alimentaria.*
