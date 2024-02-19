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
            'status'     => 'required',
            'total_amount'     => 'required',
            'doc_url'     => 'required',
            'start_date'     => 'required',
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
        $investment->patch_id =  json_encode($request->patch_id);
        $investment->total_amount =  $request->total_amount;
        $investment->doc_url =  $request->doc_url;
        $investment->start_date =  $request->start_date;
        $investment->status =  $request->status;
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
