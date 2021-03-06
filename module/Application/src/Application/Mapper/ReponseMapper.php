<?php

namespace Application\Mapper;

use Application\Entity\Question;
use Application\Entity\Reponse;
use Application\Entity\Resultat;
use Application\FormUtils\ResultatsInterface;
use Application\Hydrator\ReponseHydrator;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

/**
 * Description of EnqueteMapper
 *
 */
class ReponseMapper implements ResultatsInterface {

    private $gateway;

    public function __construct(AdapterInterface $adapter) {
        $this->gateway = new TableGateway('reponse', $adapter);
    }

    /**
     * récupère une reponse par son id
     * 
     *  @return Reponse
     */
    public function getById($id) {
        return $reponse;
    }

    /**
     * récpère la liste de reponses pour une question
     *  
     * @return Reponse[] Liste des reponses
     */
    public function getAllByIdQuestion($idQuestion) {
        return $listeReponse;
    }

    /**
     * compte le nombre de répondants à une enquête
     *  
     * @return int
     * 
     */
    public function countRepondantsByIdEnquete($idEnquete) {
        $idEnquete = (int) $idEnquete;

        $select = new Select();
        $select->columns(array('nb' => new Expression('COUNT(DISTINCT uid_repondant)')), false)
                ->from($this->gateway->getTable())
                ->join('question', 'id_question = question.id', array())
                ->where(array('id_enquete' => $idEnquete));
        $resultset = $this->gateway->selectWith($select);


        if (!$resultset || $resultset->count() > 1) { // on renvoit false si aucun resultat ou plusieurs résultats
            return 0;
        }

        foreach ($resultset as $row) {
            $nb = $row['nb'];
        }

        return $nb;
    }

    /**
     * création d'une Reponse
     *
     * @param Reponse
     *  @return bool
     */
    public function add(Reponse $reponse) {
        $hydrator = new ReponseHydrator();
        $set = $hydrator->extract($reponse);

        return $this->gateway->insert($set);
    }

    /**
     * suppression d'une reponse
     *
     * @param int
     *  @return bool
     */
    public function delete($id) {
        
    }

    /**
     * suppression des reponses à une enquete
     *
     * @param int
     *  @return bool
     */
    public function deleteAllByEnquete($idEnquete) {

        //TODO
    }

    /**
     * 
     * @param Question $question
     * @return Resultat
     */
    public function resultatByQuestion(Question $question) {

        $resultTab = $this->dispatcher($question);

        $resultat = new Resultat();

        if ($resultTab) {
            $resultat->setResultat($resultTab);
        }

        return $resultat;
    }

    /**
     * 
     * @param Question $question
     * @return array
     */
    public function resultatText(Question $question) {
        $idQuestion = (int) $question->getId();

        //SELECT contenu FROM reponse WHERE id_question =3;
        $select = new Select();
        $select->columns(array('text' => 'contenu'), false)
                ->from($this->gateway->getTable())
                ->where(array('id_question' => $idQuestion));

        $resultset = $this->gateway->selectWith($select);

        if (!$resultset) {
            return FALSE;
        }

        $arrayResultat = array();

        foreach ($resultset as $row) {
            $arrayResultat[] = $row['text'];
        }

        return $arrayResultat;
    }

    /**
     * 
     * @param Question $question
     * @return array
     */
    public function resultatNb(Question $question) {
        $idQuestion = (int) $question->getId();

        //SELECT MIN(contenu) as min, MAX(contenu) as max, AVG(contenu) as moyenne,SUM(contenu) as somme FROM reponse WHERE id_question =2;
        $select = new Select();
        $select->columns(array(
                    'min' => new Expression('MIN(contenu)'),
                    'max' => new Expression('MAX(contenu)'),
                    'moyenne' => new Expression('AVG(contenu)'),
                    'somme' => new Expression('SUM(contenu)'),
                        ), false)
                ->from($this->gateway->getTable())
                ->where(array('id_question' => $idQuestion));

        $resultset = $this->gateway->selectWith($select);

        if (!$resultset) {
            return FALSE;
        }

        $arrayResultat = array();

        foreach ($resultset as $row) {
            $arrayResultat = $row->getArrayCopy();
        }

        return $arrayResultat;
    }

    /**
     * 
     * @param Question $question
     * @return array
     */
    public function resultatQcm(Question $question) {
        $idQuestion = (int) $question->getId();

        //SELECT p.libelle, count(DISTINCT r.id) compte
        //FROM reponse r
        //RIGHT JOIN proposition p
        //ON r.id_proposition = p.id
        //WHERE p.id_question = 4
        //GROUP BY p.id
        //ORDER BY compte DESC;
        $select = new Select();
        $select->columns(array(
                    'compte' => new Expression('COUNT(DISTINCT reponse.id)')
                        ), true)
                ->from($this->gateway->getTable())
                ->join('proposition', 'id_proposition = proposition.id', 'libelle', Select::JOIN_RIGHT)
                ->where(array('proposition.id_question' => $idQuestion))
                ->group(array('libelle'))
                ->order('compte DESC');

        $resultset = $this->gateway->selectWith($select);

        if (!$resultset) {
            return FALSE;
        }

        $arrayResultat = array();

        foreach ($resultset as $row) {
            $arrayResultat[] = $row->getArrayCopy();
        }

        return $arrayResultat;
    }

    public function dispatcher(Question $question) {

        $method = self::BASE_NAME . ucfirst($question->getType());
        if (is_callable(array($this, $method))) {
            return $this->$method($question);
        }

        return false; //if method not callable
    }

}