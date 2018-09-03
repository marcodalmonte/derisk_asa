<?php

use Illuminate\Database\Seeder;

class RoomsTableSeeder extends Seeder
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
        
        for ($k = 1; $k < 51; $k++) {
            $curname = $k;
            if ($curname < 10) {
                $curname = '0' . $curname;
            }
            
            DB::table('rooms')->insert([
                'name'          =>  $curname,
                'created_at'    =>  $creation_date,
                'updated_at'    =>  $modify_date,
            ]);
        }
    }
}
