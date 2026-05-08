@extends('layouts/contentNavbarLayout')

@section('title', 'Manual de Usuario')

@section('content')
<div class="row">
  <div class="col-12">
    <!-- HERO HEADER - CLEAN VERSION -->
    <div class="card mb-4 border-0 shadow-sm overflow-hidden bg-primary">
        <div class="card-body p-5">
            <div class="text-white">
                <small class="text-uppercase fw-bold opacity-75">Documentación del Sistema</small>
                <h1 class="display-4 fw-bold text-white mb-2">Manual de Usuario</h1>
                <p class="fs-4 opacity-75 mb-0">Gestor Taller: Gestión Integral para Tapicerías</p>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT GRID -->
    <div class="row g-4">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 d-none d-lg-block">
            <div class="card sticky-top border-0 shadow-sm" style="top: 100px; z-index: 10;">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0 text-uppercase small">Índice</h6>
                </div>
                <div class="list-group list-group-flush rounded-bottom">
                    <a href="#sec-intro" class="list-group-item list-group-item-action py-3 border-0">1. Introducción</a>
                    <a href="#sec-acces" class="list-group-item list-group-item-action py-3 border-0">2. Acceso al Sistema</a>
                    <a href="#sec-dash" class="list-group-item list-group-item-action py-3 border-0">3. Dashboard</a>
                    <a href="#sec-crm" class="list-group-item list-group-item-action py-3 border-0">4. CRM de Clients</a>
                    <a href="#sec-wizard" class="list-group-item list-group-item-action py-3 border-0">5. Asistente Wizard</a>
                    <a href="#sec-kanban" class="list-group-item list-group-item-action py-3 border-0">6. Tableros Kanban</a>
                    <a href="#sec-galeria" class="list-group-item list-group-item-action py-3 border-0">7. Galería Web</a>
                    <a href="#sec-eco" class="list-group-item list-group-item-action py-3 border-0">8. Gestión Económica</a>
                    <a href="#sec-admin" class="list-group-item list-group-item-action py-3 border-0">9. Administración</a>
                    <a href="#sec-faq" class="list-group-item list-group-item-action py-3 border-0 text-primary fw-bold">10. FAQ</a>
                </div>
            </div>
        </div>

        <!-- Manual Sections -->
        <div class="col-lg-9">
            
            <!-- 1. INTRODUCCIÓN -->
            <div id="sec-intro" class="mb-5 scroll-mt-5">
                <h2 class="fw-bold mb-3">1. Introducción</h2>
                <hr class="mb-4">
                
                <h5 class="fw-bold text-dark mb-3">1.1. Bienvenida al sistema</h5>
                <p class="fs-5 text-muted mb-4">GestorTaller es tu herramienta centralizada para digitalizar el día a día de tu tapicería.</p>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="h-100 border-start border-primary border-4 ps-3">
                            <h6 class="fw-bold mb-2">¿Qué podrás hacer?</h6>
                            <ul class="list-unstyled mb-0 small">
                                <li class="mb-2">Control de clientes y vehículos</li>
                                <li class="mb-2">Gestión de citas sin errores</li>
                                <li class="mb-2">Seguimiento visual con Kanban</li>
                                <li>Facturación y Presupuestos</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="h-100 border-start border-dark border-4 ps-3 text-muted small">
                            <h6 class="fw-bold mb-2 text-dark">Dispositivos</h6>
                            <p class="mb-0">Compatible con PC (Administración), Tablet (Taller) y Móvil (Consultas rápidas).</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. ACCESO AL SISTEMA -->
            <div id="sec-acces" class="mb-5">
                <h2 class="fw-bold mb-3">2. Acceso al Sistema</h2>
                <hr class="mb-4">

                <div class="alert alert-dark border-0 mb-4 rounded-0 border-start border-warning border-4 shadow-none">
                    <h6 class="fw-bold mb-1">2.3. Activación de la cuenta</h6>
                    <p class="mb-0 small">Por seguridad, el administrador debe validar tu cuenta antes de que puedas entrar por primera vez.</p>
                </div>

                <div class="row g-4">
                    <div class="col-sm-4">
                        <div class="py-2 border-bottom">
                            <h6 class="fw-bold mb-1">Registro</h6>
                            <p class="small text-muted mb-0">Crea tu perfil con el email de la empresa.</p>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="py-2 border-bottom">
                            <h6 class="fw-bold mb-1">Aprobación</h6>
                            <p class="small text-muted mb-0">El administrador activa tu cuenta.</p>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="py-2 border-bottom border-primary">
                            <h6 class="fw-bold text-primary mb-1">Acceso</h6>
                            <p class="small text-muted mb-0">Ya puedes gestionar el taller.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. DASHBOARD -->
            <div id="sec-dash" class="mb-5">
                <h2 class="fw-bold mb-3">3. Dashboard</h2>
                <hr class="mb-4">
                <p>Resumen estadístico de la producción mensual y el volumen de encargos activos.</p>
                
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Indicador</th>
                                <th>Utilidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td><strong>Total Clientes</strong></td><td class="small">Crecimiento de la base de datos.</td></tr>
                            <tr><td><strong>Encargos Activos</strong></td><td class="small">Volumen de trabajo actual.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 4. CRM CLIENTES -->
            <div id="sec-crm" class="mb-5">
                <h2 class="fw-bold mb-3">4. Gestión de Clients y Vehículos</h2>
                <hr class="mb-4">
                <p>Listado inteligente optimizado para buscar por teléfono, nombre o matrícula. Historial completo vinculado a cada vehículo.</p>
            </div>

            <!-- 5. WIZARD -->
            <div id="sec-wizard" class="mb-5">
                <h2 class="fw-bold mb-3">5. Asistente de Nuevos Trabajos</h2>
                <hr class="mb-4">
                <div class="row text-center g-2">
                    <div class="col-3"><div class="p-2 border small fw-bold">1. CLIENTE</div></div>
                    <div class="col-3"><div class="p-2 border small fw-bold">2. VEHÍCULO</div></div>
                    <div class="col-3"><div class="p-2 border small fw-bold">3. SERVICIOS</div></div>
                    <div class="col-3"><div class="p-2 border small fw-bold text-primary border-primary">4. CITA</div></div>
                </div>
            </div>

            <!-- 6. KANBAN -->
            <div id="sec-kanban" class="mb-5">
                <h2 class="fw-bold mb-3">6. Tableros Kanban Visuales</h2>
                <hr class="mb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 border">
                            <h6 class="fw-bold border-bottom pb-2 mb-2">Recepción</h6>
                            <p class="small mb-0">Citas, revisiones y presupuestos.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border">
                            <h6 class="fw-bold border-bottom pb-2 mb-2">Producción</h6>
                            <p class="small mb-0">Corte, costura y montaje.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 7. GALERÍA -->
            <div id="sec-galeria" class="mb-5">
                <h2 class="fw-bold mb-3">7. Galería "Antes y Después"</h2>
                <hr class="mb-4">
                <p>Sube y vincula fotos para mostrar la transformación de tus trabajos en la web pública.</p>
            </div>

            <!-- 8. ECONOMÍA -->
            <div id="sec-eco" class="mb-5">
                <h2 class="fw-bold mb-3">8. Gestión Económica</h2>
                <hr class="mb-4">
                <p class="small">Facturación automática al entregar el vehículo y marcado manual de cobros (Pagado/Pendiente).</p>
            </div>

            <!-- 9. ADMINISTRACIÓN -->
            <div id="sec-admin" class="mb-5">
                <h2 class="fw-bold mb-3">9. Administración</h2>
                <hr class="mb-4">
                <p class="small">Control de usuarios, aprobación de registros y exportación de datos a Excel.</p>
            </div>

            <!-- 10. FAQ -->
            <div id="sec-faq" class="mb-5 bg-light p-4 rounded border">
                <h2 class="fw-bold mb-4">10. FAQ</h2>
                <div class="mb-3">
                    <h6 class="fw-bold mb-1 text-primary">¿No puedes mover una tarjeta?</h6>
                    <p class="small mb-0">Verifica que el presupuesto esté aceptado y tenga un precio definitivo.</p>
                </div>
                <div>
                    <h6 class="fw-bold mb-1 text-primary">¿Dónde están los trabajos terminados?</h6>
                    <p class="small mb-0">En el menú de Historial Completo.</p>
                </div>
            </div>

        </div>
    </div>
  </div>
</div>

<link rel="stylesheet" href="{{ asset('assets/css/custom/manual.css') }}?v={{ time() }}">
@endsection
