# Estructura y Evolución de la Base de Datos (Migraciones)

Este documento explica cada una de las 29 migraciones que componen el sistema del taller. Están organizadas por su función para facilitar la explicación técnica en clase.

## 1. Núcleo del Sistema (Tablas Base)
Estas migraciones definen las entidades principales del taller:
*   `create_users_table`: Gestión de usuarios (trabajadores y admin).
*   `create_clientes_table`: Registro de clientes.
*   `create_vehiculos_table`: Datos básicos de los coches vinculados a clientes.
*   `create_encargos_table`: La tabla central que gestiona los trabajos y su estado en el Kanban.
*   `create_materiales_table`: Inventario de productos del taller.
*   `create_usos_materiales_table`: Relación de qué materiales se han usado en cada encargo.
*   `create_citas_table`: Agenda para recepciones y entregas.
*   `create_fotos_table`: Almacenamiento de imágenes de los trabajos.
*   `create_facturas_table`: Registro de facturación y cobros.
*   `create_presupuestos_table`: Gestión de costes de materiales y mano de obra.

## 2. Evolución y Mejoras (Alteraciones)
A medida que el proyecto creció, se añadieron funcionalidades mediante migraciones de tipo `add` o `alter`:

### Gestión de Usuarios
*   `add_role_to_users_table`: Diferenciación entre Admin y Operario.
*   `add_approval_and_role_to_users_table`: Sistema de seguridad para que el admin apruebe nuevos registros.

### Optimización del Kanban y Procesos
*   `add_canceled_state_to_encargos_table`: Añade la posibilidad de archivar trabajos rechazados.
*   `add_cita_revision_to_encargos_table`: Vincula la fecha de revisión directamente al encargo.
*   `add_fecha_inicio_trabajo_to_encargos_table`: Registra cuándo entra el coche físicamente a producción.

### Seguridad y Auditoría
*   `add_soft_deletes_to_all_tables`: Implementa el "borrado lógico" (los datos no se borran del todo, se pueden recuperar).

### Nuevas Funcionalidades "Top"
*   `alter_fotos_table_for_gallery`: Prepara las fotos para ser mostradas en la landing page pública.
*   `add_tipo_and_relacion_id_to_fotos_table`: Implementa la lógica de **"Antes y Después"** vinculando dos fotos.
*   `add_stock_fields_to_materiales_table`: Mejora el control de inventario (stock mínimo, alertas).
*   `create_marcas_and_modelos_tables`: API interna para autocompletado de vehículos.
*   `add_estimacion_inicial_to_presupuestos_table`: Trazabilidad entre el presupuesto telefónico y el final tras revisión.

## 3. Resumen Técnico para Defensa
*   **Total de Migraciones**: 29.
*   **Motor**: MySQL / MariaDB.
*   **Patrón**: Se sigue el estándar de Laravel de migraciones incrementales, lo que permite un despliegue continuo y seguro sin pérdida de datos.
*   **Relaciones**: Se utilizan claves foráneas (`foreignId`) con restricciones de integridad (`constrained`) para asegurar que, por ejemplo, no se borre un cliente que tiene trabajos activos.
