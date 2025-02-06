<?php

namespace geoquizz\core\dto;

use geoquizz\core\domain\entity\Sequence;

class SequenceDTO extends DTO
{
    protected string $id;
    protected bool $public;
    protected string $serie_id;
    protected array $photos;

    public function __construct(Sequence $s){
        $this->public = $s->public;
        $this->serie_id = $s->serie_id;
        foreach ($s->photos as $p){
            $this->photos[] = new PhotoDTO($p);
        }
        $this->id = $s->getID();
    }

    public function jsonSerialize(): array
    {
        $tab=[];
        foreach($this->photo as $photo){
            $tab[]=$photo->jsonSerialize();
        }
        return [
            'id' => $this->id,
            '$public' => $this->status,
            '$serie_id' => $this->type,
            'photo' => $tab
        ];
    }


}