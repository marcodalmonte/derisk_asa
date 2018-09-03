<?php

use Illuminate\Database\Seeder;

class SurveyorsTableSeeder extends Seeder
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
        
        $surveyors = array(
            array(
                'name'      =>  'Warren',
                'surname'   =>  'Green',
                'email'     =>  'warren.green@deriskuk.com',
            ),
            array(
                'name'      =>  'Ben',
                'surname'   =>  'Stoker',
                'email'     =>  'ben.stoker@deriskuk.com',
            ),
            array(
                'name'      =>  'Joanna',
                'surname'   =>  'Brzezinska',
                'email'     =>  'joanna.brzezinska@deriskuk.com',
            ),
            array(
                'name'      =>  'Patrick',
                'surname'   =>  'Henson',
                'email'     =>  'patrick.henson@deriskuk.com',
            ),
            array(
                'name'      =>  'Sion',
                'surname'   =>  'Henson',
                'email'     =>  'sion.henson@deriskuk.com',
            ),
            array(
                'name'      =>  'Jack',
                'surname'   =>  'Scott',
                'email'     =>  'jack.scott@deriskuk.com',
            ),
            array(
                'name'      =>  'Sam',
                'surname'   =>  'Hitchcock',
                'email'     =>  'sam.hitchcock@deriskuk.com',
            ),
        );
        
        foreach ($surveyors as $surveyor) {
            DB::table('surveyors')->insert([
                'name'          =>  $surveyor['name'],
                'surname'       =>  $surveyor['surname'],
                'email'         =>  $surveyor['email'],
                'created_at'    =>  $creation_date,
                'updated_at'    =>  $modify_date,
            ]);
        }
    }
}
