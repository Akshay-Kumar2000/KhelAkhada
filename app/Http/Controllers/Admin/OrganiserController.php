<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Organiser;
use Auth;
use Illuminate\Support\Facades\Validator;

use Webklex\IMAP\Client;

class OrganiserController extends Controller
{
    protected $paging   =   20;

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {        
        $organisers = Organiser::latest()->paginate($this->paging);
        //echo "<pre>";print_r($organisers);die;
        return view('admin/organiser/organisers',compact('organisers'));
    }

    public function create(Request $request)
    {
        return view('admin/organiser/create');
    }

    public function mails(){
        
    $oClient = new Client([
        'host'          => 'localhost',
        'port'          => 993,
        'encryption'    => 'ssl',
        'validate_cert' => true,
        'username'      => 'neeraj.singh@celebgaze.com',
        'password'      => 'Airport@123',
        'protocol'      => 'imap'
    ]);
    //Connect to the IMAP Server
    $oClient->connect();
    //Get all Mailboxes
    /** @var \Webklex\IMAP\Support\FolderCollection $aFolder */
    $aFolder = $oClient->getFolders();
    //Loop through every Mailbox
    /** @var \Webklex\IMAP\Folder $oFolder */
    foreach($aFolder as $oFolder){
        //Get all Messages of the current Mailbox $oFolder
        /** @var \Webklex\IMAP\Support\MessageCollection $aMessage */
        $aMessage = $oFolder->messages()->all()->get();
        
        /** @var \Webklex\IMAP\Message $oMessage */
        foreach($aMessage as $oMessage){
            echo $oMessage->getSubject().'<br />';
            echo 'Attachments: '.$oMessage->getAttachments()->count().'<br />';
            echo $oMessage->getHTMLBody(true);
        }
    }
    }

    public function edit($organiser)
    {
        $organiser     =   Organiser::find($organiser);
        //echo "<pre>";print_r($organiser); die;
        return view('admin/organiser/edit',compact('organiser'));
    }

    public function store(Request $request)
    { //echo "<pre>";print_r($request->all());die;
        $validator              = Validator::make($request->all(), [
            'title'             => 'required',
            //'logo'            => 'required | image|mimes:jpeg,png,jpg|max:2048',
        ]);
        //echo "<pre>"; print_r($request->all());die;
        if($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }
        try
        {
            // $logo = $request->file('logo');
            // if($logo){
            //     $imageName = 'organiser/'.time().'.'.$logo->getClientOriginalExtension();
            //     //echo $imageName;die;
            //     Storage::disk('s3')->put($imageName, file_get_contents($logo));
            // }

            // store user information
            $event = Organiser::create([
                        'title'             => $request->title,
                        //'logo'              => $imageName,
                        'logo'              => 'test',
                        'ip'                => $request->ip(),
                    ]);

            if($event){ 
                return redirect('admin\organisers')->with('success', 'Organiser added successfully!');
            }else{
                return redirect('admin\organisers')->with('error', 'Failed to add organiser! Try again.');
            }
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function update(Request $request)
    { //echo "<pre>";print_r($request->all());die;
        $validator              = Validator::make($request->all(), [
            'title'             => 'required',
            'organiser_id'      => 'required',
        ]);

        if(isset($request->logo)){
            $validator = Validator::make($request->all(), [
                'logo'        => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);
        }
        //echo "<pre>"; print_r($request->all());die;
        if($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }
        try
        {
            $data               =   Organiser::find($request->organiser_id);
            // store user information
            $update = $data->update([
                        'title'             => $request->title,
                    ]);

            // update organiser logo
            $logo = $request->file('logo');
            if($logo){
                $imageName = 'organiser/'.time().'.'.$logo->getClientOriginalExtension();
                Storage::disk('s3')->put($imageName, file_get_contents($logo));
                Storage::disk('s3')->delete($data->logo);

                $update = $data->update([
                    'logo' => $imageName
                ]);
            }
            
            if($update){ 
                return redirect('admin\organisers')->with('success', 'Organiser updated successfully!');
            }else{
                return redirect('admin\organisers')->with('error', 'Failed to update organiser! Try again.');
            }
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function changeStatus($status,$uid)
    {
        $data   = Organiser::find($uid);
        if($data){
            $data->update(['status' => $status]);
            return redirect()->back()->with('success', 'Staus updated successfully!');
        }else{
            return redirect()->back()->with('error', 'Record not found');
        }
    }

}