<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class Gist extends Model
{
    private $APIURL;
    private $GistID;
    private $Token;

    function __construct($APIURL,$GistID,$Token) 
    {
        $this->APIURL = $APIURL;
        $this->GistID = $GistID;
        $this->Token = $Token;
    }
    
    public function getGistContent()
    {
        $headers = [
            'Authorization' => 'Token '.$this->Token
        ];
        
        $client = new Client([
            'headers'=> $headers
        ]);

        $url = $this->APIURL.$this->GistID;
        
        $request = $client->request('GET',$url);

        //Search the content
        $body = json_decode($request->getBody()->getContents(),true);
        $files = $body['files'];
        $fileDetails = $files[env('GITHUB_FILENAME')];
        return $fileDetails['content'];   
    }

    public function updateContent($GistContent)
    {
    
        $headers = [
            'Authorization' => 'Token '.$this->Token
        ];
        
        $client = new Client([
            'headers'=> $headers
        ]);

        $url = $this->APIURL.$this->GistID;
        
        $request = $client->request('PATCH',$url,[
            'body'=>$GistContent
        ]);
    } 
    
    public function test(){

    }

}
