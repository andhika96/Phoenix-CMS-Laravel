<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use Diglactic\Breadcrumbs\Breadcrumbs;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('awesome_admin', function (BreadcrumbTrail $trail) {
    $trail->push('Awesome Admin', route('admin.awesome_admin'));
});

// Home > Blog
Breadcrumbs::for('config', function (BreadcrumbTrail $trail) {
    $trail->parent('awesome_admin');
    $trail->push('Site Config', route('admin.awesome_admin.config'));
});