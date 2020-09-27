<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gist;

class WordlistController extends Controller
{
    public function addWord($word){
        //Read JsonFile
        $gist = new \App\Gist(env("GITHUB_APIURL"),env("GITHUB_GISTID"),env("GITHUB_FILENAME"),env("GITHUB_TOCKEN"));
        $dbJsonFile = json_decode($gist->getGistContent());
        
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
        $gist->updateContent($dbJsonFile);

        return response()->json([
            'response' => 'The word: '.$word.' has been save it successfully'        
        ]);
    }

    public function getExample($word){
        $url = "https://vocabulary.now.sh/word/".$word;
        $jsonFile = json_decode(file_get_contents($url),true);
        $Example = $jsonFile['data'];
        return response()->json([
            'word' => $word,
            'example' => $Example
        ]);
    }

    public function removeWord($word){
        //Read JsonFile
        $gist = new \App\Gist(env("GITHUB_APIURL"),env("GITHUB_GISTID"),env("GITHUB_FILENAME"),env("GITHUB_TOCKEN"));
        $jsonFile = json_decode($gist->getGistContent());
        
        //Check if the word exist
        if(in_array($word,array_column($jsonFile->Wordlist,"Word"))){
            
            //Delete the word
            $foundItem = array_search($word, array_column($jsonFile->Wordlist,"Word"));
            unset($jsonFile->Wordlist[$foundItem]);
            
            //Commit the changes
            $gist->updateContent($jsonFile);
            
            return response()->json(['response' => 'The word: '.$word.' has been delete it successfully' ]);
        }
        return response()->json(['response' => 'Couldn\'t delete the word: '.$word.' doen\'t exist on the system' ]);
    }

    public function getList(){
        //Read JsonFile
        $gist = new \App\Gist(env("GITHUB_APIURL"),env("GITHUB_GISTID"),env("GITHUB_FILENAME"),env("GITHUB_TOCKEN"));
        return response()
        ->json(json_decode($gist->getGistContent()));
    }

}
