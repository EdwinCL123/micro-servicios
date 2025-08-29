# 📦 Prueba Técnica Fullstack – Microservicios con Laravel + Docker

## 🚀 Objetivo
Implementar una solución con **dos microservicios en Laravel** (Productos e Inventario), que se comunican vía **HTTP/JSON API**, orquestados con **Docker Compose** y expuestos a través de un **Nginx Gateway**.

---

## 🏗 Arquitectura

- **Productos**: CRUD completo de productos.
- **Inventario**: consulta y actualización de inventarios, validando productos vía el microservicio de Productos.
- **Base de Datos**: MySQL 8.0.
- **Nginx Gateway**: expone endpoints unificados bajo `http://localhost`.

## 📂 Estructura del proyecto

```
microservicios/
 ├─ docker-compose.yml
 ├─ gateway/
 │   └─ nginx.conf
 ├─ productosinv-service/
 │   ├─ .env
 │   ├─ routes/api.php
 │   └─ ...
 └─ inventario-service/
     ├─ .env
     ├─ routes/api.php
     └─ ...
```

---

## ⚙️ Instalación y ejecución

### Requisitos
- Docker
- Docker Compose
- (Opcional) Postman para pruebas

### Pasos
1. Clonar el repositorio:
   ```bash
   git clone https://github.com/EdwinCL123/micro-servicios.git
   cd microservicios
   ```

2. Levantar los servicios:
   ```bash
   docker compose up -d --build
   ```

3. Verificar logs:
   ```bash
   docker compose logs -f productos
   docker compose logs -f inventario
   ```

---

## 🌐 Endpoints principales

### Productos
- `GET    /api/productos` → listar productos
- `POST   /api/productos` → crear producto
- `GET    /api/productos/{id}` → obtener producto por ID
- `PATCH  /api/productos/{id}` → actualizar producto por ID
- `DELETE /api/productos/{id}` → eliminar producto por ID

### Inventario
- `GET    /api/inventario/{id}` → consultar inventario de un producto
- `PUT    /api/inventario/{id}` → actualizar inventario

---

### Endpoints de documentación
- **Productos**: [http://localhost/api/docs](http://localhost/api/docs)  
- **Inventario**: [http://localhost/api/docs](http://localhost/api/docs)  

### Cómo regenerar la documentación

```bash
docker exec -it microservicios-productos php artisan l5-swagger:generate
docker exec -it microservicios-inventario php artisan l5-swagger:generate
```

---

## 🧪 Pruebas unitarias
## Ejecutar pruebas
```bash
# Microservicio Productos
docker exec -it microservicios-productos php artisan test

# Microservicio Inventario
docker exec -it microservicios-inventario php artisan test
```

### Resultados esperados
- **Productos**: validación de creación, listado y borrado de productos.  
- **Inventario**: validación de creación y actualización de inventario, incluyendo comunicación con el microservicio de productos.  

---

## 🧪 Ejemplos de uso (Postman)

También puedes probar los endpoints usando **Postman** o **cURL**.

### 1. Crear producto
```
POST http://localhost/api/productos
Headers:
  Accept: application/json
Body (JSON):
{
  "nombre": "Laptop Dell",
  "precio": 2500
}
```

### 2. Consultar producto
```
GET http://localhost/api/productos/1
Headers:
  Accept: application/json
```

### 3. Consultar inventario
```
GET http://localhost/api/inventario/1
Headers:
  Accept: application/json
  X-API-KEY: secret123
```

### 4. Actualizar inventario
```
PUT http://localhost/api/inventario/1
Headers:
  Accept: application/json
  Content-Type: application/json
  X-API-KEY: secret123
Body:
{
  "cantidad": 50
}
```

👉 También se incluye una colección `Postman_Collection.json` en la raíz del repositorio para importar directamente en Postman y probar todos los endpoints.

---

## 🔑 Seguridad
- Comunicación entre servicios autenticada con **API Key (`X-API-KEY`)**.
- Configurada en `.env` de cada microservicio.

---

## 🧹 Comandos útiles

- Ver rutas en un microservicio:
  ```bash
  docker exec -it microservicios-productos php artisan route:list
  docker exec -it microservicios-inventario php artisan route:list
  ```

- Ejecutar migraciones manualmente:
  ```bash
  docker exec -it microservicios-productos php artisan migrate --force
  docker exec -it microservicios-inventario php artisan migrate --force
  ```

- Ejecutar pruebas:
  ```bash
  docker exec -it microservicios-productos php artisan test
  docker exec -it microservicios-inventario php artisan test
  ```

---


