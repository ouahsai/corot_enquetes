<?php

namespace Application\FormUtils;

use Application\Entity\Question;

/**
 *
 * @author USER
 */
interface EnqueteFormInterface {
    
    const BASE_NAME = 'question';

    public function dispatcher(Question $question);

    public function questionText(Question $question);

    public function questionNb(Question $question);

    public function questionQcm(Question $question);

}
