@extends('layouts/contentNavbarLayout')

@section('title', 'Manual de Usuario')

@section('content')
<div class="row">
  <div class="col-12">
    <!-- Título Principal -->
    <h4 class="fw-bold py-3 mb-4">
      <span class="text-muted fw-light">Documentación /</span> Manual de Usuario
    </h4>

    <div class="row">
      <!-- Navegación lateral -->
      <div class="col-md-3">
        <div class="card mb-4 shadow-none border">
          <div class="card-header border-bottom">
            <h5 class="mb-0">ÍNDICE</h5>
          </div>
          <div class="list-group list-group-flush">
            <a href="#sec-1" class="list-group-item list-group-item-action">1. INTRODUCCIÓN</a>
            <a href="#sec-2" class="list-group-item list-group-item-action">2. ACCESO AL SISTEMA</a>
            <a href="#sec-3" class="list-group-item list-group-item-action">3. EL CUADRO DE MANDO</a>
            <a href="#sec-4" class="list-group-item list-group-item-action">4. GESTIÓN CRM</a>
            <a href="#sec-5" class="list-group-item list-group-item-action">5. EL FLUJO DE TRABAJO</a>
            <a href="#sec-6" class="list-group-item list-group-item-action">6. TABLEROS KANBAN</a>
            <a href="#sec-7" class="list-group-item list-group-item-action">7. DOCUMENTACIÓN VISUAL</a>
            <a href="#sec-8" class="list-group-item list-group-item-action">8. GESTIÓN ECONÓMICA</a>
            <a href="#sec-9" class="list-group-item list-group-item-action">9. CONFIGURACIÓN Y SEGURIDAD</a>
            <a href="#sec-10" class="list-group-item list-group-item-action text-danger">10. FAQ</a>
          </div>
        </div>
      </div>

      <!-- Contenido del Manual -->
      <div class="col-md-9">
        
        <!-- PORTADA -->
        <div class="card mb-4 text-center p-5">
            <h2 class="fw-bold mb-0">GESTOR TALLER</h2>
            <p class="text-muted mb-4">Sistema de Gestión Integral para Talleres de Tapicería de Automóviles</p>
            <h1 class="display-5 fw-bold mb-0">MANUAL DE USUARIO</h1>
        </div>

        <!-- 1. INTRODUCCIÓN -->
        <div class="card mb-4" id="sec-1">
          <div class="card-header border-bottom">
            <h5 class="mb-0 fw-bold">1. INTRODUCCIÓN</h5>
          </div>
          <div class="card-body mt-3">
            <h6 class="fw-bold">1.1. Bienvenida al sistema</h6>
            <p>Bienvenido/a a GestorTaller, tu nueva herramienta de gestión integral para el taller de tapicería. Este sistema ha sido diseñado específicamente para ayudarte a olvidar el papel y las libretas, centralizando toda la información del día a día en un entorno digital seguro y fácil de utilizar.</p>
            <p>Con GestorTaller podrás:</p>
            <ul>
                <li>Controlar tus clientes y sus vehículos de forma rápida.</li>
                <li>Gestionar las citas de entrada y salida sin errores.</li>
                <li>Ver el estado de todos los trabajos de un solo vistazo mediante tableros visuales (Kanban).</li>
                <li>Documentar tus restauraciones con fotos del "Antes y Después".</li>
                <li>Gestionar presupuestos, facturas y los materiales.</li>
            </ul>
            <p>El objetivo de este manual es guiarte paso a paso por todas las funcionalidades del programa para que puedas sacarle el máximo provecho desde el primer día.</p>
            
            <h6 class="fw-bold mt-4">1.2. Requerimientos mínimos (Navegador y dispositivos)</h6>
            <p>GestorTaller es una aplicación web moderna. Esto significa que no necesitas instalar ningún programa complicado en tu ordenador; sólo necesitas una conexión a Internet y un navegador actualizado.</p>
            <p><strong>Dispositivos recomendados:</strong></p>
            <ul>
                <li><strong>Ordenador de sobremesa o Portátil:</strong> Ideal para las tareas administrativas, gestión de facturas y visualización de gráficos estadísticos.</li>
                <li><strong>Tablets:</strong> El sistema está optimizado para dispositivos táctiles. Una tablet es la herramienta perfecta para llevarla al lado del coche, hacer fotos y mover las tarjetas en el tablero de producción.</li>
                <li><strong>Teléfono Móvil:</strong> Útil para consultas rápidas de teléfonos de clientes o para comprobar el calendario de citas desde cualquier lugar.</li>
            </ul>
            <p><strong>Navegadores compatibles:</strong></p>
            <p>Se recomienda utilizar las últimas versiones de Google Chrome (Muy recomendado), Mozilla Firefox, Safari o Microsoft Edge.</p>
          </div>
        </div>

        <!-- 2. ACCESO -->
        <div class="card mb-4" id="sec-2">
          <div class="card-header border-bottom">
            <h5 class="mb-0 fw-bold">2. ACCESO AL SISTEMA</h5>
          </div>
          <div class="card-body mt-3">
            <p>Para entrar al gestor, debes estar registrado y tu cuenta debe haber sido aprobada por el administrador del taller.</p>
            
            <h6 class="fw-bold">2.1. Pantalla de Login (Inicio de sesión)</h6>
            <p>Introduce tus credenciales: Correo electrónico y Contraseña (mínimo 8 caracteres).</p>
            
            <h6 class="fw-bold mt-4">2.2. Registro de nuevos usuarios</h6>
            <p>Si eres un nuevo trabajador, crea tu cuenta rellenando el formulario: Nombre, Email, Contraseña y Confirmación.</p>
            
            <h6 class="fw-bold mt-4 text-warning">2.3. Activación de la cuenta (Proceso de aprobación)</h6>
            <div class="alert alert-warning border-0">
                <strong>IMPORTANTE:</strong> Por motivos de seguridad, una vez te registras, tu cuenta no se activa automáticamente. El Administrador del taller debe validar tu identidad desde el panel de control para permitirte el acceso. Esto garantiza que ninguna persona externa al taller pueda ver los datos de nuestros clientes.
            </div>
          </div>
        </div>

        <!-- 3. DASHBOARD -->
        <div class="card mb-4" id="sec-3">
          <div class="card-header border-bottom text-primary">
            <h5 class="mb-0 fw-bold text-primary">3. EL CUADRO DE MANDO (DASHBOARD)</h5>
          </div>
          <div class="card-body mt-3">
            <p>El Dashboard es la pantalla principal. Está diseñado para ofrecer al gerente una "radiografía" instantánea de cómo va el negocio sin tener que entrar en cada menú por separado. La información se actualiza en tiempo real.</p>
            
            <h6 class="fw-bold">3.1. Visión general de las estadísticas</h6>
            <p>Tarjetas de resumen en la parte superior:</p>
            <ul>
                <li><strong>Total de Clientes:</strong> Crecimiento del negocio.</li>
                <li><strong>Vehículos Registrados:</strong> Cuántos coches distintos han pasado por el taller.</li>
                <li><strong>Encargos Activos:</strong> Trabajos ahora mismo en el taller.</li>
                <li><strong>Trabajos por Entregar:</strong> Coches acabados esperando recogida.</li>
            </ul>
            <p>Incluye el <strong>Gráfico de Producción Mensual</strong> interactivo para ver la evolución de la carga de trabajo.</p>
            
            <h6 class="fw-bold mt-4">3.2. Configuración del Catálogo de Materials</h6>
            <p>Fundamental para personalizar el sistema. Puedes organizar los recursos por tipos (Telas, Espumas, Hilos, Accesorios) e introducir tus propios precios de coste y unidades de medida.</p>
            
            <h6 class="fw-bold mt-4">3.3. Citas y tareas del día</h6>
            <p>Calendario de gestión diaria visible en el Dashboard para guiar al personal de recepción con listado cronológico e identificación rápida de cliente y vehículo.</p>
          </div>
        </div>

        <!-- 4. CRM -->
        <div class="card mb-4" id="sec-4">
          <div class="card-header border-bottom text-primary">
            <h5 class="mb-0 fw-bold text-primary">4. GESTIÓN DE CLIENTES Y VEHÍCULOS (CRM)</h5>
          </div>
          <div class="card-body mt-3">
            <h6 class="fw-bold">4.1. Listado de clientes</h6>
            <p>Motor de búsqueda inteligente por nombre, apellido o teléfono. Acceso rápido a datos de contacto y gestión de edición/borrado.</p>
            
            <h6 class="fw-bold mt-4">4.2. Cómo crear un cliente nuevo</h6>
            <p>Rápido registro con Nombre, Apellidos y Teléfono (campo clave). Validación automática para evitar duplicados.</p>
            
            <h6 class="fw-bold mt-4">4.3. Ficha del vehículo e historial de reparaciones</h6>
            <p>Vehículos vinculados a propietarios. Uso de marcas y modelos oficiales (API) para datos exactos. Consulta del historial completo de intervenciones pasadas.</p>
          </div>
        </div>

        <!-- 5. WIZARD -->
        <div class="card mb-4" id="sec-5">
          <div class="card-header border-bottom text-primary">
            <h5 class="mb-0 fw-bold text-primary">5. EL FLUJO DE TRABAJO (WIZARD)</h5>
          </div>
          <div class="card-body mt-3">
            <p>Asistente inteligente guiado por 4 pasos lógicos:</p>
            <ul>
                <li><strong>Paso 1: Selección del cliente:</strong> Existente o creación de uno nuevo.</li>
                <li><strong>Paso 2: Selección del vehículo:</strong> Buscador de marcas y modelos inteligentes.</li>
                <li><strong>Paso 3: Selección de servicios y tarifas:</strong> Categorías visuales y presupuesto inicial.</li>
                <li><strong>Paso 4: Programación de cita y confirmación:</strong> Fecha de entrada y finalización del proceso.</li>
            </ul>
          </div>
        </div>

        <!-- 6. KANBAN -->
        <div class="card mb-4" id="sec-6">
          <div class="card-header border-bottom text-primary">
            <h5 class="mb-0 fw-bold text-primary">6. GESTIÓN VISUAL: TABLEROS KANBAN</h5>
          </div>
          <div class="card-body mt-3">
            <h6 class="fw-bold">6.1. Kanban de Recepción</h6>
            <p>Fases: Cita Agendada, En Revisión y Presupuesto Enviado.</p>
            
            <h6 class="fw-bold mt-4">6.2. Kanban de Producción</h6>
            <p>Fases: Pendiente Inicio, En Producción y Esperando Recogida.</p>
            
            <h6 class="fw-bold mt-4">6.3. Cómo mover las tarjetas (Drag & Drop)</h6>
            <p>Interacción intuitiva arrastrando y soltando para actualizar el estado automáticamente.</p>
            
            <h6 class="fw-bold mt-4 text-danger">6.4. Bloqueos de seguridad</h6>
            <p>Reglas de negocio integradas: No se puede saltar el orden lógico, se requiere presupuesto aceptado para pasar a producción y fecha estimada de entrega obligatoria.</p>
          </div>
        </div>

        <!-- 7. GALERIA -->
        <div class="card mb-4" id="sec-7">
          <div class="card-header border-bottom text-primary">
            <h5 class="mb-0 fw-bold text-primary">7. DOCUMENTACIÓN VISUAL (GALERÍA WEB)</h5>
          </div>
          <div class="card-body mt-3">
            <h6 class="fw-bold">7.1. Cómo subir fotografías a la Galería Pública</h6>
            <p>Gestión de contenidos para la Landing Page con descripciones para el marketing del taller.</p>
            
            <h6 class="fw-bold mt-4">7.2. Vincular fotos (Efecto "Antes y Después")</h6>
            <p>Función de comparación visual vinculando fotos de entrada y salida del trabajo.</p>
          </div>
        </div>

        <!-- 8. ECONOMIA -->
        <div class="card mb-4" id="sec-8">
          <div class="card-header border-bottom text-primary">
            <h5 class="mb-0 fw-bold text-primary">8. GESTIÓ ECONÓMICA</h5>
          </div>
          <div class="card-body mt-3">
            <h6 class="fw-bold">8.1. Registro del presupuesto</h6>
            <p>Estimación inicial en el Wizard y ajuste final desde el Tablero de Recepción.</p>
            
            <h6 class="fw-bold mt-4">8.2. Generación de la factura final</h6>
            <p>Creación automática al entregar el vehículo, vinculada a los datos del cliente y presupuesto inicial.</p>
            
            <h6 class="fw-bold mt-4">8.3. Control de pagos (Pagado/Pendiente)</h6>
            <p>Marcado manual de cobros y archivo histórico de facturación para contabilidad.</p>
          </div>
        </div>

        <!-- 9. ADMIN -->
        <div class="card mb-4" id="sec-9">
          <div class="card-header border-bottom text-primary">
            <h5 class="mb-0 fw-bold text-primary">9. CONFIGURACIÓN Y SEGURETAT (ADMIN)</h5>
          </div>
          <div class="card-body mt-3">
            <h6 class="fw-bold">9.1. Gestión de usuarios y roles</h6>
            <p><strong>Rol Administrador:</strong> Acceso total, aprobación de usuarios y gestión económica.</p>
            <p><strong>Rol Operario:</strong> Trabajo diario del taller con restricciones en datos sensibles.</p>
            
            <h6 class="fw-bold mt-4">9.2. Exportación de datos a Excel/CSV</h6>
            <p>Botones de exportación en todas las tablas principales para inventarios y copias de seguridad.</p>
          </div>
        </div>

        <!-- 10. FAQ -->
        <div class="card border border-danger shadow-none" id="sec-10">
          <div class="card-header border-bottom text-danger">
            <h5 class="mb-0 fw-bold text-danger">10. RESOLUCIÓN DE PROBLEMAS COMUNES (FAQ)</h5>
          </div>
          <div class="card-body mt-3">
            <h6 class="fw-bold">He creado mi cuenta pero no puedo entrar.</h6>
            <p>El administrador debe activarte manualmente desde la sección de Usuarios.</p>
            
            <h6 class="fw-bold mt-4">Intento arrastrar una tarjeta y no se mueve.</h6>
            <p>Verifica el orden lógico y asegúrate de que el presupuesto esté aceptado.</p>
            
            <h6 class="fw-bold mt-4">No encuentro un modelo de coche.</h6>
            <p>Selecciona primero la marca para que carguen los modelos correspondientes.</p>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
