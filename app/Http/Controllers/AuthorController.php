<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\Setting;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class AuthorController extends Controller
{
    public function index(Request $request) 
    {
        return view('back.pages.home');
    }

    public function ResetForm(Request $request, $token=null) 
    {
        $data = [
            "pageTitle"=>"Reset Password"
        ];
        return view('back.pages.auth.reset_password', $data)->with(['token'=>$token, 'email'=>$request->email]);
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('author.login');
    }

    public function ChangeProfilePicture(Request $request)
    {
        $user = User::find(auth('web')->id());
        $path = 'back/dist/img/author/';
        $file = $request->file('file');
        $old_picture = $user->getAttributes()['picture'];
        $file_path = $path.$old_picture;
        $new_picture_name = 'AIMG'.$user->id.time().rand(1, 10000).'.jgp';

        if($old_picture != null && File::exists(public_path($file_path))) {
            File::delete(public_path($file_path));
        }

        $upload = $file->move(public_path($path), $new_picture_name);
        if($upload) {
            $user->update([
                'picture' => $new_picture_name
            ]);
            return response()->json(['status'=>1, 'msg'=>'Your profile picture has been updated successfully.']);
        } else {
            return response()->json(['status'=>0, 'msg'=>'Something went wrong.']);
        }
    }

    public function ChangeBlogLogo(Request $request)
    {
        $settings = Setting::find(1);
        $logo_path = 'back/dist/img/logo-favicon/';
        $old_logo = $settings->getAttributes()['blog_logo'];
        $file = $request->file('blog_logo');
        $file_name = time().'_'.rand(1, 10000).'_demoblog_logo.png';

        if($request->hasFile('blog_logo')) {
            if($old_logo != null && File::exists(public_path($logo_path.$old_logo))) {
                File::delete(public_path($logo_path.$old_logo));
            } 

            $upload = $file->move(public_path($logo_path), $file_name);

            if($upload) {
                $settings->update([
                    'blog_logo' => $file_name,
                ]);
                return response()->json(['staust'=>1, 'message'=>'Logo has been updated successfully']);
            } else {
                return response()->json(['staust'=>0, 'error'=>'Something went wrong']);
            }
        }
    }

    public function createPost(Request $request)
    {
        $request->validate([
            'post_title'=>'required|unique:posts,post_title',
            'post_content'=>'required',
            'post_category'=>'required|exists:sub_categories,id',
            'featured_image'=>'required|mimes:jpeg,jpg,png|max:1024'
        ]);

        if($request->hasFile('featured_image')) {

            $path = "images/post_images/";
            $file = $request->file('featured_image');
            $fileName = $file->getClientOriginalName();
            $new_filename = time().'_'.$fileName;

            $upload = Storage::disk('public')->put($path.$new_filename, (string) file_get_contents($file));

            $post_thumbnail_path = $path.'thumbnails';
            
            if( !Storage::disk('public')->exists($post_thumbnail_path) ) {
                Storage::disk('public')->makeDirectory($post_thumbnail_path, 0755, true, true);
            } 

            // Create Square thumbnail
            Image::make( storage_path('app/public/'.$path.$new_filename) )
                ->fit(200, 200)
                ->save( storage_path('app/public/'.$path.'thumbnails/'.'thumb_'.$new_filename) );

            // Create Resized image
            Image::make( storage_path('app/public/'.$path.$new_filename) )
                ->fit(500, 350)
                ->save( storage_path('app/public/'.$path.'thumbnails/'.'resized_'.$new_filename) );
            

            if($upload) {
                $post = new Post();
                $post->author_id = auth()->id();
                $post->category_id = $request->post_category;
                $post->post_title = $request->post_title;
                // $post->post_slug = Str::slug($request->post_title);
                $post->post_content = $request->post_content;
                $post->featured_image = $new_filename;
                $post->post_tags = $request->post_tags;
                $saved = $post->save();

                if($saved) {
                    return response()->json(['code'=>1, 'msg'=>'New post added successfully']);
                } else {
                    return response()->json(['code'=>3, 'msg'=>'Something went wrong while adding post']);
                }
            } else {
                return response()->json(['code'=>3, 'msg'=>'Something went wrong while uploading featured image']);
            }
        }
    }

    public function editPost(Request $request)
    {
        if(!request()->post_id) {
            return abort(404);
        } else {
            $post = Post::find(request()->post_id);
            $data = [
                'post' => $post,
                'pageTitle' => 'Edit Post'
            ];
            return view('back.pages.edit_post', $data);
        }
    }

    public function updatePost(Request $request)
    {
        if($request->hasFile('featured_image')) {
            $request->validate([
                'post_title'=>'required|unique:posts,post_title,'.$request->post_id,
                'post_content'=>'required',
                'post_category'=>'required|exists:sub_categories,id',
                'featured_image'=>'required|mimes:jpeg,jpg,png|max:1024'
            ]);

            $path = "images/post_images/";
            $file = $request->file('featured_image');
            $fileName = $file->getClientOriginalName();
            $new_filename = time().'_'.$fileName;

            $upload = Storage::disk('public')->put($path.$new_filename, (string) file_get_contents($file));

            $post_thumbnail_path = $path.'thumbnails';
            
            if( !Storage::disk('public')->exists($post_thumbnail_path) ) {
                Storage::disk('public')->makeDirectory($post_thumbnail_path, 0755, true, true);
            }

            // Create Square thumbnail
            Image::make( storage_path('app/public/'.$path.$new_filename) )
                ->fit(200, 200)
                ->save( storage_path('app/public/'.$path.'thumbnails/'.'thumb_'.$new_filename) );

            // Create Resized image
            Image::make( storage_path('app/public/'.$path.$new_filename) )
                ->fit(500, 350)
                ->save( storage_path('app/public/'.$path.'thumbnails/'.'resized_'.$new_filename) );

            if($upload) {
                $old_post_image = Post::find($request->post_id)->featured_image;

                if($old_post_image != null && Storage::disk('public')->exists($path.$old_post_image)) {
                    Storage::disk('public')->delete($path.$old_post_image);

                    if(Storage::disk('public')->exists($path.'thumbnails/resized_'.$old_post_image)) {
                        Storage::disk('public')->delete($path.'thumbnails/resized_'.$old_post_image);
                    }

                    if(Storage::disk('public')->exists($path.'thumbnails/thumb_'.$old_post_image)) {
                        Storage::disk('public')->delete($path.'thumbnails/thumb_'.$old_post_image);
                    }
                }


                $post = Post::find($request->post_id);
                $post->category_id = $request->post_category;
                $post->post_slug = null;
                $post->post_title = $request->post_title;
                $post->post_content = $request->post_content;
                $post->featured_image = $new_filename;
                $post->post_tags = $request->post_tags;
                $saved = $post->save();
                if($saved) {
                    return response()->json(['code'=>1, 'msg'=>'New post added successfully']);
                } else {
                    return response()->json(['code'=>3, 'msg'=>'Something went wrong while adding post']);
                }
            } else {
                return response()->json(['code'=>3, 'msg'=>'Something went wrong while updating featured image']);
            }

        } else {
            $request->validate([
                'post_title'=>'required|unique:posts,post_title,'.$request->post_id,
                'post_content'=>'required',
                'post_category'=>'required|exists:sub_categories,id',
            ]);

            $post = Post::find($request->post_id);
            $post->category_id = $request->post_category;
            $post->post_slug = null;
            $post->post_content = $request->post_content;
            $post->post_title = $request->post_title;
            $post->post_tags = $request->post_tags;
            $saved = $post->save();

            if($saved) {
                return response()->json(['code'=>1, 'msg'=>'Post updated successfully']);
            } else {
                return response()->json(['code'=>3, 'msg'=>'Something went wrong.']);
            }
        }
    }
}