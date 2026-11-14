<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\School;
use App\Models\CertificateType;

return new class extends Migration
{
    /**
     * Configura y deja activo el Certificado de Ética Profesional de COTOLAR
     * con su redacción legal completa, datos dinámicos (Nombre, DNI, Matrícula, Fecha)
     * y código QR de validación en tiempo real; y desactiva temporalmente el Certificado de Matrícula/Habilitación.
     */
    public function up(): void
    {
        $school = School::where('slug', 'cotolar')->first();

        if (!$school) {
            return;
        }

        // Plantilla legal formal con recuadro destacado para el Certificado de Ética Profesional
        $eticaTemplate = 'Por la presente, la Comisión Directiva y el Tribunal de Ética y Disciplina del <strong>' . e($school->name) . '</strong> certifican y hacen constar que el/la profesional:

<div class="professional-card">
    <div class="name">@{{nombre}}</div>
    <div class="credentials-row">
        <span class="credentials-badge">D.N.I. N°: @{{dni}}</span>
        &nbsp;|&nbsp;
        <span class="credentials-badge">MATRÍCULA PROFESIONAL N°: @{{matricula}}</span>
    </div>
</div>

se encuentra debidamente inscripto/a y con matrícula <strong>ACTIVA Y HABILITADA</strong> para el ejercicio profesional de la Terapia Ocupacional en todo el ámbito de la Provincia de La Rioja.
<br><br>
Asimismo, se deja expresa constancia de que a la fecha de expedición del presente documento, el/la profesional mencionado/a <strong>NO REGISTRA SANCIONES ÉTICAS NI DISCIPLINARIAS</strong> firmes ni causas inhabilitantes en trámite ante el Tribunal de Ética y Disciplina de esta Institución, encontrándose en pleno goce de sus derechos colegiales y con sus obligaciones arancelarias regulares.
<br><br>
A solicitud del/de la interesado/a y para ser presentado ante las autoridades, obras sociales, instituciones de salud o entidades públicas y privadas que así lo requieran, se expide, sella y firma digitalmente el presente certificado en la Ciudad de La Rioja, a los <strong>@{{fecha_emision}}</strong>.';

        // 1. Dejamos INACTIVO temporalmente el Certificado de Matrícula / Habilitación para que no se confundan
        CertificateType::where('school_id', $school->id)
            ->where(function ($q) {
                $q->where('name', 'like', '%Matricula%')
                  ->orWhere('name', 'like', '%Matrícula%')
                  ->orWhere('name', 'like', '%habilitaci%');
            })
            ->update([
                'is_active' => false,
            ]);

        // 2. Buscamos el Certificado de Ética Profesional de COTOLAR (o lo creamos si no existiera) y lo dejamos ACTIVO y listo para emitir al instante
        $eticaCert = CertificateType::where('school_id', $school->id)
            ->where(function ($q) {
                $q->where('name', 'like', '%Ética%')
                  ->orWhere('name', 'like', '%Etica%')
                  ->orWhere('name', 'like', '%ética%')
                  ->orWhere('name', 'like', '%etica%');
            })
            ->first();

        if ($eticaCert) {
            $eticaCert->update([
                'name' => 'Certificado de Ética Profesional',
                'description' => 'Constancia oficial de matriculación vigente y ausencia de sanciones éticas o disciplinarias con código QR de validación en tiempo real.',
                'price' => 0.00,
                'validity_days' => 30,
                'requires_clearance' => false, // Permitimos emisión inmediata sin bloqueo durante esta etapa inicial
                'requires_no_sanctions' => true,
                'has_qr' => true,
                'is_active' => true,
                'background_path' => null, // Usa el diseño diplomático institucional limpio y completo en A4 horizontal
                'template_content' => $eticaTemplate,
            ]);
        } else {
            CertificateType::create([
                'school_id' => $school->id,
                'name' => 'Certificado de Ética Profesional',
                'description' => 'Constancia oficial de matriculación vigente y ausencia de sanciones éticas o disciplinarias con código QR de validación en tiempo real.',
                'price' => 0.00,
                'validity_days' => 30,
                'is_single_use' => false,
                'requires_clearance' => false,
                'requires_no_sanctions' => true,
                'has_qr' => true,
                'is_active' => true,
                'background_path' => null,
                'page_size' => 'a4',
                'page_orientation' => 'landscape',
                'template_content' => $eticaTemplate,
            ]);
        }
    }

    /**
     * Revierte la migración sin eliminar registros (cumpliendo la regla de oro).
     */
    public function down(): void
    {
        // Se preservan todos los registros de trámites
    }
};
