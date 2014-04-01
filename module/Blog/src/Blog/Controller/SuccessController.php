<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Blog\Controller;

/**
 * Description of SuccessController
 *
 * @author sylvain
 */
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Model\Member;
use Blog\Form\MemberForm;
use Blog\Model\Tutorial;
use Blog\Form\TutorialForm;


class SuccessController extends AbstractActionController {

     protected $tutorialTable;
     protected $memberTable;
     protected $form;
    
    public function getTutorialTable() {

        if (!$this->tutorialTable) {
            $sm = $this->getServiceLocator();
            $this->tutorialTable = $sm->get('Blog\Model\TutorialTable');
        }
        return $this->tutorialTable;
    }

//----------------------------------------------------------------------------->
// methode for connect to my db on Member table

    public function getMemberTable() {

        if (!$this->memberTable) {
            $sm = $this->getServiceLocator();
            $this->memberTable = $sm->get('Blog\Model\MemberTable');
        }
        return $this->memberTable;
    }

    public function indexAction() {


        if (!$this->getServiceLocator()->get('AuthService')->hasIdentity()) {
            return $this->redirect()->toRoute('home');
        }


        return new ViewModel(array
            (
            'tutorials' => $this->getTutorialTable()->fetchAll(),
            'members' => $this->getMemberTable()->fetchAll(),
                )
        );
    }

}
