<?php

namespace App\Helpers;

use App\Category;
use App\Page;
use App\Post;
use App\Setting;
use App\Tag;
use App\UserLog;
use App\Visitor;
use DateTime;
use Harimayco\Menu\Facades\Menu;
use Harimayco\Menu\Models\Menus;
use Illuminate\Support\Facades\Auth;
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

    public static function listMenu(){
        $setting = Setting::where('setting_name','main_menu')->first();
        if($setting){
            $menus = Menus::find($setting->id)->items->toArray();
            
            return $menus;
        }

        return null;
        
    }

    public static function getMyIP()
	{
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		   $ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

    public static function getIpDetail($ip)
    {
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, "http://ipinfo.io/{$ip}/json"); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $output = curl_exec($ch); 
        return [
            'ip'=>$ip,
            'ip_detail'=>$output
        ];
    }

    public static function addVisitor($link = null,$ip = null)
    {
        if($link == null){
            $link = url()->current();
        }

        if($ip == null){
            $ip = Helper::getMyIP();
        }

        $cekVisitToday = Visitor::where(['link'=>$link,'ip'=>$ip])->whereDate('created_at',date('Y-m-d'))->first();

        if(!$cekVisitToday){
            Visitor::create([
                'link'=>$link,
                'ip'=>$ip,
                'ip_detail'=>Helper::getIpDetail($ip)['ip_detail']
            ]);
        }
        
        
    }

    public static function addUserLog($description,$ip = null)
    {
        
        if($ip == null){
            $ip = Helper::getMyIP();
        }
        

        UserLog::create([
            'description'=>$description,
            'user_id'=>Auth::user()->id,
            'ip'=>$ip,
            'ip_detail'=>Helper::getIpDetail($ip)['ip_detail']
        ]);
        
    }

    public static function checkAccess($access,$action)
    {
        $cek = Auth::user()->role->whereHas('access',function($w)use($access,$action){
            $w->where("accesses.access_name",$access)->where('accesses.access_action',$action);
        })->first();
        
        if(!$cek){
            return false;
        }

        return true;
        

    }

    public static function getDataByDay($dayBefore)
    {
    	$date = new DateTime(date("Y-m-d"));
        $date->modify('-'.$dayBefore.' day');
		$weekOfdays = array();
        

		for($i=0; $i < $dayBefore ; $i++){
		    $date->modify('+1 day');
		    $weekOfdays[] = [
		    	'tanggal'=>$date->format('Y-m-d'),
				'data'=>Visitor::whereDate('created_at',$date->format('Y-m-d'))->count()
		    ];
		}

		return $weekOfdays;
    }
    
    public static function getDataByMonth($monthBefore)
    {
    	$date = new DateTime(date("Y-m-d"));
        $date->modify('-'.$monthBefore.' month');
		$weekOfdays = array();
        

		for($i=0; $i < $monthBefore ; $i++){
		    $date->modify('+1 month');
		    $weekOfdays[] = [
		    	'tanggal'=>$date->format('F,Y'),
				'data'=>Visitor::whereDate('created_at',$date->format('Y-m-d'))->count()
		    ];
		}

		return $weekOfdays;
    }
    
    public static function active_class($url,$class)
    {
        if (\Request::is($url) || \Request::is($url.'/*')) { 
            return $class;
        }
    }
}
