<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Accounts;
use App\Models\Blog_Article;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): View
    {
        $data = [];

        // $blog_articles = DB::table('blog_article')->get();
        $blog_articles = Blog_Article::get();

        return view('Dashboard.dashboard', ['blog_articles' => $blog_articles]);
    }

    public function testCreateRole()
    {
        $role = Role::create(['name' => 'Administrator']);

        print_r($role);
    }
}

?>
