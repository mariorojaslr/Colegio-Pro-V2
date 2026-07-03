<?php
require 'c:/xampp/htdocs/Colegio-Pro/vendor/autoload.php';
$app = require_once 'c:/xampp/htdocs/Colegio-Pro/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\School;
use App\Models\Slider;
use App\Models\SliderItem;

$school = School::where('slug', 'trabajosocial')->first();
if (!$school) {
    echo "ERROR: No se encontró el colegio con slug 'trabajosocial'.\n";
    exit(1);
}

echo "Colegio encontrado: " . $school->name . " (ID: " . $school->id . ")\n";

// Crear o buscar el slider principal para este colegio
$slider = Slider::updateOrCreate(
    ['school_id' => $school->id, 'name' => 'Slider Principal Trabajo Social'],
    ['is_active' => true]
);

echo "Slider creado/actualizado (ID: " . $slider->id . "). Creando items...\n";

// Borrar items previos del slider para evitar duplicados
SliderItem::where('slider_id', $slider->id)->delete();

$items = [
    [
        'slider_id' => $slider->id,
        'image_url' => 'images/slide_comunitario.png',
        'title' => 'Derechos, Inclusión &<br><span>Acción Comunitaria.</span>',
        'description' => 'Promovemos la transformación social en el territorio, defendiendo las incumbencias y el compromiso ético en La Rioja.',
        'link' => '#quienes-somos',
        'order' => 0,
        'starts_at' => now(),
        'ends_at' => now()->addYears(50)
    ],
    [
        'slider_id' => $slider->id,
        'image_url' => 'images/slide_academico.png',
        'title' => 'Capacitación Continua &<br><span>Debates Académicos.</span>',
        'description' => 'Impulsamos el desarrollo profesional mediante talleres, debates sobre políticas públicas y congresos de actualización.',
        'link' => '#novedades',
        'order' => 1,
        'starts_at' => now(),
        'ends_at' => now()->addYears(50)
    ],
    [
        'slider_id' => $slider->id,
        'image_url' => 'images/slide_institucional.png',
        'title' => 'Garantía del Ejercicio<br><span>Legal & Transparente.</span>',
        'description' => 'Respaldamos a la comunidad centralizando el padrón oficial de matriculados bajo la Ley Provincial Nº 8.522.',
        'link' => '#contacto',
        'order' => 2,
        'starts_at' => now(),
        'ends_at' => now()->addYears(50)
    ]
];

foreach ($items as $itemData) {
    $item = SliderItem::create($itemData);
    echo "Item creado: " . strip_tags($item->title) . " | Image: " . $item->image_url . "\n";
}

echo "PROCESO COMPLETADO EXITOSAMENTE. El carrusel interactivo ya está configurado en la base de datos.\n";
