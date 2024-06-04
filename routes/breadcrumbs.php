<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use Diglactic\Breadcrumbs\Breadcrumbs;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Awesome Admin
Breadcrumbs::for('awesome_admin', function (BreadcrumbTrail $trail) 
{
    $trail->push('Awesome Admin', route('admin.awesome_admin'));
});

// Awesome Admin > Config
Breadcrumbs::for('awesome_admin.config', function (BreadcrumbTrail $trail) 
{
    $trail->parent('awesome_admin');
    $trail->push('Site Config', route('admin.awesome_admin.config'));
});

// Awesome Admin > Role
Breadcrumbs::for('awesome_admin.role', function (BreadcrumbTrail $trail) 
{
    $trail->parent('awesome_admin');
    $trail->push('Manage Role', route('admin.awesome_admin.role'));
});

// Awesome Admin > Permission
Breadcrumbs::for('awesome_admin.permission', function (BreadcrumbTrail $trail) 
{
    $trail->parent('awesome_admin');
    $trail->push('Manage Permission', route('admin.awesome_admin.permission'));
});