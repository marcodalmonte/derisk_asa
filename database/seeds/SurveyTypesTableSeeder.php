<?php

use Illuminate\Database\Seeder;

class SurveyTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $creation_date = date('Y-m-d H:i:s',time());
        $modify_date = date('Y-m-d H:i:s',time());
        
        $types = array(
            array(
                'surveytype'  =>  'Refurbishment',
            ),
            array(
                'surveytype'  =>  'Management',
            ),
            array(
                'surveytype'  =>  'Demolition',
            ),
            array(
                'surveytype'  =>  'Refurbishment and Management',
            ),
            array(
                'surveytype'  =>  'Refurbishment and Demolition',
            ),
            array(
                'surveytype'  =>  'Management and Demolition',
            ),
        );
        
        foreach ($types as $curtype) {
            DB::table('surveytypes')->insert([
                'surveytype'    =>  $curtype['surveytype'],
                'created_at'    =>  $creation_date,
                'updated_at'    =>  $modify_date,
            ]);
        }
    }
}
