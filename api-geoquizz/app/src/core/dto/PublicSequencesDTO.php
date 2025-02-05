<?php

namespace geoquizz\core\dto;

class PublicSequencesDTO extends DTO
{
    protected string $sequence_id;
    protected string $serie_id;
    protected string $theme;
    protected string $score;

    public function __construct($sequence_id, $serie_id, $theme, $score){
        $this->sequence_id = $sequence_id;
        $this->serie_id = $serie_id;
        $this->theme = $theme;
        $this->score = $score;
    }

    public function jsonSerialize(): array
    {
        return [
            'sequence_id' => $this->sequence_id,
            'serie_id' => $this->serie_id,
            'theme' => $this->theme,
            'score' => $this->score
        ];
    }
}