<?php

namespace App\Http\Controllers;

use App\Models\StoryComment;
use App\Models\Category;
use App\Models\Page;
use App\Models\SuccessStory;
use Illuminate\Http\Request;

class SuccessStoryController extends Controller
{

    public function index()
    {
     
         $pageTitle     = 'Success Stories';
         $sections      = Page::where('tempname', $this->activeTemplate)->where('slug', 'campaign')->first();
         $categories    = Category::active()->whereHas('story')->get();
         $stories       = SuccessStory::searchable(['title'])->orderBy('id', 'DESC')->with('category')->filter(request(['month', 'year', 'category_id']))->paginate(getPaginate());
         $archives      = SuccessStory::selectRaw('year(created_at) year, monthname(created_at) month , count(*)')->groupBy('year', 'month')->get();
        return view($this->activeTemplate . 'success_story.index', compact('pageTitle', 'stories', 'categories', 'archives','sections'));
    }

    public function details($slug, $id)
    {
        $pageTitle = 'Success Story Details';

        $story        = SuccessStory::where('slug', $slug)->findOrFail($id);
        $story->view += 1;
        $story->save();

        $categories    = Category::active()->whereHas('story')->get();
        $archives      = SuccessStory::selectRaw('year(created_at) Year, monthname(created_at) Month , count(*)')->groupBy('year', 'month')->get();
        $recentStories = SuccessStory::where('id', '!=', $id)->orderBy('id', 'DESC')->take(4)->get();
        $comments      = StoryComment::where('success_story_id', $id)->published()->orderBy('id', 'DESC')->get();

        $seoContents['keywords']           = $story->meta_keywords ?? [];
        $seoContents['social_title']       = $story->title;
        $seoContents['description']        = strLimit(strip_tags($story->description), 150);
        $seoContents['social_description'] = strLimit(strip_tags($story->description), 150);

        $seoContents['image']              = getImage(getFilePath('success') . '/' . $story->image, '725x600');
        $seoContents['image_size']         = '725x600';


        return view($this->activeTemplate . 'success_story.details', compact('pageTitle', 'story', 'categories', 'archives', 'recentStories', 'comments', 'seoContents'));
    }


    public function comment(Request $request, $storyId)
    {
        $story = SuccessStory::findOrFail($storyId);
        $request->validate([
            'name'    => 'required|min:3|max:40',
            'email'   => 'required|email|max:40',
            'comment' => 'string|required|max:400',
        ]);
        $comment                   = new StoryComment();
        $comment->success_story_id = $story->id;
        $comment->commenter        = $request->name;
        $comment->email            = $request->email;
        $comment->comment          = $request->comment;
        $comment->save();
        $notify[] = ['success', 'Comment saved successfully, Please wait for publish!'];
        return back()->withNotify($notify)->withInput();
    }
}
