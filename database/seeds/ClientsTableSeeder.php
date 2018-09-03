<?php

use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
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
        
        $clients = array(
            array(
                'name'          =>  'PRET',
                'companyname'   =>  'Pret a Manger',
                'address1'      =>  '75b Verde',
                'address2'      =>  '10 Bressenden Place',
                'city'          =>  'London',
                'postcode'      =>  'SW1E 5DH',
                'phones'        =>  '',
                'emails'        =>  '',
            ),
            array(
                'name'          =>  'ICAEW',
                'companyname'   =>  'ICAEW',
                'address1'      =>  '321 Avebury Boulevard',
                'address2'      =>  '',
                'city'          =>  'Central Milton Keynes',
                'postcode'      =>  'MK9 2FZ',
                'phones'        =>  '',
                'emails'        =>  '',
            ),
            array(
                'name'          =>  'INTERSER',
                'companyname'   =>  'Interserve Construction Ltd',
                'address1'      =>  '111 Old Broad Street',
                'address2'      =>  '',
                'city'          =>  'London',
                'postcode'      =>  'EC2N 1AP',
                'phones'        =>  '',
                'emails'        =>  '',
            ),
            array(
                'name'          =>  'G4S',
                'companyname'   =>  'G4S Intergrated Services (UK) Ltd',
                'address1'      =>  'Carlton House',
                'address2'      =>  'Carlton Road',
                'city'          =>  'Worksop, Notts',
                'postcode'      =>  'S81 7QF',
                'phones'        =>  '',
                'emails'        =>  '',
            ),
            array(
                'name'          =>  'ISG',
                'companyname'   =>  'ISG Fit Out',
                'address1'      =>  'Aldgate House',
                'address2'      =>  '33 Aldgate High Street',
                'city'          =>  'London',
                'postcode'      =>  'EC3N 1AG',
                'phones'        =>  '',
                'emails'        =>  '',
            ),
            array(
                'name'          =>  'HILL',
                'companyname'   =>  'Hill Group',
                'address1'      =>  'The Power House, Gunpowder Mill',
                'address2'      =>  'Powdermill Lane',
                'city'          =>  'Waltham Abbey, Essex',
                'postcode'      =>  'EN9 1BN',
                'phones'        =>  '',
                'emails'        =>  '',
            ),
        );
        
        foreach ($clients as $curclient) {
            DB::table('clients')->insert([
                'name'          =>  $curclient['name'],
                'companyname'   =>  $curclient['companyname'],
                'address1'      =>  $curclient['address1'],
                'address2'      =>  $curclient['address2'],
                'city'          =>  $curclient['city'],
                'postcode'      =>  $curclient['postcode'],
                'phones'        =>  $curclient['phones'],
                'emails'        =>  $curclient['emails'],                
                'created_at'    =>  $creation_date,
                'updated_at'    =>  $modify_date,
            ]);
        }
    }
}
