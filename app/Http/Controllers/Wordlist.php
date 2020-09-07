<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gist;

class Wordlist extends Controller
{
    public function addNewWord(){
        $gist = new Gist;
        $result = $gist->getWordList();
        
        return response()->json([
            'response' => $result
        ]);
    }

    public function getExample(){
        return void;
    }

    public function deleteWord(){
        return void;
    }

    public function getWordList(){
        return void;
    }

}
