<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Volt::route('templates', 'admin.templates.index')->name('admin.templates.index');
    Volt::route('templates/create', 'admin.templates.create')->name('admin.templates.create');
    Volt::route('templates/{pageTemplate}/edit', 'admin.templates.edit')->name('admin.templates.edit');

    Volt::route('pages', 'admin.pages.index')->name('admin.pages.index');
    Volt::route('pages/create', 'admin.pages.create')->name('admin.pages.create');
    Volt::route('pages/{page}/edit', 'admin.pages.edit')->name('admin.pages.edit');
});

require __DIR__.'/settings.php';

Route::get('/{slug}', [PageController::class, 'show'])->name('page.show');
