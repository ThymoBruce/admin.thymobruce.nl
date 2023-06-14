<?php

namespace App\Services;

use Symfony\Component\HttpClient\HttpClient;

class thymobruceClient
{
    private $baseUrl;
    private $token;
    private $login;
    private $client;

    public function __construct()
    {
        $this->baseUrl = "https://thymobruce.nl/api/";
        $this->client = HttpClient::create([
            'base_uri' => $this->baseUrl,
            'verify_host' => false,
            'verify_peer' => false,
        ]);
    }

    public function login($data)
    {
        $url = $this->baseUrl . "login"; 
        $password = $data['password'];
        $email = $data["email"];
        $config = ['body' => [
           'email' => $email,
           'password' => $password,
        ]];

        try {
            $request = $this->client->request("POST", $url, $config);
            $result = $request->getContent();
        }
        catch(\Exception $exception){
            throw new \Exception($exception->getMessage());
        }

  
        return $result;
    }

    public function loginByUsername($username)
    {
        $url = $this->baseUrl . "login/username";
        $config = ['body' => [
            'email' => $username,
        ]];

        $request = $this->client->request("POST", $url, $config);
        $result = $request->getContent();
        return $result;
    }

    public function createTodo($todo)
    {
        $url = $this->baseUrl . "to-do/create";
        $config = ['body' => [
            'email' => $todo["email"],
            'description' => $todo["description"],
            'name' => $todo["name"],
        ]];
        $request = $this->client->request("POST", $url, $config);
        try {
            $result = $request->getContent();
        }
        catch(\Exception $e){
            return $e->getMessage();
        }
        return $result;
    }

    public function createProject($data)
    {
        $url = $this->baseUrl . "project/create";
        $config = ['body' => [
            'name' => $data["name"],
            'description' => $data["description"],
            'github_url' => $data["gitRepository"],
            'url' => $data["url"],
            'type' => "code",
        ]];

        $request = $this->client->request("POST", $url, $config);
        $result = $request->getContent();
        return $result;
    }
}