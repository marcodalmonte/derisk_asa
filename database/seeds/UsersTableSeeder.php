<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
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
        
        $users = array(
            array(
                'name'          =>  'Hannah',
                'surname'       =>  'Setterington',
                'email'         =>  'hannah.setterington@deriskuk.com',
                'password'      =>  'B5BBUraW',
                'qualification' =>  '',
                'usertype'      =>  '1',
                'created_at'    =>  $creation_date,
                'updated_at'    =>  $modify_date,
            ),
            array(
                'name'          =>  'Marco',
                'surname'       =>  'Dal Monte',
                'email'         =>  'marco.dalmonte@deriskuk.com',
                'password'      =>  'p1nkY4rd',
                'qualification' =>  '',
                'usertype'      =>  '1',
                'created_at'    =>  $creation_date,
                'updated_at'    =>  $modify_date,
            ),
            array(
                'name'          =>  'Mark',
                'surname'       =>  'Butler',
                'email'         =>  'mark.butler@deriskuk.com',
                'password'      =>  'ELT6zktE',
                'qualification' =>  '',
                'usertype'      =>  '1',
                'created_at'    =>  $creation_date,
                'updated_at'    =>  $modify_date,
            ),
            array(
                'name'          =>  'Charlotte',
                'surname'       =>  'Carr',
                'email'         =>  'charlotte.carr@deriskuk.com',
                'password'      =>  'RzS6zdMX',
                'qualification' =>  '',
                'usertype'      =>  '1',
                'created_at'    =>  $creation_date,
                'updated_at'    =>  $modify_date,
            ),
            array(
                'name'          =>  'Robin',
                'surname'       =>  'Nower',
                'email'         =>  'robin.nower@deriskuk.com',
                'password'      =>  '9qZpCp9M',
                'qualification' =>  '',
                'usertype'      =>  '1',
                'created_at'    =>  $creation_date,
                'updated_at'    =>  $modify_date,
            ),
            array(
                'name'          =>  'Marc',
                'surname'       =>  'Smith',
                'email'         =>  'marc.smith@deriskuk.com',
                'password'      =>  'KsbATv6J',
                'qualification' =>  '',
                'usertype'      =>  '1',
                'created_at'    =>  $creation_date,
                'updated_at'    =>  $modify_date,
            ),
            array(
                'name'          =>  'Ian',
                'surname'       =>  'Taylor',
                'email'         =>  'ian.taylor@deriskuk.com',
                'password'      =>  'EhqjaM5p',
                'qualification' =>  '',
                'usertype'      =>  '1',
                'created_at'    =>  $creation_date,
                'updated_at'    =>  $modify_date,
            ),
            array(
                'name'          =>  'Fay',
                'surname'       =>  'Gulston',
                'email'         =>  'fay.gulston@deriskuk.com',
                'password'      =>  'KwLwk5dt',
                'qualification' =>  '',
                'usertype'      =>  '1',
                'created_at'    =>  $creation_date,
                'updated_at'    =>  $modify_date,
            ),
        );
        
        foreach ($users as $curuser) {
            DB::table('users')->insert([
                'name'          =>  $curuser['name'],
                'surname'       =>  $curuser['surname'],
                'email'         =>  $curuser['email'],
                'password'      =>  bcrypt($curuser['password']),
                'qualification' =>  $curuser['qualification'],
                'usertype'      =>  $curuser['usertype'],
                'created_at'    =>  $creation_date,
                'updated_at'    =>  $modify_date,
            ]);
        }
    }
}
