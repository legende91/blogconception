<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Blog\Form;

use Zend\Form\Form;

/**
 * Description of TutorialForm
 *
 * @author sylvain
 */
class TutorialForm extends Form {

    public function __construct($name = null) {
        /**
         * 
         *  we want to ignore the name passed
         * 
         */

        parent::__construct('tutorial');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'title',
            'type' => 'Text',
            'options' => array(
                'label' => 'Name',
            ),
        ));

        $this->add(array(
            'name' => 'contente',
            'type' => 'Textarea',
            'options' => array(
                'cols' => '400',
                'rows' => '4000',
                'label' => 'contente',
            ),
        ));
        $this->add(array(
            'name' => 'date_pub',
            'type' => 'Text',
            'options' => array(
                'label' => 'date_pub',
            ),
        ));

        $this->add(array(
            'name' => 'member_id',
            'type' => 'Text',
            'options' => array(
                'label' => 'member_id',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Envoyer',
                'id' => 'submitbutton',
            ),
        ));
    }

}
