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

    public function __construct(Photo $p){
        $this->id = $p->getID();
        $this->nom = $p->nom;
        $this->image = $p->image;
        $this->lat = $p->lat;
        $this->long = $p->long;
    }

    public function jsonSerialize(): array{
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'image' => $this->image,
            'lat' => $this->lat,
            'long' => $this->long
        ];
    }

}