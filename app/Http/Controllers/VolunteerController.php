<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class VolunteerController extends Controller
{
    public function index()
    {
        $pageTitle  = "List of Volunteers";
        $volunteers = Volunteer::active()->orderBy('id', 'DESC')->paginate(getPaginate());
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view($this->activeTemplate.'volunteer.index',compact('pageTitle', 'volunteers', 'countries'));
    }

    public function form()
    {
        $pageTitle  = "Join as Volunteer";
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view($this->activeTemplate.'volunteer.form',compact('pageTitle','mobileCode','countries'));
    }


    public function store(Request $request)
    {
        $countryData = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes = implode(',',array_column($countryData, 'dial_code'));
        $countries = implode(',',array_column($countryData, 'country'));
        
        $request->validate([
            'firstname'    => 'required|max:200',
            'lastname'     => 'required|max:200',
            'email'        => 'required|email|unique:volunteers,email',
            'mobile_code'  => 'required|in:'.$mobileCodes,
            'country_code' => 'required|in:'.$countryCodes,
            'country'      => 'required|in:'.$countries,
            'mobile'       => 'required|regex:/^([0-9]*)$/',
            'state'        => 'required|max:100',
            'zip'          => 'required|max:50',
            'city'         => 'required|max:100',
            'address'      => 'required',
            'image'        => 'required','image', new FileTypeValidate(['jpg','jpeg','png'])
        ]);

        if ($request->hasFile('image')) {
            try {
                $old = '';
                $filename = fileUploader($request->image, getFilePath('volunteer'), getFileSize('volunteer'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $volunteer = new Volunteer();
        $volunteer->firstname    = $request->firstname;
        $volunteer->lastname     = $request->lastname;
        $volunteer->email        = $request->email;
        $volunteer->mobile        = $request->mobile_code . $request->mobile;
        $volunteer->country      = $request->country;
        $volunteer->country_code = $request->country_code;
        $volunteer->address      = [
            'address' => $request->address,
            'state'   => $request->state,
            'zip'     => $request->zip,
            'city'    => $request->city
        ];
        $volunteer->image = $filename;
        $volunteer->save();
        $notify[] = ['success', 'Added successfully. Please wait for admin response!'];
        return back()->withNotify($notify);
    }


    public function filter(Request $request)
    {
        $pageTitle  = "Volunteers List";
        $volunteers = Volunteer::active()->searchable(['firstname','lastname'])->filter(['country_code'])->paginate(getPaginate());
        $html=view($this->activeTemplate . 'partials.volunteer', compact('pageTitle', 'volunteers'))->render();
        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

}
