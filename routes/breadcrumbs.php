<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Spatie\Permission\Models\Role;

Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push(__('dashboard'), route('dashboard'));
});

Breadcrumbs::for('send-email.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Send Email', route('send-email.index'));
});
Breadcrumbs::for('directory.importView', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Import Emails', route('directory.importView'));
});


Breadcrumbs::for('document-upload.document-index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Documents Upload', route('document-upload.document-index'));
});

Breadcrumbs::for('send-email.history', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('History', route('send-email.history'));

});
