<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandsSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            [
                'brand_id' => 'BR-001',
                'name' => 'NAFFCO',
                'description' => [
                    'en' => 'A leading producer and supplier of firefighting equipment, fire protection systems, and safety engineering systems, headquartered in Dubai.',
                    'fr' => 'Un producteur et fournisseur leader d\'équipements de lutte contre l\'incendie, de systèmes de protection incendie et de systèmes d\'ingénierie de sécurité, basé à Dubaï.',
                    'ar' => 'شركة رائدة في إنتاج وتوريد معدات مكافحة الحرائق وأنظمة الحماية من الحرائق وأنظمة هندسة السلامة، ومقرها في دبي.'
                ],
                'website' => 'https://www.naffco.com/en',
            ],
            [
                'brand_id' => 'BR-002',
                'name' => 'Sicli',
                'description' => [
                    'en' => 'A renowned brand specializing in fire safety equipment and solutions, known for their high-quality fire extinguishers and suppression systems.',
                    'fr' => 'Une marque renommée spécialisée dans les équipements et solutions de sécurité incendie, connue pour ses extincteurs et systèmes de suppression de haute qualité.',
                    'ar' => 'علامة تجارية مشهورة متخصصة في معدات وحلول السلامة من الحرائق، معروفة بطفايات الحريق وأنظمة الإخماد عالية الجودة.'
                ],
                'website' => 'https://www.sicli.com/',
            ],
            [
                'brand_id' => 'BR-003',
                'name' => 'R. Pons',
                'description' => [
                    'en' => 'French manufacturer offering a range of fire-fighting equipment including hose reels, monitors, and hand nozzles.',
                    'fr' => 'Fabricant français proposant une gamme d\'équipements de lutte contre l\'incendie, notamment des dévidoirs, des lances monitors et des lances à main.',
                    'ar' => 'شركة تصنيع فرنسية تقدم مجموعة من معدات مكافحة الحرائق بما في ذلك بكرات الخراطيم والمراقبة وفوهات اليد.'
                ],
                'website' => 'https://r-pons.com/en/',
            ],
            [
                'brand_id' => 'BR-004',
                'name' => 'MOBIAK',
                'description' => [
                    'en' => 'One of the leading manufacturers and suppliers of firefighting equipment and fire protection systems worldwide, based in Greece.',
                    'fr' => 'L\'un des principaux fabricants et fournisseurs d\'équipements de lutte contre l\'incendie et de systèmes de protection incendie dans le monde, basé en Grèce.',
                    'ar' => 'واحدة من الشركات الرائدة في تصنيع وتوريد معدات مكافحة الحرائق وأنظمة الحماية من الحرائق في جميع أنحاء العالم، ومقرها في اليونان.'
                ],
                'website' => 'https://mobiak.com/en/',
            ],
            [
                'brand_id' => 'BR-005',
                'name' => '3M',
                'description' => [
                    'en' => 'A global science company offering a wide range of safety and industrial products, including fire protection solutions.',
                    'fr' => 'Une entreprise scientifique mondiale offrant une large gamme de produits de sécurité et industriels, y compris des solutions de protection contre les incendies.',
                    'ar' => 'شركة علمية عالمية تقدم مجموعة واسعة من منتجات السلامة والمنتجات الصناعية، بما في ذلك حلول الحماية من الحرائق.'
                ],
                'website' => 'https://www.3m.com/',
            ],
            [
                'brand_id' => 'BR-006',
                'name' => 'Tyco',
                'description' => [
                    'en' => 'Global leader in fire protection and security solutions, offering a comprehensive range of fire safety products and systems.',
                    'fr' => 'Leader mondial des solutions de protection contre les incendies et de sécurité, offrant une gamme complète de produits et systèmes de sécurité incendie.',
                    'ar' => 'شركة رائدة عالمياً في حلول الحماية من الحرائق والأمن، تقدم مجموعة شاملة من منتجات وأنظمة السلامة من الحرائق.'
                ],
                'website' => 'https://www.tyco.com/',
            ],
            [
                'brand_id' => 'BR-007',
                'name' => 'Kidde',
                'description' => [
                    'en' => 'Renowned manufacturer of fire safety products, specializing in fire detection and suppression equipment for residential and commercial use.',
                    'fr' => 'Fabricant renommé de produits de sécurité incendie, spécialisé dans les équipements de détection et d\'extinction d\'incendie à usage résidentiel et commercial.',
                    'ar' => 'شركة مصنعة مشهورة لمنتجات السلامة من الحرائق، متخصصة في معدات كشف وإخماد الحرائق للاستخدام السكني والتجاري.'
                ],
                'website' => 'https://www.kidde.com/',
            ]
        ];

        foreach ($brands as $brandData) {
            Brand::create($brandData);
        }
    }
}
