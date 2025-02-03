<?php
namespace mail\app\src\core\services;
interface serviceMailEnvoiInterface
{
    public function envoi($dns,$from,$to,$subject,$content):void;
}