<?php

namespace App\Services;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;

class ErrorService
{
    public function __construct()
    {

    }

    public function EmailErrorLog($error)
    {
        $filesystem = new Filesystem();
        $filepath = "logs/mailer/email_log.txt";
        if(!$filesystem->exists($filepath)){
            $filesystem->touch($filepath);
        }
        try {
            $file = fopen($filepath, 'a');
            fwrite($file, $error . "\n");
            fclose($file);
            return true;
        }
        catch(\Exception $exception){
            return new Response($exception->getMessage());
        }
    }
}