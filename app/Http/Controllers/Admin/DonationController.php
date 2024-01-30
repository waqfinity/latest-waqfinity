<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\DonationCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\Deposit;
use App\Models\CampaignMeta;
use Illuminate\Support\Facades\Hash;
use App\Models\CampaignsDonationCategory;
use Illuminate\Support\Facades\DB;

class DonationController extends Controller {

    public function index() {

        $pageTitle = "Donation List";
        $donations = Donation::where('status', Status::DONATION_PAID)->orderBy('id', 'DESC')->searchable(['fullname', 'mobile', 'donation', 'email', 'country', 'campaign:title', 'deposit.gateway:name'])->filter(['anonymous'])->dateFilter()->with('campaign', 'deposit', 'deposit.gateway')->filter(['status'])->whereHas('campaign')->paginate(getPaginate());

        $donations->getCollection()->each(function ($donation) {
            $donationCategoriesId = explode(',', $donation->donation_categories_id);
            $names = [];

            foreach ($donationCategoriesId as $donationCatId) {
                $donationCategory = DonationCategory::where("id", $donationCatId)->first();

                if ($donationCategory) {
                    array_push($names, $donationCategory->name);
                }
            }

            $donationCategories = implode(', ', $names);

            // Add the new parameter to each donation object
            $donation->donationCategories = $donationCategories;
        });

        return view('admin.donation.index', compact('pageTitle', 'donations'));
    }

    public function campaignWiseDonations($campaignId) {
        $donations = Donation::where('status', Status::DONATION_PAID)->where('campaign_id', $campaignId)->orderBy('id', 'DESC')->searchable(['fullname', 'mobile', 'donation', 'campaign:title'])->with('campaign', 'deposit', 'deposit.gateway')->whereHas('campaign')->paginate(getPaginate());
        $pageTitle = Campaign::where('id', $campaignId)->first()->title;
        return view('admin.donation.index', compact('pageTitle', 'donations'));
    }

    public function donors(Request $request) {

//        $searchTerm = $request->search;
//
//        $users = User::select('users.*')
//                ->join('donations', 'donations.user_id', '=', 'users.id')
//                ->where(function ($query) use ($searchTerm) {
//                    $query->where('users.firstname', 'LIKE', "%$searchTerm%")
//                    ->orwhere('users.lastname', 'LIKE', "%$searchTerm%")
//                    ->orwhere('users.email', 'LIKE', "%$searchTerm%")
//                    ->orWhere('users.mobile', 'LIKE', "%$searchTerm%");
//                })
//                ->groupBy('users.id')
//                ->orderBy('users.id', 'DESC')
//                ->paginate(getPaginate());
//
//        $pageTitle = "Donors List";
//
//        return view('admin.donation.donors', compact('pageTitle', 'users'));



        $pageTitle = "Donors List";
        $users = Donation::select(
                        'fullname',
                        'email',
                        'mobile',
                        'city',
                        DB::raw('count(*) as total_donations'),
                        DB::raw('SUM(donation) as total_amount'),
                        'address'
                    )
                    ->where('status', Status::DONATION_PAID)
                    ->groupBy('fullname', 'email', 'user_id')
                    ->orderBy('id', 'DESC')
                    ->searchable(['fullname', 'mobile', 'address', 'email', 'city', 'campaign:title', 'deposit.gateway:name'])
                    ->filter(['anonymous'])
                    ->dateFilter()
                    ->with('campaign', 'deposit', 'deposit.gateway')
                    ->filter(['status'])
                    ->whereHas('campaign')
                    ->groupBy('user_id', 'email')
                    ->paginate(getPaginate());

        $users->getCollection()->each(function ($donation) {
            $donationCategoriesId = explode(',', $donation->donation_categories_id);
            $names = [];

            foreach ($donationCategoriesId as $donationCatId) {
                $donationCategory = DonationCategory::where("id", $donationCatId)->first();

                if ($donationCategory) {
                    array_push($names, $donationCategory->name);
                }
            }

            $donationCategories = implode(', ', $names);

            // Add the new parameter to each donation object
            $donation->donationCategories = $donationCategories;
        });

        return view('admin.donation.donors', compact('pageTitle', 'users'));
    }

    public function readData(Request $request) {


//        $csvFilePath = storage_path('app/public/Contributions_2023_11_20.csv');
//
//        $file = fopen($csvFilePath, 'r');
//
//        $rowCount = 0;
//
//        while (($row = fgetcsv($file)) !== false) {
//
//            $rowCount++;
//
//            if ($rowCount == 1) {
//                continue;
//            }
//
//            // get campaign
//            $campaign = Campaign::where('title', 'like', '%' . $row["31"] . '%')->get();
//            if (count($campaign) == 0) {
//                Log::info("empty Campaign with data " . $row["31"]);
//                $campaignId = 0;
//            } else {
//                Log::info("campaignId " . $campaign[0]->id);
//                $campaignId = $campaign[0]->id;
//            }
//
//            // get user
//            $user = User::where('username', $row["34"] . ' ' . $row["32"])->get();
//            if (count($user) == 0) {
//                Log::info("empty user with data " . $row["34"] . ' ' . $row["32"]);
//                $userId = 0;
//            } else {
//                Log::info("userId " . $user[0]->id);
//                $userId = $user[0]->id;
//            }
//            // get user
//            $giftAid = 0;
//            if ($row["37"] == "True") {
//
//                $giftAid = 1;
//            }
//            $donation = new Donation();
//            $donation->user_id = $userId;
//            $donation->campaign_id = $campaignId;
//            $donation->anonymous = $row["35"] == "True" ? Status::YES : Status::NO;
//            $donation->fullname = $row["34"] . ' ' . $row["32"];
//            $donation->email = $row["40"];
//            $donation->country = $row["44"];
//            $donation->mobile = $row["33"];
//            $donation->donation = $row["41"];
////            $donation->donation_categories_id = $campaignsDonCatIds;
//            $donation->accept_marketing = 0;
//            $donation->status = 1;
//            $donation->gift_aid = $giftAid;
//            $donation->note = "migration";
//            $donation->save();
//        }
//        dd("sabsab");
//        $donations = Donation::where("note", "migration")->get();
//        foreach ($donations as $donation) {
//
//            $data = new Deposit();
//            $data->user_id = $donation->user_id;           //campaign_creator
//            $data->donation_id = $donation->id;
//            $data->method_code = "1000";
//            $data->method_currency = "GBP";
//            $data->amount = $donation->donation;
//            $data->charge = "";
//            $data->rate = "";
//            $data->final_amo = $donation->donation;
//            $data->btc_amo = 0;
//            $data->btc_wallet = "";
//            $data->trx = "";
//            $data->note = "migration";
//             $data->status = "1";
//            $data->save();
//        }
//        dd("sabsab");
        // descritpions edits
//        $campaigns = Campaign::all();
//        foreach ($campaigns as $campaign) {
//            if ($campaign->description == "") {
//                $campaign->description = $campaign->title;
//                $campaign->save();
//            }
//        }
//        dd("dis");
//        $campaigns = Campaign::where("note", "migration")->get();
//        foreach ($campaigns as $campaign) {
//            $campaignMeta = CampaignMeta::where("post_id", $campaign->id)->where("meta_key", "about_box")->get();
//            if (count($campaignMeta) != 0) {
//
//                $campaign->description = $campaignMeta[0]->meta_value;
//                $campaign->save();
//            }
//        }
//        dd("sabsab");
        // user first and last name
//        $users = User::where("note", "migration")->get();
//        foreach ($users as $user) {
//            $userMeta = \App\Models\UserMeta::where("user_id", $user->id)->where("meta_key", "first_name")->get();
//            if (count($userMeta) != 0) {
//                if ($userMeta[0]->meta_value != "") {
//                    $user->firstname = $userMeta[0]->meta_value;
//                    $user->save();
//                }
//            }
//            
//            $userMetaLast = \App\Models\UserMeta::where("user_id", $user->id)->where("meta_key", "last_name")->get();
//            if (count($userMetaLast) != 0) {
//                if ($userMetaLast[0]->meta_value != "") {
//                    $user->lastname = $userMetaLast[0]->meta_value;
//                    $user->save();
//                }
//            }
//        }
        // user password
//        $users = User::where("note", "migration")->get();
//        foreach ($users as $user) {
//
//            $user->password = Hash::make("Waqf_".$user->id);
//            $user->save();
//        }
//
//
//
//        $csvFilePath = storage_path('app/public/Contributions_2023_11_20.csv');
//
//        $file = fopen($csvFilePath, 'r');
//
//        $rowCount = 0;
//
//        while (($row = fgetcsv($file)) !== false) {
//
//            $rowCount++;
//
//            if ($rowCount == 1) {
//                continue;
//            }
//
//            // get campaign
//            $user = User::where('username', $row["34"] . ' ' . $row["32"])->get();
//            if (count($user) == 0) {
//
//                $userId = 0;
//            } else {
//
//                $userId = $user[0]->id;
//            }
//            $giftAid = 0;
//            if ($row["37"] == "True") {
//
//                $giftAid = 1;
//            }
//            $campaign = Campaign::where('title', 'like', '%' . $row["31"] . '%')->get();
//           
//            if (count($campaign) != 0) {
//                 
//                $donation = Donation::where("fullname", $row["34"] . ' ' . $row["32"])
//                                ->where("mobile", $row["33"])->where("donation", $row["41"])->where("email", $row["40"])->where("user_id", $userId)
//                                ->get();
//               
//                
//
//                $donation[0]->address = $row["36"];
//                $donation[0]->city = $row["44"];
//                $donation[0]->country = null;
//                
//                $donation[0]->save();
//               
//            }
//        }
//        dd($rowCount);
        // donations
//        $csvFilePath = storage_path('app/public/Waqfs_2023_11_20.csv');
//
//        $file = fopen($csvFilePath, 'r');
//
//        $rowCount = 0;
//
//        while (($row = fgetcsv($file)) !== false) {
//            $rowCount++;
//
//            if ($rowCount == 1) {
//
//                $communityDevelopmentDonationCategory = DonationCategory::where('name', $row["31"])->get();
//                $futuresFundDonationCategory = DonationCategory::where('name', "Future Funds")->get();
//                $capacityBuildingDonationCategory = DonationCategory::where('name', $row["33"])->get();
//                $faithLeadershipDonationCategory = DonationCategory::where('name', $row["36"])->get();
//            }
//
//            break;
//        }
//        fclose($file);
//
//        $csvFilePath = storage_path('app/public/Waqfs_2023_11_20.csv');
//
//        $file = fopen($csvFilePath, 'r');
//
//        $rowCount = 0;
//
//        while (($row = fgetcsv($file)) !== false) {
//
//            $rowCount++;
//
//            if ($rowCount == 1) {
//
//                continue;
//            }
//
//            // get campaign
//            $campaign = Campaign::where('title', 'like', '%' . $row["1"] . '%')->get();
//
//            $campaignsDonationCategory = new CampaignsDonationCategory();
//            $campaignsDonationCategory->campaign_id = $campaign[0]->id;
//            $campaignsDonationCategory->donation_category_id = $communityDevelopmentDonationCategory[0]->id;
//            $campaignsDonationCategory->percentage = $row["31"];
//            $campaignsDonationCategory->save();
//
//            $campaignsDonationCategory = new CampaignsDonationCategory();
//            $campaignsDonationCategory->campaign_id = $campaign[0]->id;
//            $campaignsDonationCategory->donation_category_id = $futuresFundDonationCategory[0]->id;
//            $campaignsDonationCategory->percentage = $row["32"];
//            $campaignsDonationCategory->save();
//
//            $campaignsDonationCategory = new CampaignsDonationCategory();
//            $campaignsDonationCategory->campaign_id = $campaign[0]->id;
//            $campaignsDonationCategory->donation_category_id = $capacityBuildingDonationCategory[0]->id;
//            $campaignsDonationCategory->percentage = $row["33"];
//            $campaignsDonationCategory->save();
//
//            $campaignsDonationCategory = new CampaignsDonationCategory();
//            $campaignsDonationCategory->campaign_id = $campaign[0]->id;
//            $campaignsDonationCategory->donation_category_id = $faithLeadershipDonationCategory[0]->id;
//            $campaignsDonationCategory->percentage = $row["36"];
//            $campaignsDonationCategory->save();
//
//            $donations = Donation::where("campaign_id", $campaign[0]->id)->get();
//
//            foreach ($donations as $donation) {
//
//                $jsonArray = [
//                    ['id' => $communityDevelopmentDonationCategory[0]->id, 'name' => $communityDevelopmentDonationCategory[0]->name, 'percentage' => $row["31"]],
//                    ['id' => $futuresFundDonationCategory[0]->id, 'name' => $futuresFundDonationCategory[0]->name, 'percentage' => $row["32"]],
//                    ['id' => $capacityBuildingDonationCategory[0]->id, 'name' => $capacityBuildingDonationCategory[0]->name, 'percentage' => $row["33"]],
//                    ['id' => $faithLeadershipDonationCategory[0]->id, 'name' => $faithLeadershipDonationCategory[0]->name, 'percentage' => $row["36"]],
//                ];
//
//                $jsonString = json_encode($jsonArray);
//
//                $donation->donation_categories_id = "6,8,9,11";
//                $donation->donation_categories_meta = $jsonString;
//                $donation->save();
//            }
//        }
        // remove zero categry
//        $count = 0;
//        $campaigns = Campaign::where("note", "migration")->get();
//        foreach($campaigns as $campaign){
//            $campaignsDonationCategory = CampaignsDonationCategory::where("campaign_id", $campaign->id)->get();
//            foreach($campaignsDonationCategory as $campaignsDonationCat){
//                if($campaignsDonationCat->percentage == "0"){
//                    $count++;
//                    $campaignsDonationCat->delete();
//                }
//            }
//        }
//        $donations = Donation::where("note", "migration")->where("campaign_id", '!=', "0")->get();
//
//        foreach ($donations as $donation) {
//            $donationCategoriesMeta = json_decode($donation->donation_categories_meta, true);
//            $donationCategoryMetaIds = [];
//            foreach ($donationCategoriesMeta as $donationCategoryMeta) {
//                if ($donationCategoryMeta["percentage"] == "0") {
//
//                    array_push($donationCategoryMetaIds, $donationCategoryMeta["id"]);
//                }
//            }
//            if (count($donationCategoryMetaIds) != 0) {
//
//                $string = "6,8,9,11";
//
//                $donationCategoryMetaString = implode(',', $donationCategoryMetaIds);
//
//                $newString = implode(',', array_diff(explode(',', $string), $donationCategoryMetaIds));
//              
//
//                $donation->donation_categories_id = $newString;
//                $donation->save();
//            }
//        }
//        dd("test");

        $deposits = Deposit::where("note", "migration")->get();
        $count = 0;
        foreach ($deposits as $deposit) {
            $donation = Donation::where("id", $deposit->donation_id)->get();

            if (count($donation) == 0) {
                $deposit->delete();
                $count++;
            }
        }
        dd($count);
    }
}
