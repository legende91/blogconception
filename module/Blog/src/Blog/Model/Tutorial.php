<?php

namespace Blog\Model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Description of Article
 *
 * @author sylvain
 */
class Tutorial implements InputFilterAwareInterface {
    /**
     *
     * @var $id, $title, $contente, $date_pub, $member_id, $name, $inputFilter 
     * 
     */
    public $id;
    public $title;
    public $contente;
    public $date_pub;
    public $member_id;
    public $name;
    protected $inputFilter;

    
    /**
     * 
     * Function for reply automatiquenly Tutorial object
     * @param type $data
     * @var $this->id, $this->name, $this->mail, $this->date_creat, $this->tel, $this->logo,
     * @var $this->level, $this->adress, $this->skype
     * 
     */
    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
        $this->contente = (!empty($data['contente'])) ? $data['contente'] : null;
        $this->date_pub = (!empty($data['date_pub'])) ? $data['date_pub'] : null;
        $this->member_id = (!empty($data['member_id'])) ? $data['member_id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
    }

    /**
     * 
     * @return type
     * 
     * 
     */
    
    public function getArrayCopy() {
        return get_object_vars($this);
    }

    /**
     * 
     * @param \Zend\InputFilter\InputFilterInterface $inputFilter
     * @throws \Exception Add content to these methods:
     * 
     */
    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Non utilisé");
    }

     /**
     * 
     * Function for Filter Formulaire
     * @var $inputFilter
     * @return $this->inputFilter return Objet of Filter
     * 
     */
    
    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'id',
                'required' => false,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'title',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 255,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'contente',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 10,
                        ),
                    ),
                ),
            ));
            $inputFilter->add(array(
                'name' => 'member_id',
                'required' => false,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'date_pub',
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                        ),
                    ),
                ),
            ));
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
