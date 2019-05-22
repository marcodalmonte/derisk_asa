<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $surveytypes = DB::table('surveytypes')->orderBy('surveytype','ASC')->get();
        
        $surveyors = DB::table('surveyors')->orderBy('name','ASC')->get();
        
        $labs = DB::table('labs')->orderBy('company','ASC')->get();
        
        $rooms = DB::table('rooms')->orderBy('name','ASC')->get();
        
        $floors = DB::table('floors')->orderBy('menu','ASC')->get();
        
        $products = DB::table('products')->orderBy('score','ASC')->orderBy('name','ASC')->get();
        
        $extents = DB::table('extents')->orderBy('code','ASC')->orderBy('score','ASC')->get();
        
        $surface_treatments = DB::table('surface_treatments')->orderBy('score','ASC')->orderBy('code','ASC')->get();
        
        $passed_data = array(
            'surveytypes' => $surveytypes,
            'surveyors' => $surveyors,
            'labs' => $labs,
            'rooms' => $rooms,
            'floors' => $floors,
            'products' => $products,
            'extents' => $extents,
            'surface_treatments' => $surface_treatments,
        );
        
        return view('settings', $passed_data);
    }
    
    /**
     * Saves the new surveyor if another with the same name does not exist
     */
    public function saveSurveyor(Request $request)
    {
        $email = $request->input('email');
        $name = $request->input('name');
        $surname = $request->input('surname');
        
        if (empty($email)) {
            return response()->json(['saved' => 0]);
        }
                
        $surveyor = DB::table('surveyors')
                ->where('email','=',$email)
                ->first();
        
        if ($surveyor == null) {
            DB::table('surveyors')
                ->insert([
                    'name' => $name,
                    'surname' => $surname,
                    'email' => $email,
                ]);
            
            $surveyor = DB::table('surveyors')
                ->where('email','=',$email)
                ->first();
        }
        
        // Return the information via json
        return response()->json(['saved' => 1, 'surveyor' => $surveyor]);
    }
    
    /**
     * Deletes a requested surveyor if not used in any record,
     * otherwise just deactive it
     */
    public function deleteSurveyor(Request $request)
    {
        $idsurveyor = $request->input('idsurveyor');
        
        if (empty($idsurveyor)) {
            return response()->json(['saved' => 0]);
        }
                
        $surveys = DB::table('surveys_surveyors')
                ->where('surveyor_id','=',$idsurveyor)
                ->count();
        
        $op = 'deleted';
        
        if ($surveys == 0) {
            DB::table('surveyors')
                ->where('id','=',$idsurveyor)
                ->delete();
        } else {
            DB::table('surveyors')
                ->where('id','=',$idsurveyor)
                ->update(['active' => 0]);
            
            $op = 'disabled';
        }
        
        // Return the information via json
        return response()->json(['saved' => 1, 'op' => $op]);
    }
    
    /**
     * Activates a requested surveyor
     */
    public function enableSurveyor(Request $request)
    {
        $idsurveyor = $request->input('idsurveyor');
        
        if (empty($idsurveyor)) {
            return response()->json(['saved' => 0]);
        }
                
        DB::table('surveyors')
                ->where('id','=',$idsurveyor)
                ->update(['active' => 1]);
        
        // Return the information via json
        return response()->json(['saved' => 1, 'enabled' => 1]);
    }
    
    /**
     * Saves the new survey type if another with the same name does not exist
     */
    public function saveSurveyType(Request $request)
    {
        $stype = $request->input('surveytype');
        
        if (empty($stype)) {
            return response()->json(['saved' => 0]);
        }
                
        $surveytype = DB::table('surveytypes')
                ->where('surveytype','=',$stype)
                ->first();
        
        if ($surveytype == null) {
            DB::table('surveytypes')
                ->insert(['surveytype' => $stype]);
            
            $surveytype = DB::table('surveytypes')
                ->where('surveytype','=',$stype)
                ->first();
        }
        
        // Return the information via json
        return response()->json(['saved' => 1, 'surveytype' => $surveytype]);
    }
    
    /**
     * Deletes a requested survey type if not used in any record,
     * otherwise just deactive it
     */
    public function deleteSurveyType(Request $request)
    {
        $idtype = $request->input('idtype');
        
        if (empty($idtype)) {
            return response()->json(['saved' => 0]);
        }
                
        $surveys = DB::table('surveys')
                ->where('surveytype_id','=',$idtype)
                ->count();
        
        $op = 'deleted';
        
        if ($surveys == 0) {
            DB::table('surveytypes')
                ->where('id','=',$idtype)
                ->delete();
        } else {
            DB::table('surveytypes')
                ->where('id','=',$idtype)
                ->update(['active' => 0]);
            
            $op = 'disabled';
        }
        
        // Return the information via json
        return response()->json(['saved' => 1, 'op' => $op]);
    }
    
    /**
     * Activates a requested survey type
     */
    public function enableSurveyType(Request $request)
    {
        $idtype = $request->input('idtype');
        
        if (empty($idtype)) {
            return response()->json(['saved' => 0]);
        }
                
        DB::table('surveytypes')
                ->where('id','=',$idtype)
                ->update(['active' => 1]);
        
        // Return the information via json
        return response()->json(['saved' => 1, 'enabled' => 1]);
    }
    
    /**
     * Saves the new lab if another with the same name does not exist
     */
    public function saveLab(Request $request)
    {
        $company = $request->input('company');
        $building = $request->input('building');
        $address = $request->input('address');
        $town = $request->input('town');
        $postcode = $request->input('postcode');
        
        if (empty($company)) {
            return response()->json(['saved' => 0]);
        }
                
        $lab = DB::table('labs')
                ->where('company','=',$company)
                ->first();
        
        if ($lab == null) {
            DB::table('labs')
                ->insert([
                    'company' => $company,
                    'building' => $building,
                    'address' => $address,
                    'town' => $town,
                    'postcode' => $postcode,
                ]);
        } else {
            DB::table('labs')
                ->where('company','=',$company)
                ->update([
                    'building' => $building,
                    'address' => $address,
                    'town' => $town,
                    'postcode' => $postcode,
                ]);
        }
            
        $lab = DB::table('labs')
            ->where('company','=',$company)
            ->first();
        
        // Return the information via json
        return response()->json(['saved' => 1, 'lab' => $lab]);
    }
    
    /**
     * Deletes a requested lab if not used in any record,
     * otherwise just deactive it
     */
    public function deleteLab(Request $request)
    {
        $idlab = $request->input('idlab');
        
        if (empty($idlab)) {
            return response()->json(['saved' => 0]);
        }
                
        $surveys = DB::table('surveys')
                ->where('lab_id','=',$idlab)
                ->count();
        
        $op = 'deleted';
        
        if ($surveys == 0) {
            DB::table('labs')
                ->where('id','=',$idlab)
                ->delete();
        } else {
            DB::table('labs')
                ->where('id','=',$idlab)
                ->update(['active' => 0]);
            
            $op = 'disabled';
        }
        
        // Return the information via json
        return response()->json(['saved' => 1, 'op' => $op]);
    }
    
    /**
     * Activates a requested lab
     */
    public function enableLab(Request $request)
    {
        $idlab = $request->input('idlab');
        
        if (empty($idlab)) {
            return response()->json(['saved' => 0]);
        }
                
        DB::table('labs')
                ->where('id','=',$idlab)
                ->update(['active' => 1]);
        
        // Return the information via json
        return response()->json(['saved' => 1, 'enabled' => 1]);
    }
    
    /**
     * Saves the new lab if another with the same name does not exist
     */
    public function saveRoom(Request $request)
    {
        $name = $request->input('name');
        
        if (empty($name)) {
            return response()->json(['saved' => 0]);
        }
                
        $room = DB::table('rooms')
                ->where('name','=',$name)
                ->first();
        
        if ($room == null) {
            DB::table('rooms')
                ->insert([
                    'name' => $name,
                ]);
        }
            
        $room = DB::table('rooms')
                ->where('name','=',$name)
                ->first();
        
        // Return the information via json
        return response()->json(['saved' => 1, 'room' => $room]);
    }
    
    /**
     * Deletes a requested room if not used in any record,
     * otherwise just deactive it
     */
    public function deleteRoom(Request $request)
    {
        $idroom = $request->input('idroom');
        
        if (empty($idroom)) {
            return response()->json(['saved' => 0]);
        }
                
        $inspections = DB::table('inspections')
                ->where('room_id','=',$idroom)
                ->count();
        
        $op = 'deleted';
        
        if ($inspections == 0) {
            DB::table('rooms')
                ->where('id','=',$idroom)
                ->delete();
        } else {
            DB::table('rooms')
                ->where('id','=',$idroom)
                ->update(['active' => 0]);
            
            $op = 'disabled';
        }
        
        // Return the information via json
        return response()->json(['saved' => 1, 'op' => $op]);
    }
    
    /**
     * Activates a requested room
     */
    public function enableRoom(Request $request)
    {
        $idroom = $request->input('idroom');
        
        if (empty($idroom)) {
            return response()->json(['saved' => 0]);
        }
                
        DB::table('rooms')
                ->where('id','=',$idroom)
                ->update(['active' => 1]);
        
        // Return the information via json
        return response()->json(['saved' => 1, 'enabled' => 1]);
    }
    
    /**
     * Saves the new floor if another with the same name does not exist
     */
    public function saveFloor(Request $request)
    {
        $code = $request->input('room');
        $name = $request->input('room');
        $menu = $request->input('menu');
        
        if (empty($code)) {
            return response()->json(['saved' => 0]);
        }
                
        $floor = DB::table('floors')
                ->where('code','=',$code)
                ->first();
                
        if ($floor == null) {
            DB::table('floors')
                ->insert([
                    'code' => $code,
                    'name' => $name,
                    'menu' => $menu,
                ]);
        } else {
            DB::table('floors')
                ->where('code','=',$code)
                ->update([
                    'name' => $name,
                    'menu' => $menu,
                ]);
        }
            
        $floor = DB::table('floors')
                ->where('code','=',$code)
                ->first();
        
        // Return the information via json
        return response()->json(['saved' => 1, 'floor' => $floor]);
    }
    
    /**
     * Deletes a requested floor if not used in any record,
     * otherwise just deactive it
     */
    public function deleteFloor(Request $request)
    {
        $idfloor = $request->input('idfloor');
        
        if (empty($idfloor)) {
            return response()->json(['saved' => 0]);
        }
                
        $inspections = DB::table('inspections')
                ->where('floor_id','=',$idfloor)
                ->count();
        
        $op = 'deleted';
        
        if ($inspections == 0) {
            DB::table('floors')
                ->where('id','=',$idfloor)
                ->delete();
        } else {
            DB::table('floors')
                ->where('id','=',$idfloor)
                ->update(['active' => 0]);
            
            $op = 'disabled';
        }
        
        // Return the information via json
        return response()->json(['saved' => 1, 'op' => $op]);
    }
    
    /**
     * Activates a requested floor
     */
    public function enableFloor(Request $request)
    {
        $idfloor = $request->input('idfloor');
        
        if (empty($idfloor)) {
            return response()->json(['saved' => 0]);
        }
                
        DB::table('floors')
                ->where('id','=',$idfloor)
                ->update(['active' => 1]);
        
        // Return the information via json
        return response()->json(['saved' => 1, 'enabled' => 1]);
    }
    
    /**
     * Saves the new product if another with the same name does not exist
     */
    public function saveProduct(Request $request)
    {
        $name = $request->input('name');
        $score = $request->input('score');
        
        if (empty($name)) {
            return response()->json(['saved' => 0]);
        }
                
        $product = DB::table('products')
                ->where('name','=',$name)
                ->first();
                
        if ($product == null) {
            DB::table('products')
                ->insert([
                    'name' => $name,
                    'score' => (empty($score) ? 0 : $score),
                ]);
        } else {
            DB::table('products')
                ->where('name','=',$name)
                ->update([
                    'score' => (empty($score) ? 0 : $score),
                ]);
        }
            
        $product = DB::table('products')
                ->where('name','=',$name)
                ->first();
        
        // Return the information via json
        return response()->json(['saved' => 1, 'product' => $product]);
    }
    
    /**
     * Deletes a requested product if not used in any record,
     * otherwise just deactive it
     */
    public function deleteProduct(Request $request)
    {
        $idproduct = $request->input('idproduct');
        
        if (empty($idproduct)) {
            return response()->json(['saved' => 0]);
        }
                
        $inspections = DB::table('inspections')
                ->where('product_id','=',$idproduct)
                ->count();
        
        $op = 'deleted';
        
        if ($inspections == 0) {
            DB::table('products')
                ->where('id','=',$idproduct)
                ->delete();
        } else {
            DB::table('products')
                ->where('id','=',$idproduct)
                ->update(['active' => 0]);
            
            $op = 'disabled';
        }
        
        // Return the information via json
        return response()->json(['saved' => 1, 'op' => $op]);
    }
    
    /**
     * Activates a requested product
     */
    public function enableProduct(Request $request)
    {
        $idproduct = $request->input('idproduct');
        
        if (empty($idproduct)) {
            return response()->json(['saved' => 0]);
        }
                
        DB::table('products')
                ->where('id','=',$idproduct)
                ->update(['active' => 1]);
        
        // Return the information via json
        return response()->json(['saved' => 1, 'enabled' => 1]);
    }
    
    /**
     * Saves the new extent of damage if another with the same name does not exist
     */
    public function saveExtent(Request $request)
    {
        $code = $request->input('code');
        $name = $request->input('name');
        $score = $request->input('score');
        
        if (empty($code)) {
            return response()->json(['saved' => 0]);
        }
                
        $extent = DB::table('extents')
                ->where('code','=',$code)
                ->first();
                
        if ($extent == null) {
            DB::table('extents')
                ->insert([
                    'name' => $name,
                    'score' => (empty($score) ? 0 : $score),
                ]);
        } else {
            DB::table('extents')
                ->where('code','=',$code)
                ->update([
                    'name' => $name,
                    'score' => (empty($score) ? 0 : $score),
                ]);
        }
            
        $extent = DB::table('extents')
                ->where('code','=',$code)
                ->first();
        
        // Return the information via json
        return response()->json(['saved' => 1, 'extent' => $extent]);
    }
    
    /**
     * Deletes a requested extent of damage if not used in any record,
     * otherwise just deactive it
     */
    public function deleteExtent(Request $request)
    {
        $idextent = $request->input('idextent');
        
        if (empty($idextent)) {
            return response()->json(['saved' => 0]);
        }
                
        $inspections = DB::table('inspections')
                ->where('extent_of_damage','=',$idextent)
                ->count();
        
        $op = 'deleted';
        
        if ($inspections == 0) {
            DB::table('extents')
                ->where('id','=',$idextent)
                ->delete();
        } else {
            DB::table('extents')
                ->where('id','=',$idextent)
                ->update(['active' => 0]);
            
            $op = 'disabled';
        }
        
        // Return the information via json
        return response()->json(['saved' => 1, 'op' => $op]);
    }
    
    /**
     * Activates a requested extent of damage
     */
    public function enableExtent(Request $request)
    {
        $idextent = $request->input('idextent');
        
        if (empty($idextent)) {
            return response()->json(['saved' => 0]);
        }
                
        DB::table('extents')
                ->where('id','=',$idextent)
                ->update(['active' => 1]);
        
        // Return the information via json
        return response()->json(['saved' => 1, 'enabled' => 1]);
    }
    
    /**
     * Saves the new surface treatment if another with the same name does not exist
     */
    public function saveSurfaceTreatment(Request $request)
    {
        $code = $request->input('code');
        $description = $request->input('description');
        $score = $request->input('score');
        
        if (empty($code)) {
            return response()->json(['saved' => 0]);
        }
                
        $treatment = DB::table('surface_treatments')
                ->where('code','=',$code)
                ->first();
                
        if ($treatment == null) {
            DB::table('surface_treatments')
                ->insert([
                    'code' => $code,
                    'description' => $description,
                    'score' => (empty($score) ? 0 : $score),
                ]);
        } else {
            DB::table('surface_treatments')
                ->where('code','=',$code)
                ->update([
                    'description' => $description,
                    'score' => (empty($score) ? 0 : $score),
                ]);
        }
            
        $treatment = DB::table('surface_treatments')
                ->where('code','=',$code)
                ->first();
        
        // Return the information via json
        return response()->json(['saved' => 1, 'surface_treatment' => $treatment]);
    }
    
    /**
     * Deletes a requested surface treatment if not used in any record,
     * otherwise just deactive it
     */
    public function deleteSurfaceTreatment(Request $request)
    {
        $idtreatment = $request->input('idtreatment');
        
        if (empty($idtreatment)) {
            return response()->json(['saved' => 0]);
        }
                
        $inspections = DB::table('inspections')
                ->where('surface_treatment','=',$idtreatment)
                ->count();
        
        $op = 'deleted';
        
        if ($inspections == 0) {
            DB::table('surface_treatments')
                ->where('id','=',$idtreatment)
                ->delete();
        } else {
            DB::table('surface_treatments')
                ->where('id','=',$idtreatment)
                ->update(['active' => 0]);
            
            $op = 'disabled';
        }
        
        // Return the information via json
        return response()->json(['saved' => 1, 'op' => $op]);
    }
    
    /**
     * Activates a requested surface treatment
     */
    public function enableSurfaceTreatment(Request $request)
    {
        $idtreatment = $request->input('idtreatment');
        
        if (empty($idtreatment)) {
            return response()->json(['saved' => 0]);
        }
                
        DB::table('surface_treatments')
                ->where('id','=',$idtreatment)
                ->update(['active' => 1]);
        
        // Return the information via json
        return response()->json(['saved' => 1, 'enabled' => 1]);
    }
}
