<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\School;
use App\Models\CertificateType;

return new class extends Migration
{
    /**
     * Agrega soporte para firma escaneada en las autoridades (board_members)
     * y actualiza la plantilla del Certificado de Ética Profesional al formato compacto
     * de Media Hoja A4 (una sola página garantizada, sin desborde lateral).
     */
    public function up(): void
    {
        if (!Schema::hasColumn('board_members', 'signature_path')) {
            Schema::table('board_members', function (Blueprint $table) {
                $table->string('signature_path')->nullable()->after('image_path');
            });
        }

        // Actualizamos también el template_content de los Certificados de Ética de COTOLAR
        // para que usen la nueva estructura central limpia y sin desbordes
        $school = School::where('slug', 'cotolar')->first();
        if ($school) {
            CertificateType::where('school_id', $school->id)
                ->where(function ($q) {
                    $q->where('name', 'like', '%Ética%')
                      ->orWhere('name', 'like', '%Etica%')
                      ->orWhere('name', 'like', '%ética%')
                      ->orWhere('name', 'like', '%etica%');
                })
                ->update([
                    'template_content' => null, // Al dejarlo en null utiliza el diseño compacto estructurado de pdf/certificate.blade.php con las variables dinámicas
                    'page_size' => 'a4',
                    'page_orientation' => 'portrait',
                ]);
        }
    }

    /**
     * Revierte la migración preservando los datos existentes.
     */
    public function down(): void
    {
        // Regla de oro: no se eliminan columnas ni datos
    }
};
