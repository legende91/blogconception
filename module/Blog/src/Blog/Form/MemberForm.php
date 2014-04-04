<?php

namespace Blog\Form;

use Zend\Form\Form;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MemberForm
 *
 * @author sylvain
 */
class MemberForm extends Form {

    public function __construct($name = null) {
        // we want to ignore the name passed
        parent::__construct('member');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'class' => 'form-control',
            'id' => 'name',
            'options' => array(
                'label' => 'Name',
                'placeholder' => 'name',
                'class' => 'sr-only',
            ),
        ));
        $this->add(array(
            'name' => 'mail',
            'type' => 'Text',
            'class' => 'form-control',
            'options' => array(
                'label' => 'Mail',
            ),
        ));
        $this->add(array(
            'name' => 'password',
            'type' => 'password',
            'class' => 'form-control',
            'options' => array(
                'label' => 'Password',
            ),
        ));
        $this->add(array(
            'name' => 'tel',
            'type' => 'Text',
            'class' => 'form-control',
            'options' => array(
                'label' => 'Tel',
            ),
        ));

        $this->add(array(
            'name' => 'level',
            'type' => 'Text',
            'class' => 'form-control',
            'options' => array(
                'label' => 'Level',
            ),
        ));

        $this->add(array(
            'name' => 'adress',
            'type' => 'Text',
            'class' => 'form-control',
            'options' => array(
                'label' => 'Adress',
            ),
        ));

        $this->add(array(
            'name' => 'skype',
            'type' => 'Text',
            'class' => 'form-control',
            'options' => array(
                'label' => 'Skype',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'class' => 'form-control',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }

}
