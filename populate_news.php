<?php

use App\Models\School;
use App\Models\User;
use App\Models\NewsArticle;
use Illuminate\Support\Str;

$school = School::where('slug', 'demo')->first();
$author = User::where('email', 'admin@demo.com')->first();

if (!$school || !$author) {
    echo "Falta inicializar la demo. Ve a /demo-fast primero.\n";
    exit;
}

$news = [
    [
        'title' => 'Nueva Ley de Honorarios y Nomenclador de Prestaciones',
        'excerpt' => 'Se ha aprobado en asamblea el nuevo piso ético para los honorarios profesionales con un ajuste del 35% semestral.',
        'content' => '<h3>Actualización de Valores Referenciales</h3><p>Estimados matriculados, nos complace informar que tras una extensa negociación con las obras sociales y el Ministerio de Salud, se ha logrado actualizar la escala de honorarios mínimos éticos.</p><p>A partir del próximo mes, las consultas de evaluación inicial tendrán un incremento base del 35%, y las sesiones de tratamiento de 45 minutos estarán atadas al índice de inflación del INDEC.</p><p>Agradecemos a la Comisión de Legislación por su incansable trabajo en la defensa de nuestra profesión.</p>',
        'featured_image_url' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=800&q=80',
    ],
    [
        'title' => 'Convocatoria a Asamblea General Ordinaria 2026',
        'excerpt' => 'El Consejo Directivo convoca a todos los colegiados habilitados a participar de la Asamblea anual para tratar la memoria y balance.',
        'content' => '<h3>Día, hora y modalidad</h3><p>Por disposición del Consejo Directivo y en cumplimiento del artículo 45 de nuestros estatutos, se convoca a los profesionales habilitados a la Asamblea General Ordinaria que se llevará a cabo de manera mixta (presencial y por videoconferencia).</p><ul><li><strong>Fecha:</strong> 15 de Noviembre de 2026</li><li><strong>Sede:</strong> Salón de Actos Institucional y vía Zoom</li></ul><p>El orden del día y el padrón de colegiados habilitados para votar se encuentran disponibles en la sección "Asamblea" de su Panel de Control.</p>',
        'featured_image_url' => 'https://images.unsplash.com/photo-1557804506-669a67965ba0?auto=format&fit=crop&w=800&q=80',
    ],
    [
        'title' => 'Convenio con Universidad Nacional para Posgrados',
        'excerpt' => 'Los colegiados activos y al día obtendrán un 50% de beca en las nuevas diplomaturas de neuro-rehabilitación.',
        'content' => '<h3>Formación Continua</h3><p>En el marco del programa de beneficios a matriculados, anunciamos la firma del nuevo convenio marco con la Facultad de Ciencias de la Salud de la Universidad Nacional.</p><p>Este acuerdo histórico permitirá que nuestros matriculados accedan a una bonificación directa del 50% en las cuotas de todas las Diplomaturas de Especialización que inician este semestre.</p><p><strong>Requisito indispensable:</strong> Presentar el Certificado de Habilitación y Libre Deuda descargado desde este mismo portal de autogestión al momento de inscribirse en la Universidad.</p>',
        'featured_image_url' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=800&q=80',
    ]
];

foreach ($news as $index => $item) {
    NewsArticle::updateOrCreate(
        ['slug' => Str::slug($item['title'])],
        [
            'school_id' => $school->id,
            'author_id' => $author->id,
            'title' => $item['title'],
            'excerpt' => $item['excerpt'],
            'content' => $item['content'],
            'featured_image_url' => $item['featured_image_url'],
            'status' => 'published',
            'published_at' => now()->subDays($index * 5), // Noticia más reciente a más antigua
        ]
    );
}

echo "Noticias de ejemplo cargadas exitosamente.\n";
