<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PublicNewsController;
use App\Http\Controllers\NewsArticleController;

Route::get('/fix-prueba-domain', function () {
    $school = \App\Models\School::find(6);
    if ($school) {
        $school->custom_domain = 'lab-colepro.gentepiola.net';
        $school->slug = 'colegioprofesional';
        $school->logo = 'logos/colegio_profesional_demo.png';
        $school->primary_color = '#4338ca';
        $school->secondary_color = '#0d9488';
        $school->save();
        return "Dominio, slug y colores vinculados exitosamente al ID 6 en la base de datos de producción.";
    }
    return "Error: No se encontró el colegio con ID 6.";
});

Route::get('change-language/{lang}', [LanguageController::class, 'switch'])->name('lang.switch');

// === Rutas Públicas (Landing Pages y Autenticación) ===
Route::get('/admin/magic-impersonate/{userId}/{ownerId}', [App\Http\Controllers\Admin\DashboardController::class, 'magicImpersonate'])->name('admin.magic_impersonate')->middleware('signed');

// Ruta de Acceso Rápido a la Demo (Cero Fricción)
Route::get('/demo-fast', function(\Illuminate\Http\Request $request) {
    auth()->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Create a demo school if not exists
    $school = \App\Models\School::firstOrCreate(
        ['slug' => 'demo'],
        ['name' => 'Colegio de Terapistas Ocupacionales', 'status' => 'active']
    );

    // Create a demo admin user
    $user = \App\Models\User::firstOrCreate(
        ['email' => 'admin@demo.com'],
        [
            'name' => 'Administrador Demo',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'ADMIN_COLEGIO',
            'school_id' => $school->id
        ]
    );

    auth()->login($user);
    return redirect()->route('collegiates.index')->with('status', '¡Bienvenido al modo demostración libre!');
})->name('demo.fast');

// Ruta de Emergencia para Acceso Administrativo (OWNER)
Route::get('/force-admin-mario', function() {
    $user = \App\Models\User::updateOrCreate(
        ['email' => 'prueba@gmail.com'],
        [
            'name' => 'MARIO OWNER',
            'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
            'role' => 'owner'
        ]
    );
    return "USUARIO_CREADO_EXITO: " . $user->email . " | Password: 12345678";
});

// Rutas Públicas de Validación (Escaneo de QR)
Route::get('/validador/{uuid}', [\App\Http\Controllers\ValidationController::class, 'validateQR'])->name('validador.verify');
Route::get('/v/{uuid}', [\App\Http\Controllers\ValidationController::class, 'show'])->name('validation.show');
Route::post('/v/{uuid}/burn', [\App\Http\Controllers\ValidationController::class, 'burn'])->name('validation.burn');

Route::post('/chatbot/ask', [\App\Http\Controllers\ChatbotController::class, 'ask'])->name('chatbot.ask');

Route::get('/dev/fix-cache', function() {
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    return '¡Base de datos y caché actualizados con éxito! Ya puedes volver y probar.';
});

Route::get('/', [\App\Http\Controllers\PublicLandingController::class, 'index'])->name('landing');
Route::post('/validar-matricula', [\App\Http\Controllers\PublicLandingController::class, 'validateMatricula'])->name('public.validate.matricula');
Route::get('/noticias/{slug}', [\App\Http\Controllers\PublicNewsController::class, 'show'])->name('public.news.show');

// Demo Registration
Route::get('/demo/unirse', [App\Http\Controllers\DemoRegistrationController::class, 'show'])->name('demo.register');
Route::post('/demo/register', [App\Http\Controllers\DemoRegistrationController::class, 'register'])->name('demo.register.post');

// PWA Dynamic Manifest
Route::get('/manifest.json', function (\Illuminate\Http\Request $request) {
    $tenant = app('tenant');
    return response()->json([
        'name' => $tenant->name ?? 'Colegio Profesional',
        'short_name' => $tenant->slug ?? 'Colegio',
        'start_url' => '/',
        'display' => 'standalone',
        'background_color' => $tenant->secondary_color ?? '#0f172a',
        'theme_color' => $tenant->primary_color ?? '#3b82f6',
        'icons' => [
            [
                'src' => $tenant->logo ? asset($tenant->logo) : asset('favicon.ico'),
                'sizes' => '192x192',
                'type' => 'image/png'
            ],
            [
                'src' => $tenant->logo ? asset($tenant->logo) : asset('favicon.ico'),
                'sizes' => '512x512',
                'type' => 'image/png'
            ]
        ]
    ]);
})->name('pwa.manifest');

// Escuela Virtual Pública (Vitrina de Cursos)
Route::get('/escuela-virtual', [\App\Http\Controllers\Student\LessonController::class, 'index'])->name('academy.public');

// Rutas Públicas Adicionales
Route::get('/noticias', [PublicNewsController::class, 'index'])->name('news.index');
Route::get('/noticias/{slug}', [PublicNewsController::class, 'show'])->name('news.show');

// RUTAS DE ACTIVACIÓN DE CUENTA (Para Colegiados importados sin usuario)
Route::get('activar-cuenta', [App\Http\Controllers\Auth\AccountActivationController::class, 'showSearchForm'])->name('activate.step1');
Route::post('activar-cuenta/buscar', [App\Http\Controllers\Auth\AccountActivationController::class, 'search'])->name('activate.search');
Route::get('activar-cuenta/confirmar', [App\Http\Controllers\Auth\AccountActivationController::class, 'showRegisterForm'])->name('activate.step2');
Route::post('activar-cuenta/registrar', [App\Http\Controllers\Auth\AccountActivationController::class, 'register'])->name('activate.register');

Auth::routes();

// Permite cerrar sesión a través de una petición GET (evita errores 405 si el usuario escribe la URL o recarga la página)
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout.get');

Route::post('/register/verify', [App\Http\Controllers\Auth\RegisterController::class, 'verify'])->name('register.verify');
Route::get('/register/finalize', [App\Http\Controllers\Auth\RegisterController::class, 'showFinalizeForm'])->name('register.finalize');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Rutas exclusivas para el OWNER y Administradores Internos (Gestión Global del SaaS)
Route::middleware(['auth', 'role:OWNER,ADMIN_INTERNO'])->group(function () {
    // Dashboard principal con métricas globales
    Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Rutas para la "Visión Omnisciente" y retorno al rol original (Iniciador)
    Route::get('/admin/impersonate/{schoolId}', [App\Http\Controllers\Admin\DashboardController::class, 'impersonate'])->name('admin.impersonate');
    
    // Gestión de Colegios (Tenants)
    Route::get('/admin/colegios', [App\Http\Controllers\Admin\SchoolController::class, 'index'])->name('admin.schools.index');
    Route::get('/admin/colegios/nuevo', [App\Http\Controllers\Admin\SchoolController::class, 'create'])->name('admin.schools.create');
    Route::post('/admin/colegios/guardar', [App\Http\Controllers\Admin\SchoolController::class, 'store'])->name('admin.schools.store');
    Route::get('/admin/colegios/{school}/editar', [App\Http\Controllers\Admin\SchoolController::class, 'edit'])->name('admin.schools.edit');
    Route::post('/admin/colegios/{school}/actualizar', [App\Http\Controllers\Admin\SchoolController::class, 'update'])->name('admin.schools.update');
    Route::post('/admin/colegios/{school}/generar-demo', [App\Http\Controllers\Admin\SchoolController::class, 'generateDemoUser'])->name('admin.schools.generate_demo');

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

    // Ajustes Globales (Owner side)
    Route::post('/admin/global-settings/domain', function (\Illuminate\Http\Request $request) {
        if (!auth()->user()->isOwner()) abort(403);
        $request->validate(['base_domain' => 'required|string']);
        \App\Models\GlobalSetting::setVal('base_domain', $request->base_domain);
        return back()->with('success', 'Dominio base actualizado correctamente.');
    })->name('admin.global_settings.update_domain');

    // Finanzas Globales (Owner side)
    Route::get('/admin/global-billing', [\App\Http\Controllers\Admin\GlobalBillingController::class, 'index'])->name('admin.billing.global');

    // (Rutas antiguas del CMS eliminadas para evitar colisión)

    Route::resource('/admin/academy', \App\Http\Controllers\Admin\LessonController::class)->names('admin.academy');
    Route::resource('/admin/exams', \App\Http\Controllers\Admin\ExamController::class)->names('admin.exams');
    Route::post('/admin/exams/{exam}/questions', [\App\Http\Controllers\Admin\ExamController::class, 'storeQuestion'])->name('admin.exams.questions.store');
    Route::delete('/admin/questions/{question}', [\App\Http\Controllers\Admin\ExamController::class, 'destroyQuestion'])->name('admin.exams.questions.destroy');
    
    // Gestión de Facturación Global (OWNER)
    Route::get('/admin/billing-download/{invoice}', [\App\Http\Controllers\Admin\BillingController::class, 'downloadInvoice'])->name('admin.billing.download_global');

    // Gestión de Recursos (PDF, Slides, etc)
    Route::post('/admin/academy/{lesson}/resources', [\App\Http\Controllers\Admin\LessonResourceController::class, 'store'])->name('admin.lesson_resources.store');
    Route::delete('/admin/resources/{resource}', [\App\Http\Controllers\Admin\LessonResourceController::class, 'destroy'])->name('admin.lesson_resources.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/switch-context/{context}', [App\Http\Controllers\HomeController::class, 'switchContext'])->name('switch_context');
    
    // Perfil de Usuario Colegiado
    Route::get('/mi-perfil', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/mi-perfil', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/mi-perfil/avatar', [App\Http\Controllers\ProfileController::class, 'uploadAvatar'])->name('profile.avatar');
    Route::post('/mi-perfil/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
    
    // Noticias Administrativas
    Route::resource('admin/news', NewsArticleController::class)->names('admin.news');
    Route::resource('admin/agreements', \App\Http\Controllers\Admin\AgreementController::class)->names('admin.agreements');

    // Autoridades y Permisos
    Route::get('admin/permissions', [\App\Http\Controllers\Admin\RolePermissionController::class, 'index'])->name('admin.permissions.index');
    Route::post('admin/permissions', [\App\Http\Controllers\Admin\RolePermissionController::class, 'store'])->name('admin.permissions.store');
    Route::delete('admin/permissions/{user}', [\App\Http\Controllers\Admin\RolePermissionController::class, 'destroy'])->name('admin.permissions.destroy');

    // Banners Promocionales (Flyers)
    Route::resource('admin/banners', \App\Http\Controllers\Admin\EventBannerController::class)->names('admin.banners');
    Route::post('admin/banners/{banner}/toggle', [\App\Http\Controllers\Admin\EventBannerController::class, 'toggle'])->name('admin.banners.toggle');


    // Facturación y Suscripción (Admin de Colegio)
    Route::get('/mi-plan', [\App\Http\Controllers\BillingController::class, 'index'])->name('billing.index');
    Route::post('/mi-plan/upgrade', [\App\Http\Controllers\BillingController::class, 'upgrade'])->name('billing.upgrade');

    // CMS y Constructor de Páginas (Admin de Colegio)
    Route::get('/cms/paginas', [\App\Http\Controllers\Admin\CmsController::class, 'pagesIndex'])->name('admin.cms.pages.index');
    Route::get('/cms/paginas/nueva', [\App\Http\Controllers\Admin\CmsController::class, 'pagesCreate'])->name('admin.cms.pages.create');
    Route::post('/cms/paginas', [\App\Http\Controllers\Admin\CmsController::class, 'pagesStore'])->name('admin.cms.pages.store');
    Route::get('/cms/paginas/{page}/editar', [\App\Http\Controllers\Admin\CmsController::class, 'pagesEdit'])->name('admin.cms.pages.edit');
    Route::put('/cms/paginas/{page}', [\App\Http\Controllers\Admin\CmsController::class, 'pagesUpdate'])->name('admin.cms.pages.update');
    
    Route::get('/cms/menus', [\App\Http\Controllers\Admin\CmsController::class, 'menusIndex'])->name('admin.cms.menus.index');
    Route::post('/cms/menus', [\App\Http\Controllers\Admin\CmsController::class, 'menusStore'])->name('admin.cms.menus.store');
    Route::post('/cms/menus/{menu}/items', [\App\Http\Controllers\Admin\CmsController::class, 'menuItemsStore'])->name('admin.cms.menus.items.store');

    Route::get('/cms/sliders', [\App\Http\Controllers\Admin\CmsController::class, 'slidersIndex'])->name('admin.cms.sliders.index');
    Route::post('/cms/sliders', [\App\Http\Controllers\Admin\CmsController::class, 'slidersStore'])->name('admin.cms.sliders.store');
    Route::post('/cms/sliders/{slider}/items', [\App\Http\Controllers\Admin\CmsController::class, 'sliderItemsStore'])->name('admin.cms.sliders.items.store');
    Route::delete('/cms/slider-items/{sliderItem}', [\App\Http\Controllers\Admin\CmsController::class, 'sliderItemsDestroy'])->name('admin.cms.sliders.items.destroy');

    Route::get('/cms/autoridades', [\App\Http\Controllers\Admin\CmsController::class, 'boardMembersIndex'])->name('admin.cms.board_members.index');
    Route::post('/cms/autoridades', [\App\Http\Controllers\Admin\CmsController::class, 'boardMembersStore'])->name('admin.cms.board_members.store');
    Route::put('/cms/autoridades/{boardMember}', [\App\Http\Controllers\Admin\CmsController::class, 'boardMembersUpdate'])->name('admin.cms.board_members.update');
    Route::delete('/cms/autoridades/{boardMember}', [\App\Http\Controllers\Admin\CmsController::class, 'boardMembersDestroy'])->name('admin.cms.board_members.destroy');

    // Bóveda de Resguardo
    Route::get('/settings/boveda', [\App\Http\Controllers\Admin\BackupVaultController::class, 'index'])->name('admin.settings.vault.index');
    Route::get('/settings/boveda/descargar-datos', [\App\Http\Controllers\Admin\BackupVaultController::class, 'downloadData'])->name('admin.settings.vault.data');
    Route::get('/settings/boveda/descargar-fotos', [\App\Http\Controllers\Admin\BackupVaultController::class, 'downloadPhotos'])->name('admin.settings.vault.photos');
    Route::get('/settings/boveda/descargar-certificados', [\App\Http\Controllers\Admin\BackupVaultController::class, 'downloadCertificates'])->name('admin.settings.vault.certificates');
    Route::get('/settings/boveda/descarga-maestra', [\App\Http\Controllers\Admin\BackupVaultController::class, 'downloadMaster'])->name('admin.settings.vault.master');

    // Chatbot Knowledge (Para administradores de colegio)
    Route::get('admin/chatbot', [\App\Http\Controllers\ChatbotKnowledgeController::class, 'index'])->name('admin.chatbot.index')->middleware('role:OWNER,ADMIN_COLEGIO');
    Route::post('admin/chatbot', [\App\Http\Controllers\ChatbotKnowledgeController::class, 'store'])->name('admin.chatbot.store')->middleware('role:OWNER,ADMIN_COLEGIO');
    Route::put('admin/chatbot/{knowledge}', [\App\Http\Controllers\ChatbotKnowledgeController::class, 'update'])->name('admin.chatbot.update')->middleware('role:OWNER,ADMIN_COLEGIO');
    Route::delete('admin/chatbot/{knowledge}', [\App\Http\Controllers\ChatbotKnowledgeController::class, 'destroy'])->name('admin.chatbot.destroy')->middleware('role:OWNER,ADMIN_COLEGIO');
    Route::post('admin/chatbot/ban-ip', [\App\Http\Controllers\ChatbotKnowledgeController::class, 'banIp'])->name('admin.chatbot.ban_ip')->middleware('role:OWNER,ADMIN_COLEGIO');

    // Asistente IA
    Route::get('/ai/asistente', [App\Http\Controllers\AIController::class, 'index'])->name('ai.index');
    Route::post('/ai/query', [App\Http\Controllers\AIController::class, 'query'])->name('ai.query');
    Route::post('/ai/voice-command', [App\Http\Controllers\AIController::class, 'voiceCommand'])->name('ai.voice');

    // Gestión de Colegiados (Admin de Colegio)
    Route::get('/colegiados', [\App\Http\Controllers\CollegiateController::class, 'index'])->name('collegiates.index');
    Route::get('/colegiados/exportar', [\App\Http\Controllers\CollegiateController::class, 'export'])->name('collegiates.export');
    Route::get('/padron/importar', [\App\Http\Controllers\CollegiateController::class, 'import'])->name('collegiates.import');
    Route::get('/padron/importar/plantilla', [\App\Http\Controllers\CollegiateController::class, 'downloadTemplate'])->name('collegiates.import.template');
    Route::post('/padron/importar', [\App\Http\Controllers\CollegiateController::class, 'storeImport'])->name('collegiates.import.store');
    Route::get('/colegiados/{collegiate}', [\App\Http\Controllers\CollegiateController::class, 'show'])->name('collegiates.show');
    Route::post('/collegiates/{collegiate}/refinance', [\App\Http\Controllers\CollegiateController::class, 'refinance'])->name('collegiates.refinance');
    Route::post('/collegiates/{collegiate}/sanctions', [\App\Http\Controllers\CollegiateController::class, 'storeSanction'])->name('collegiates.sanctions.store');
    Route::post('/collegiates/{collegiate}/custom-due', [\App\Http\Controllers\CollegiateController::class, 'storeCustomDue'])->name('collegiates.custom_due.store');
    Route::put('/collegiates/{collegiate}/quick-update', [\App\Http\Controllers\CollegiateController::class, 'quickUpdate'])->name('collegiates.quick_update');
    Route::get('/collegiates/{collegiate}/certificate', [\App\Http\Controllers\CollegiateController::class, 'certificate'])->name('collegiates.certificate');
    Route::put('/colegiados/{collegiate}', [\App\Http\Controllers\CollegiateController::class, 'update'])->name('collegiates.update');
    Route::get('/colegiados/{collegiate}/certificado', [\App\Http\Controllers\CollegiateController::class, 'certificate'])->name('collegiates.certificate');
    Route::post('/colegiados/{collegiate}/avatar', [\App\Http\Controllers\CollegiateController::class, 'uploadAvatar'])->name('collegiates.avatar');
    Route::get('/colegiados/{collegiate}/impersonate', [\App\Http\Controllers\CollegiateController::class, 'impersonate'])->name('admin.collegiates.impersonate');
    Route::post('/colegiados/{collegiate}/force-password', [\App\Http\Controllers\CollegiateController::class, 'forcePassword'])->name('collegiates.force_password');

    // Motor de Pagos y Liquidaciones
    Route::get('/mis-pagos', [\App\Http\Controllers\PaymentController::class, 'index'])->name('payment.index');
    Route::post('/pagos/procesar', [\App\Http\Controllers\PaymentController::class, 'payDues'])->name('payment.dues');
    Route::get('/pagos/exito', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
    Route::get('/pagos/pendiente', [\App\Http\Controllers\PaymentController::class, 'pending'])->name('payment.pending');
    Route::get('/pagos/fallo', [\App\Http\Controllers\PaymentController::class, 'failure'])->name('payment.failure');
    Route::post('/pagos/anual', [\App\Http\Controllers\PaymentController::class, 'generateAnnualPayment'])->name('payment.annual');
    Route::post('/pagar/reserva/{booking}', [\App\Http\Controllers\PaymentController::class, 'payBooking'])->name('payment.booking');

    // Gestión del Plan de Documentación (Requisitos de Cumplimiento)
    Route::get('/requisitos', [\App\Http\Controllers\ComplianceRequirementController::class, 'index'])->name('compliance_requirements.index');
    Route::post('/requisitos', [\App\Http\Controllers\ComplianceRequirementController::class, 'store'])->name('compliance_requirements.store');
    Route::put('/requisitos/{requirement}', [\App\Http\Controllers\ComplianceRequirementController::class, 'update'])->name('compliance_requirements.update');
    Route::delete('/requisitos/{requirement}', [\App\Http\Controllers\ComplianceRequirementController::class, 'destroy'])->name('compliance_requirements.destroy');

    // Auditoría y Legajos
    Route::get('/admin/compliance', [\App\Http\Controllers\Admin\ComplianceReviewController::class, 'index'])->name('admin.compliance.index');
    Route::get('/admin/certificates', [\App\Http\Controllers\Admin\CertificateController::class, 'index'])->name('admin.certificates.index');
    Route::put('/admin/certificates/{certificate}/approve', [\App\Http\Controllers\Admin\CertificateController::class, 'approve'])->name('admin.certificates.approve');
    Route::put('/admin/certificates/{certificate}/reject', [\App\Http\Controllers\Admin\CertificateController::class, 'reject'])->name('admin.certificates.reject');
    Route::post('/revision-documentos/{document}/aprobar', [\App\Http\Controllers\Admin\ComplianceReviewController::class, 'approve'])->name('admin.compliance.approve');
    Route::post('/revision-documentos/{document}/rechazar', [\App\Http\Controllers\Admin\ComplianceReviewController::class, 'reject'])->name('admin.compliance.reject');
    Route::post('/revision-documentos/{collegiate}/{requirement}/marcar-fisico', [\App\Http\Controllers\Admin\ComplianceReviewController::class, 'markPhysical'])->name('admin.compliance.mark_physical');

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

    // Facturación Institucional (Admin de Colegio)
    Route::get('/finanzas', [\App\Http\Controllers\Admin\BillingController::class, 'index'])->name('admin.billing.index');
    Route::post('/mi-facturacion/cuota', [\App\Http\Controllers\Admin\BillingController::class, 'updateFee'])->name('admin.billing.update_fee');
    Route::post('/mi-facturacion/generar-cuotas', [\App\Http\Controllers\Admin\BillingController::class, 'generateMonthlyDues'])->name('admin.billing.generate_dues');
    Route::post('/mi-facturacion/generar-novedad', [\App\Http\Controllers\Admin\BillingController::class, 'storeCustom'])->name('admin.billing.store_custom');
    Route::post('/mi-facturacion/conceptos', [\App\Http\Controllers\Admin\BillingController::class, 'storeConcept'])->name('admin.billing.store_concept');
    Route::put('/mi-facturacion/conceptos/{concept}', [\App\Http\Controllers\Admin\BillingController::class, 'updateConcept'])->name('admin.billing.update_concept');
    Route::post('/mi-facturacion/conceptos/{concept}/toggle', [\App\Http\Controllers\Admin\BillingController::class, 'toggleConcept'])->name('admin.billing.toggle_concept');
    Route::delete('/mi-facturacion/conceptos/{concept}', [\App\Http\Controllers\Admin\BillingController::class, 'destroyConcept'])->name('admin.billing.destroy_concept');
    Route::post('/mi-facturacion/pago-presencial', [\App\Http\Controllers\Admin\BillingController::class, 'payInPerson'])->name('admin.billing.pay_in_person');
    Route::get('/mi-facturacion/descargar/{invoice}', [\App\Http\Controllers\Admin\BillingController::class, 'download'])->name('billing.download');
    Route::get('/mi-facturacion/historial/{collegiate}', [\App\Http\Controllers\Admin\BillingController::class, 'collegiateHistory'])->name('admin.billing.history');

    // Gestión de Ética y Sanciones (Admin de Colegio)
    Route::get('/gestion-etica', [\App\Http\Controllers\Admin\EthicsController::class, 'index'])->name('admin.ethics.index');
    Route::post('/gestion-etica/reglas', [\App\Http\Controllers\Admin\EthicsController::class, 'storeRule'])->name('admin.ethics.store_rule');
    Route::delete('/gestion-etica/reglas/{rule}', [\App\Http\Controllers\Admin\EthicsController::class, 'destroyRule'])->name('admin.ethics.destroy_rule');
    Route::post('/gestion-etica/sancion', [\App\Http\Controllers\Admin\EthicsController::class, 'createSanction'])->name('admin.ethics.create_sanction');
    Route::post('/gestion-etica/levantar/{sanction}', [\App\Http\Controllers\Admin\EthicsController::class, 'liftSanction'])->name('admin.ethics.lift_sanction');
    Route::post('/gestion-etica/comision', [\App\Http\Controllers\Admin\EthicsController::class, 'addCommissionMember'])->name('admin.ethics.commission.store');
    Route::delete('/gestion-etica/comision/{member}', [\App\Http\Controllers\Admin\EthicsController::class, 'removeCommissionMember'])->name('admin.ethics.commission.destroy');
    Route::get('/gestion-etica/libro-actas/pdf', [\App\Http\Controllers\Admin\EthicsController::class, 'downloadActBookPdf'])->name('admin.ethics.act-book.pdf');
    Route::get('/gestion-etica/sanciones/{sanction}/pdf', [\App\Http\Controllers\Admin\EthicsController::class, 'downloadSanctionActPdf'])->name('admin.ethics.sanctions.pdf');

    // CRUD de Tipos de Infracciones Éticas
    Route::resource('/gestion-etica/tipos-infraccion', \App\Http\Controllers\EthicsInfractionTypeController::class)->names('admin.ethics_infractions')->except(['create', 'show', 'edit']);



    // Gestión de Trámites y Certificados Valorizados (Admin de Colegio)
    Route::get('/gestion-tramites/{certificate_type}/preview', [\App\Http\Controllers\Admin\CertificateTypeController::class, 'preview'])->name('admin.certificate_types.preview');
    Route::get('/gestion-tramites/{certificate_type}/export-bulk', [\App\Http\Controllers\Admin\CertificateTypeController::class, 'exportBulkView'])->name('admin.certificate_types.export_bulk_view');
    Route::post('/gestion-tramites/{certificate_type}/export-bulk', [\App\Http\Controllers\Admin\CertificateTypeController::class, 'exportBulkPdf'])->name('admin.certificate_types.export_bulk_pdf');
    Route::post('/gestion-tramites/{certificate_type}/email-bulk', [\App\Http\Controllers\Admin\CertificateTypeController::class, 'emailBulkPdfToImprenta'])->name('admin.certificate_types.email_bulk');
    Route::resource('/gestion-tramites', \App\Http\Controllers\Admin\CertificateTypeController::class)->names('admin.certificate_types')->parameters(['gestion-tramites' => 'certificate_type'])->except(['show']);

    // Configuración de la Empresa / Institución (Para el Admin del Colegio)
    Route::get('/configuracion-institucion', [\App\Http\Controllers\Admin\SchoolSettingsController::class, 'edit'])->name('admin.school_settings.edit');
    Route::put('/configuracion-institucion', [\App\Http\Controllers\Admin\SchoolSettingsController::class, 'update'])->name('admin.school_settings.update');

    // Tickets
    Route::get('/soporte', [App\Http\Controllers\TicketController::class, 'index'])->name('tickets.index');
    Route::get('/soporte/nuevo', [App\Http\Controllers\TicketController::class, 'create'])->name('tickets.create');
    Route::post('/soporte/nuevo', [App\Http\Controllers\TicketController::class, 'store'])->name('tickets.store');
    Route::get('/soporte/{ticket}', [App\Http\Controllers\TicketController::class, 'show'])->name('tickets.show');
    Route::post('/soporte/{ticket}/reply', [App\Http\Controllers\TicketController::class, 'reply'])->name('tickets.reply');

    // Retorno al OWNER (Liberado de rol para permitir escape desde modo ADMIN)
    Route::get('/admin/leave-original-owner', [\App\Http\Controllers\Admin\DashboardController::class, 'leaveImpersonation'])->name('admin.leave_impersonation');

    // Portal del Colegiado: Gestión de Legajo Digital
    Route::get('/mi-legajo', [\App\Http\Controllers\ComplianceController::class, 'index'])->name('compliance.index');
    Route::post('/mi-legajo/subir/{requirement}', [\App\Http\Controllers\ComplianceController::class, 'upload'])->name('compliance.upload');

    // Portal del Colegiado: Trámites y Certificados (Comprar)
    Route::get('/tramites', [\App\Http\Controllers\CollegiateCertificateStoreController::class, 'index'])->name('collegiates.certificates.store');
    Route::post('/tramites/{type}/purchase', [\App\Http\Controllers\CollegiateCertificateStoreController::class, 'purchase'])->name('collegiates.certificates.purchase');
    Route::get('/tramites/descargar/{certificate}', [\App\Http\Controllers\CollegiateCertificateStoreController::class, 'download'])->name('collegiates.certificates.download');

    // Rutas de Vinculación OAuth (Mercado Pago Connect)
    Route::get('/admin/mercadopago/vincular', [\App\Http\Controllers\MercadoPagoOAuthController::class, 'redirect'])->name('mercadopago.redirect');
});

// Ruta maestra central para el retorno de MP (Fuera del middleware auth porque MP nos manda acá y podríamos perder la sesión temporal)
Route::get('/mp/callback', [\App\Http\Controllers\MercadoPagoOAuthController::class, 'callback'])->name('mercadopago.callback');

// Ruta Webhook de Mercado Pago (Recibe los IPN POST automáticos)
Route::post('/webhook/mercadopago', [\App\Http\Controllers\MercadoPagoWebhookController::class, 'handle'])->name('mercadopago.webhook');

