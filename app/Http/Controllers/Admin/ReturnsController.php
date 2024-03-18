<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyReturn;

class ReturnsController extends Controller
{

    public function index()
    {
        $pageTitle  = 'Returns';
        $returns = PropertyReturn::orderBy('property_id')->paginate(10);
   
        return view('admin.returns.index', compact('pageTitle', 'returns'));
    }

    public function store(Request $request, $id = 0)
    {
        
        $request->validate([
            'property_id'   => 'required',
            'amount'          => 'required',
            'from'     => 'required',
            'to'     => 'required',
        ]);

        if ($id) {
            $return           = PropertyReturn::findOrFail($id);
            $notification       = 'Returns updated successfully';
        } else {
            $return           = new PropertyReturn();
            $notification       = 'Returns added successfully';
        }
      
        $return->property_id = $request->property_id;
        $return->amount =  $request->amount;
        $return->from =  $request->from;
        $return->to =  $request->to;
        $return->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }
}
