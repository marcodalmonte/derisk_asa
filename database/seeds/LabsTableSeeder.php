<?php

use Illuminate\Database\Seeder;

class LabsTableSeeder extends Seeder
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
        
        $labs = array(
            array(
                'company'   =>  'Vintec Laboratories Ltd',
                'building'  =>  'BRE Bucknalls Lane',
                'address'   =>  'Garston',
                'town'      =>  'Watford, Hertfordshire',
                'postcode'  =>  'WD25 9XX',
            ),
            array(
                'company'   =>  'Precision Analysis (NW) Ltd',
                'building'  =>  'Essex House',
                'address'   =>  'Bridle Road',
                'town'      =>  'Bootle, Merseyside',
                'postcode'  =>  'L30 4UE',
            ),
            array(
                'company'   =>  'Ayerst Environmental Ltd',
                'building'  =>  '',
                'address'   =>  '182a High Street',
                'town'      =>  'Beckenham, Kent',
                'postcode'  =>  'BR3 1EW',
            ),
            array(
                'company'   =>  'Kova Asbestos Consultants',
                'building'  =>  'Suite 10 Rochester House',
                'address'   =>  '275 Baddow Road',
                'town'      =>  'Chelmsford, Essex',
                'postcode'  =>  'CM2 7QA',
            ),
            array(
                'company'   =>  'Chemtest',
                'building'  =>  '',
                'address'   =>  'Brunswick Place',
                'town'      =>  'Liverpool, Merseyside',
                'postcode'  =>  'L3 4BJ',
            ),
            array(
                'company'   =>  'PHE Porton',
                'building'  =>  'Rare and Imported Pathogens Laboratory',
                'address'   =>  'Porton Down',
                'town'      =>  'Salisbury, Wiltshire',
                'postcode'  =>  'SP4 0JG',
            ),
            array(
                'company'   =>  'IOM',
                'building'  =>  'Research Avenue North',
                'address'   =>  'Riccarton',
                'town'      =>  'Edinburgh',
                'postcode'  =>  'EH14 4AP',
            ),
        );
        
        foreach ($labs as $curlab) {
            DB::table('labs')->insert([
                'company'       =>  $curlab['company'],
                'building'      =>  $curlab['building'],
                'address'       =>  $curlab['address'],
                'town'          =>  $curlab['town'],
                'postcode'      =>  $curlab['postcode'],
                'created_at'    =>  $creation_date,
                'updated_at'    =>  $modify_date,
            ]);
        }
    }
}
