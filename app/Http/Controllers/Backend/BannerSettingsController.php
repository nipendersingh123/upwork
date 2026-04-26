<?php

namespace App\Http\Controllers\Backend;



use App\Http\Controllers\Controller;
use Modules\Service\Entities\Category;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerSettingsController extends Controller
{
    public function mobile_app_banner_settings(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate(
                [
                    'image' => 'required',
                    'category_id' => 'required',
                    'project_id' => 'required',
                    'status' => 'required',
                ]
            );

            Banner::create([
                'image' => $request->image,
                'category_id' => $request->category_id,
                'project_id' => $request->project_id,
                'type' => 'project',
                'status' => $request->status,
            ]);
            return redirect()->back()->with(toastr_success(__('New Banner Added')));
        }

        $banners = Banner::with(['category', 'project'])->latest()->get();
        $categories = Category::select('id', 'category')->where('status', 1)->get();

        return view('backend.pages.banner.all-banner', compact('banners', 'categories'));
    }

    public function edit_banner(Request $request)
    {
        $request->validate([
            'image' => 'required',
            'category_id' => 'required',
            'project_id' => 'required',
            'status' => 'required',
        ]);

        Banner::where('id', $request->id)->update([
            'image' => $request->image,
            'category_id' => $request->category_id,
            'project_id' => $request->project_id,
            'status' =>  $request->status,
        ]);

        return redirect()->back()->with(toastr_success(__('Banner Update Success')));
    }

    public function delete_banner($id)
    {
        Banner::find($id)->delete();
        return redirect()->back()->with(toastr_success(__('Banner Deleted Success')));
    }

    public function bulk_action(Request $request)
    {
        Banner::whereIn('id', $request->ids)->delete();
        return response()->json([
            'status' => 'ok'
        ]);
    }

    public function get_projects_by_category(Request $request)
    {
        $projects = \App\Models\Project::where('category_id', $request->category_id)
            ->where('status', '1')
            ->where('project_on_off', '1')
            ->select('id', 'title')
            ->get();
        return response()->json($projects);
    }
}
