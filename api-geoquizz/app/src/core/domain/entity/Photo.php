<?php

namespace geoquizz\core\domain\entity;

use geoquizz\core\dto\PhotoDTO;

class Photo extends Entity
{
    protected string $nom;
    protected string $image;
    protected string $lat;
    protected string $long;
    protected string $theme_id;

    public function __construct(string $nom, string $image, string $lat, string $long, string $theme_id){
        $this->nom = $nom;
        $this->image = $image;
        $this->lat = $lat;
        $this->long = $long;
        $this->theme_id = $theme_id;
    }

    public function toDTO(): PhotoDTO{
        return new PhotoDTO($this);
    }


}