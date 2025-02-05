<?php

namespace geoquizz\core\domain\entity;

use geoquizz\core\dto\PhotoDTO;

class Photo extends Entity
{
    protected string $nom;
    protected string $image;
    protected string $lat;
    protected string $long;

    public function __construct(string $nom, string $image, string $lat, string $long){
        $this->nom = $nom;
        $this->image = $image;
        $this->lat = $lat;
        $this->long = $long;
    }

    public function toDTO(): PhotoDTO{
        return new PhotoDTO($this);
    }


}