<?php

namespace Application\InputFilter;

use Application\Entity\Question;
use Application\FormUtils\AbstractEnqueteInputFilter;
use Application\Mapper\PropositionMapper;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\Input;
use Zend\Validator\Regex;

class EnqueteInputFilter extends AbstractEnqueteInputFilter {

    /**
     *
     * @var PropositionMapper
     */
    protected $mapperPropositions;

    /**
     * $listeQuestions est un tableau d'objets Question
     * 
     * @param array $listeQuestions
     */
    public function __construct($listeQuestions, Adapter $adapter) {

        $this->mapperPropositions = new PropositionMapper($adapter);

        foreach ($listeQuestions as $question) /* @var $question Question */ {

            if ($filtre = $this->dispatcher($question)) {
                $this->add($filtre);
            }
        }
    }

    public function questionText(Question $question) {

        $input = new Input(self::BASE_NAME . $question->getId());
        $input->isRequired();
        
        $filter = new StripTags();
        $input->getFilterChain()->attach($filter);
        
        $filter = new StringTrim();
        $input->getFilterChain()->attach($filter);

        return $input;
    }

    public function questionNb(Question $question) {
        $filtre = new Input(self::BASE_NAME . $question->getId());
        $filtre->isRequired();

        $validator = new Regex('/^-?[0-9]*$/');
        $filtre->getValidatorChain()->addValidator($validator);

        return $filtre;
    }

    public function questionQcm(Question $question) {
        $filtre = new Input(self::BASE_NAME . $question->getId());
        $filtre->isRequired();

        //validateur sur les id Proposition
        $listePropositions = $this->mapperPropositions->getAllByIdQuestion($question->getId());
        $regexString = "/^(";
        foreach ($listePropositions as $choix) /* @var $choix Proposition */ {
            $regexString .= $choix->getId() . "|";
        }
        $regexString = substr($regexString, 0, strlen($regexString) - 1);
        $regexString .= ")$/";

        $validator = new Regex($regexString);
        $filtre->getValidatorChain()->addValidator($validator);

        return $filtre;

    }

}
