<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Property;
use App\Models\MultiImage;
use App\Models\Facility;
use App\Models\Amenities;
use App\Models\PropertyType;
use App\Models\User;
use Intervention\Image\Facades\Image;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Carbon\Carbon;
use DB;
use App\Models\PackagePlan;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PropertyMessage;
use App\Models\State;
use App\Models\Schedule;
use Illuminate\Support\Facades\Mail;
use App\Mail\ScheduleMail;
use App\Models\SmtpSetting;
use App\Models\SiteSetting;

class SettingController extends Controller
{
    

    public function SmtpSetting()
    {
        
        $setting = SmtpSetting::find(1);

        return view('backend.setting.smtp_update',compact('setting'));
    }


    public function UpdateSmtpSetting(Request $request)
    {
        

        $smtp_id = $request->id;

        SmtpSetting::findOrFail($smtp_id)->update([

            'mailer' => $request->mailer,


            'host' => $request->host,


            'post' => $request->post,

            'username' => $request->username,

            'password' => $request->password,

            'encryption' => $request->encryption,

            'from_address' => $request->from_address,

        ]);


        $notification = array(

            'message' => 'SMTP Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }


    public function SiteSetting()
    {
        
        $sitesetting = SiteSetting::find(1);

        return view('backend.setting.site_update',compact('sitesetting'));
    }



    public function UpdateSiteSetting(Request $request){

        $site_id = $request->id;


        if ($request->file('logo')) {
            

            $image = $request->file('logo');

            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();

            Image::make($image)->resize(1500,386)->save('upload/logo/'.$name_gen);

            $save_url = 'upload/logo/'.$name_gen;


            SiteSetting::findOrFail($site_id)->update([


                'support_phone' => $request->support_phone,

                'company_address' => $request->company_address,

                'email' => $request->email,

                'facebook' => $request->facebook,

                'twitter' => $request->twitter,

                'copyright' => $request->copyright,

                'logo' => $save_url,
            ]);


                $notification = array(

                'message' => 'Site Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);



        }else{


            SiteSetting::findOrFail($site_id)->update([


                'support_phone' => $request->support_phone,

                'company_address' => $request->company_address,

                'email' => $request->email,

                'facebook' => $request->facebook,

                'twitter' => $request->twitter,

                'copyright' => $request->copyright,

                
            ]);


                $notification = array(

                'message' => 'Site Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);



        }

        
    }
}
