<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * The three plans the landing page shipped with.
     * A super admin can edit them afterwards at /superadmin/plans.
     */
    public function run(): void
    {
        $plans = [
            [
                'slug' => 'starter',
                'price' => null,
                'is_free' => true,
                'cta_type' => 'register',
                'is_featured' => false,
                'sort_order' => 1,
                'translations' => [
                    'en' => [
                        'name' => 'Starter', 'audience' => 'Up to 5 people', 'price_label' => '',
                        'features' => ['3 active projects', 'Tasks, board and timesheets', 'Email support'],
                    ],
                    'fr' => [
                        'name' => 'Découverte', 'audience' => 'Jusqu\'à 5 personnes', 'price_label' => '',
                        'features' => ['3 projets actifs', 'Tâches, tableau et feuilles de temps', 'Assistance par e-mail'],
                    ],
                    'ar' => [
                        'name' => 'البداية', 'audience' => 'حتى 5 أشخاص', 'price_label' => '',
                        'features' => ['3 مشاريع نشطة', 'المهام واللوحة وسجل الساعات', 'دعم عبر البريد'],
                    ],
                ],
            ],
            [
                'slug' => 'studio',
                'price' => 4900,
                'is_free' => false,
                'cta_type' => 'register',
                'is_featured' => true,
                'sort_order' => 2,
                'translations' => [
                    'en' => [
                        'name' => 'Studio', 'audience' => 'Up to 25 people', 'price_label' => '',
                        'features' => ['Unlimited projects', 'Custom statuses and reports', 'Team chat and client access', 'Support in Arabic and French'],
                    ],
                    'fr' => [
                        'name' => 'Studio', 'audience' => 'Jusqu\'à 25 personnes', 'price_label' => '',
                        'features' => ['Projets illimités', 'Statuts personnalisés et rapports', 'Messagerie et accès client', 'Assistance en arabe et en français'],
                    ],
                    'ar' => [
                        'name' => 'استوديو', 'audience' => 'حتى 25 شخصًا', 'price_label' => '',
                        'features' => ['مشاريع بلا حد', 'حالات مخصّصة وتقارير', 'محادثات ووصول العملاء', 'دعم بالعربية والفرنسية'],
                    ],
                ],
            ],
            [
                'slug' => 'enterprise',
                'price' => null,
                'is_free' => false,
                'cta_type' => 'contact',
                'is_featured' => false,
                'sort_order' => 3,
                'translations' => [
                    'en' => [
                        'name' => 'Enterprise', 'audience' => '25 people and up', 'price_label' => 'Let us talk',
                        'features' => ['Unlimited everything', 'Onboarding for your team', 'Dedicated contact'],
                    ],
                    'fr' => [
                        'name' => 'Entreprise', 'audience' => 'À partir de 25 personnes', 'price_label' => 'Parlons-en',
                        'features' => ['Tout en illimité', 'Prise en main avec votre équipe', 'Interlocuteur dédié'],
                    ],
                    'ar' => [
                        'name' => 'مؤسسة', 'audience' => 'من 25 شخصًا فأكثر', 'price_label' => 'لنتحدّث',
                        'features' => ['كل شيء بلا حدود', 'مرافقة فريقك عند الانطلاق', 'جهة اتصال مخصّصة'],
                    ],
                ],
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['slug' => $plan['slug']], $plan + ['currency' => 'DA', 'is_active' => true]);
        }
    }
}
