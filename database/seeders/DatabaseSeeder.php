<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            FournisseurSeeder::class,
            EventSeeder::class,
            PartnerSeeder::class,
            SponsoringSeeder::class,
            RessourceSeeder::class,
            RegistrationSeeder::class,
            FeedbackCategorySeeder::class,
            FeedbackSeeder::class,
            GlobalEvaluationSeeder::class,
            InteractionSeeder::class,
        ]);
    }
}