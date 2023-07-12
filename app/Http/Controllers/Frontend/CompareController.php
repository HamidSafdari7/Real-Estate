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
use App\Models\Compare;
use Barryvdh\DomPDF\Facade\Pdf;


class CompareController extends Controller
{
    

    public function AddToCompare(Request $request,$property_id)
    {
        if (Auth::check()) {
            
            $exists = Compare::where('user_id',Auth::id())->where('property_id',$property_id)->first();

            if (!$exists) {
                

                Compare::insert([

                    'user_id' => Auth::id(),

                    'property_id' => $property_id,

                    "created_at" => Carbon::now(),
                ]);

                return response()->json(['success' => 'Item Added To Your Compare List Successfully']);
            }else{

              return response()->json(['error' => 'This Item Has Already Been Added On Your Compare List !!']);  
            }
        }else{

            return response()->json(['error' => 'You Must Login To Your Account First !!']);
        }
    }



    public function UserCompare()
    {

        return view('frontend.dashboard.compare');
    }


    public function GetCompareProperty()
    {
        

        $compare = Compare::with('property')->where('user_id',Auth::id())->latest()->get();

        

        return response()->json($compare);
    }


    public function CompareRemove($id)
    {
        
        Compare::where('user_id',Auth::id())->where('id',$id)->delete();

        return response()->json(['success' => 'Item Removed From Your Compare List Successfully']);
    }

}
