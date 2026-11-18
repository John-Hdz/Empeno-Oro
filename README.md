<div align="center">

# Sistema de Gestión de Empeños

**Aplicación web desarrollada en PHP y MySQL para la administración integral de una casa de empeños.**

![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-MariaDB-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Composer](https://img.shields.io/badge/Composer-Dependencias-885630?style=for-the-badge&logo=composer&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-Vanilla-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

</div>

---

## Descripción

**Presta Prenda** es un proyecto web creado originalmente durante mi etapa de aprendizaje de desarrollo web en PHP y posteriormente endurecido con buenas prácticas de seguridad y configuración mediante variables de entorno (`.env`) para su publicación.

Permite gestionar el ciclo completo de empeños: desde la cotización en línea y el registro de clientes y prendas, hasta la emisión de boletas en PDF con código QR, control de guarda valores, pagos de refrendo/liquidación, transacciones y reportes estadísticos en gráficas y Excel.

---

## Funcionalidades principales

- **Autenticación y control de acceso por roles/permisos**
  - Inicio y cierre de sesión con sesiones endurecidas.
  - Recuperación y restablecimiento de contraseña mediante correo electrónico (SMTP).
  - Gestión de usuarios, roles y permisos (`Administrador`, `Empleado_Administrador`, `Valuador`, `Lider_Tienda`, `Cliente`).
- **Gestión de empeños y boletas**
  - Registro, consulta, modificación y eliminación de empeños.
  - Búsqueda por nombre de cliente, número de crédito o cliente único.
  - Generación de **boletas en PDF** con código de barras (`mPDF`).
  - Generación dinámica de **códigos QR** (`endroid/qr-code`) para descargar la boleta.
- **Cotizador en línea**
  - Cálculo estimado de préstamo e intereses según kilataje, peso en gramos y tipo de cotización.
- **Transacciones y pagos**
  - Registro y detalle de movimientos, pagos de **refrendo** y **liquidación**.
- **Guarda valores y catálogos**
  - Control de ubicación física en bóveda/depósito (`depósito`, `columna`, `fila`).
  - Administración de artículos, clasificaciones, kilatajes, cotizaciones, sucursales, estados, ciudades, localidades, clientes y empleados.
- **Reportes y exportación**
  - Gráficas interactivas con **Chart.js** (clientes por sucursal y estado de empeños).
  - Exportación a **Excel (`.xlsx`)** con **PhpSpreadsheet**:
    - Reporte de transacciones
    - Reporte de empeños por empleado
    - Reporte de clientes morosos

---

## Tecnologías y librerías

| Área | Tecnología / Paquete |
| :--- | :--- |
| **Backend** | PHP 8.1+ (PDO con `utf8mb4`) |
| **Base de datos** | MySQL / MariaDB |
| **Frontend** | HTML5, CSS3, JavaScript, Font Awesome 5, Chart.js |
| **Correos SMTP** | [`phpmailer/phpmailer`](https://github.com/PHPMailer/PHPMailer) `^6.9` |
| **Generación de PDF** | [`mpdf/mpdf`](https://github.com/mpdf/mpdf) `^8.2.2` |
| **Reportes Excel** | [`phpoffice/phpspreadsheet`](https://github.com/PHPOffice/PhpSpreadsheet) `^4.3` |
| **Códigos QR** | [`endroid/qr-code`](https://github.com/endroid/qr-code) `^6.0` |

---

## Estructura del proyecto

```text
Empenio/
├── admin/
│   ├── api/                 # Endpoints REST (cliente.php)
│   ├── login/               # Vistas de inicio y recuperación de sesión
│   ├── models/              # Modelos PDO por entidad
│   ├── views/               # Vistas HTML/PHP y reportes PDF/Excel/QR
│   ├── admin_*.php          # Controladores por módulo
│   ├── config.php           # Configuración global, sesión y helpers (e, CSRF)
│   ├── env_loader.php       # Cargador ligero de variables .env
│   └── model.php            # Clase base Model (BD, auth, correo, subida segura)
├── CSS/                     # Hojas de estilo (main.css)
├── JS/                      # Scripts del cliente (script.js, script_login.js)
├── images/                  # Iconos e ilustraciones estáticas
├── uploads/                 # Carpeta de cargas con protección .htaccess
├── visitante/               # Página de inicio pública
├── .env.example             # Plantilla de variables de entorno
├── .gitignore               # Exclusiones para Git (.env, vendor/, etc.)
├── .htaccess                # Reglas de seguridad Apache
├── composer.json            # Dependencias de Composer
└── README.md
```

---

## Medidas de seguridad implementadas

- **Variables de entorno (`.env`)**: credenciales de base de datos, SMTP, rutas y URL base separadas del código fuente y excluidas en `.gitignore`.
- **Hashing seguro de contraseñas**: uso de `password_hash()` (`PASSWORD_DEFAULT`) y `password_verify()`, con actualización automática de hashes MD5 heredados al iniciar sesión.
- **Tokens criptográficos**: generación de tokens de recuperación con `random_bytes()` y validación con `hash_equals()`.
- **Consultas preparadas PDO**: uso de parámetros enlazados y `PDO::ATTR_EMULATE_PREPARES => false`.
- **Protección contra CSRF**: tokens por sesión en formularios `POST` y conversión de acciones destructivas (`eliminar`) a peticiones `POST` verificadas.
- **Protección contra XSS**: escape sistemático de salida HTML mediante el helper `e()` y validación numérica en el frontend.
- **Control de acceso e IDOR**: verificación de sesión, roles/permisos y propiedad de boletas antes de imprimir, descargar PDF, generar QR, exportar Excel o consultar la API.
- **Protección en Apache (`.htaccess`)**: bloqueo de acceso HTTP a `.env`, `composer.json`, `.git`, desactivación de listado de directorios y bloqueo de ejecución PHP en `uploads/`.

---

## Instalación y puesta en marcha

### 1. Requisitos previos
- **PHP 8.1+** con extensiones `pdo_mysql`, `fileinfo`, `gd` y `mbstring` habilitadas.
- **MySQL** o **MariaDB**.
- **Composer** instalado.
- Servidor web Apache (por ejemplo **XAMPP**).

### 2. Clonar el repositorio e instalar dependencias

```bash
git clone https://github.com/tu-usuario/Empenio.git
cd Empenio
composer install
```

### 3. Configurar variables de entorno

Copia la plantilla `.env.example` a `.env`:

```bash
cp .env.example .env
```

En Windows (PowerShell):

```powershell
Copy-Item .env.example .env
```

Edita `.env` con tus credenciales locales:

| Variable | Descripción | Ejemplo |
| :--- | :--- | :--- |
| `APP_ENV` | Entorno de ejecución | `local` |
| `APP_DEBUG` | Mostrar errores detallados (`false` en producción) | `false` |
| `APP_URL` | URL base del proyecto | `http://localhost/Empenio` |
| `BASE_PATH` | Ruta base relativa en el servidor | `/Empenio` |
| `DB_CONNECTION` | Motor PDO | `mysql` |
| `DB_HOST` | Servidor de base de datos | `localhost` |
| `DB_PORT` | Puerto de MySQL | `3306` |
| `DB_NAME` | Nombre de la base de datos | `presta_prenda` |
| `DB_USER` | Usuario de la base de datos | `tu_usuario_bd` |
| `DB_PASSWORD` | Contraseña de la base de datos | `tu_contrasena_bd` |
| `UPLOAD_DIR` | Carpeta de archivos subidos | `uploads` |
| `MAIL_HOST` | Servidor SMTP | `smtp.gmail.com` |
| `MAIL_PORT` | Puerto SMTP | `465` |
| `MAIL_ENCRYPTION` | Tipo de cifrado (`smtps` o `tls`) | `smtps` |
| `MAIL_USERNAME` | Correo remitente SMTP | `tu_correo@gmail.com` |
| `MAIL_PASSWORD` | Contraseña de aplicación SMTP | `tu_contrasena_de_aplicacion` |
| `MAIL_FROM_NAME` | Nombre visible del remitente | `"Presta Prenda"` |

> **Importante:** Nunca subas tu archivo `.env` real a GitHub. Ya está excluido en `.gitignore`.

### 4. Consideraciones de la base de datos

- Asegúrate de que la columna `usuario.contrasenia` tenga longitud suficiente para `password_hash()` (mínimo **`VARCHAR(255)`**).
- La columna `usuario.token` debe admitir al menos **`VARCHAR(32)`** para los tokens de restablecimiento.

### 5. Abrir en el navegador

Coloca el proyecto dentro de tu carpeta pública (por ejemplo `htdocs/Empenio`) e inicia Apache y MySQL:

- **Página principal:** `http://localhost/Empenio/admin/admin_index.php`
- **Inicio de sesión:** `http://localhost/Empenio/admin/admin_login.php`
