# 🚗 Moveo — App Gamificada para el Carnet de Conducir

> **Aparca los libros.** Los tiempos han cambiado. La manera de sacarte el carnet también.

Moveo es una **Progressive Web App (PWA)** gamificada que ayuda a preparar el examen teórico de conducir con un enfoque moderno e interactivo, inspirado en Duolingo.

![Estado](https://img.shields.io/badge/estado-beta-blue)
![Licencia](https://img.shields.io/badge/licencia-académico-green)

---

## 📋 Índice

1. [Descripción](#-descripción)
2. [Características](#-características)
3. [Tecnologías Utilizadas](#-tecnologías-utilizadas)
4. [Herramientas de Desarrollo](#-herramientas-de-desarrollo)
5. [Estructura de Carpetas](#-estructura-de-carpetas)
6. [Instalación para Técnicos](#-instalación-para-técnicos)
7. [Guía de Usuario](#-guía-de-usuario)
8. [Base de Datos](#-base-de-datos)
9. [API Endpoints](#-api-endpoints)
10. [PWA](#-pwa)
11. [SEO](#-seo)

---

## 📖 Descripción

Moveo es una aplicación web progresiva (PWA) diseñada para que los aspirantes al carnet de conducir estudien el examen teórico de una forma **gamificada, visual y divertida**.

### Funcionalidades principales:
- 🎯 **Tests interactivos** con temporizador de 30 segundos por pregunta
- 🔥 **Sistema de rachas** — días consecutivos practicando
- 📊 **Seguimiento de progreso** por temas con gráficos y porcentajes
- 🔁 **Repaso inteligente** de las preguntas más falladas
- 🎵 **Sonidos gamificados** (acierto/fallo) con Web Audio API
- 📱 **Diseño mobile-first** con showcase desktop
- 🔐 **Autenticación** con email/contraseña + Google/Apple Sign-In
- 👨‍💼 **Panel de administración** con CRUD completo de usuarios
- 🍪 **Aviso de cookies** con almacenamiento real en cookie
- 📤 **Exportación** de datos en CSV y XML

---

## ✨ Características

### HTML
- ✅ Semántica correcta: `<nav>`, `<main>`, `<header>`, `<footer>`, `<section>`
- ✅ Jerarquía de encabezados: `h1` → `h2` → `h3` → `h4`
- ✅ Meta etiquetas SEO: title, description, keywords, robots
- ✅ Textos alternativos en todas las imágenes
- ✅ OpenGraph y Twitter Cards

### CSS
- ✅ Responsive multidispositivo (mobile-first + desktop showcase)
- ✅ Variables CSS (`--blue`, `--orange`, `--green`, `--bg`, `--text`, etc.)
- ✅ Google Fonts: **Nunito** (títulos) + **Nunito Sans** (cuerpo)
- ✅ FontAwesome 6 (sistema de iconos vectoriales)
- ✅ Animaciones y transiciones en toda la interfaz
- ✅ Flex y Grid layouts
- ✅ Hover states en todos los elementos interactivos
- ✅ Contraste y legibilidad (WCAG)

### JavaScript
- ✅ Tests interactivos con temporizador (pase de diapositivas)
- ✅ Sanitización de formularios en cliente antes de enviar
- ✅ **Cambio de vista**: lista, retícula (grid), tarjetas (cards) en la Home
- ✅ 🚗 Coche como indicador de progreso en las barras de lección
- ✅ Capitalización automática de nombres y apellidos
- ✅ Sonidos gamificados con Web Audio API

### PHP
- ✅ `require_once` para bloques reutilizables (`db.php`, `functions.php`, `auth_admin.php`)
- ✅ Funciones reutilizables en `functions.php`: `sanitize_input()`, `capitalize_name()`, `json_response()`, etc.
- ✅ Sanitización con `filter_var()`, `htmlspecialchars()`, `strip_tags()`
- ✅ Código comentado y estructurado
- ✅ **CRUD completo**: Create (`register.php`), Read (`get_users.php`), Update (`update_user.php`), Delete (`delete_user.php`)

### Sesiones
- ✅ Tokens de sesión admin en MySQL (`admin_sessions`)
- ✅ Autenticación de usuario con `localStorage` + validación backend

### LocalStorage
- ✅ Progreso completo del usuario (rachas, stats por pregunta, historial)
- ✅ Preferencia de vista (lista/retícula/tarjetas)
- ✅ Datos de sesión del usuario

### Cookies
- ✅ Banner de aviso de cookies con aceptar/rechazar
- ✅ Cookie real `moveo_cookie_consent` con expiración de 365 días

### Base de Datos
- ✅ Diagrama y planificación (ver sección BD)
- ✅ Acceso con PDO y prepared statements
- ✅ CRUD completo

### PWA
- ✅ `manifest.json` con iconos, colores y display standalone
- ✅ `service-worker.js` con cache-first para assets y network-first para API
- ✅ OpenGraph y meta etiquetas
- ✅ Favicons e iconos de app

### SEO Avanzado
- ✅ `sitemap.xml`
- ✅ Meta etiquetas completas
- ✅ `.htaccess` con URLs limpias y ErrorDocument 404

### APIs Externas
- ✅ Google Sign-In (Google Identity Services)
- ✅ Apple Sign-In (UI preparada)

---

## 🛠️ Tecnologías Utilizadas

| Tecnología | Uso |
|---|---|
| **Vue 3** | Framework frontend (SPA) |
| **Vite** | Build tool y dev server |
| **Tailwind CSS v4** | Utilidades CSS + tema personalizado |
| **PHP 8+** | Backend API REST |
| **MySQL** | Base de datos relacional |
| **JSON** | Banco de preguntas (`questions.json`) + API responses |
| **CSV** | Exportación de datos de usuarios/resultados |
| **XML** | Exportación de datos + sitemap.xml |
| **JavaScript (ES6+)** | Lógica del cliente |
| **Web Audio API** | Sonidos gamificados sin archivos externos |
| **FontAwesome 6** | Sistema de iconos |
| **Google Fonts** | Tipografías Nunito y Nunito Sans |
| **PWA** | manifest.json + Service Worker |

---

## 🔧 Herramientas de Desarrollo

| Herramienta | Para qué se usó |
|---|---|
| **GitHub** | Control de versiones y almacenamiento del proceso |
| **VS Code** | Editor de código principal |
| **LocalWP** | Servidor local PHP + MySQL para desarrollo |
| **Figma** | Prototipado y diseño de interfaz |
| **Excalidraw** | Bocetos rápidos y wireframes |
| **drawdb.app** | Diseño visual del diagrama de base de datos |
| **dbdiagram.io** | Documentación de relaciones entre tablas |
| **tableconvert.com** | Conversión de datos entre formatos (JSON/CSV/SQL) |
| **codepen.io** | Prototipado rápido de componentes CSS |
| **Bootstrap** | Referencia de componentes (no usado directamente) |
| **Tailwind CSS** | Framework de utilidades CSS (v4) |
| **Generadores CSS** | Grid generator, box-shadow generator, gradient generator |
| **Notion** | Registro del proceso de trabajo y organización |
| **Chrome DevTools** | Debugging y testing responsive |
| **Lighthouse** | Auditoría de PWA, accesibilidad y rendimiento |

---

## 📁 Estructura de Carpetas

```
moveo-app/
├── index.html              # Entrada HTML (SEO, meta tags, PWA)
├── package.json            # Dependencias npm
├── vite.config.js          # Configuración de Vite
├── tailwind.config.js      # Configuración de Tailwind
├── postcss.config.js       # PostCSS
│
├── public/                 # Assets estáticos (copiados tal cual)
│   ├── manifest.json       # PWA manifest
│   ├── service-worker.js   # Service Worker (cache)
│   ├── sitemap.xml         # SEO sitemap
│   ├── htaccess            # Apache: URLs limpias, 404, caché, seguridad
│   └── icons/              # Iconos PWA (72x72 → 512x512)
│
├── src/                    # Código fuente Vue
│   ├── main.js             # Punto de entrada
│   ├── App.vue             # Layout principal + nav + desktop showcase
│   ├── style.css           # Estilos globales + tema Tailwind
│   │
│   ├── assets/             # Imágenes y recursos
│   │   ├── logoblue.png
│   │   ├── iconapp.png
│   │   ├── penguin-sad.png
│   │   └── penguin-broken.png
│   │
│   ├── components/         # Componentes reutilizables
│   │   ├── CookieBanner.vue    # Banner de cookies
│   │   └── LoadingSpinner.vue  # Animación de carga (coche)
│   │
│   ├── composables/        # Lógica reutilizable (hooks)
│   │   ├── useApi.js       # Wrapper HTTP para backend
│   │   ├── useProgress.js  # Gestión de progreso en localStorage
│   │   └── useSounds.js    # Sonidos gamificados (Web Audio API)
│   │
│   ├── data/               # Datos estáticos
│   │   └── questions.json  # Banco de 30 preguntas
│   │
│   ├── router/
│   │   └── index.js        # Rutas + guards de autenticación
│   │
│   └── views/              # Vistas/páginas
│       ├── LoginView.vue       # Login con email + Google/Apple
│       ├── HomeView.vue        # Dashboard con toggle de vistas
│       ├── WelcomeView.vue     # Bienvenida
│       ├── StreakLost.vue      # Pantalla de racha perdida
│       ├── NotFoundView.vue    # Error 404
│       │
│       ├── onboarding/         # Flujo de registro
│       │   ├── OnboardingFlow.vue
│       │   └── steps/
│       │       ├── Step01Welcome.vue
│       │       ├── Step02Name.vue
│       │       ├── Step03Email.vue
│       │       ├── Step04Password.vue
│       │       ├── Step05Notifications.vue
│       │       └── Step06Done.vue
│       │
│       ├── test/               # Sistema de tests
│       │   ├── TestSelector.vue    # Selección de test
│       │   ├── TestSession.vue     # Sesión de test (con 🚗)
│       │   ├── TestResults.vue     # Resultados
│       │   └── ReviewSession.vue   # Repaso de errores
│       │
│       ├── stats/
│       │   └── StatsView.vue   # Estadísticas detalladas
│       │
│       └── admin/              # Panel de administración
│           ├── AdminLogin.vue
│           ├── AdminDashboard.vue
│           ├── AdminUsers.vue
│           └── AdminQuestions.vue
│
└── backend/                # API PHP
    ├── db.php              # Conexión PDO a MySQL
    ├── functions.php       # Funciones reutilizables compartidas
    ├── auth_admin.php      # Middleware de autenticación admin
    ├── register.php        # POST — Crear usuario (C)
    ├── login.php           # POST — Iniciar sesión
    ├── get_users.php       # GET — Listar usuarios (R)
    ├── get_stats.php       # GET — Estadísticas globales
    ├── update_user.php     # PUT — Actualizar usuario (U)
    ├── delete_user.php     # DELETE — Eliminar usuario (D)
    ├── admin_login.php     # POST — Login admin
    ├── export_csv.php      # GET — Exportar en CSV
    ├── export_xml.php      # GET — Exportar en XML
    └── schema_full.sql     # Schema completo de la BD
```

---

## ⚙️ Instalación para Técnicos

### Requisitos previos
- **Node.js** v18+ y npm
- **PHP** 8.0+ con extensión PDO MySQL
- **MySQL** 5.7+ o MariaDB 10.3+
- Un servidor local como **LocalWP**, **XAMPP** o **MAMP**

### 1. Clonar el repositorio
```bash
git clone https://github.com/tu-usuario/moveo-app.git
cd moveo-app
```

### 2. Instalar dependencias frontend
```bash
npm install
```

### 3. Configurar la base de datos

1. Abre tu gestor MySQL (phpMyAdmin, HeidiSQL, DBeaver...)
2. Ejecuta el script `backend/schema_full.sql`
3. Edita `backend/db.php` con tus credenciales:

```php
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');      // ← tu puerto MySQL
define('DB_NAME', 'moveo_db');  // ← nombre de tu BD
define('DB_USER', 'root');
define('DB_PASS', 'root');
```

### 4. Configurar el proxy de desarrollo

En `vite.config.js`, ajusta el target del proxy al dominio de tu servidor PHP local:

```js
server: {
  proxy: {
    '/backend': {
      target: 'http://localhost:8080', // ← tu servidor PHP
      changeOrigin: true,
    },
  },
},
```

### 5. Iniciar el servidor de desarrollo
```bash
npm run dev
```

### 6. Construir para producción
```bash
npm run build
```
Los archivos se generan en la carpeta `dist/`. Sube todo el contenido a tu servidor Apache.

### 7. Configurar Apache
- Copia `public/htaccess` como `.htaccess` en la raíz del servidor
- Asegúrate de que `mod_rewrite` está habilitado
- La carpeta `backend/` debe estar accesible desde el servidor PHP

---

## 👤 Guía de Usuario

### Primer uso
1. Abre la app en tu móvil
2. Sigue el **flujo de onboarding** (nombre, email, contraseña)
3. ¡Listo! Ya puedes hacer tu primer test

### Hacer un test
1. Pulsa **"Empezar test"** en la pantalla principal
2. Elige entre test aleatorio o por tema
3. Responde las 10 preguntas (30 segundos por pregunta)
4. Revisa tus resultados y explicaciones

### Seguir tu progreso
- La **barra de progreso** con el 🚗 muestra cuánto temario has cubierto
- En la Home puedes cambiar entre vista **lista**, **retícula** o **tarjetas**
- Tus preferencias se guardan automáticamente

### Panel de administración
- Accede a `/admin` con las credenciales de administrador
- Gestiona usuarios (ver, editar, eliminar)
- Exporta datos en CSV o XML

---

## 🗄️ Base de Datos

### Diagrama Entidad-Relación

```
┌─────────────────┐     ┌─────────────────────┐
│     users        │     │    test_results      │
├─────────────────┤     ├─────────────────────┤
│ id (PK)         │──┐  │ id (PK)             │
│ name            │  │  │ user_id (FK) ───────┘
│ email (UNIQUE)  │  │  │ topic               │
│ password_hash   │  │  │ score               │
│ birthdate       │  └──│ total               │
│ avatar          │     │ taken_at            │
│ goals           │     └─────────────────────┘
│ streak          │
│ last_test_date  │
│ created_at      │     ┌─────────────────────┐
│ updated_at      │     │      admins          │
└─────────────────┘     ├─────────────────────┤
                        │ id (PK)             │
                        │ username (UNIQUE)   │──┐
                        │ password_hash       │  │
                        │ created_at          │  │
                        └─────────────────────┘  │
                                                 │
                        ┌─────────────────────┐  │
                        │  admin_sessions      │  │
                        ├─────────────────────┤  │
                        │ token (PK)          │  │
                        │ admin_id (FK) ──────┘  │
                        │ expires_at          │
                        └─────────────────────┘
```

### Relaciones
- `users` 1:N `test_results` (un usuario tiene muchos resultados)
- `admins` 1:N `admin_sessions` (un admin tiene varias sesiones)
- CASCADE en DELETE: borrar un usuario borra sus resultados

---

## 🔌 API Endpoints

| Método | Endpoint | Descripción | Auth |
|--------|----------|-------------|------|
| POST | `/backend/register.php` | Crear usuario | No |
| POST | `/backend/login.php` | Iniciar sesión | No |
| PUT | `/backend/update_user.php` | Actualizar usuario | Sí |
| DELETE | `/backend/delete_user.php` | Eliminar usuario | Sí |
| GET | `/backend/get_users.php` | Listar usuarios | Admin |
| GET | `/backend/get_stats.php` | Estadísticas globales | Admin |
| POST | `/backend/admin_login.php` | Login admin | No |
| GET | `/backend/export_csv.php?type=users` | Exportar CSV | Admin |
| GET | `/backend/export_xml.php?type=users` | Exportar XML | Admin |

---

## 📱 PWA

La app funciona como PWA con:
- **manifest.json**: identidad de la app, iconos, colores
- **service-worker.js**: caché offline de assets estáticos
- **Instalable**: se puede añadir a la pantalla de inicio del móvil

---

## 🔍 SEO

- `sitemap.xml` para crawlers
- Meta tags: title, description, keywords, robots
- OpenGraph para redes sociales (og:title, og:description, og:image)
- Twitter Cards
- `.htaccess` con URLs limpias y ErrorDocument 404
- `lang="es"` en el HTML

---

## 📄 Licencia

Proyecto académico. Todos los derechos reservados.

---

*Hecho con ❤️ y 🚗 por David*
