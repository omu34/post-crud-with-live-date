<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Post\Post;
use Carbon\Carbon;



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
// Authenication
Route::group([
    'middleware' => ['auth:user'],
    'prefix' => 'user',
    'namespace'=>'Auth'
], function () {
     Route::post('login', 'UserAuthController@login')->withoutMiddleware(['auth:user']);
     Route::post('logout', 'UserAuthController@logout');
     Route::post('refresh', 'UserAuthController@refresh')->withoutMiddleware(['auth:user']);
     Route::get('user', 'UserAuthController@me');
});

// Post
Route::group([
    'middleware' => ['auth:user'],
    'namespace' => 'Post'
], function () {
    Route::resource('posts', 'PostController');    
    Route::get('/posts', function (Request $request) {
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $posts = Post::whereBetween('live_at', [$startDate, $endDate])
        ->orderBy('live_at', 'desc')
        ->get();    
        return response()->json([
            'data' => $posts,            
            'message' => 'Post(s) retrieved successfully'
        ],201);   
    });
});

Route::group([
        'middleware' => ['auth:user'],        
        'namespace' => 'Agent'
    ], function () {  
    // Route::get('conversations', 'MetricsController@conversationsByAgent');
    // Route::get('metrics', 'MetricsController@metricsByAgent');
Route::get('/agent-metrics/conversations-by-agent', 'AgentController@conversationsByAgent');
Route::get('/agent-metrics/ongoing-messages', 'AgentController@ongoingMessages');
Route::get('/agent-metrics/response-time', 'AgentController@responseTime');
Route::get('/agent-metrics/response-and-resolution-time', 'AgentController@responseAndResolutionTime');
Route::get('/agent-metrics/resolution-count', 'AgentController@resolutionCount');
   
});
Route::group([
    'middleware' => ['auth:user'],        
    'namespace' => 'Agent'
], function () {
Route::resource('agent-metrics', 'Agent1Controller')->only(['index']);
});

Route::group([
    'middleware' => ['auth:user'],        
    'namespace' => 'chat'
], function () {
Route::post('/chatbot', 'ChatbotController@handle');
});
Route::group([
    'middleware' => ['auth:user'],        
    'namespace' => 'gpt'
], function () {
Route::post('/gpt', 'GptController@handle');
});

// Admin auth
// Route::group([
//     'middleware' => ['auth:admin'],
//     'prefix' => 'admin',
//     'namespace'=>'Auth'
// ], function () {
//      Route::post('login', 'AdminAuthController@login')->withoutMiddleware(['auth:admin']);
//      Route::post('logout', 'AdminAuthController@logout');
//      Route::post('refresh', 'AdminAuthController@refresh')->withoutMiddleware(['auth:admin']);
//      Route::get('user', 'AdminAuthController@me');
// });