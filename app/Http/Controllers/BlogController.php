<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    public function getArticles()
    {

        $blog = DB::table('blog')->get();

        return response()->json([
            'blog' => $blog
        ], 200);
    }

    public function getArticleBySlug($slug)
    {
        $article = DB::table('blog')->where('slug', $slug)->first();

        return response()->json([
            'article' => $article
        ], 200);
    }
    
}

