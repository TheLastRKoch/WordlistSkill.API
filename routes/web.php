<?php

use Illuminate\Support\Facades\Route;
use app\Http\Controllers\Wordlist;
use app\Gist;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return response()->json([
        'response' => 'Welcome to the Siri Wordlist Skill API, for more infomations please go to: www.github.com/TheLastRkoch/...'        
    ]);
});

/*
* PHP setup infomation
*/
Route::get('phpinfo', function () {
    return view('phpinfo');
});

Route::group(['prefix' => 'Wordlist'], function () {
    Route::get('Add/{word}', [
        'uses' => 'WordlistController@addWord',
        'as' => 'wordlist.new'
    ]);

    Route::get('Example/{word}', [
        'uses' => 'WordlistController@getExample',
        'as' => 'wordlist.example'
    ]);

    Route::get('Remove/{word}', [
        'uses' => 'WordlistController@removeWord',
        'as' => 'wordlist.delete'
    ]);

    Route::get('List', [
        'uses' => 'WordlistController@getList',
        'as' => 'wordlist.list'
    ]);
});

