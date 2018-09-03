<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    public function index()
    {
        $clients = DB::table('clients')->get();
        
        return view('clients', ['clients' => $clients]);
    }
    
    public function getClient($client_name)
    {
        $client = array(
            'id'            =>  '',
            'name'          =>  '',
            'derisk_number' =>  '',
            'companyname'   =>  '',
            'contact'       =>  '',
            'address1'      =>  '',
            'address2'      =>  '',
            'city'          =>  '',
            'postcode'      =>  '',
            'phones'        =>  array(),
            'emails'        =>  array(),
        );
        
        $title = 'New Client';
        
        if ('new' != $client_name) {
            $fclient = DB::table('clients')
                    ->where('name','=',$client_name)
                    ->get()
                    ->first();
            
            $client['id'] = $fclient->id;
            $client['name'] = $fclient->name;
            $client['derisk_number'] = $fclient->derisk_number;
            $client['companyname'] = $fclient->companyname;
            $client['contact'] = $fclient->contact;
            $client['address1'] = $fclient->address1;
            $client['address2'] = $fclient->address2;
            $client['city'] = $fclient->city;
            $client['postcode'] = $fclient->postcode;
            $client['phones'] = explode(';',$fclient->phones);
            $client['emails'] = explode(';',$fclient->emails);
            
            $title = 'Details of ' . $client['companyname'];
        }
        
        $passed_data = array(
            'client_name'   =>  $client_name,
            'title'         =>  $title,
            'client'        =>  $client,
        );
        
        return view('client', $passed_data);
    }
    
    public function saveClient(Request $request)
    {       
        $name = $request->input('name');
        $derisk_number = $request->input('derisk_number');
        $companyname = $request->input('companyname');
        $contact = $request->input('contact');
        $address1 = $request->input('address1');
        $address2 = $request->input('address2');
        $city = $request->input('city');
        $postcode = $request->input('postcode');
        $myphones = $request->input('phones');
        
        $phonesarr = explode(';',$myphones);
        
        $tels = array();
        
        foreach ($phonesarr as $number) {
            if (empty($number)) {
                continue;
            }
            
            $tels[] = $number;
        }
        
        $phones = implode(';',$tels);
        
        $myemails = $request->input('emails');
        
        $emailsarr = explode(';',$myemails);
        
        $emarr = array();
        
        foreach ($emailsarr as $addr) {
            if (empty($addr)) {
                continue;
            }
            
            $emarr[] = $addr;
        }
        
        $emails = implode(';',$emarr);
        
        $found = DB::table('clients')
                ->where('name','=',$name)
                ->get();
        
        $creation_date = date('Y-m-d H:i:s',time());
        $modify_date = date('Y-m-d H:i:s',time());
        
        if (0 < $found->count()) {
            $data = array(
                'name'          =>  $name,
                'derisk_number' =>  $derisk_number,
                'companyname'   =>  $companyname,
                'contact'       =>  $contact,
                'address1'      =>  $address1,
                'address2'      =>  $address2,
                'city'          =>  $city,
                'postcode'      =>  $postcode,
                'phones'        =>  $phones,
                'emails'        =>  $emails,
                'updated_at'    =>  $modify_date,
            );
            
            DB::table('clients')
                ->where('name',$name)
                ->update($data);
        } else {
            $data = array(
                'name'          =>  $name,
                'derisk_number' =>  $derisk_number,
                'companyname'   =>  $companyname,
                'contact'       =>  $contact,
                'address1'      =>  $address1,
                'address2'      =>  $address2,
                'city'          =>  $city,
                'postcode'      =>  $postcode,
                'phones'        =>  $phones,
                'emails'        =>  $emails,
                'created_at'    =>  $creation_date,
                'updated_at'    =>  $modify_date,
            );
            
            DB::table('clients')
                ->insert($data);
        }
    }
    
    public function deleteClient(Request $request)
    {       
        $name = $request->input('name');
            
        DB::table('clients')
            ->where('name',$name)
            ->delete();
    }
}
