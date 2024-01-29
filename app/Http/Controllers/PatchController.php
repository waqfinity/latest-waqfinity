<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Patch;

class PatchController extends Controller
{
    public function index()
    {
        $pageTitle  = 'Patches List';
        $patches = Patch::searchable(['name'])->orderBy('name')->paginate(10);
        return view('admin.patch.index', compact('pageTitle', 'patches'));
    }


    public function store(Request $request, $id = 0)
    {
        
        $request->validate([
            'name'   => 'required',
            'selected_months'          => 'required',
            'amount'     => 'required',
        ]);

        if ($id) {
            $patch           =     Patch::findOrFail($id);
            $notification    =     'Patch updated successfully';
        } else {
            $patch              =    new Patch();
            $notification       = 'Patch added successfully';
        }
       

        $patch->name = $request->name;
        $patch->donation_ids = json_encode($request->selected_months);
        $patch->amount =  $request->amount;
        $patch->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function delete($id)
    {
        $patch = Patch::findOrFail($id);
        if($patch){
            $patch->delete();
            $notify[] = ['success', 'Deleted successfully'];
        }else{
             $notify[] = ['error', 'Error !! Could not find patch'];
        }
         return back()->withNotify($notify);
    }
}
