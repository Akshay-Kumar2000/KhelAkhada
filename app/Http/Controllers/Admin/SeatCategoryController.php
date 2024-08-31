<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\SeatCategory;
use Auth;
use Illuminate\Support\Facades\Validator;

class SeatCategoryController extends Controller
{
    protected $paging   =   20;

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {        
        $categories = SeatCategory::latest()->paginate($this->paging);
        //echo "<pre>";print_r($categories);die;
        return view('admin/stage/seat-categories',compact('categories'));
    }

    public function create(Request $request)
    {
        return view('admin/stage/create-seat-category');
    }

    public function edit($category)
    {
        $category     =   SeatCategory::find($category);
        //echo "<pre>";print_r($category); die;
        return view('admin/stage/edit-seat-category',compact('category'));
    }

    public function store(Request $request)
    { //echo "<pre>";print_r($request->all());die;
        $validator              = Validator::make($request->all(), [
            'name'              => 'required',
            //'icon'            => 'required | image|mimes:jpeg,png,jpg|max:2048',
        ]);
        //echo "<pre>"; print_r($request->all());die;
        if($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }
        try
        {
            // $icon = $request->file('icon');
            // if($icon){
            //     $imageName = 'seat/'.time().'.'.$icon->getClientOriginalExtension();
            //     //echo $imageName;die;
            //     Storage::disk('s3')->put($imageName, file_get_contents($icon));
            // }

            // store user information
            $event = SeatCategory::create([
                        'name'              => $request->name,
                        //'icon'              => $imageName,
                        'icon'              => 'test',
                        'ip'                => $request->ip(),
                    ]);

            if($event){ 
                return redirect('admin\seat-categories')->with('success', 'Seat Categories added successfully!');
            }else{
                return redirect('admin\seat-categories')->with('error', 'Failed to add seat categories! Try again.');
            }
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function update(Request $request)
    { //echo "<pre>";print_r($request->all());die;
        $validator              = Validator::make($request->all(), [
            'name'              => 'required',
            'category_id'       => 'required',
        ]);

        if(isset($request->icon)){
            $validator = Validator::make($request->all(), [
                'icon'        => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);
        }
        //echo "<pre>"; print_r($request->all());die;
        if($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }
        try
        {
            $data               =   SeatCategory::find($request->category_id);
            // store user information
            $update = $data->update([
                        'name'             => $request->name,
                    ]);

            // update seat category icon
            $icon = $request->file('icon');
            if($icon){
                $imageName = 'seat/'.time().'.'.$icon->getClientOriginalExtension();
                Storage::disk('s3')->put($imageName, file_get_contents($icon));
                Storage::disk('s3')->delete($data->icon);

                $update = $data->update([
                    'icon' => $imageName
                ]);
            }
            
            if($update){ 
                return redirect('admin\seat-categories')->with('success', 'Seat Category updated successfully!');
            }else{
                return redirect('admin\seat-categories')->with('error', 'Failed to update seat category! Try again.');
            }
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function changeStatus($status,$uid)
    {
        $data   = SeatCategory::find($uid);
        if($data){
            $data->update(['status' => $status]);
            return redirect()->back()->with('success', 'Staus updated successfully!');
        }else{
            return redirect()->back()->with('error', 'Record not found');
        }
    }

}