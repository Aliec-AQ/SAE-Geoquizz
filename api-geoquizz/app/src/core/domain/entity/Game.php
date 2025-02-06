<?php

namespace geoquizz\core\domain\entity;


use geoquizz\core\dto\GameDTO;

class Game extends Entity
{
    protected ?string $player_id;
    protected string $serie_id;
    protected ?Sequence $sequence;
    protected int $score;
    protected bool $status;

    public function __construct(?string $player_id, string $serie_id, Sequence $sequence = null, int $score, bool $status){
        $this->player_id = $player_id;
        $this->serie_id = $serie_id;
        $this->sequence = $sequence;
        $this->score = $score;
        $this->status = $status;
    }

    public function toDTO(): GameDTO{
        return new GameDTO($this);
    }


}