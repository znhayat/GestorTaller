# Documentación Técnica: Sistema de Gestión de Vehículos y API Interna

## 1. Introducción
El objetivo de este módulo es optimizar el proceso de entrada de datos en el taller mecánico "GestorTaller". Se ha implementado una base de datos de marcas y modelos de vehículos conectada mediante una API interna para reducir errores humanos y mejorar la velocidad de operación.

## 2. Arquitectura del Sistema

### A. Modelo de Datos (Base de Datos)
Se ha optado por una estructura relacional de **1 a Muchos (1:N)**:
*   **Marcas**: Almacena los fabricantes (ej: BMW, Audi).
*   **Modelos**: Cada modelo está vinculado a una marca mediante una clave foránea (`marca_id`). Esto garantiza que no existan modelos "huérfanos" y facilita el filtrado.

### B. Patrón de Diseño: API RESTful Interna
Para que la interfaz de usuario sea fluida, se ha implementado una **API interna** utilizando el framework Laravel. 
*   **Concepto**: La API actúa como un intermediario entre el navegador del usuario y la base de datos.
*   **Endpoints**:
    *   `GET /api/vehiculos/marcas?q=...`: Devuelve marcas que coinciden con la búsqueda.
    *   `GET /api/vehiculos/modelos?marca_id=...`: Devuelve solo los modelos de la marca seleccionada.
*   **Ventaja**: Evita la recarga de la página (tecnología AJAX/Fetch), permitiendo una experiencia de usuario (UX) similar a una aplicación de escritorio.

## 3. Beneficios del Proyecto (Defensa en Clase)

### 1. Integridad de los Datos
Al obligar (o sugerir) al usuario elegir de una lista predefinida, evitamos inconsistencias en la base de datos. Por ejemplo, evitamos tener "Mercedes", "MB" y "Merzedes" como marcas distintas; todas se agrupan bajo una única entrada "Mercedes-Benz".

### 2. Optimización del Tiempo (UX)
Un trabajador del taller suele tener las manos ocupadas o poco tiempo para escribir. Con este sistema, puede dar de alta un vehículo con solo pulsar 3 o 4 teclas y un clic, en lugar de escribir 20 caracteres manualmente.

### 3. Escalabilidad
El sistema es **autoadaptable**. Aunque hay una base de datos precargada, el formulario permite escribir marcas nuevas. El diseño permite que en el futuro estos datos se utilicen para generar estadísticas (ej: "¿Qué marca de coche reparamos más en el taller?").

## 4. Tecnologías Utilizadas
*   **Backend**: PHP 8.2 + Laravel 11 (Eloquent ORM).
*   **Frontend**: JavaScript (Fetch API para comunicación asíncrona).
*   **Base de Datos**: MySQL (Migrations & Seeders para el despliegue automático).
