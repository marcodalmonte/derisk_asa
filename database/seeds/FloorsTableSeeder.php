<?php

use Illuminate\Database\Seeder;

class FloorsTableSeeder extends Seeder
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
        
        $floors = array(
            array(
                'code'  =>  'G',
                'name'  =>  'Ground Floor',
                'menu'  =>  64,
            ),
            array(
                'code'  =>  '1',
                'name'  =>  'First Floor',
                'menu'  =>  62,
            ),
            array(
                'code'  =>  '2',
                'name'  =>  'Second Floor',
                'menu'  =>  60,
            ), 
            array(
                'code'  =>  '3',
                'name'  =>  'Third Floor',
                'menu'  =>  58,
            ),
            array(
                'code'  =>  '4',
                'name'  =>  'Fourth Floor',
                'menu'  =>  56,
            ), 
            array(
                'code'  =>  '5',
                'name'  =>  'Fifth Floor',
                'menu'  =>  54,
            ), 
            array(
                'code'  =>  '6',
                'name'  =>  'Sixth Floor',
                'menu'  =>  52,
            ),
            array(
                'code'  =>  '7',
                'name'  =>  'Seventh Floor',
                'menu'  =>  50,
            ), 
            array(
                'code'  =>  '8',
                'name'  =>  'Eighth Floor',
                'menu'  =>  48,
            ),
            array(
                'code'  =>  '9',
                'name'  =>  'Ninth Floor',
                'menu'  =>  46,
            ), 
            array(
                'code'  =>  '10',
                'name'  =>  'Tenth Floor',
                'menu'  =>  44,
            ), 
            array(
                'code'  =>  '11',
                'name'  =>  'Eleventh Floor',
                'menu'  =>  42,
            ),
            array(
                'code'  =>  '12',
                'name'  =>  'Twelfth Floor',
                'menu'  =>  41,
            ), 
            array(
                'code'  =>  '13',
                'name'  =>  'Thirteenth Floor',
                'menu'  =>  40,
            ),
            array(
                'code'  =>  '14',
                'name'  =>  'Fourteenth Floor',
                'menu'  =>  39,
            ), 
            array(
                'code'  =>  '15',
                'name'  =>  'Fifteenth Floor',
                'menu'  =>  38,
            ), 
            array(
                'code'  =>  '16',
                'name'  =>  'Sixteenth Floor',
                'menu'  =>  37,
            ),
            array(
                'code'  =>  '17',
                'name'  =>  'Seventeenth Floor',
                'menu'  =>  36,
            ), 
            array(
                'code'  =>  '18',
                'name'  =>  'Eighteenth Floor',
                'menu'  =>  35,
            ),
            array(
                'code'  =>  '19',
                'name'  =>  'Nineteenth Floor',
                'menu'  =>  34,
            ), 
            array(
                'code'  =>  '20',
                'name'  =>  'Twentieth Floor',
                'menu'  =>  33,
            ), 
            array(
                'code'  =>  '21',
                'name'  =>  'Twenty-First Floor',
                'menu'  =>  32,
            ),
            array(
                'code'  =>  '22',
                'name'  =>  'Twenty-Second Floor',
                'menu'  =>  31,
            ), 
            array(
                'code'  =>  '23',
                'name'  =>  'Twenty-Third Floor',
                'menu'  =>  30,
            ),
            array(
                'code'  =>  '24',
                'name'  =>  'Twenty-Fourth Floor',
                'menu'  =>  29,
            ), 
            array(
                'code'  =>  '25',
                'name'  =>  'Twenty-Fifth Floor',
                'menu'  =>  28,
            ), 
            array(
                'code'  =>  '26',
                'name'  =>  'Twenty-Sixth Floor',
                'menu'  =>  27,
            ),
            array(
                'code'  =>  '27',
                'name'  =>  'Twenty-Seventh Floor',
                'menu'  =>  26,
            ), 
            array(
                'code'  =>  '28',
                'name'  =>  'Twenty-Eighth Floor',
                'menu'  =>  25,
            ),
            array(
                'code'  =>  '29',
                'name'  =>  'Twenty-Ninth Floor',
                'menu'  =>  24,
            ), 
            array(
                'code'  =>  '30',
                'name'  =>  'Thirtieth Floor',
                'menu'  =>  23,
            ), 
            array(
                'code'  =>  'B',
                'name'  =>  'Basement',
                'menu'  =>  66,
            ),
            array(
                'code'  =>  'B01',
                'name'  =>  'Basement 1',
                'menu'  =>  67,
            ),
            array(
                'code'  =>  'B02',
                'name'  =>  'Basement 2',
                'menu'  =>  68,
            ), 
            array(
                'code'  =>  'B03',
                'name'  =>  'Basement 3',
                'menu'  =>  69,
            ),
            array(
                'code'  =>  'B04',
                'name'  =>  'Basement 4',
                'menu'  =>  70,
            ),  
            array(
                'code'  =>  'B05',
                'name'  =>  'Basement 5',
                'menu'  =>  71,
            ), 
            array(
                'code'  =>  'SB',
                'name'  =>  'Sub-Basement',
                'menu'  =>  72,
            ),
            array(
                'code'  =>  'L',
                'name'  =>  'Loft',
                'menu'  =>  2,
            ), 
            array(
                'code'  =>  'R',
                'name'  =>  'Roof',
                'menu'  =>  1,
            ), 
            array(
                'code'  =>  'M',
                'name'  =>  'Mezzanine',
                'menu'  =>  63,
            ), 
            array(
                'code'  =>  'LS',
                'name'  =>  'Lift',
                'menu'  =>  114,
            ),
            array(
                'code'  =>  'RS',
                'name'  =>  'Riser',
                'menu'  =>  135,
            ), 
            array(
                'code'  =>  'SW',
                'name'  =>  'Stairwell',
                'menu'  =>  93,
            ),
            array(
                'code'  =>  'EX',
                'name'  =>  'External Area',
                'menu'  =>  156,
            ),
            array(
                'code'  =>  'SB01',
                'name'  =>  'Sub-Basement 1',
                'menu'  =>  73,
            ),
            array(
                'code'  =>  'SB02',
                'name'  =>  'Sub-Basement 2',
                'menu'  =>  74,
            ),
            array(
                'code'  =>  'SB03',
                'name'  =>  'Sub-Basement 3',
                'menu'  =>  75,
            ),
            array(
                'code'  =>  'SB04',
                'name'  =>  'Sub-Basement 4',
                'menu'  =>  76,
            ),
            array(
                'code'  =>  'SB05',
                'name'  =>  'Sub-Basement 5',
                'menu'  =>  77,
            ),
            array(
                'code'  =>  'SB06',
                'name'  =>  'Sub-Basement 6',
                'menu'  =>  78,
            ),
            array(
                'code'  =>  'SB07',
                'name'  =>  'Sub-Basement 7',
                'menu'  =>  79,
            ),
            array(
                'code'  =>  'SB08',
                'name'  =>  'Sub-Basement 8',
                'menu'  =>  80,
            ),
            array(
                'code'  =>  'SB09',
                'name'  =>  'Sub-Basement 9',
                'menu'  =>  81,
            ),
            array(
                'code'  =>  'SB10',
                'name'  =>  'Sub-Basement 10',
                'menu'  =>  82,
            ),
            array(
                'code'  =>  'SB11',
                'name'  =>  'Sub-Basement 11',
                'menu'  =>  83,
            ),
            array(
                'code'  =>  'SB12',
                'name'  =>  'Sub-Basement 12',
                'menu'  =>  84,
            ),
            array(
                'code'  =>  'SB13',
                'name'  =>  'Sub-Basement 13',
                'menu'  =>  85,
            ),
            array(
                'code'  =>  'SB14',
                'name'  =>  'Sub-Basement 14',
                'menu'  =>  86,
            ),
            array(
                'code'  =>  'SB15',
                'name'  =>  'Sub-Basement 15',
                'menu'  =>  87,
            ),
            array(
                'code'  =>  'SB16',
                'name'  =>  'Sub-Basement 16',
                'menu'  =>  88,
            ),
            array(
                'code'  =>  'SB17',
                'name'  =>  'Sub-Basement 17',
                'menu'  =>  89,
            ),
            array(
                'code'  =>  'SB18',
                'name'  =>  'Sub-Basement 18',
                'menu'  =>  90,
            ),
            array(
                'code'  =>  'SB19',
                'name'  =>  'Sub-Basement 19',
                'menu'  =>  91,
            ),
            array(
                'code'  =>  'SB20',
                'name'  =>  'Sub-Basement 20',
                'menu'  =>  92,
            ),
            array(
                'code'  =>  'RS01',
                'name'  =>  'Riser 1',
                'menu'  =>  136,
            ),
            array(
                'code'  =>  'RS02',
                'name'  =>  'Riser 2',
                'menu'  =>  137,
            ),
            array(
                'code'  =>  'RS03',
                'name'  =>  'Riser 3',
                'menu'  =>  138,
            ),
            array(
                'code'  =>  'RS04',
                'name'  =>  'Riser 4',
                'menu'  =>  139,
            ),
            array(
                'code'  =>  'RS05',
                'name'  =>  'Riser 5',
                'menu'  =>  140,
            ),
            array(
                'code'  =>  'RS06',
                'name'  =>  'Riser 6',
                'menu'  =>  141,
            ),
            array(
                'code'  =>  'RS07',
                'name'  =>  'Riser 7',
                'menu'  =>  142,
            ),
            array(
                'code'  =>  'RS08',
                'name'  =>  'Riser 8',
                'menu'  =>  143,
            ),
            array(
                'code'  =>  'RS09',
                'name'  =>  'Riser 9',
                'menu'  =>  144,
            ),
            array(
                'code'  =>  'RS10',
                'name'  =>  'Riser 10',
                'menu'  =>  145,
            ),
            array(
                'code'  =>  'RS11',
                'name'  =>  'Riser 11',
                'menu'  =>  146,
            ),
            array(
                'code'  =>  'RS12',
                'name'  =>  'Riser 12',
                'menu'  =>  147,
            ),
            array(
                'code'  =>  'RS13',
                'name'  =>  'Riser 13',
                'menu'  =>  148,
            ),
            array(
                'code'  =>  'RS14',
                'name'  =>  'Riser 14',
                'menu'  =>  149,
            ),
            array(
                'code'  =>  'RS15',
                'name'  =>  'Riser 15',
                'menu'  =>  150,
            ),
            array(
                'code'  =>  'RS16',
                'name'  =>  'Riser 16',
                'menu'  =>  151,
            ),
            array(
                'code'  =>  'RS17',
                'name'  =>  'Riser 17',
                'menu'  =>  152,
            ),
            array(
                'code'  =>  'RS18',
                'name'  =>  'Riser 18',
                'menu'  =>  153,
            ),
            array(
                'code'  =>  'RS19',
                'name'  =>  'Riser 19',
                'menu'  =>  154,
            ),
            array(
                'code'  =>  'RS20',
                'name'  =>  'Riser 20',
                'menu'  =>  155,
            ),
            array(
                'code'  =>  'L01',
                'name'  =>  'Loft 1',
                'menu'  =>  3,
            ),
            array(
                'code'  =>  'L02',
                'name'  =>  'Loft 2',
                'menu'  =>  4,
            ),
            array(
                'code'  =>  'L03',
                'name'  =>  'Loft 3',
                'menu'  =>  5,
            ),
            array(
                'code'  =>  'L04',
                'name'  =>  'Loft 4',
                'menu'  =>  6,
            ),
            array(
                'code'  =>  'L05',
                'name'  =>  'Loft 5',
                'menu'  =>  7,
            ),
            array(
                'code'  =>  'L06',
                'name'  =>  'Loft 6',
                'menu'  =>  8,
            ),
            array(
                'code'  =>  'L07',
                'name'  =>  'Loft 7',
                'menu'  =>  9,
            ),
            array(
                'code'  =>  'L08',
                'name'  =>  'Loft 8',
                'menu'  =>  10,
            ),
            array(
                'code'  =>  'L09',
                'name'  =>  'Loft 9',
                'menu'  =>  11,
            ),
            array(
                'code'  =>  'L10',
                'name'  =>  'Loft 10',
                'menu'  =>  12,
            ),
            array(
                'code'  =>  'L11',
                'name'  =>  'Loft 11',
                'menu'  =>  13,
            ),
            array(
                'code'  =>  'L12',
                'name'  =>  'Loft 12',
                'menu'  =>  14,
            ),
            array(
                'code'  =>  'L13',
                'name'  =>  'Loft 13',
                'menu'  =>  15,
            ),
            array(
                'code'  =>  'L14',
                'name'  =>  'Loft 14',
                'menu'  =>  16,
            ),
            array(
                'code'  =>  'L15',
                'name'  =>  'Loft 15',
                'menu'  =>  17,
            ),
            array(
                'code'  =>  'L16',
                'name'  =>  'Loft 16',
                'menu'  =>  18,
            ),
            array(
                'code'  =>  'L17',
                'name'  =>  'Loft 17',
                'menu'  =>  19,
            ),
            array(
                'code'  =>  'L18',
                'name'  =>  'Loft 18',
                'menu'  =>  20,
            ),
            array(
                'code'  =>  'L19',
                'name'  =>  'Loft 19',
                'menu'  =>  21,
            ),
            array(
                'code'  =>  'L20',
                'name'  =>  'Loft 20',
                'menu'  =>  22,
            ),
            array(
                'code'  =>  'LS01',
                'name'  =>  'Lift 1',
                'menu'  =>  115,
            ),
            array(
                'code'  =>  'LS02',
                'name'  =>  'Lift 2',
                'menu'  =>  116,
            ),
            array(
                'code'  =>  'LS03',
                'name'  =>  'Lift 3',
                'menu'  =>  117,
            ),
            array(
                'code'  =>  'LS04',
                'name'  =>  'Lift 4',
                'menu'  =>  118,
            ),
            array(
                'code'  =>  'LS05',
                'name'  =>  'Lift 5',
                'menu'  =>  119,
            ),
            array(
                'code'  =>  'LS06',
                'name'  =>  'Lift 6',
                'menu'  =>  120,
            ),
            array(
                'code'  =>  'LS07',
                'name'  =>  'Lift 7',
                'menu'  =>  121,
            ),
            array(
                'code'  =>  'LS08',
                'name'  =>  'Lift 8',
                'menu'  =>  122,
            ),
            array(
                'code'  =>  'LS09',
                'name'  =>  'Lift 9',
                'menu'  =>  123,
            ),
            array(
                'code'  =>  'LS10',
                'name'  =>  'Lift 10',
                'menu'  =>  124,
            ),
            array(
                'code'  =>  'LS11',
                'name'  =>  'Lift 11',
                'menu'  =>  125,
            ),
            array(
                'code'  =>  'LS12',
                'name'  =>  'Lift 12',
                'menu'  =>  126
            ),
            array(
                'code'  =>  'LS13',
                'name'  =>  'Lift 13',
                'menu'  =>  127,
            ),
            array(
                'code'  =>  'LS14',
                'name'  =>  'Lift 14',
                'menu'  =>  128,
            ),
            array(
                'code'  =>  'LS15',
                'name'  =>  'Lift 15',
                'menu'  =>  129,
            ),
            array(
                'code'  =>  'LS16',
                'name'  =>  'Lift 16',
                'menu'  =>  130,
            ),
            array(
                'code'  =>  'LS17',
                'name'  =>  'Lift 17',
                'menu'  =>  131,
            ),
            array(
                'code'  =>  'LS18',
                'name'  =>  'Lift 18',
                'menu'  =>  132,
            ),
            array(
                'code'  =>  'LS19',
                'name'  =>  'Lift 19',
                'menu'  =>  133,
            ),
            array(
                'code'  =>  'LS20',
                'name'  =>  'Lift 20',
                'menu'  =>  134,
            ),
            array(
                'code'  =>  'SW01',
                'name'  =>  'Stairwell 1',
                'menu'  =>  94,
            ),
            array(
                'code'  =>  'SW02',
                'name'  =>  'Stairwell 2',
                'menu'  =>  95,
            ),
            array(
                'code'  =>  'SW03',
                'name'  =>  'Stairwell 3',
                'menu'  =>  96,
            ),
            array(
                'code'  =>  'SW04',
                'name'  =>  'Stairwell 4',
                'menu'  =>  97,
            ),
            array(
                'code'  =>  'SW05',
                'name'  =>  'Stairwell 5',
                'menu'  =>  98,
            ),
            array(
                'code'  =>  'SW06',
                'name'  =>  'Stairwell 6',
                'menu'  =>  99,
            ),
            array(
                'code'  =>  'SW07',
                'name'  =>  'Stairwell 7',
                'menu'  =>  100,
            ),
            array(
                'code'  =>  'SW08',
                'name'  =>  'Stairwell 8',
                'menu'  =>  101,
            ),
            array(
                'code'  =>  'SW09',
                'name'  =>  'Stairwell 9',
                'menu'  =>  102,
            ),
            array(
                'code'  =>  'SW10',
                'name'  =>  'Stairwell 10',
                'menu'  =>  103,
            ),
            array(
                'code'  =>  'SW11',
                'name'  =>  'Stairwell 11',
                'menu'  =>  104,
            ),
            array(
                'code'  =>  'SW12',
                'name'  =>  'Stairwell 12',
                'menu'  =>  105,
            ),
            array(
                'code'  =>  'SW13',
                'name'  =>  'Stairwell 13',
                'menu'  =>  106,
            ),
            array(
                'code'  =>  'SW14',
                'name'  =>  'Stairwell 14',
                'menu'  =>  107,
            ),
            array(
                'code'  =>  'SW15',
                'name'  =>  'Stairwell 15',
                'menu'  =>  108,
            ),
            array(
                'code'  =>  'SW16',
                'name'  =>  'Stairwell 16',
                'menu'  =>  109,
            ),
            array(
                'code'  =>  'SW17',
                'name'  =>  'Stairwell 17',
                'menu'  =>  110,
            ),
            array(
                'code'  =>  'SW18',
                'name'  =>  'Stairwell 18',
                'menu'  =>  111,
            ),
            array(
                'code'  =>  'SW19',
                'name'  =>  'Stairwell 19',
                'menu'  =>  112,
            ),
            array(
                'code'  =>  'SW20',
                'name'  =>  'Stairwell 20',
                'menu'  =>  113,
            ),
            array(
                'code'  =>  'LG',
                'name'  =>  'Lower Ground Floor',
                'menu'  =>  65,
            ),
            array(
                'code'  =>  'M01',
                'name'  =>  'First Floor Mezzanine',
                'menu'  =>  61,
            ),
            array(
                'code'  =>  'M02',
                'name'  =>  'Second Floor Mezzanine',
                'menu'  =>  59,
            ), 
            array(
                'code'  =>  'M03',
                'name'  =>  'Third Floor Mezzanine',
                'menu'  =>  57,
            ),
            array(
                'code'  =>  'M04',
                'name'  =>  'Fourth Floor Mezzanine',
                'menu'  =>  55,
            ), 
            array(
                'code'  =>  'M05',
                'name'  =>  'Fifth Floor Mezzanine',
                'menu'  =>  53,
            ), 
            array(
                'code'  =>  'M06',
                'name'  =>  'Sixth Floor Mezzanine',
                'menu'  =>  51,
            ),
            array(
                'code'  =>  'M07',
                'name'  =>  'Seventh Floor Mezzanine',
                'menu'  =>  49,
            ), 
            array(
                'code'  =>  'M08',
                'name'  =>  'Eighth Floor Mezzanine',
                'menu'  =>  47,
            ),
            array(
                'code'  =>  'M09',
                'name'  =>  'Ninth Floor Mezzanine',
                'menu'  =>  45,
            ), 
            array(
                'code'  =>  'M10',
                'name'  =>  'Tenth Floor Mezzanine',
                'menu'  =>  43,
            ),
        );
        
        foreach ($floors as $floor) {
            DB::table('floors')->insert([
                'code'          =>  $floor['code'],
                'name'          =>  $floor['name'],
                'menu'          =>  $floor['menu'],
                'created_at'    =>  $creation_date,
                'updated_at'    =>  $modify_date,
            ]);
        }
    }
}
