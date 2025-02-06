<?php

namespace geoquizz\core\dto;

use geoquizz\core\domain\entity\Sequence;

class SequenceDTO extends DTO
{
    protected string $id;
    protected bool $public;
    protected string $serie_id;
    protected ?array $photos;

    public function __construct(Sequence $s){
        $this->public = $s->public;
        $this->serie_id = $s->serie_id;
        if($s->photos != null){
            foreach ($s->photos as $p){
                $this->photos[] = new PhotoDTO($p);
            }
        }else{
            $this->photos = [];
        }
        $this->id = $s->getID();
    }

    public function jsonSerialize(): array
    {
        $tab=[];
        foreach($this->photos as $photo){
            $tab[]=$photo->jsonSerialize();
        }
        return [
            'id' => $this->id,
            '$public' => $this->public,
            '$serie_id' => $this->serie_id,
            'photo' => $tab
        ];
    }


}