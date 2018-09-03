<?php

use Illuminate\Database\Seeder;

class SurfaceTreatmentsTableSeeder extends Seeder
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
        
        $treatments = array(
            array(
                'code'          =>  '0',
                'description'   =>  'Composite Materials',
                'score'         =>  '0',
            ),
            array(
                'code'          =>  '1',
                'description'   =>  'Enclosed sprays',
                'score'         =>  '1',
            ),
            array(
                'code'          =>  '1',
                'description'   =>  'Enclosed lagging',
                'score'         =>  '1',
            ),
            array(
                'code'          =>  '1',
                'description'   =>  'Sealed AIB',
                'score'         =>  '1',
            ),
            array(
                'code'          =>  '1',
                'description'   =>  'Cement',
                'score'         =>  '1',
            ),
            array(
                'code'          =>  '2',
                'description'   =>  'Unsealed AIB',
                'score'         =>  '2',
            ),
            array(
                'code'          =>  '2',
                'description'   =>  'Encapsulated lagging',
                'score'         =>  '2',
            ),
            array(
                'code'          =>  '2',
                'description'   =>  'Encapsulated sprays',
                'score'         =>  '2',
            ), 
            array(
                'code'          =>  '3',
                'description'   =>  'Unsealed lagging and sprays',
                'score'         =>  '3',
            ),
            array(
                'code'          =>  '3',
                'description'   =>  'Unsealed sprays',
                'score'         =>  '3',
            ),
        );
        
        foreach ($treatments as $treatment) {
            DB::table('surface_treatments')->insert([
                'code'          =>  $treatment['code'],
                'description'   =>  $treatment['description'],
                'score'         =>  $treatment['score'],
                'created_at'    =>  $creation_date,
                'updated_at'    =>  $modify_date,
            ]);
        }
    }
}
