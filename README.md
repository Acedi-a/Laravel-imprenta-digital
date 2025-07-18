# 🖨️ Sistema de Gestión de Pedidos - Imprenta Digital

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-red?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue?style=for-the-badge&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/PostgreSQL-16-336791?style=for-the-badge&logo=postgresql" alt="PostgreSQL">
  <img src="https://img.shields.io/badge/Tecnología_Web_2-2025-green?style=for-the-badge" alt="Tecnología Web 2">
</p>

## 📋 Descripción del Proyecto

**Sistema de gestión de pedidos para una imprenta digital** - Plataforma web donde los clientes pueden cargar sus diseños, seleccionar tipo de impresión, cotizar y dar seguimiento al estado del pedido.

Este proyecto fue desarrollado como parte de la materia **Tecnología Web 2** utilizando **Laravel 11** y **PostgreSQL**.

## 🚀 Funcionalidades

### 👥 Gestión de Usuarios
- Registro y autenticación de clientes
- Perfiles de usuario con información personal
- Gestión de direcciones de envío

### 📦 Gestión de Productos
- Catálogo de productos de impresión
- Categorías de productos
- Opciones personalizables (tamaño, material, acabado)
- Precios dinámicos según especificaciones

### 💰 Sistema de Cotizaciones
- Cotizaciones automáticas basadas en especificaciones
- Carga de archivos de diseño
- Aprobación/rechazo de cotizaciones
- Historial de cotizaciones

### 📋 Gestión de Pedidos
- Conversión automática de cotizaciones aprobadas a pedidos
- Seguimiento de estado del pedido
- Sistema de prioridades
- Historial de cambios de estado

### 💳 Sistema de Pagos
- Registro de pagos por pedido
- Estados de pago (pendiente, pagado, cancelado)
- Montos calculados automáticamente

### 🚚 Sistema de Envíos
- Gestión de envíos por transportista
- Códigos de seguimiento
- Fechas estimadas de entrega
- Direcciones de envío

### 🔔 Sistema de Notificaciones
- Notificaciones de estado de pedidos
- Notificaciones de envío
- Mensajes personalizados

## 🏗️ Arquitectura del Sistema

### 📊 Diagrama de Base de Datos
```
Usuarios → Cotizaciones → Pedidos → Envíos
    ↓           ↓           ↓        ↓
Direcciones   Archivos    Pagos   Notificaciones
                ↓           ↓
             Productos  Historial_Estados
                ↓
           Opciones_Producto
```

### 🗃️ Tablas Principales

| Tabla | Descripción | Registros Típicos |
|-------|-------------|------------------|
| `usuarios` | Clientes de la imprenta | 🙋‍♂️ Información personal |
| `productos` | Catálogo de servicios | 📄 Tarjetas, flyers, banners |
| `cotizaciones` | Solicitudes de precio | 💰 Precio según especificaciones |
| `pedidos` | Órdenes de trabajo | 📋 Estado, prioridad, notas |
| `envios` | Despachos y entregas | 🚚 Transportista, seguimiento |
| `pagos` | Transacciones financieras | 💳 Montos, estados |
| `notificaciones` | Comunicaciones | 🔔 Mensajes automáticos |

## 📁 Estructura del Proyecto

```
proyecto-imprenta-laravel/
├── app/
│   ├── Models/           # Modelos Eloquent
│   │   ├── Usuario.php
│   │   ├── Producto.php
│   │   ├── Cotizacion.php
│   │   ├── Pedido.php
│   │   └── ...
│   ├── Http/Controllers/ # Controladores
│   └── ...
├── database/
│   ├── migrations/       # Migraciones de BD
│   ├── seeders/         # Seeders de datos
│   │   └── FlujoPrincipalSeeder.php
│   └── factories/       # Factories para datos fake
├── resources/
│   ├── views/           # Vistas Blade
│   └── ...
└── ...
```

## 🛠️ Instalación y Configuración

### 📋 Requisitos Previos

- **PHP 8.2+**
- **Composer**
- **PostgreSQL 12+**
- **Node.js & NPM** (opcional, para frontend)

### ⚡ Instalación Rápida

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/tu-usuario/proyecto-imprenta-laravel.git
   cd proyecto-imprenta-laravel
   ```

2. **Instalar dependencias**
   ```bash
   composer install
   ```

3. **Configurar variables de entorno**
   ```bash
   cp .env.example .env
   ```

4. **Configurar base de datos en `.env`**
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=localhost
   DB_PORT=5432
   DB_DATABASE=proyecto_laravel
   DB_USERNAME=laravel_user
   DB_PASSWORD=laravel_password
   ```

5. **Crear base de datos PostgreSQL**
   ```sql
   CREATE DATABASE proyecto_laravel;
   CREATE USER laravel_user WITH PASSWORD 'laravel_password';
   GRANT ALL PRIVILEGES ON DATABASE proyecto_laravel TO laravel_user;
   ```

6. **Generar clave de aplicación**
   ```bash
   php artisan key:generate
   ```

7. **Ejecutar migraciones**
   ```bash
   php artisan migrate
   ```

8. **Poblar base de datos con datos de prueba**
   ```bash
   php artisan db:seed --class=FlujoPrincipalSeeder
   ```

9. **Iniciar servidor de desarrollo**
   ```bash
   php artisan serve
   ```

## 🎯 Seeder Principal - "Efecto Dominó"

El proyecto incluye un seeder especial llamado `FlujoPrincipalSeeder` que crea todo el ecosistema de datos relacionados de una sola vez:

```bash
php artisan db:seed --class=FlujoPrincipalSeeder
```

### 🔄 ¿Qué hace el FlujoPrincipalSeeder?

1. **Crea 5 usuarios** con sus respectivas direcciones
2. **Genera 10 productos** del catálogo
3. **Crea 13 cotizaciones** (2-3 por usuario)
4. **Convierte cotizaciones aprobadas en pedidos**
5. **Genera pagos** automáticamente
6. **Crea envíos** para pedidos completados
7. **Registra historial de estados** de cada pedido
8. **Envía notificaciones** en cada etapa del proceso

### 📊 Datos Generados

| Entidad | Cantidad | Descripción |
|---------|----------|-------------|
| Usuarios | 5 | Clientes con datos reales |
| Productos | 10 | Catálogo de servicios |
| Cotizaciones | 13 | Solicitudes de precio |
| Pedidos | 8 | Órdenes de trabajo |
| Envíos | 4 | Despachos realizados |
| Pagos | 8 | Transacciones |
| Notificaciones | 17 | Mensajes automáticos |

## 🔧 Comandos Útiles

```bash
# Limpiar y recrear base de datos
php artisan migrate:fresh

# Ejecutar seeder principal
php artisan db:seed --class=FlujoPrincipalSeeder

# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Generar controladores
php artisan make:controller NombreController

# Crear migraciones
php artisan make:migration create_tabla_table
```

## 📸 Capturas de Pantalla

<!-- Para agregar imágenes, usa la siguiente sintaxis: -->
<!-- ![Descripción](ruta/a/imagen.png) -->
<!-- ![Dashboard](screenshots/dashboard.png) -->
<!-- ![Cotizaciones](screenshots/cotizaciones.png) -->

> **Nota:** Para agregar imágenes al README, coloca las imágenes en una carpeta `screenshots/` o `images/` y utiliza la sintaxis:
> ```markdown
> ![Descripción de la imagen](ruta/a/imagen.png)
> ```

## 🤝 Contribución

Este proyecto es parte de la materia **Tecnología Web 2**. Para contribuir:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📝 Notas Importantes

- **Estado del proyecto**: En desarrollo (Backend completado)
- **Frontend**: Pendiente de implementación
- **Autenticación**: Sistema básico de Laravel implementado
- **API**: Endpoints disponibles para integraciones futuras

## 🐛 Solución de Problemas

### Error de conexión a PostgreSQL
```bash
# Verificar que PostgreSQL esté corriendo
sudo systemctl status postgresql

# Iniciar PostgreSQL si está detenido
sudo systemctl start postgresql
```

### Error de permisos en storage
```bash
sudo chmod -R 775 storage/
sudo chmod -R 775 bootstrap/cache/
```

## 📄 Licencia

Este proyecto está bajo la licencia MIT. Ver el archivo [LICENSE](LICENSE) para más detalles.

## 👨‍💻 Autor

**Proyecto desarrollado para Tecnología Web 2**
- Universidad: [Tu Universidad]
- Materia: Tecnología Web 2
- Año: 2025

---

<p align="center">
  <strong>🖨️ Sistema de Gestión de Pedidos - Imprenta Digital</strong><br>
  <em>Desarrollado con ❤️ usando Laravel y PostgreSQL</em>
</p>
