<?php

namespace geoquizz\core\repositoryInterfaces;

use geoquizz\core\repositoryInterfaces\Sequence;

interface GameRepositoryInterface
{
    public function createSequence($idserie) : Sequence ;
}