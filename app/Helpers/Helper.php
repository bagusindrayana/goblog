<?php

namespace App\Helpers;

use App\Category;
use App\Page;
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
        $list = [];
        if(env('DB_CONNECTION') == "mysql"){
            $list = DB::select(DB::raw("SELECT YEAR(created_at) as year,
            MONTH(created_at) as month,
            MONTHNAME(created_at) month_name,
            COUNT(*) post_count
            FROM gb_posts
            WHERE status = 'Publish'
            AND deleted_at IS NULL
            GROUP BY year, MONTH(created_at)
            UNION ALL
            SELECT YEAR(created_at) as year,
                    13 as month,
                    NULL month_name,
                    COUNT(*) post_count
            FROM gb_posts
            WHERE status = 'Publish'
            AND deleted_at IS NULL
            GROUP BY year
            ORDER BY year DESC, month DESC"));
        } elseif(env('DB_CONNECTION') == "pgsql"){
            $list = DB::select(DB::raw("SELECT  EXTRACT(year FROM created_at) as year,
            EXTRACT(month FROM created_at) as month,
            to_char(created_at, 'Month') as month_name,
            COUNT(*) post_count
            FROM gb_posts
                WHERE status = 'Publish'
                AND deleted_at IS NULL
                GROUP BY year, EXTRACT(month FROM created_at),created_at
                UNION ALL
                SELECT EXTRACT(year FROM created_at) as year,
                        13 as month,
                        NULL month_name,
                        COUNT(*) post_count
                FROM gb_posts
                WHERE status = 'Publish'
                AND deleted_at IS NULL
                GROUP BY year,created_at
                ORDER BY year DESC, month DESC"));
        }
        

        return $list;
    }

    public static function listPage()
    {
        $page = Page::where('status','Publish')->orderBy('created_at')->get();
        return $page;
    }
}
