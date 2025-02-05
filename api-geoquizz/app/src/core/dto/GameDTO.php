<?php

namespace geoquizz\core\dto;

use geoquizz\core\domain\entity\Game;
use geoquizz\core\domain\entity\Sequence;

class GameDTO extends DTO
{
    protected string  $id;
    protected ?string $player_id;
    protected string $serie_id;
    protected Sequence $sequence;
    protected int $score;
    protected bool $status;

    public function __construct(Game $g){
        $this->id = $g->getID();
        $this->player_id = $g->player_id;
        $this->serie_id = $g->serie_id;
        $this->sequence = $g->sequence;
        $this->score = $g->score;
        $this->status = $g->status;
    }

    public function getID(){
        return $this->id;
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