<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(ExtentsTableSeeder::class);
        $this->call(FloorsTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        $this->call(RoomsTableSeeder::class);
        $this->call(SurfaceTreatmentsTableSeeder::class);
        $this->call(SurveyTypesTableSeeder::class);
        $this->call(ClientsTableSeeder::class);
        $this->call(SurveyorsTableSeeder::class);
        $this->call(LabsTableSeeder::class);
        $this->call(RaSectionsTableSeeder::class);
        $this->call(RaQuestionsTableSeeder::class);
        $this->call(RaAnswerssTableSeeder::class);
    }
}
