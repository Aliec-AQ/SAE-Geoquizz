<?php

namespace geoquizz\core\dto;

use geoquizz\core\domain\entity\Player;

class InputPlayerDTO extends DTO
{
    protected string $id;
    protected ?string $pseudo;

    public function __construct(string $id, ?string $pseudo){
        $this->id = $id;
        $this->pseudo =$pseudo;
    }
}