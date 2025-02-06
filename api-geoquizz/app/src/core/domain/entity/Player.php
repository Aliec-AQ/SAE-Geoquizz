<?php

namespace geoquizz\core\domain\entity;

use DateTime;
use geoquizz\core\dto\PlayerDTO;

class Player extends Entity
{
    protected ?string $pseudo;
    protected DateTime $lastConnections;

    public function __construct(?string $pseudo, DateTime $lastConnections = new DateTime("now")){
        $this->pseudo = $pseudo;
        $this->lastConnections = $lastConnections;
    }

    public function toDTO(){
        return new PlayerDTO($this);
    }
}