<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
|--------------------------------------------------------------------------
| Notifications routes
|--------------------------------------------------------------------------
|
*/
Route::resource('notifications', \App\Http\Controllers\User\NotificationController::class)
    ->except(['create', 'edit', 'store']);

/*
|--------------------------------------------------------------------------
| Users routes
|--------------------------------------------------------------------------
|
*/
Route::get('users/team-members', [\App\Http\Controllers\User\UserController::class, 'teamMember'])
    ->name('admin.team_members');

Route::get('users/response-time-by-roles', [\App\Http\Controllers\User\UserController::class, 'getAverageResponseTimeByRole'])
    ->name('admin.response-time-by-role');

Route::get('users/{user}/response-time', [\App\Http\Controllers\User\UserController::class, 'responseTime'])
    ->name('admin.response-time');

Route::get('users/pending-tasks', [\App\Http\Controllers\User\UserController::class, 'pendingTasks'])
    ->name('admin.pending-tasks');

Route::get('users/{user}/statistics', [\App\Http\Controllers\User\UserController::class, 'statistics'])
    ->name('admin.statistics');

Route::resource('users', \App\Http\Controllers\User\UserController::class)
    ->except(['create', 'edit']);

/*
|--------------------------------------------------------------------------
| Inquiries routes
|--------------------------------------------------------------------------
|
*/
Route::prefix('inquiries/{inquiry}')
    ->name('inquiries.')
    ->group(function () {
        // todo: reform inquiry controller and make related controller for each action
        Route::get('/log', [\App\Http\Controllers\Inquiry\InquiryController::class, 'log'])
            ->name('log');

        /*
        |--------------------------------------------------------------------------
        | Inquiry statuses
        |--------------------------------------------------------------------------
        |
        */
        Route::get('/statuses', [\App\Http\Controllers\Inquiry\StatusController::class, 'index'])
            ->name('statuses.index');

        Route::post('/statuses', [\App\Http\Controllers\Inquiry\StatusController::class, 'store'])
            ->name('statuses.store');
        /*
        |--------------------------------------------------------------------------
        | Inquiry assign
        |--------------------------------------------------------------------------
        |
        */
        Route::post('/assign', [\App\Http\Controllers\Inquiry\InquiryController::class, 'assign'])
            ->name('assign');

        /*
        |--------------------------------------------------------------------------
        | Inquiry commenting
        |--------------------------------------------------------------------------
        |
        */
        Route::post('/commenting', [\App\Http\Controllers\Inquiry\InquiryController::class, 'storeComment'])
            ->name('commenting');

        /*
        |--------------------------------------------------------------------------
        | Inquiry actions
        |--------------------------------------------------------------------------
        |
        */
        Route::post('/actions', [\App\Http\Controllers\Inquiry\InquiryController::class, 'performAction'])
            ->name('actions');

        /*
        |--------------------------------------------------------------------------
        | Inquiry document routs
        |--------------------------------------------------------------------------
        |
        */
        Route::get('/docs/{collection_name}', [\App\Http\Controllers\Inquiry\DocumentController::class, 'index'])
            ->name('documents.index')
            ->whereIn('collection_name', \App\Enums\InquiryDocsCollectionEnum::values());

        Route::post('/docs', [\App\Http\Controllers\Inquiry\DocumentController::class, 'store'])
            ->name('documents.store');

        Route::delete('/docs/{media}', [\App\Http\Controllers\Inquiry\DocumentController::class, 'destroy'])
            ->name('documents.destroy');

        /*
        |--------------------------------------------------------------------------
        | Inquiry calculation
        |--------------------------------------------------------------------------
        |
        */
        Route::resource('calculations', \App\Http\Controllers\Inquiry\CalculationController::class)
            ->except(['create', 'edit']);

        /*
        |--------------------------------------------------------------------------
        | Inquiry future dues
        |--------------------------------------------------------------------------
        |
        */
        Route::post(
            'future-dues/{futureDue}/payment-status',
            [\App\Http\Controllers\Inquiry\FutureDueController::class, 'paymentStatus']
        )
            ->name('future-dues.paymentStatus');

        Route::resource('future-dues', \App\Http\Controllers\Inquiry\FutureDueController::class)
            ->except(['create', 'edit']);
    });

Route::resource('inquiries', \App\Http\Controllers\Inquiry\InquiryController::class)
    ->except(['create', 'edit']);

/*
|--------------------------------------------------------------------------
| Dashboard routes
|--------------------------------------------------------------------------
|
*/
Route::prefix('dashboard')
    ->name('dashboard')
    ->group(function () {
        Route::get('stats/charts', [\App\Http\Controllers\Admin\DashboardController::class, 'getChartsData'])
            ->name('stats.charts');

        Route::get('stats/inquiries', [\App\Http\Controllers\Admin\DashboardController::class, 'inquiriesStats'])
            ->name('stats.inquiries');


    });

/*
|--------------------------------------------------------------------------
| Reminders routes
|--------------------------------------------------------------------------
|
*/
Route::resource('reminders', \App\Http\Controllers\Admin\ReminderController::class)
    ->except(['create', 'edit']);

/*
|--------------------------------------------------------------------------
| Suppliers routes
|--------------------------------------------------------------------------
|
*/
Route::resource('suppliers', \App\Http\Controllers\Admin\SupplierController::class)
    ->except(['create', 'edit']);

/*
|--------------------------------------------------------------------------
| Accounting routes
|--------------------------------------------------------------------------
|
*/
Route::get(
    'accounting/users/{meta}',
    [
        \App\Http\Controllers\Accounting\AccountingController::class,
        'getByMeta'
    ]
);

Route::patch(
    'accounting/future_dues/{payment}',
    [
        \App\Http\Controllers\Accounting\AccountingController::class,
        'changePaidStatus'
    ]
);

Route::resource('accounting/{tab_name}', \App\Http\Controllers\Accounting\AccountingController::class)
    ->only(['index', 'store']);


/*
|--------------------------------------------------------------------------
| Shipping routes
|--------------------------------------------------------------------------
|
*/
Route::post('shipping/{shipping}/boxes', [\App\Http\Controllers\Shipping\BoxController::class, 'store'])
    ->name('shipping.boxes.store');

Route::patch('shipping/{shipping}/boxes/{box}', [\App\Http\Controllers\Shipping\BoxController::class, 'update'])
    ->name('shipping.boxes.update');

Route::delete('shipping/{shipping}/boxes/{box}', [\App\Http\Controllers\Shipping\BoxController::class, 'destroy'])
    ->name('shipping.boxes.destroy');

Route::post('shipping/{shipping}/comments', [\App\Http\Controllers\Shipping\CommentController::class, 'store'])
    ->name('shipping.comments.store');

Route::patch('shipping/{shipping}/comments/{comment}', [\App\Http\Controllers\Shipping\CommentController::class, 'update'])
    ->name('shipping.comments.update');

Route::resource('shipping', \App\Http\Controllers\Shipping\ShippingController::class)
    ->only(['index', 'show', 'store', 'update']);
