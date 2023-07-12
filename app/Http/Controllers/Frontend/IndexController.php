<?php

namespace App\Http\Controllers\Frontend;

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
use App\Models\PackagePlan;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PropertyMessage;
use App\Models\State;
use App\Models\Schedule;

class IndexController extends Controller
{
    

    public function PropertyDetails($id,$slug)
    {
        
        $property = Property::findOrFail($id);

        $amenities = $property->amenities_id;

        $property_amen = explode(',', $amenities);

        $multiImage = MultiImage::where('property_id',$id)->get();

        $facilitiy = Facility::where('property_id',$id)->get();

        $type_id = $property->ptype_id;

        $relatedProperty = Property::where('ptype_id',$type_id)->where('id','!=',$id)->orderBy('id','DESC')->limit(3)->get();

        return view('frontend.property.property_details',compact('property','multiImage','property_amen','facilitiy','relatedProperty'));

    }

    public function PropertyMessage(Request $request)
    {
        

        $pid = $request->property_id;

        $aid = $request->agent_id;


        if (Auth::check()) {


            PropertyMessage::insert([

                'user_id' => Auth::user()->id,

                'agent_id' => $aid,

                'property_id' => $pid,

                'msg_name' => $request->msg_name,

                'msg_email' => $request->msg_email,

                'msg_phone' => $request->msg_phone,

                'message' => $request->message,

                'created_at' => Carbon::now(),

            ]);

            $notification = array(

            'message' => 'Message Sent Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

        }else{

            $notification = array(

            'message' => 'Please Login To Your Account First',
            'alert-type' => 'error'
        );

        return redirect()->back()->with($notification);
        }
    }



    public function AgentDetails($id)
    {
        

        $agent = User::findOrFail($id);

        $property = Property::where('agent_id',$id)->get();

        $featured = Property::where('featured','1')->limit(3)->get();

        $rentproperty = Property::where('property_status','rent')->get();

        $buyproperty = Property::where('property_status','buy')->get();

        return view('frontend.agent.agent_details',compact('agent','property','featured','rentproperty','buyproperty'));
    }



    public function AgentDetailsMessage(Request $request)
    {
        


        $aid = $request->agent_id;


        if (Auth::check()) {


            PropertyMessage::insert([

                'user_id' => Auth::user()->id,

                'agent_id' => $aid,

                'msg_name' => $request->msg_name,

                'msg_email' => $request->msg_email,

                'msg_phone' => $request->msg_phone,

                'message' => $request->message,

                'created_at' => Carbon::now(),

            ]);

            $notification = array(

            'message' => 'Message Sent Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

        }else{

            $notification = array(

            'message' => 'Please Login To Your Account First',
            'alert-type' => 'error'
        );

        return redirect()->back()->with($notification);
        }
    }



    public function RentProperty()
    {
        
        $property = Property::where('status','1')->where('property_status','rent')->paginate(3);

        $rentproperty = Property::where('property_status','rent')->get();

        $buyproperty = Property::where('property_status','buy')->get();

        return view('frontend.property.rent_property',compact('property','rentproperty','buyproperty'));
    }


    public function BuyProperty()
    {
        
        $property = Property::where('status','1')->where('property_status','buy')->paginate(3);

        $rentproperty = Property::where('property_status','rent')->get();

        $buyproperty = Property::where('property_status','buy')->get();

        return view('frontend.property.buy_property',compact('property','rentproperty','buyproperty'));
    }



    public function PropertyType($id)
    {
        
        $property = Property::where('status','1')->where('ptype_id',$id)->paginate(3);

        $rentproperty = Property::where('property_status','rent')->get();

        $buyproperty = Property::where('property_status','buy')->get();

        $pbread = PropertyType::where('id',$id)->first();


        return view('frontend.property.property_type',compact('property','rentproperty','buyproperty','pbread'));
    }




    public function StateDetails($id)
    {
        

        $property = Property::where('status','1')->where('state',$id)->paginate(3);


        $rentproperty = Property::where('property_status','rent')->get();

        $buyproperty = Property::where('property_status','buy')->get();

        $pbread = State::where('id',$id)->first();

        return view('frontend.property.state_property',compact('property','rentproperty','buyproperty','pbread'));
    }



    public function BuyPropertySearch(Request $request) 
    {
        

        $request->validate(['search' => 'required']);

        $item = $request->search;

        $sstate = $request->state;

        $stype = $request->ptype_id;


        $property = Property::where('property_name', 'like' , '%' .$item. '%')->where('property_status','buy')->with('type','pstate')->whereHas('pstate', function($q) use ($sstate){

            $q->where('state_name', 'like' , '%' .$sstate. '%');
        })->whereHas('type', function($q) use ($stype){

            $q->where('type_name', 'like' , '%' .$stype. '%');
        })->paginate(3);


        $rentproperty = Property::where('property_status','rent')->get();

        $buyproperty = Property::where('property_status','buy')->get();

        

        return view('frontend.property.property_search',compact('property','rentproperty','buyproperty'));    
    }



    public function RentPropertySearch(Request $request) 
    {
        

        $request->validate(['search' => 'required']);

        $item = $request->search;

        $sstate = $request->state;

        $stype = $request->ptype_id;


        $property = Property::where('property_name', 'like' , '%' .$item. '%')->where('property_status','rent')->with('type','pstate')->whereHas('pstate', function($q) use ($sstate){

            $q->where('state_name', 'like' , '%' .$sstate. '%');
        })->whereHas('type', function($q) use ($stype){

            $q->where('type_name', 'like' , '%' .$stype. '%');
        })->paginate(3);


        $rentproperty = Property::where('property_status','rent')->get();

        $buyproperty = Property::where('property_status','buy')->get();

        

        return view('frontend.property.property_search',compact('property','rentproperty','buyproperty'));    
    }




    public function AllPropertySearch(Request $request) 
    {
        

        

        $bathrooms = $request->bathrooms;

        $sstate = $request->state;

        $stype = $request->ptype_id;


        $property_status = $request->property_status;

        $bedrooms = $request->bedrooms;


        $property = Property::where('status','1')->where('bedrooms',$bedrooms)->where('bathrooms',$bathrooms)->where('property_status',$property_status)->with('type','pstate')->whereHas('pstate', function($q) use ($sstate){

            $q->where('state_name', 'like' , '%' .$sstate. '%');
        })->whereHas('type', function($q) use ($stype){

            $q->where('type_name', 'like' , '%' .$stype. '%');
        })->paginate(3);


        $rentproperty = Property::where('property_status','rent')->get();

        $buyproperty = Property::where('property_status','buy')->get();

        

        return view('frontend.property.property_search',compact('property','rentproperty','buyproperty'));    
    }




    public function StoreSchedule(Request $request)
    {

        $aid = $request->agent_id;

        $pid = $request->property_id;

        if (Auth::check()) {
            
            Schedule::insert([

                'user_id' => Auth::user()->id,

                'agent_id' => $aid,

                'property_id' => $pid,

                'tour_date' => $request->tour_date,

                'tour_time' => $request->tour_time,

                'message' => $request->message,

                'created_at' => Carbon::now(),

            ]);


            $notification = array(

            'message' => 'Request Sent Successfully',
            'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);


        }else{

            $notification = array(

            'message' => 'Please Login To Your Account First',
            'alert-type' => 'error'
            );

            return redirect()->back()->with($notification);

        }
    }



    public function FrontPropertyCategories()
    {
        $ptype = PropertyType::latest()->get();
        
        return view('frontend.categories.all_category',compact('ptype'));
    }


    public function FrontAllProperty()
    {
        $property = Property::where('status','1')->paginate(3);

        $rentproperty = Property::where('property_status','rent')->get();

        $buyproperty = Property::where('property_status','buy')->get();

        return view('frontend.property.front_all_property',compact('property','rentproperty','buyproperty'));
    }
}
