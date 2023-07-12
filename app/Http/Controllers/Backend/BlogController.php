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
use App\Models\PackagePlan;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PropertyMessage;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Comment;

class BlogController extends Controller
{
    
    public function AllBlogCategory()
    {
        
        $category = BlogCategory::latest()->get();

        return view('backend.category.blog_category',compact('category'));
    }



    public function StoreBlogCategory(Request $request){

        

        BlogCategory::insert([

            'category_name' => $request->category_name,

            'category_slug' => strtolower(str_replace(' ','-',$request->category_name)),

            
        ]);

        $notification = array(

                'message' => 'Blog Category Created Successfully!',
                'alert-type' => 'success'
            );

        return redirect()->route('all.blog.category')->with($notification);
    }


    public function EditBlogCategory($id)
    {
        $categories = BlogCategory::findOrFail($id);

        return response()->json($categories);
    }



    public function UpdateBlogCategory(Request $request){

        $cat_id = $request->cat_id;

        BlogCategory::findOrFail($cat_id)->update([

            'category_name' => $request->category_name,

            'category_slug' => strtolower(str_replace(' ','-',$request->category_name)),

            
        ]);

        $notification = array(

                'message' => 'Blog Category Updated Successfully!',
                'alert-type' => 'success'
            );

        return redirect()->route('all.blog.category')->with($notification);
    }




    public function DeleteBlogCategory($id)
    {
        BlogCategory::findOrFail($id)->delete();

        $notification = array(

                'message' => 'Blog Category Deleted Successfully!',
                'alert-type' => 'success'
            );

        return redirect()->back()->with($notification);
    }


    public function AllPost()
    {
        $post = BlogPost::latest()->get();

        return view('backend.post.all_post',compact('post'));
    }


    public function AddPost()
    {
        $blogcat = BlogCategory::latest()->get();

        return view('backend.post.add_post',compact('blogcat'));
    }



    public function StorePost(Request $request){



        $image = $request->file('post_image');

        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();

        Image::make($image)->resize(370,250)->save('upload/post/'.$name_gen);

        $save_url = 'upload/post/'.$name_gen;


        BlogPost::insert([


            'blogcat_id' => $request->blogcat_id,

            'user_id' => Auth::user()->id,

            'post_title' => $request->post_title,

            'post_slug' => strtolower(str_replace(' ','-',$request->post_title)),

            'short_descp' => $request->short_descp,

            'long_descp' => $request->long_descp,

            'post_tags' => $request->post_tags,

            'post_image' => $save_url,

            'created_at' => Carbon::now(),
        ]);


            $notification = array(

            'message' => 'Blog Post Created Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.post')->with($notification);


    }


    public function EditPost($id)
    {

        $blogcat = BlogCategory::latest()->get();
        $post = BlogPost::findOrFail($id);

        return view('backend.post.edit_post',compact('post','blogcat'));
    }



    public function UpdatePost(Request $request){

        $post_id = $request->id;


        if ($request->file('post_image')) {
            

            $image = $request->file('post_image');

            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();

            Image::make($image)->resize(370,250)->save('upload/post/'.$name_gen);

            $save_url = 'upload/post/'.$name_gen;


            BlogPost::findOrFail($post_id)->update([


                'blogcat_id' => $request->blogcat_id,

                'user_id' => Auth::user()->id,

                'post_title' => $request->post_title,

                'post_slug' => strtolower(str_replace(' ','-',$request->post_title)),

                'short_descp' => $request->short_descp,

                'long_descp' => $request->long_descp,

                'post_tags' => $request->post_tags,

                'post_image' => $save_url,

                'created_at' => Carbon::now(),
            ]);


                $notification = array(

                'message' => 'Blog Post Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.post')->with($notification);



        }else{

            BlogPost::findOrFail($post_id)->update([


                'blogcat_id' => $request->blogcat_id,

                'user_id' => Auth::user()->id,

                'post_title' => $request->post_title,

                'post_slug' => strtolower(str_replace(' ','-',$request->post_title)),

                'short_descp' => $request->short_descp,

                'long_descp' => $request->long_descp,

                'post_tags' => $request->post_tags,


                'created_at' => Carbon::now(),
            ]);


                $notification = array(

                'message' => 'Blog Post Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.post')->with($notification);




        }

        
    }


    public function DeletePost($id)
    {
        
        $post = BlogPost::findOrFail($id);

        $img = $post->post_image;

        unlink($img);

        BlogPost::findOrFail($id)->delete();


        $notification = array(

                'message' => 'Blog Post Deleted Successfully',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);

    }




    public function BlogDetails($slug)
    {
        $blog = BlogPost::where('post_slug',$slug)->first();

        $tags = $blog->post_tags;

        $tags_all = explode(',',$tags);

        $bcategory = BlogCategory::latest()->get();

        $dpost = BlogPost::latest()->limit(3)->get();

        return view('frontend.blog.blog_details',compact('blog','tags_all','bcategory','dpost'));
    }


    public function BlogCatList($id)
    {
        
        $blog = BlogPost::where('blogcat_id',$id)->paginate(6);

        $breadcat = BlogCategory::where('id',$id)->first();

        $bcategory = BlogCategory::latest()->get();

        $dpost = BlogPost::latest()->limit(3)->get();


        return view('frontend.blog.blog_cat_list',compact('blog','breadcat','bcategory','dpost'));
    }


    public function BlogList()
    {
        
        $blog = BlogPost::latest()->paginate(6);

        $bcategory = BlogCategory::latest()->get();

        $dpost = BlogPost::latest()->limit(3)->get();


        return view('frontend.blog.blog_list',compact('blog','bcategory','dpost'));
    }




    public function StoreComment(Request $request)
    {
        $pid = $request->post_id;

        Comment::insert([

            'user_id' => Auth::user()->id,

            'post_id' => $pid,

            'parent_id' => null,

            'subject' => $request->subject,

            'message' => $request->message,

            'created_at' => Carbon::now(),

        ]);


        $notification = array(

                'message' => 'Comment Added Successfully',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);

    }



    public function AdminBlogComment()
    {
        
        $comment = Comment::where('parent_id',null)->latest()->get();


        return view('backend.comment.comment_all',compact('comment'));
    }


    public function AdminCommentReply($id)
    {
        $comment = Comment::where('id',$id)->first();

        return view('backend.comment.reply_comment',compact('comment'));
    }


    public function ReplyMessage(Request $request)
    {
        $id = $request->id;

        $user_id = $request->user_id;

        $post_id = $request->post_id;



        Comment::insert([

            'user_id' => $user_id,

            'post_id' => $post_id,

            'parent_id' => $id,

            'subject' => $request->subject,

            'message' => $request->message,

            'created_at' => Carbon::now(),

        ]);


        $notification = array(

                'message' => 'Comment Replyed Successfully',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);

    }
}
