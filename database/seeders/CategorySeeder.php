<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Subcategory;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'category_id' => 'CA-001',
                'category_name' => [
                    'en' => 'Fire Fighting Equipment',
                    'fr' => 'Équipement de lutte contre l’incendie',
                    'ar' => 'معدات مكافحة الحرائق',
                ],
                'category_description' => [
                    'en' => 'Equipment and tools for fire fighting.',
                    'fr' => 'Équipement et outils pour la lutte contre l’incendie.',
                    'ar' => 'معدات وأدوات مكافحة الحرائق.',
                ],
                'subcategories' => [
                    [
                        'subcategory_id' => 'SB-001',
                        'subcategory_name' => [
                            'en' => 'Fire Extinguishers',
                            'fr' => 'Extincteurs',
                            'ar' => 'طفايات الحريق',
                        ],
                        'subcategory_description' => [
                            'en' => 'Portable fire extinguishers of various types.',
                            'fr' => 'Extincteurs portables de différents types.',
                            'ar' => 'طفايات الحريق المحمولة بأنواع مختلفة.',
                        ],
                    ],
                    [
                        'subcategory_id' => 'SB-002',
                        'subcategory_name' => [
                            'en' => 'Fire Hose Reels',
                            'fr' => 'Enrouleurs de tuyaux',
                            'ar' => 'بكرات خراطيم الحريق',
                        ],
                        'subcategory_description' => [
                            'en' => 'High-quality fire hose reels.',
                            'fr' => 'Enrouleurs de tuyaux de haute qualité.',
                            'ar' => 'بكرات خراطيم حريق عالية الجودة.',
                        ],
                    ],
                    [
                        'subcategory_id' => 'SB-003',
                        'subcategory_name' => [
                            'en' => 'Fire Sprinkler Systems',
                            'fr' => 'Systèmes de gicleurs',
                            'ar' => 'أنظمة الرشاشات',
                        ],
                        'subcategory_description' => [
                            'en' => 'Efficient sprinkler systems for various applications.',
                            'fr' => 'Systèmes de gicleurs efficaces pour diverses applications.',
                            'ar' => 'أنظمة رشاشات فعالة لتطبيقات مختلفة.',
                        ],
                    ],
                    [
                        'subcategory_id' => 'SB-004',
                        'subcategory_name' => [
                            'en' => 'Fire Blankets',
                            'fr' => 'Couvertures anti-feu',
                            'ar' => 'بطانيات الحريق',
                        ],
                        'subcategory_description' => [
                            'en' => 'Fire blankets for safety purposes.',
                            'fr' => 'Couvertures anti-feu pour la sécurité.',
                            'ar' => 'بطانيات للحريق لأغراض السلامة.',
                        ],
                    ],
                ],
            ],
            [
                'category_id' => 'CA-002',
                'category_name' => [
                    'en' => 'Detection & Alarm Systems',
                    'fr' => 'Systèmes de détection et d’alarme',
                    'ar' => 'أنظمة الكشف والإنذار',
                ],
                'category_description' => [
                    'en' => 'Systems for detecting fire hazards and alerting occupants.',
                    'fr' => 'Systèmes pour détecter les dangers d’incendie et alerter les occupants.',
                    'ar' => 'أنظمة لاكتشاف مخاطر الحرائق وتنبيه السكان.',
                ],
                'subcategories' => [
                    [
                        'subcategory_id' => 'SB-005',
                        'subcategory_name' => [
                            'en' => 'Smoke Detectors',
                            'fr' => 'Détecteurs de fumée',
                            'ar' => 'كاشفات الدخان',
                        ],
                        'subcategory_description' => [
                            'en' => 'Reliable smoke detection devices.',
                            'fr' => 'Appareils fiables de détection de fumée.',
                            'ar' => 'أجهزة كشف الدخان الموثوقة.',
                        ],
                    ],
                    [
                        'subcategory_id' => 'SB-006',
                        'subcategory_name' => [
                            'en' => 'Heat Detectors',
                            'fr' => 'Détecteurs de chaleur',
                            'ar' => 'كاشفات الحرارة',
                        ],
                        'subcategory_description' => [
                            'en' => 'Devices that detect abnormal heat levels.',
                            'fr' => 'Appareils qui détectent des niveaux de chaleur anormaux.',
                            'ar' => 'أجهزة تكشف عن مستويات الحرارة غير الطبيعية.',
                        ],
                    ],
                    [
                        'subcategory_id' => 'SB-007',
                        'subcategory_name' => [
                            'en' => 'Fire Alarm Control Panels',
                            'fr' => 'Panneaux de contrôle d’alarme incendie',
                            'ar' => 'لوحات التحكم في إنذار الحريق',
                        ],
                        'subcategory_description' => [
                            'en' => 'Central units for fire alarm systems.',
                            'fr' => 'Unités centrales pour systèmes d’alarme incendie.',
                            'ar' => 'وحدات مركزية لأنظمة إنذار الحريق.',
                        ],
                    ],
                ],
            ],
            [
                'category_id' => 'CA-003',
                'category_name' => [
                    'en' => 'Safety & Evacuation',
                    'fr' => 'Sécurité et évacuation',
                    'ar' => 'السلامة والإخلاء',
                ],
                'category_description' => [
                    'en' => 'Equipment and systems for safe evacuation.',
                    'fr' => 'Équipement et systèmes pour une évacuation en toute sécurité.',
                    'ar' => 'معدات وأنظمة للإخلاء الآمن.',
                ],
                'subcategories' => [
                    [
                        'subcategory_id' => 'SB-008',
                        'subcategory_name' => [
                            'en' => 'Emergency Lighting',
                            'fr' => 'Éclairage de secours',
                            'ar' => 'إضاءة الطوارئ',
                        ],
                        'subcategory_description' => [
                            'en' => 'Lighting systems for emergency situations.',
                            'fr' => 'Systèmes d’éclairage pour situations d’urgence.',
                            'ar' => 'أنظمة إضاءة للحالات الطارئة.',
                        ],
                    ],
                    [
                        'subcategory_id' => 'SB-009',
                        'subcategory_name' => [
                            'en' => 'Exit Signs',
                            'fr' => 'Signalisations de sortie',
                            'ar' => 'علامات الخروج',
                        ],
                        'subcategory_description' => [
                            'en' => 'Signs to indicate emergency exits.',
                            'fr' => 'Signalisations indiquant les sorties de secours.',
                            'ar' => 'علامات لتحديد مخارج الطوارئ.',
                        ],
                    ],
                ],
            ],
            [
                'category_id' => 'CA-004',
                'category_name' => [
                    'en' => 'Suppression Systems',
                    'fr' => 'Systèmes de suppression',
                    'ar' => 'أنظمة الإخماد',
                ],
                'category_description' => [
                    'en' => 'Systems designed to suppress fire.',
                    'fr' => 'Systèmes conçus pour supprimer le feu.',
                    'ar' => 'أنظمة مصممة لإخماد الحرائق.',
                ],
                'subcategories' => [
                    [
                        'subcategory_id' => 'SB-010',
                        'subcategory_name' => [
                            'en' => 'Gas Suppression Systems',
                            'fr' => 'Systèmes de suppression par gaz',
                            'ar' => 'أنظمة الإخماد بالغاز',
                        ],
                        'subcategory_description' => [
                            'en' => 'Fire suppression using gas agents.',
                            'fr' => 'Suppression de feu à l’aide d’agents gazeux.',
                            'ar' => 'إخماد الحرائق باستخدام وكلاء غازية.',
                        ],
                    ],
                    [
                        'subcategory_id' => 'SB-011',
                        'subcategory_name' => [
                            'en' => 'Water Mist Systems',
                            'fr' => 'Systèmes de brouillard d’eau',
                            'ar' => 'أنظمة رذاذ الماء',
                        ],
                        'subcategory_description' => [
                            'en' => 'Efficient water mist fire suppression systems.',
                            'fr' => 'Systèmes d\'inondation par brouillard d\'eau efficaces.',
                            'ar' => 'أنظمة إخماد الحرائق برذاذ الماء الفعال.',
                        ],
                    ],
                    [
                        'subcategory_id' => 'SB-012',
                        'subcategory_name' => [
                            'en' => 'Foam Systems',
                            'fr' => 'Systèmes de mousse',
                            'ar' => 'أنظمة الرغوة',
                        ],
                        'subcategory_description' => [
                            'en' => 'Fire suppression systems using foam agents.',
                            'fr' => 'Systèmes de suppression de feu utilisant des agents moussants.',
                            'ar' => 'أنظمة إخماد الحرائق باستخدام عوامل رغوية.',
                        ],
                    ],
                ],
            ],
        ];

        foreach ($categories as $catData) {
            // Extract subcategories and remove them from the category data.
            $subcategories = $catData['subcategories'];
            unset($catData['subcategories']);

            // Create the category with translatable fields and custom category_id.
            $category = Category::create($catData);

            // Create each subcategory, adding the category_id to associate it.
            foreach ($subcategories as $subData) {
                $subData['category_id'] = $category->id;
                Subcategory::create($subData);
            }
        }
    }
}
