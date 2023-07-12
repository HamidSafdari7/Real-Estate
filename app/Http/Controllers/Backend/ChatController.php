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
use App\Models\ChatMessage;
use App\Models\SiteSetting;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PermissionsExport;
use App\Imports\PermissionImport;

class ChatController extends Controller
{
    
    public function SendMsg(Request $request)
    {
        $request->validate([

            'msg' => 'required'
        ]);

        ChatMessage::create([

            'sender_id' => Auth::user()->id,

            'reciever_id' => $request->reciever_id,

            'msg' => $request->msg,

            'created_at' => Carbon::now(),
        ]);

        return response()->json(['message' => 'Message Sent Successfully']);
    }



    public function GetAllUsers()
    {
        $chats = ChatMessage::orderBy('id','DESC')->where('sender_id',auth()->id())->orWhere('reciever_id',auth()->id())->get();

        $users = $chats->flatMap(function($chat){

            if ($chat->sender_id === auth()->id()) {
                
                return[$chat->sender, $chat->reciever];
            }

            return [$chat->reciever , $chat->sender];
        })->unique();

        return $users;
    }



    public function UserMsgById($userId)
    {
        
        $user = User::find($userId);

        if ($user) {
            
            $messages = ChatMessage::where(function($q) use ($userId){

                $q->where('sender_id',auth()->id());

                $q->where('reciever_id',$userId);
            })->orWhere(function($q) use ($userId){

                $q->where('sender_id',$userId);

                $q->where('reciever_id',auth()->id());
            })->with('user')->get();

            return response()->json([

                'user' => $user,

                'messages' => $messages,

            ]);
        }else{

            abort(404);
        }
    }


    public function AgentLiveChat()
    {
        
        return view('agent.message.live_chat');
    }
}
