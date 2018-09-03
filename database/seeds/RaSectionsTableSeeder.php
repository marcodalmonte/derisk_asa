<?php

use Illuminate\Database\Seeder;

class RaSectionsTableSeeder extends Seeder
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
        
        $rasections = array(
            array(
                'name'  =>  'ELECTRICAL SOURCES OF IGNITION',
            ),
            
            array(
                'name'  =>  'SMOKING',
            ),
            
            array(
                'name'  =>  'ARSON',
            ),
            
            array(
                'name'  =>  'PORTABLE HEATER AND HEATING INSTALLATIONS',
            ),
            
            array(
                'name'  =>  'LIGHTNING',
            ),
            
            array(
                'name'  =>  'COOKING',
            ),
            
            array(
                'name'  =>  'HAZARDS INTRODUCED BY OUTSIDE CONTRACTORS\' BUILDING WORKS',
            ),
            
            array(
                'name'  =>  'HOUSEKEEPING',
            ),
        
            array(
                'name'  =>  'DANGEROUS & HAZARDOUS SUBSTANCES',
            ),
            
            array(
                'name'  =>  'OTHER SIGNIFICANT FIRE HAZARDS',
            ),
            
            array(
                'name'  =>  'MEANS OF ESCAPE FROM FIRE',
            ),
            
            array(
                'name'  =>  'MEASURES TO LIMIT FIRE SPREAD AND DEVELOPMENT',
            ),
            
            array(
                'name'  =>  'MEANS OF DETECTING A FIRE AND RAISING THE ALARM',
            ),
            
            array(
                'name'  =>  'PORTABLE FIRE FIGHTING EQUIPMENT',
            ),
            
            array(
                'name'  =>  'OTHER RELEVANT FIXED SYSTEMS AND EQUIPMENT',
            ),
            
            array(
                'name'  =>  'GENERAL ARRANGEMENTS',
            ),
            
            array(
                'name'  =>  'PROCEDURES TO BE FOLLOWED IN THE CASE OF FIRE',
            ),
            
            array(
                'name'  =>  'FIRE SAFETY TRAINING',
            ),
            
            array(
                'name'  =>  'MAINTENANCE AND TESTING OF FIRE SAFETY SYSTEMS AND EQUIPMENT',
            ),
        );
        
        foreach ($rasections as $rasection) {
            DB::table('rasections')->insert([
                'name'          =>  $rasection['name'],               
                'created_at'    =>  $creation_date,
                'updated_at'    =>  $modify_date,
            ]);
        }
    }
}
