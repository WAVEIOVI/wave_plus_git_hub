<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pressure;

class PressuresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pressures = [
            [
                'pressure_id' => 'PR-001',
                'name' => [
                    'en' => 'Permanent Pressure',
                    'fr' => 'Pression Permanente',
                    'ar' => 'ضغط دائم',
                ],
                'description' => [
                    'en' => 'Equipment with permanent pressure maintains a constant, pre-established pressure level. This configuration ensures immediate readiness and stable performance across various applications where steady pressure is essential.',
                    'fr' => 'Les équipements avec pression permanente maintiennent un niveau de pression constant et préétabli. Cette configuration assure une disponibilité immédiate et des performances stables dans diverses applications nécessitant une pression constante.',
                    'ar' => 'المعدات ذات الضغط الدائم تحافظ على مستوى ضغط ثابت ومحدد مسبقاً. يضمن هذا التكوين الجاهزية الفورية والأداء المستقر في مختلف التطبيقات التي تتطلب ضغطًا ثابتًا.',
                ],
            ],
            [
                'pressure_id' => 'PR-002',
                'name' => [
                    'en' => 'Auxiliary Pressure',
                    'fr' => 'Pression Auxiliaire',
                    'ar' => 'ضغط مساعد',
                ],
                'description' => [
                    'en' => 'In this system, an auxiliary pressure source works alongside the primary pressure. This additional pressure can be activated as needed, enhancing performance for operations requiring extra force or output.',
                    'fr' => 'Dans ce système, une source de pression auxiliaire fonctionne en parallèle avec la pression principale. Cette pression supplémentaire peut être activée au besoin, améliorant ainsi les performances pour des opérations nécessitant une force ou une production supplémentaires.',
                    'ar' => 'في هذا النظام، يعمل مصدر ضغط مساعد جنبًا إلى جنب مع الضغط الرئيسي. يمكن تفعيل هذا الضغط الإضافي عند الحاجة، مما يعزز الأداء في العمليات التي تتطلب قوة أو مخرجات إضافية.',
                ],
            ],
            [
                'pressure_id' => 'PR-003',
                'name' => [
                    'en' => 'No Pressure',
                    'fr' => 'Pas de pression',
                    'ar' => 'بدون ضغط',
                ],
                'description' => [
                    'en' => 'Some equipment is designed to operate without a dedicated pressure system. In these cases, performance relies on the inherent properties of the materials or external factors, eliminating the need for an independent pressure setting.',
                    'fr' => 'Certains équipements sont conçus pour fonctionner sans un système de pression dédié. Dans ces cas, la performance repose sur les propriétés inhérentes des matériaux ou sur des facteurs externes, éliminant ainsi le besoin d\'un réglage de pression indépendant.',
                    'ar' => 'بعض المعدات مصممة للعمل بدون نظام ضغط مخصص. في هذه الحالات، يعتمد الأداء على الخصائص الطبيعية للمواد أو العوامل الخارجية، مما يلغي الحاجة إلى إعداد ضغط مستقل.',
                ],
            ],
        ];

        foreach ($pressures as $pressureData) {
            Pressure::create($pressureData);
        }
    }
}
