<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Property;


class PropertyController extends Controller
{
    public function index()
    {
        $pageTitle  = 'Properties List';
        $properties = Property::searchable(['property_name'])->orderBy('property_name')->paginate(10);
   
        return view('admin.property.index', compact('pageTitle', 'properties'));
    }

    public function store(Request $request, $id = 0)
    {
        
        $request->validate([
            'property_name'   => 'required',
            'amount'          => 'required',
            'key_person_name'     => 'required',
            'key_person_mobile'     => 'required',
            'key_person_email'     => 'required',
            'location'        => 'required',
            'property_doc_url'        => 'required',
        ]);

        if ($id) {
            $property           = Property::findOrFail($id);
            $notification       = 'Property updated successfully';
        } else {
            $property           = new Property();
            $notification       = 'Property added successfully';
        }
      
        $property->property_name = $request->property_name;
        $property->amount =  $request->amount;
        $property->location =  $request->location;
        $property->key_person_name =  $request->key_person_name;
        $property->key_person_mobile =  $request->key_person_mobile;
        $property->key_person_email =  $request->key_person_email;
        $property->description =  $request->description;
        $property->property_doc_url =  $request->property_doc_url;
        $property->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function delete($id)
    {
        $property = Property::findOrFail($id);
        if($property){
           $property->delete();
            $notify[] = ['success', 'Deleted successfully'];
        }else{
             $notify[] = ['error', 'Error !! Could not find property'];
        }
         return back()->withNotify($notify);
    }
}
