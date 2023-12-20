<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\DonationCategory;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;

class DonationCategoryController extends Controller
{
       public function index()
    {
         
        $pageTitle  = 'Donation Categories';
       
        $categories = DonationCategory::searchable(['name'])->orderBy('name')->paginate(getPaginate());
       

        return view('admin.donationCategory.index', compact('pageTitle', 'categories'));
    }

    public function save(Request $request, $id = 0)
    {
     
        $imageValidation = $id ? 'nullable' : 'required';
   
        $request->validate([
            'name'        => 'required|unique:donation_category,name,'.$id,
            'image'       => ["$imageValidation", new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        if ($id) {
            $category           = DonationCategory::findOrFail($id);
            $notification       = 'Donation Category updated successfully';
        } else {
            $category           = new DonationCategory();
            $notification       = 'Donation Category added successfully';
        }

        if ($request->hasFile('image')) {
            try {
                $old = @$category->image;
                $category->image = fileUploader($request->image, getFilePath('category'), getFileSize('category'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload Donation category image'];
                return back()->withNotify($notify);
            }
        }
        
       
        
        $category->name = $request->name;
        $category->slug = slug($request->name);
        $category->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }
    
    public function status($id)
    {
      
        return DonationCategory::changeStatus($id);
    }
}
