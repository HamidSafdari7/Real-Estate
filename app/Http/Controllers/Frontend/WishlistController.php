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
use App\Models\Wishlist;
use Barryvdh\DomPDF\Facade\Pdf;


class WishlistController extends Controller
{
    

    public function AddToWishList(Request $request,$property_id)
    {
        if (Auth::check()) {
            
            $exists = Wishlist::where('user_id',Auth::id())->where('property_id',$property_id)->first();

            if (!$exists) {
                

                Wishlist::insert([

                    'user_id' => Auth::id(),

                    'property_id' => $property_id,

                    "created_at" => Carbon::now(),
                ]);

                return response()->json(['success' => 'Item Added On Your Wish List Successfully']);
            }else{

              return response()->json(['error' => 'This Item Has Already Been Added On Your Wish List !!']);  
            }
        }else{

            return response()->json(['error' => 'You Must Login To Your Account First !!']);
        }
    }

    public function UserWishlist()
    {
        

        $id = Auth::user()->id;

        $userData = User::find($id);

        return view('frontend.dashboard.wishlist',compact('userData'));
    }


    public function GetWishListProperty()
    {
        

        $wishlist = Wishlist::with('property')->where('user_id',Auth::id())->latest()->get();

        $wishQty = wishlist::count();

        return response()->json(['wishlist' => $wishlist , 'wishQty' => $wishQty]);
    }


    public function WishListRemove($id)
    {
        
        wishlist::where('user_id',Auth::id())->where('id',$id)->delete();

        return response()->json(['success' => 'Item Removed From Your WishList Successfully']);
    }
}
