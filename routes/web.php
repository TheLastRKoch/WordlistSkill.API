<?php

use Illuminate\Support\Facades\Route;
use app\Http\Controllers\Wordlist;

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

Route::get('/test', function () {
    return response()->json([
        'response' => 'All good'
    ]);
});

/*
* PHP setup infomation
*/
Route::get('/info', function () {
    $test = "";
    return view('phpinfo');
});


Route::get('Wordlist/New/{word}', function ($word){
    $result = Wordlist::addNewWord();
    var_dump($result);
});

/*
* Add a new word with an example to the JSON
*/
Route::get('Wordlist/New2/{word}', function ($word) {
    //Read JsonFile
    $path = storage_path()."/app/db.json";
    $dbJsonFile = json_decode(file_get_contents($path));
    
    //Chech if the word is null or empty
    if(is_null($word)){
        return response()->json([
            'response' => 'Error the word parameter is empty.'
        ]);
    }
    
    //Check if the word exist
    if(in_array($word,array_column($dbJsonFile->Wordlist,"Word"))){
        return response()->json([
            'response' => 'Error the word already exist in the system.'
        ]);
    }
    
    //Try to get the example
    try
    {
        //Get example from vocabulary.com
        $url = "https://vocabulary.now.sh/word/".$word;
        $requestJsonFile = json_decode(file_get_contents($url),true);
        $example = $requestJsonFile['data'];
    }
    catch(Exception $e){
        return response()->json([
            'response' => 'The selected word is invalid or couldn\'t be found.',
            'errors' => array("Something goes wrong with the URL request",$e->getMessage())        
        ]);
    }

    //Generate newItem
    $newItem = array("Word"=>$word, "Example"=>$example);

    //Add new item
    array_push($dbJsonFile->Wordlist,$newItem);
    
    //Save the new list
    $path = storage_path()."/app/db.json";
    file_put_contents($path,stripslashes(json_encode($dbJsonFile)));

    return response()->json([
        'response' => 'The word: '.$word.' has been save it successfully'        
    ]);
});

Route::get('TestURL/{word}', function ($word) {
    //Try to get the example
    try
    {
        //Get example from vocabulary.com
        $url = "https://vocabulary.now.sh/word/".$word;
        var_dump(file_get_contents($url));
        $jsonFile = json_decode(file_get_contents($url),true);
        $example = $jsonFile['data'];
    }
    catch(Exception $e){
        return response()->json([
            'response' => 'The selected word is invalid or couldn\'t be found.',
            'errors' => array("Something goes wrong with the URL request",$e->getMessage())        
        ]);
    }
    
});

/*
* Return a JSON with a example of the word
*/
Route::get('Wordlist/Example/{word}', function ($word) {
    $url = "https://vocabulary.now.sh/word/".$word;
    $jsonFile = json_decode(file_get_contents($url),true);
    $Example = $jsonFile['data'];
    return response()->json([
        'word' => $word,
        'example' => $Example
    ]);
});

/*
* Deletes a word from the JSON File
*/
Route::get('Wordlist/Delete/{word}', function ($word) {
    //Read JsonFile
    $path = storage_path()."/app/db.json";
    $jsonFile = json_decode(file_get_contents($path));
    //Check if the word exist
    if(in_array($word,array_column($jsonFile->Wordlist,"Word"))){
        //Delete the word
        $foundItem = array_search($word, array_column($jsonFile->Wordlist,"Word"));
        unset($jsonFile->Wordlist[$foundItem]);
        //Commit the changes
        $path = storage_path()."/app/db.json";
        file_put_contents($path,stripslashes(json_encode($jsonFile)));
        return response()->json(['response' => 'The word: '.$word.' has been delete it successfully' ]);
    }
    return response()->json(['response' => 'Couldn\'t delete the word: '.$word.' doen\'t exist on the system' ]);
});

/*
* Returns the complete JSON file
*/
Route::get('Wordlist/List', function () {
    //Read JsonFile
    $path = storage_path()."/app/db.json";
    $jsonFile = json_decode(file_get_contents($path));
    return response()
    ->json($jsonFile);
});

