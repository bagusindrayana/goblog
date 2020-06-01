<?php

namespace App\Helpers;

use App\Category;
use App\Post;
use App\Tag;
use Illuminate\Support\Facades\DB;

class Helper
{

    public static function makeSlug($normal, $model)
    {
        $slug = strtolower(str_replace(' ', '-', str_replace('/', '-', $normal)));
        if ($model) {
            $cek = $model->where('slug', $slug)->orderBy('created_at', 'DESC')->first();
            if ($cek) {
                $slug .= "-" . ($cek->id + 1);
            }
        }
        return $slug;
    }

    public static function categoryList($limit = 0)
    {
        if ($limit == 0) {
            return Category::orderBy('name', 'ASC')->pluck('name', 'slug');
        } else {
            return Category::orderBy('name', 'ASC')->limit($limit)->pluck('name', 'slug');
        }
    }

    public static function tagList($limit = 0)
    {
        if ($limit == 0) {
            return Tag::orderBy('name', 'ASC')->pluck('name', 'slug');
        } else {
            return Tag::orderBy('name', 'ASC')->limit($limit)->pluck('name', 'slug');
        }
    }

    public static function postList($paginate = true, $limit = 5, $search = null, $where = null)
    {
        $s = request()->s ?? ($search ?? "");
        $posts = Post::orderBy('created_at', 'DESC')->where('status', 'Publish')->where(function ($q) use ($s) {
            $q->where('title', 'LIKE', '%' . $s . '%')->orWhere('content', 'LIKE', '%' . $s . '%');
        });

        if ($where != null) {
            $posts = $posts->where($where);
        }

        if ($paginate) {
            return $posts->paginate($limit);
        } else if ($limit > 0) {
            return $posts->limit($limit)->get();
        } else {
            return $posts->get();
        }
    }

    public static function archiveList()
    {
        $list = DB::select(DB::raw("SELECT YEAR(created_at) year,
        MONTH(created_at) month,
        MONTHNAME(created_at) month_name,
        COUNT(*) post_count
        FROM gb_posts
        WHERE status = 'Publish'
        AND deleted_at IS NULL
        GROUP BY year, MONTH(created_at)
        UNION ALL
        SELECT YEAR(created_at) year,
                13 month,
                NULL month_name,
                COUNT(*) post_count
        FROM gb_posts
        WHERE status = 'Publish'
        AND deleted_at IS NULL
        GROUP BY year
        ORDER BY year DESC, month DESC"));

        return $list;
    }
}
