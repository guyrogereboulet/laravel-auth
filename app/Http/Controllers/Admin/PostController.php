<?php

namespace App\Http\Controllers\Admin;
use App\Post;
use App\Tag;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    private $validateRules;

    public function __construct()
    {
    
        $this->validateRules = [
            'title' => 'required|string|max:255',
            'body' => 'required|string'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        //Con questo chiamo tutta la tabella Post
        // $posts = Post::all();

        //Questa chiamata al database mi permette di vedere tutti i post per ID autenticato
        $posts = Post::where('user_id', Auth::id())->get();

        return view('admin.posts.index', compact('posts'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag:: all();
        return view('admin.posts.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     
     
    public function store(Request $request)
    {
        $idUser = Auth::user()->id;
        

        $request->validate($this->validateRules);
        $data = $request->all();
        $path = Storage::disk('public')->put('img', $data['img']);
        

        $newPost = new Post;
        $newPost->img = $path;
        $newPost->title = $data['title'];
        $newPost->body = $data['body'];
        $newPost->user_id = $idUser;
        $newPost->slug = Str::finish(Str::slug($newPost->title), rand(1, 1000000));
        
        $saved = $newPost->save();

        
        if(!$saved) {
            return redirect()->back();
        }

        $tags = $data['tags'];
        if(!empty($tags)) {
            $newPost->tags()->attach($tags);
        }

    

        return redirect()->route('admin.posts.show', $newPost->slug);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $post = Post::where('slug', $slug)->first();

        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $post = Post::where('slug', $slug)->first();
        $tags = Tag::all();
        // $images = Image::all();

        $data = [
            'tags' => $tags,
            'post' => $post,
            // 'images' => $images
        ];
        
        return view('admin.posts.edit', $data);

    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //Prendo l'ID dell'utente autenticato
        $idUser = Auth::user()->id;
        //Se il Post esiste
        if(empty($post)){
            abort(404);
        }
        //L'ID del l'User è diverso dal l'ID autenticato
        if($post->user->id != $idUser){
            abort(404);
        }

        $request->validate($this->validateRules);
        $data = $request->all();

        $post->title = $data['title'];
        $post->body = $data['body'];
        $post->slug = Str::finish(Str::slug($post->title), rand(1, 1000000));
        //Usiamo carbon per aggiornare la data
        $post->updated_at = Carbon::now();

        $updated = $post->update();

        if (!$updated) {
            return redirect()->back();
        }

        return redirect()->route('admin.posts.show', $post->slug);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if(empty($post)) {
            abort(404);
        }

        $post->delete();
        //Appena viene cancellato l’item ritorniamo alla pagina index
        return redirect()->route('admin.posts.index');

    }
}
