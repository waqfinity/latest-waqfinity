<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use Illuminate\Http\Request;

class MangeVolunteerController extends Controller
{
    public function index()
    {
        $pageTitle = "All Volunteers";
        $volunteers = Volunteer::searchable(['firstname','lastname', 'email','mobile', 'country'])->orderBy('id', 'DESC')->paginate(getPaginate());
        return view('admin.volunteer.index', compact('pageTitle', 'volunteers'));
    }
    public function status($id)
    {
        return Volunteer::changeStatus($id);
    }
    public function details($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        $pageTitle = 'Volunteer Detail - ' . $volunteer->fullname;
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.volunteer.details', compact('pageTitle', 'volunteer', 'countries'));
    }

    public function update(Request $request, $id)
    {
        $volunteer = Volunteer::findOrFail($id);
        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryArray   = (array)$countryData;
        $countries      = implode(',', array_keys($countryArray));
        $countryCode    = $request->country;
        $country        = $countryData->$countryCode->country;
        $dialCode       = $countryData->$countryCode->dial_code;

        $request->validate([
            'firstname' => 'required|max:40',
            'lastname'  => 'required|max:40',
            'state'     => 'sometimes',
            'zip'       => 'required',
            'city'      => 'required',
            'address'   => 'required',
            'email'     => 'required|email|max:40|unique:volunteers,email,' . $volunteer->id,
            'mobile'    => 'required|max:40|unique:volunteers,mobile,' . $volunteer->id,
            'country'   => 'required|in:' . $countries,
        ]);

        $volunteer->firstname    = $request->firstname;
        $volunteer->lastname     = $request->lastname;
        $volunteer->email        = $request->email;
        $volunteer->mobile       = $dialCode . $request->mobile;
        $volunteer->participated = $request->participation;
        $volunteer->country      = $request->country;
        $volunteer->country_code = $countryCode;
        $volunteer->address      = [
            'address' => $request->address,
            'state'   => $request->state,
            'zip'     => $request->zip,
            'city'    => $request->city,
            'country' => $country
        ];
        $volunteer->save();

        $notify[] = ['success', 'Volunteer updated successfully'];
        return back()->withNotify($notify);
    }


    public function formEmail($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        $general   = gs();
        if (!$general->en) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.volunteer.details', $volunteer->id)->withNotify($notify);
        }
        $pageTitle = 'Send Notification to ' . $volunteer->firstname . ' ' . $volunteer->lastname;
        return view('admin.volunteer.single_email', compact('pageTitle', 'volunteer'));
    }

    public function sendEmail(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'subject' => 'required|string',
        ]);

        $user = Volunteer::findOrFail($id);

        notify($user, 'DEFAULT', [
            'subject' => $request->subject,
            'message' => $request->message,
        ]);
        $notify[] = ['success', 'Notification sent successfully'];
        return back()->withNotify($notify);
    }
}
