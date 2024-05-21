<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Account;
use App\Models\BlogArticle;

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
        // $blog_articles = BlogArticle::get();

        // $accounts = Accounts::get();

        return view('Dashboard.dashboard', ['accounts' => $blog_articles]);
    }

    public function testCreateRole()
    {
        $role = Role::create(['name' => 'Administrator']);

        print_r($role);
    }

    public function listdata()
    {
        $blog_articles = BlogArticle::get();

        return response()->json(['status' => 'success', 'message' => 'Success', 'data' => $blog_articles]);
    }

    public function test()
    {
        $accounts = Account::with('roles')->where('email', 'andhika.adhitia96@gmail.com')->get();

        $user = Account::where('email', 'andhika.adhitia96@gmail.com')->first();
        // $user->assignRole('Administrator');
        // $user->syncRoles(['Administrator']);
        // $user->save();

        // $role = Role::create(['name' => 'General Member']);
        // $permission = Permission::create(['name' => 'edit articles']);

        // $role = Role::findByName('Administrator');
        // $role->givePermissionTo('edit articles');

        if ($user->isAdmin())
        {
            return response()->json(['status' => 'success', 'message' => 'Success', 'data' => $accounts]);
        }
        else
        {
            return response()->json(['status' => 'failed', 'message' => 'You are not Admin!', 'data' => '']);
        }
    }
}

?>
