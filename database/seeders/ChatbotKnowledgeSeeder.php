<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\ChatbotKnowledge;

class ChatbotKnowledgeSeeder extends Seeder
{
    public function run()
    {
        // Encontrar CoTOLaR (suponiendo que es el colegio principal, o recorremos todos)
        $schools = School::all();

        foreach ($schools as $school) {
            // Requisitos de alta:
            // Intentar buscar los requisitos reales si existen en la BD.
            // Para simplificar y hacerlo genérico pero útil, creamos conocimientos básicos
            // que luego el admin puede ajustar, pero que responden al 90% de las consultas básicas.
            
            $knowledges = [
                [
                    'question' => '¿Qué requisitos necesito para matricularme o darme de alta?',
                    'keywords' => 'requisito,requisitos,alta,unirme,matricularme,inscripcion,documentacion,documentos,papeles',
                    'answer' => 'Para darte de alta y validar tu matrícula, necesitamos que inicies sesión y completes tu Legajo Digital. Generalmente, requerimos foto de tu DNI (frente y dorso), constancia de CUIL, y copia de tu Título. Puedes ver el detalle exacto de lo que te falta en la sección "Mi Legajo".',
                ],
                [
                    'question' => '¿Cuánto cuesta la cuota o cómo puedo pagar?',
                    'keywords' => 'cuota,cuotas,pagar,pago,costo,precio,monto,deuda,moroso,arancel,arancelaria',
                    'answer' => 'El monto de la cuota societaria se actualiza periódicamente según la asamblea. Puedes consultar tu estado de deuda, generar un comprobante de pago o ver las opciones de financiación directamente desde la sección "Estado de Cuenta" en tu Panel de Control una vez que ingreses al sistema.',
                ],
                [
                    'question' => 'No recuerdo mi contraseña o no puedo ingresar',
                    'keywords' => 'contraseña,clave,password,ingresar,login,olvide,acceder,olvidado',
                    'answer' => 'Si no recuerdas tu contraseña o es tu primera vez ingresando, puedes hacer clic en "¿Olvidaste tu contraseña?" en la pantalla de ingreso. Te enviaremos un correo con un enlace seguro para crear una nueva.',
                ],
                [
                    'question' => '¿Cómo obtengo mi certificado de habilitación o libre deuda?',
                    'keywords' => 'certificado,certificados,habilitacion,libre deuda,constancia,pdf,imprimir',
                    'answer' => 'Tus certificados se generan de forma automática con un código QR de validación legal. Solo debes ir a la sección "Certificados" en tu menú principal y presionar "Descargar". Recuerda que para poder descargarlo debes tener tu legajo completo y no registrar deuda arancelaria.',
                ],
                [
                    'question' => '¿Dónde queda el colegio o cómo me contacto?',
                    'keywords' => 'contacto,ubicacion,telefono,direccion,donde queda,email,correo,comunicarme,oficina',
                    'answer' => 'Puedes comunicarte con la administración enviando un mensaje directo desde la sección "Contacto" de la web, o revisando nuestros números de teléfono y dirección al final de nuestra página de inicio.',
                ],
                [
                    'question' => 'Quiero cambiar mis datos personales, ¿cómo hago?',
                    'keywords' => 'datos,cambiar,modificar,actualizar,perfil,telefono,direccion,correo',
                    'answer' => 'Es muy importante mantener tus datos actualizados. Puedes modificarlos en cualquier momento haciendo clic en tu nombre en la esquina superior derecha del sistema y eligiendo la opción "Perfil".',
                ],
            ];

            foreach ($knowledges as $knowledge) {
                // Usamos firstOrCreate para evitar duplicados si se corre el seeder varias veces
                ChatbotKnowledge::firstOrCreate([
                    'school_id' => $school->id,
                    'question'  => $knowledge['question']
                ], [
                    'keywords' => $knowledge['keywords'],
                    'answer'   => $knowledge['answer'],
                    'status'   => 'learned'
                ]);
            }
        }
    }
}
