<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function show(Post $post)
    {
        $relatedPosts = Post::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->whereHas('tags', function ($query) use ($post) {
                $query->whereIn('tags.id', $post->tags->pluck('id'));
            })
            ->withCount(['tags' => function ($query) use ($post) {
                $query->whereIn('tags.id', $post->tags->pluck('id'));
            }])
            ->orderBy('tags_count', 'desc')
            ->orderBy('published_at', 'desc')
            ->take(4)
            ->get();
        if ($relatedPosts->count() < 4) {
            $relatedPosts2 = Post::where('is_published', true)
                ->where('id', '!=', $post->id)
                /*->whereHas('catefory_id', $post->category_id)*/
                ->whereNotIn('id', $relatedPosts->pluck('id'))
                ->orderBy('published_at', 'desc')
                ->take(4 - $relatedPosts->count())
                ->get();
            $relatedPosts = $relatedPosts->merge($relatedPosts2);
        }
        return view('posts.show', compact('post', 'relatedPosts'));
    }
}
