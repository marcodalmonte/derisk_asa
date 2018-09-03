<?php

use Illuminate\Database\Seeder;

class ExtentsTableSeeder extends Seeder
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
        
        $extents = array(
            array(
                'code'  =>  '0',
                'name'  =>  'Good Condition',
                'score' =>  '0',
            ),
            array(
                'code'  =>  '1',
                'name'  =>  'Low',
                'score' =>  '1',
            ),
            array(
                'code'  =>  '2',
                'name'  =>  'Medium',
                'score' =>  '2',
            ),
            array(
                'code'  =>  '3',
                'name'  =>  'High',
                'score' =>  '3',
            ),
        );
        
        foreach ($extents as $extent) {
            DB::table('extents')->insert([
                'code'          =>  $extent['code'],
                'name'          =>  $extent['name'],
                'score'         =>  $extent['score'],
                'created_at'    =>  $creation_date,
                'updated_at'    =>  $modify_date,
            ]);
        }
    }
}
