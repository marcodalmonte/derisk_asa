<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
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
        
        $products = array(
            array(
                'name'  =>  'Adhesive',
                'score' =>  '1',
            ),
            array(
                'name'  =>  'Bitumen Products',
                'score' =>  '1',
            ),
            array(
                'name'  =>  'Cement',
                'score' =>  '1',
            ),
            array(
                'name'  =>  'Composite',
                'score' =>  '1',
            ),
            array(
                'name'  =>  'Sealant',
                'score' =>  '1',
            ),
            array(
                'name'  =>  'Textured Coating',
                'score' =>  '1',
            ),
            array(
                'name'  =>  'Thermoplastic',
                'score' =>  '1',
            ),
            array(
                'name'  =>  'Gaskets',
                'score' =>  '2',
            ),
            array(
                'name'  =>  'Insulating Board',
                'score' =>  '2',
            ),
            array(
                'name'  =>  'Paper',
                'score' =>  '2',
            ),
            array(
                'name'  =>  'Rope',
                'score' =>  '2',
            ),
            array(
                'name'  =>  'Textile',
                'score' =>  '2',
            ),
            array(
                'name'  =>  'Debris',
                'score' =>  '3',
            ),
            array(
                'name'  =>  'Dust',
                'score' =>  '3',
            ),
            array(
                'name'  =>  'Residue',
                'score' =>  '3',
            ),
            array(
                'name'  =>  'Insulation',
                'score' =>  '3',
            ),
            array(
                'name'  =>  'Sprayed Coating',
                'score' =>  '3',
            ),
        );
        
        foreach ($products as $product) {
            DB::table('products')->insert([
                'name'          =>  $product['name'],
                'score'         =>  $product['score'],
                'created_at'    =>  $creation_date,
                'updated_at'    =>  $modify_date,
            ]);
        }
    }
}
