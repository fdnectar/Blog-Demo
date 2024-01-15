<?php
    use App\Models\Setting;
    use App\Models\Post;
    use App\Models\SubCategory;
    use Illuminate\Support\Str;
    use Carbon\Carbon;

    if(!function_exists('blogInfo')) {
        function blogInfo(){
            return Setting::find(1);
        }
    }

    
    // Date Format e.g- December 29, 2023
    if(!function_exists('date_formatter')) {
        function date_formatter($date) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $date)->isoFormat('LL');
        }
    }


    // Strip words - removes html tags from given string
    if(!function_exists('words')) {
        function words($value, $words = 15, $end = "...") {
            return Str::words(strip_tags($value), $words, $end);
        }
    }


    // check if user has internet connection/online
    if(!function_exists('isOnline')) {
        function isOnline($site = "https://youtube.com") {
            if(@fopen($site, "r")) {
                return true;
            } else {
                return false;
            }
        }
    }


    // Reading outline duration
    if(!function_exists('readDuration')) {
        function readDuration(...$text) {
            Str::macro('timeCounter', function($text) {
                $totalWords = str_word_count(implode(" ", $text));
                $minutesToRead = round($totalWords/200);
                return (int)max(1, $minutesToRead);
            });
            return Str::timeCounter($text);
        }
    }


    //Single latest post
    if(!function_exists('single_latest_post')) {
        function single_latest_post() {
            return Post::with('author')
                        ->with('subcategory')
                        ->limit(1)
                        ->orderBy('created_at', 'desc')
                        ->first();
        }
    }


    // 6 latest post
    if(!function_exists('latest_home_6post')) {
        function latest_home_6post() {
            return Post::with('author')
                        ->with('subcategory')
                        ->skip(1)
                        ->limit(6)
                        ->orderBy('created_at', 'desc')
                        ->get();
        }
    }


    // Display random post
    if(!function_exists('recommended_post')) {
        function recommended_post() {
            return Post::with('author')
                        ->with('subcategory')
                        ->limit(4)
                        ->inRandomOrder('created_at', 'desc')
                        ->get();
        }
    }


    // Display categories with no of post
    if(!function_exists('categories')) {
        function categories() {
            return SubCategory::whereHas('posts')
                        ->with('posts')
                        ->orderBy('sub_category_name', 'asc')
                        ->get();
        }
    }


    // Display latest post on sidebar
    if(!function_exists('sidebar_latest_post')) {
        function sidebar_latest_post($except = null, $limit = 4) {
            return Post::where('id', '!=', $except)
                        ->limit($limit)
                        ->orderBy('created_at', 'desc')
                        ->get();
        }
    }

    // All tags
    if(!function_exists('all_tags')) {
        function all_tags() {
            return Post::where('post_tags', '!=', null)->distinct()->pluck('post_tags')->join(',');
        }
    }


?>