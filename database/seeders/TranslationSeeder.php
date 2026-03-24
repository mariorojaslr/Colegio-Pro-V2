<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $translations = [
            ['group' => 'ui', 'key' => 'dashboard', 'es' => 'Panel de Control', 'en' => 'Control Panel', 'pt' => 'Painel de Controle'],
            ['group' => 'ui', 'key' => 'padron', 'es' => 'Padrón Profesional', 'en' => 'Professional Registry', 'pt' => 'Registro Profissional'],
            ['group' => 'ui', 'key' => 'finances', 'es' => 'Situación Financiera', 'en' => 'Financial Situation', 'pt' => 'Situação Financeira'],
            ['group' => 'ui', 'key' => 'academy', 'es' => 'Escuela Virtual', 'en' => 'Virtual Academy', 'pt' => 'Academia Virtual'],
            ['group' => 'ui', 'key' => 'ethics', 'es' => 'Gestión Ética', 'en' => 'Ethics Management', 'pt' => 'Gestão Ética'],
            ['group' => 'ui', 'key' => 'audit', 'es' => 'Auditoría Institucional', 'en' => 'Institutional Audit', 'pt' => 'Auditoria Institucional'],
        ];

        foreach ($translations as $t) {
            \App\Models\Translation::updateOrCreate(
                ['group' => $t['group'], 'key' => $t['key']],
                $t
            );
        }
    }
}
