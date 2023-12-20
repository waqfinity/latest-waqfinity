<?php

namespace App\Http\Controllers\Admin;


use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\FileTypeValidate;

class CategoryController extends Controller
{
    public function index()
    {
       
        $pageTitle  = 'Pages Categories';
        $categories = Category::searchable(['name'])->orderBy('name')->paginate(getPaginate());
   
        return view('admin.category.index', compact('pageTitle', 'categories'));
    }

    public function store(Request $request, $id = 0)
    {
        $imageValidation = $id ? 'nullable' : 'required';
        
        $request->validate([
            'name'        => 'required|unique:categories,name,'.$id,
            'image'       => ["$imageValidation", new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        if ($id) {
            $category           = Category::findOrFail($id);
            $notification       = 'Category updated successfully';
        } else {
            $category           = new Category();
            $notification       = 'Category added successfully';
        }

        if ($request->hasFile('image')) {
            try {
                $old = @$category->image;
                $category->image = fileUploader($request->image, getFilePath('category'), getFileSize('category'), $old);
               
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload category image'];
                return back()->withNotify($notify);
            }
        }
        
        $is_corporate = 0;
        if($request->is_corporate == "on"){
            $is_corporate = 1;
        }
      
        $category->name = $request->name;
        $category->slug = slug($request->name);
        $category->is_corporate =  $is_corporate;
        $category->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Category::changeStatus($id);
    }
}
