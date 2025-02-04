<?php

namespace geoquizz\core\dto;

use geoquizz\core\domain\entity\Game;
use geoquizz\core\domain\entity\Sequence;

class GameDTO extends DTO
{
    public string  $id;
    public ?string $player_id;
    public string $serie_id;
    public Sequence $sequence;
    public int $score;
    public bool $status;

    public function __construct(Game $g){
        $this->id = $g->getID();
        $this->player_id = $g->player_id;
        $this->serie_id = $g->serie_id;
        $this->sequence = $g->sequence;
        $this->score = $g->score;
        $this->status = $g->status;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'player_id' => $this->player_id,
            'serie_id' => $this->serie_id,
            'sequence' => $this->sequence->toDTO(),
            'score' => $this->score,
            'status' => $this->status
        ];
    }
}