<?php

use Illuminate\Support\Facades\Route;

// Ruta de Acceso Rápido a la Demo (Cero Fricción)
Route::get('/demo-fast', function(\Illuminate\Http\Request $request) {
    // Cerramos cualquier sesión previa para asegurar una experiencia limpia
    auth()->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    $user = \App\Models\User::where('email', 'admin@demo.com')->first();
    if ($user) {
        auth()->login($user);
        return redirect()->route('home')->with('status', '¡Bienvenido a la Experiencia Colegio-Pro!');
    }
    return redirect('/')->with('error', 'El entorno de demo no está listo.');
})->name('demo.fast');

// Rutas Públicas de Validación (Escaneo de QR)
Route::get('/v/{uuid}', [\App\Http\Controllers\ValidationController::class, 'show'])->name('validation.show');

Route::get('/', function () {
    $plans = \App\Models\SubscriptionPlan::all();
    return view('welcome', compact('plans'));
});

// Demo Registration
Route::get('/demo/unirse', [App\Http\Controllers\DemoRegistrationController::class, 'show'])->name('demo.register');
Route::post('/demo/register', [App\Http\Controllers\DemoRegistrationController::class, 'register'])->name('demo.register.post');

// Escuela Virtual Pública (Vitrina de Cursos)
Route::get('/escuela-virtual', [\App\Http\Controllers\Student\LessonController::class, 'index'])->name('academy.public');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Rutas exclusivas para el OWNER y Administradores Internos (Gestión Global del SaaS)
Route::middleware(['auth', 'role:OWNER,ADMIN_INTERNO'])->group(function () {
    // Dashboard principal con métricas globales
    Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Rutas para la "Visión Omnisciente" y retorno al rol original
    Route::get('/admin/impersonate/{schoolId}', [App\Http\Controllers\Admin\DashboardController::class, 'impersonate'])->name('admin.impersonate');
    Route::get('/admin/leave-impersonation', [App\Http\Controllers\Admin\DashboardController::class, 'leaveImpersonation'])->name('admin.leave_impersonation');
    
    // Gestión de Colegios (Tenants)
    Route::get('/admin/colegios', [App\Http\Controllers\Admin\SchoolController::class, 'index'])->name('admin.schools.index');
    Route::get('/admin/colegios/nuevo', [App\Http\Controllers\Admin\SchoolController::class, 'create'])->name('admin.schools.create');
    Route::post('/admin/colegios/guardar', [App\Http\Controllers\Admin\SchoolController::class, 'store'])->name('admin.schools.store');
    Route::get('/admin/colegios/{school}/editar', [App\Http\Controllers\Admin\SchoolController::class, 'edit'])->name('admin.schools.edit');
    Route::post('/admin/colegios/{school}/actualizar', [App\Http\Controllers\Admin\SchoolController::class, 'update'])->name('admin.schools.update');

    // Comunicación masiva a todos los clientes (tenants)
    Route::post('/admin/notifications', [App\Http\Controllers\Admin\NotificationController::class, 'store'])->name('admin.notifications.store');
    
    // Gestión de Tickets (Admin side)
    Route::get('/tickets', [\App\Http\Controllers\Admin\TicketController::class, 'index'])->name('admin.tickets.index');
    Route::get('/tickets/{ticket}', [\App\Http\Controllers\Admin\TicketController::class, 'show'])->name('admin.tickets.show');
    Route::post('/tickets/{ticket}/reply', [\App\Http\Controllers\Admin\TicketController::class, 'reply'])->name('admin.tickets.reply');
    Route::post('/tickets/{ticket}/resolve', [\App\Http\Controllers\Admin\TicketController::class, 'resolve'])->name('admin.tickets.resolve');
    Route::post('/tickets/{ticket}/close', [\App\Http\Controllers\Admin\TicketController::class, 'close'])->name('admin.tickets.close');

    // Gestión de Planes de Suscripción (Owner side)
    Route::get('/planes', [\App\Http\Controllers\Admin\PlanController::class, 'index'])->name('admin.plans.index');
    Route::get('/planes/{plan}/editar', [\App\Http\Controllers\Admin\PlanController::class, 'edit'])->name('admin.plans.edit');
    Route::post('/planes/{plan}/actualizar', [\App\Http\Controllers\Admin\PlanController::class, 'update'])->name('admin.plans.update');

    // Módulo de Auditoría y Seguridad Global (Owner side)
    Route::get('/auditoria', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('admin.activity_logs.index');

    // Finanzas Globales (Owner side)
    Route::get('/finanzas', [\App\Http\Controllers\Admin\BillingController::class, 'index'])->name('admin.billing.index');

    // Gestión Global de Academia (Cursos/Lecciones)
    Route::resource('/admin/academy', \App\Http\Controllers\Admin\LessonController::class)->names('admin.academy');
    Route::resource('/admin/exams', \App\Http\Controllers\Admin\ExamController::class)->names('admin.exams');
    Route::post('/admin/exams/{exam}/questions', [\App\Http\Controllers\Admin\ExamController::class, 'storeQuestion'])->name('admin.exams.questions.store');
    Route::delete('/admin/questions/{question}', [\App\Http\Controllers\Admin\ExamController::class, 'destroyQuestion'])->name('admin.exams.questions.destroy');
    
    // Gestión de Facturación Global (OWNER)
    Route::get('/admin/billing', [\App\Http\Controllers\Admin\BillingController::class, 'index'])->name('admin.billing.index');
    Route::get('/admin/billing/download/{invoice}', [\App\Http\Controllers\Admin\BillingController::class, 'downloadInvoice'])->name('admin.billing.download');

    // Gestión de Recursos (PDF, Slides, etc)
    Route::post('/admin/academy/{lesson}/resources', [\App\Http\Controllers\Admin\LessonResourceController::class, 'store'])->name('admin.lesson_resources.store');
    Route::delete('/admin/resources/{resource}', [\App\Http\Controllers\Admin\LessonResourceController::class, 'destroy'])->name('admin.lesson_resources.destroy');
});

// Rutas Generales Autenticadas (Cualquier Rol)
Route::middleware(['auth'])->group(function () {
    // Facturación y Suscripción (Admin de Colegio)
    Route::get('/mi-plan', [\App\Http\Controllers\BillingController::class, 'index'])->name('billing.index');
    Route::post('/mi-plan/upgrade', [\App\Http\Controllers\BillingController::class, 'upgrade'])->name('billing.upgrade');

    // Asistente IA
    Route::get('/ai/asistente', [App\Http\Controllers\AIController::class, 'index'])->name('ai.index');
    Route::post('/ai/query', [App\Http\Controllers\AIController::class, 'query'])->name('ai.query');

    // Gestión de Colegiados (Admin de Colegio)
    Route::get('/colegiados', [\App\Http\Controllers\CollegiateController::class, 'index'])->name('collegiates.index');
    Route::get('/colegiados/exportar', [\App\Http\Controllers\CollegiateController::class, 'export'])->name('collegiates.export');
    Route::get('/colegiados/importar', [\App\Http\Controllers\CollegiateController::class, 'import'])->name('collegiates.import');
    Route::post('/colegiados/importar', [\App\Http\Controllers\CollegiateController::class, 'storeImport'])->name('collegiates.import.store');
    Route::get('/colegiados/{collegiate}', [\App\Http\Controllers\CollegiateController::class, 'show'])->name('collegiates.show');
    Route::get('/colegiados/{collegiate}/certificado', [\App\Http\Controllers\CollegiateController::class, 'certificate'])->name('collegiates.certificate');

    // Motor de Pagos y Liquidaciones
    Route::post('/pagar/cuotas', [\App\Http\Controllers\PaymentController::class, 'payDues'])->name('payment.dues');
    Route::post('/pagar/reserva/{booking}', [\App\Http\Controllers\PaymentController::class, 'payBooking'])->name('payment.booking');

    // Gestión del Plan de Documentación (Requisitos de Cumplimiento)
    Route::get('/requisitos', [\App\Http\Controllers\ComplianceRequirementController::class, 'index'])->name('compliance_requirements.index');
    Route::post('/requisitos', [\App\Http\Controllers\ComplianceRequirementController::class, 'store'])->name('compliance_requirements.store');
    Route::put('/requisitos/{requirement}', [\App\Http\Controllers\ComplianceRequirementController::class, 'update'])->name('compliance_requirements.update');
    Route::delete('/requisitos/{requirement}', [\App\Http\Controllers\ComplianceRequirementController::class, 'destroy'])->name('compliance_requirements.destroy');

    // Auditoría de Legajos (Revisión de Documentación)
    Route::get('/revision-documentos', [\App\Http\Controllers\Admin\ComplianceReviewController::class, 'index'])->name('admin.compliance.index');
    Route::post('/revision-documentos/{document}/aprobar', [\App\Http\Controllers\Admin\ComplianceReviewController::class, 'approve'])->name('admin.compliance.approve');
    Route::post('/revision-documentos/{document}/rechazar', [\App\Http\Controllers\Admin\ComplianceReviewController::class, 'reject'])->name('admin.compliance.reject');

    // Gestión de Notificaciones y Cobranza
    Route::post('/notificaciones/aviso/{collegiate}', [\App\Http\Controllers\CollegiateNotificationController::class, 'sendWarning'])->name('notifications.warning');
    Route::post('/notificaciones/masivas', [\App\Http\Controllers\CollegiateNotificationController::class, 'bulkNotify'])->name('notifications.bulk');

    // Gestión de Amenidades y Servicios (Sedes, Canchas, Salones)
    Route::get('/servicios', [\App\Http\Controllers\AmenityController::class, 'index'])->name('amenities.index');
    Route::post('/servicios/reservar', [\App\Http\Controllers\AmenityController::class, 'book'])->name('amenities.book');
    Route::post('/servicios/{amenity}/toggle', [\App\Http\Controllers\AmenityController::class, 'toggle'])->name('amenities.toggle');

    // Videoteca y Aprendizaje (Cualquier Usuario / Alumno de Institución)
    Route::get('/mis-clases', [\App\Http\Controllers\Student\LessonController::class, 'index'])->name('student.lessons.index');
    Route::post('/mis-clases/inscribirse/{lesson}', [\App\Http\Controllers\Student\LessonController::class, 'enroll'])->name('student.lessons.enroll');
    Route::get('/mis-clases/{lesson}', [\App\Http\Controllers\Student\LessonController::class, 'show'])->name('student.lessons.show');

    // Exámenes (Para Estudiantes/Colegiados)
    Route::get('/academia/examen/{exam}', [\App\Http\Controllers\Student\ExamController::class, 'take'])->name('student.exams.take');
    Route::post('/academia/examen/{exam}/submit', [\App\Http\Controllers\Student\ExamController::class, 'submit'])->name('student.exams.submit');
    Route::get('/academia/certificado/{certificate}', [\App\Http\Controllers\Student\CertificateController::class, 'download'])->name('student.certificates.download');

    // Tickets
    Route::get('/soporte', [App\Http\Controllers\TicketController::class, 'index'])->name('tickets.index');
    Route::get('/soporte/nuevo', [App\Http\Controllers\TicketController::class, 'create'])->name('tickets.create');
    Route::post('/soporte/nuevo', [App\Http\Controllers\TicketController::class, 'store'])->name('tickets.store');
    Route::get('/soporte/{ticket}', [App\Http\Controllers\TicketController::class, 'show'])->name('tickets.show');
    Route::post('/soporte/{ticket}/reply', [App\Http\Controllers\TicketController::class, 'reply'])->name('tickets.reply');

    // Portal del Colegiado: Gestión de Legajo Digital
    Route::get('/mi-legajo', [\App\Http\Controllers\ComplianceController::class, 'index'])->name('compliance.index');
    Route::post('/mi-legajo/subir/{requirement}', [\App\Http\Controllers\ComplianceController::class, 'upload'])->name('compliance.upload');
});
