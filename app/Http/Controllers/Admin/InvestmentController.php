<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Patch;
use App\Models\Investment;

class InvestmentController extends Controller
{
    public function index()
    {
        $pageTitle  = 'Investments';
        $investments = Investment::searchable(['name'])->with(['property', 'patch'])->orderBy('name')->paginate(10);
   
        return view('admin.investments.index', compact('pageTitle', 'investments'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'name'   => 'required',
            'property_id'  => 'required',
            'patch_id'     => 'required',
        ]);

        if ($id) {
            $investment           = Investment::findOrFail($id);
            $notification       = 'Property updated successfully';
        } else {
            $investment           = new Investment();
            $notification       = 'Property added successfully';
        }
      
        $investment->name = $request->name;
        $investment->property_id =  $request->property_id;
        $investment->patch_id =  $request->patch_id;
        $investment->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function delete($id)
        {
            $investment = Investment::findOrFail($id);
            if($investment){
               $investment->delete();
                $notify[] = ['success', 'Investment Deleted successfully'];
            }else{
                 $notify[] = ['error', 'Error !! Could not find Investment'];
            }
             return back()->withNotify($notify);
        }

}
