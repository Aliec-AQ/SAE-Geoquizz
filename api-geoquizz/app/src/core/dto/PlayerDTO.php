<?php

namespace geoquizz\core\dto;

use geoquizz\core\domain\entity\Player;

class PlayerDTO extends DTO
{
    protected string $id;
    protected string $pseudo;
    protected string $lastConnections;

    public function __construct(Player $p){
        $this->id = $p->getID();
        $this->pseudo = $p->pseudo;
        $this->lastConnections = $p->lastConnections;
    }

    public function jsonSerialize(): array{
        return [
            'id' => $this->id,
            'pseudo' => $this->pseudo,
            'lastConnections' => $this->lastConnections->format('Y-m-d H:i:s')
        ];
    }
}