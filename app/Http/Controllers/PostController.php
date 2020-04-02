<?php


namespace App\Http\Controllers;
use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        
        return view('guest.posts.index', compact('posts'));
    }

    public function show($slug)
    {
        $posts = Post::where('slug',$slug)->get();
        
        return view('guest.posts.index', compact('posts'));

        if(empty($posts)){
           abort(404);
        }

        return view('guest.posts.show', compact('posts'));
    }

}
