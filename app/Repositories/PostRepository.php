<?php

namespace App\Repositories;

use App\Models\{Post, Category, Serie};
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class PostRepository
{
    /**
     * Récupère la requête de base pour les posts.
     *
     * @return Builder
     */
    protected function getBaseQuery(): Builder
    {
        $adaptedReqForSqliteOrMysql = (env('DB_CONNECTION') == "mysql") ? "LEFT(body, LOCATE(' ', body, 300))" : "substr(body, 1, instr(substr(body, 300), ' '))";

        return Post::select(
            'id',
            'slug',
            'image',
            'title',
            'user_id',
            'category_id',
            'serie_id',
            'created_at'
        )
                        ->selectRaw("
                                CASE
                                    WHEN LENGTH(body) <= 300 THEN body
                                    ELSE $adaptedReqForSqliteOrMysql 
                                END AS excerpt
                            ")
                        ->with('user:id,name', 'category', 'serie')
                        ->whereActive(true)
                        ->latest();

    }

    /**
     * Récupère les posts paginés en fonction de la catégorie ou de la série.
     *
     * @param Category|null $category
     * @param Serie|null $serie
     * @return LengthAwarePaginator
     */
    public function getPostsPaginate(?Category $category, ?Serie $serie): LengthAwarePaginator
    {
        $query = $this->getBaseQuery();

        if ($category) {
            $query->whereBelongsTo($category);
        }

        if ($serie) {
            $query->whereBelongsTo($serie)->oldest();
        }

        return $query->paginate(config('app.pagination'));
    }

    /**
     * Récupère un post par son slug.
     *
     * @param string $slug
     * @return Post
     */
    public function getPostBySlug(string $slug): Post
    {
        $post = Post::with('user:id,name', 'category', 'serie')
                    ->withCount('validComments')
                    ->whereSlug($slug)
                    ->firstOrFail();

        if ($post->serie_id) {
            $post->previous = $post->parent_id ? Post::findOrFail($post->parent_id) : null;
            $post->next = Post::whereParentId($post->id)->first() ?: null;
        }

        return $post;
    }

    /**
     * Recherche les posts en fonction d'un terme de recherche.
     *
     * @param string $search
     * @return LengthAwarePaginator
     */
    public function search(string $search): LengthAwarePaginator
    {
        return $this->getBaseQuery()
                    ->where(function ($query) use ($search) {
                        $query->where('body', 'like', "%$search%")
                              ->orWhere('title', 'like', "%$search%");
                    })
                    ->paginate(config('app.pagination'));
    }

    /**
     * Génère un slug unique pour un post.
     *
     * @param string $slug
     * @return string
     */
    public function generateUniqueSlug(string $slug): string
    {
        $newSlug = $slug;
        $counter = 1;
        while (Post::where('slug', $newSlug)->exists()) {
            $newSlug = $slug . '-' . $counter;
            $counter++;
        }
        return $newSlug;
    }
}
