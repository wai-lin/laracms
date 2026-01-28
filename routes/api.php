<?php

use App\Http\Controllers\Api\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

Route::prefix('pages')->group(function () {
    // GET /api/pages - List all published pages (paginated)
    // Query params: per_page, template
    Route::get('/', [PageController::class, 'index'])->name('api.pages.index');

    // GET /api/pages/{slug} - Get a single page by slug
    Route::get('/{slug}', [PageController::class, 'show'])->name('api.pages.show');
});
