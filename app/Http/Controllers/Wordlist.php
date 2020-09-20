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
        return 0;
    }

    public function deleteWord(){
        return 0;
    }

    public function getWordList(){
        return 0;
    }

}
