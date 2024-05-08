<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function test()
    {
        echo 'Hello World!';
    }

    public function index(): View
    {
        $data = [];

        $blog_articles = DB::table('blog_article')->get();

        return view('dashboard', ['blog_articles' => $blog_articles]);
    }
}

?>