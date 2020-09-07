<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gist extends Model
{
    public function getWordList(){
        return "Hello Word 2";
    }

    public function addWord(){
        return "Add new word";
    }

    public function removeWord(){
        return "Remove Word";
    }
}
