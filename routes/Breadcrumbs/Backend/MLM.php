<?php

Breadcrumbs::register('aa', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.dashboard');
    $breadcrumbs->push('AA', route('frontend.user.demandside.index'));
});

Breadcrumbs::register('bb', function ($breadcrumbs) {
    $breadcrumbs->parent('aa');
    $breadcrumbs->push('BB', route('frontend.user.demandside.index'));
});
