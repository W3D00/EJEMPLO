<?php

namespace App\Http\Controllers\Admin;

use App\Events\UploadedImage;
use App\Http\Controllers\Controller;
use App\Jobs\ResizeImage;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class PostController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:manage posts'),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::latest('id')
            ->where('user_id', auth()->id())
            ->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts',
            'category_id' => 'required|exists:categories,id',
        ]);

        $data['user_id'] = auth('web')->id();

        $post = Post::create($data);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Post creado!',
            'text' => 'el post ha sido creado correctamente',
        ]);
        
        return redirect()->route('admin.posts.edit', $post);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        Gate::authorize('author', $post);
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            /*'slug' => 'required|string|max:255|unique:posts,slug,' . $post->id,*/
            'slug' => [
                Rule::requiredIf( function () use ($post){
                    return !$post->published_at;
                }),
                'string',
                'max:255',
                'unique:posts,slug,' . $post->id,               
            ],
            'image' => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,id',
            'excerpt' => 'required_if:is_published,1|string',
            'content' => 'required_if:is_published,1|string',
            'tags' => 'array',
            'is_published' => 'boolean',
        ]); 
        
        if ($request->hasFile('image')) {
            if ($post->image_path) {
                Storage::delete($post->image_path);
            }
            $extension = $request->image->extension();
            $nameFile = $post->slug . '.' . $extension;

            while(Storage::exists('posts/' . $nameFile)) {
                $nameFile = str_replace('.' . $extension, '-copia.' . $extension, $nameFile);
            }
            $data['image_path'] = Storage::putFileAs('posts', $request->image, $nameFile);

            //ResizeImage::dispatch($data['image_path']);

            UploadedImage::dispatch($data['image_path']);
            
        }

        $post->update($data);

        $tags = [];
        foreach ($request->tags ?? [] as $tag) {
            $tags[] = Tag::firstOrCreate(['name' => $tag]);
        }

        $post->tags()->sync($tags);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Post actualizado!',
            'text' => 'el post ha sido actualizado correctamente',
        ]);
        
        return redirect()->route('admin.posts.edit', $post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('author', $post);
        /*if($post->image_path) {
            Storage::delete($post->image_path);
        }*/
        $post->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Post eliminado!',
            'text' => 'el post ha sido eliminado correctamente',
        ]);
        
        return redirect()->route('admin.posts.index');
    }
}
