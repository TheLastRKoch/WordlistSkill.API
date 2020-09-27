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
    private $FileName;
    private $Token;

    function __construct($APIURL,$GistID,$FileName,$Token) 
    {
        $this->APIURL = $APIURL;
        $this->GistID = $GistID;
        $this->FileName = $FileName;
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
        $GistContent = json_encode(($GistContent));
        
        //Generate Github API Body
        $body = array (
            'files' => 
            array (
              $this->FileName => 
              array (
                'content' => $GistContent
              ),
            ),
        );

        $body = json_encode($body);

        $headers = [
            'Authorization' => 'Token '.$this->Token
        ];
        
        $client = new Client([
            'headers'=> $headers
        ]);

        $url = $this->APIURL.$this->GistID;
        
        $request = $client->request('PATCH',$url,[
            'body'=>$body
        ]);
    } 
    
    public function test(){

    }

}
