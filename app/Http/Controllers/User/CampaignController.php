<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Category;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use App\Models\CampaignsDonationCategory;
 use App\Models\DonationCategory;


class CampaignController extends Controller
{

    public function create()
    {
       
        $pageTitle = 'Create New Waqf Page';
        $categories = Category::active()->orderBy('id', 'DESC')->get();
        return view($this->activeTemplate . 'user.campaign.form', compact('pageTitle', 'categories'));
    }

    
    public function storeCampaign(Request $request, $id = 0)
    {
     

       
        $this->validation($request, $id);
        $category = Category::active()->find($request->category_id);

        if (!$category) {
            $notify[] = ['error', 'Campaign category inactive/invalid'];
            return back()->withNotify($notify);
        }

        if ($id) {
          
            $campaign = Campaign::where('user_id', auth()->id())->findOrFail($id);
            $notification = 'Campaign update and approval request send successfully';
            
            if($request->donationCategories == null){
                $selectedCategories  = ["10"];
            }else{

                $selectedCategories = $request->donationCategories;
            }
        } else {
           
            $campaign = new Campaign();
            $notification = 'Campaign created successfully';
            
            if($request->selectedCategories == null){
                $selectedCategories  = ["10"];
            }else{

                $selectedCategoriesString = trim( $request->selectedCategories, '[]');
                $selectedCategories = explode(',', $selectedCategoriesString);
            }
           
        }
 
        foreach($request->attachments as $attachment){
              try {
                
                $path = getFilePath('campaign');
                $oldImage = '';
                $filename = fileUploader($attachment, $path, getFileSize('campaign'), $oldImage);
               
               
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload campaign image'];
                return back()->withNotify($notify);
            }
        }
        
        if ($request->hasFile('image')) {
            try {
                $path = getFilePath('campaign');
                $oldImage = '';
                $filename = fileUploader($request->image, $path, getFileSize('campaign'), $oldImage);

                if ($id) {
                    $deleteImage = $path . '/' . $campaign->image;
                    fileManager()->removeFile($deleteImage);
                }
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload campaign image'];
                return back()->withNotify($notify);
            }
        }
    
        if ($request->hasFile('attachments')) {
            try {
                $path = getFilePath('proof');
                $size = getFileSize('proof');
                $proofFiles = [];
                foreach ($request->attachments as $key => $attachment) {
                    if ($attachment->getClientOriginalExtension() == 'pdf') {
                        $filename2 = uniqid() . time() . '.' . $attachment->getClientOriginalExtension();
                        $proof = [
                            "proof_$key" => $filename2,
                        ];
                        $proofFiles = $proofFiles + $proof;
                        $attachment->move($path, $filename2);
                    } else {
                        $image = [
                            "proof_$key" => fileUploader($attachment, $path, $size)
                        ];
                        $proofFiles =  $proofFiles + $image;
                    }
                }

                if ($id) {
                    foreach ($campaign->proof_images as $proof) {
                        $deleteProofPath = $path . '/' . $proof;
                        fileManager()->removeFile($deleteProofPath);
                    }
                }
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Could not upload proof attachment'];
                return back()->withNotify($notify);
            }
        }

        $filename = @$filename ? $filename : $campaign->image;
        $proofData = @$proofFiles ? $proofFiles : $campaign->proof_images;
        $purifier = new \HTMLPurifier();

        $campaign->category_id  = $request->category_id;

        $campaign->user_id      = auth()->user()->id;
        $campaign->title        = $request->title;
        $campaign->description  = $purifier->purify($request->description);
        $campaign->deadline     = Carbon::parse($request->deadline);
        $campaign->goal         = $request->goal;
        $campaign->image        = $filename;
        $campaign->proof_images = $proofData;
        $campaign->slug         = slug($request->title);
        $campaign->save();
       
        CampaignsDonationCategory::where('campaign_id',$id)->delete();
       
        foreach($selectedCategories as $selectedCategory){
           
            $campaignsDonationCategory = new CampaignsDonationCategory();
            $campaignsDonationCategory->campaign_id = $campaign->id;
            $campaignsDonationCategory->donation_category_id = (int)$selectedCategory;
            $campaignsDonationCategory->percentage = (int)(100 / count($selectedCategories));
            $campaignsDonationCategory->save();
        }
      
      

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }



    protected function validation($request, $id)
    {
        $image          = $id ? 'nullable' : 'required';
        $proofDocuments = $id ? 'nullable' : 'required';

         $request->validate([
            'category_id'   => 'required|integer',
            'title'         => 'required|max:200',
            'description'   => 'required|min:200',
            'goal'          => 'required|numeric|gt:0',
            'deadline'      => 'required|date|after:today',
            'image'         => [$image, 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'attachments.*' => [$proofDocuments, new FileTypeValidate(['jpg', 'jpeg', 'png', 'pdf']), 'max:5120']
        ]);
    }

    public function extended(Request $request, $id = 0)
    {
        $campaign = Campaign::where('user_id', auth()->id())->where('id', $id)->expired()->first();

        if (!$campaign) {
            $notify[] = ['error', 'The request to extend the campaign is invalid'];
            return back()->withNotify($notify);
        }

        $request->validate([
            'goal'          => 'required|numeric|gt:0',
            'deadline'      => 'required|date|after:today',
        ]);

        $campaign->deadline    = Carbon::parse($request->deadline);
        $campaign->goal        = $request->goal + $campaign->goal;
        $campaign->extend_goal = $request->goal;
        $campaign->is_extend   = Status::YES;
        $campaign->expired     = Status::NO;
        $campaign->status      = Status::PENDING;
        $campaign->save();
        $notify[] = ['success', 'Successfully sent the campaign extend request to author'];
        return back()->withNotify($notify);
    }

    public function edit($id)
    {
       
        $pageTitle  = "Edit Campaign ";
        $campaign   = Campaign::where('user_id', auth()->id())->findOrFail($id);
        $categories = Category::active()->get();
      
        return view($this->activeTemplate . 'user.campaign.edit', compact('pageTitle', 'campaign', 'categories'));
    }

    protected function campaignData($scope = null)
    {
        if ($scope) {
            $campaigns = Campaign::$scope();
        } else {
            $campaigns = Campaign::query();
        }
        return $campaigns->where('user_id', auth()->id())->searchable(['title'])->with('donation')->orderBy('id', 'DESC')->paginate(getPaginate());
    }
    public function approvedCampaign()
    {
        $pageTitle  = "Approved Campaigns";
        $campaigns  = $this->campaignData('running');
        return view($this->activeTemplate . 'user.campaign.index', compact('campaigns', 'pageTitle'));
    }
    public function pendingCampaign()
    {
        $pageTitle  = "Pending Campaigns";
        $campaigns = $this->campaignData('pending');
        return view($this->activeTemplate . 'user.campaign.index', compact('campaigns', 'pageTitle'));
    }
    public function rejectedCampaign()
    {
        $pageTitle  = "Rejected Campaigns";
        $campaigns = $this->campaignData('rejected');
        return view($this->activeTemplate . 'user.campaign.index', compact('campaigns', 'pageTitle'));
    }
    public function completeCampaign()
    {
        $pageTitle = "Successful Campaigns";
        $campaigns = $this->campaignData('success');
        return view($this->activeTemplate . 'user.campaign.index', compact('campaigns', 'pageTitle'));
    }
    public function expiredCampaign()
    {
        $pageTitle = "Expired Campaigns";
        $campaigns = $this->campaignData('expired');
        return view($this->activeTemplate . 'user.campaign.index', compact('campaigns', 'pageTitle'));
    }
    public function campaignDetails(Request $request)
    {
       
        $pageTitle = "Campaign Details";
        $campaign  = Campaign::where('slug', $request->slug)->with('donation')->findOrFail($request->id);
        
        $donationCategoriesArr = [];
        $donationCategories = CampaignsDonationCategory::where("campaign_id", $campaign->id)->get();
        foreach($donationCategories as $donationCategory){
            $donationCategory = DonationCategory::where("id", $donationCategory->donation_category_id)->first();
            array_push($donationCategoriesArr, $donationCategory->name);
                 
        }
        // get all DonationCategory
        $allDonationCategoriesArr = [];
        $allDonationCategories = DonationCategory::all();
            
        foreach($allDonationCategories as $allDonationCategory){
                array_push($allDonationCategoriesArr, $allDonationCategory->name);
               
        }
        $campaign->CampaignDonationCategoriesNames = $donationCategoriesArr; 
        $campaign->allDonationCategoriesNames = $allDonationCategoriesArr; 
          

        return view($this->activeTemplate . 'user.campaign.details', compact('pageTitle', 'campaign'));
    }
    public function runAndStop($id)
    {
        $campaign = Campaign::where('user_id', auth()->id())->findOrFail($id);
        if ($campaign->stop) {
            $campaign->stop = Status::NO;
            $notification = 'Campaign started successfully';
        } else {
            $campaign->stop = Status::YES;
            $notification = 'Campaign stopped successfully';
        }
        $campaign->save();
        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }
    public function complete($id)
    {
        $campaign = Campaign::active()->findOrFail($id);
        $campaign->completed = Status::YES;
        $campaign->save();
        $notify[] = ['success', 'Campaign Completed Successfully'];
        return back()->withNotify($notify);
    }
    public function delete($id)
    {
        $campaign = Campaign::findOrFail($id);
        try {
            $path = getFilePath('campaign') . '/' . $campaign->image;
            if (!empty($campaign->proof_images)) {
                foreach ($campaign->proof_images as  $proof) {
                    $proofPath = getFilePath('proof') . '/' . $proof;
                    fileManager()->removeFile($proofPath);
                }
            }
            $campaign->delete();
            fileManager()->removeFile($path);
        } catch (Exception $ex) {
            $notify[] = ['error', $ex->getMessage()];
            return back()->withNotify($notify);
        }
        $notify[] = ['success', 'Campaign deleted Successfully'];
        return back()->withNotify($notify);
    }

    public function allCampaign()
    {
        $pageTitle = "All Campaigns";
        $campaigns = Campaign::searchable(['title'])->where('user_id', auth()->user()->id)->with('donation')->latest()->paginate(getPaginate());
        return view($this->activeTemplate . 'user.campaign.all_campaign', compact('pageTitle', 'campaigns'));
    }
}
