<?php

namespace geoquizz\core\dto;

use geoquizz\core\domain\entity\Photo;

class PhotoDTO extends DTO
{
    protected string $id;
    protected string $nom;
    protected string $image;
    protected string $lat;
    protected string $long;
    protected string $theme_id;

    public function __construct(Photo $p){
        $this->id = $p->getID();
        $this->nom = $p->nom;
        $this->image = $p->image;
        $this->lat = $p->lat;
        $this->long = $p->long;
        $this->theme_id = $p->theme_id;
    }

    public function jsonSerialize(): array{
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'image' => $this->image,
            'lat' => $this->lat,
            'long' => $this->long,
            'theme_id' => $this->theme_id
        ];
    }

}