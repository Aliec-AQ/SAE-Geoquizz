<?php

namespace geoquizz\core\domain\entity;

use geoquizz\core\dto\SequenceDTO;

class Sequence extends Entity
{
    protected bool $public;
    protected string $serie_id;
    protected ?array $photos;

    public function __construct(bool $public, string $serie_id, array $photo=null){
        $this->public = $public;
        $this->serie_id = $serie_id;
        $this->photos = $photo;
    }

    public function toDTO():SequenceDTO{
        return new SequenceDTO($this);
    }

    public function setPhotos(array $photos): void{
        $this->photos = $photos;
    }


}