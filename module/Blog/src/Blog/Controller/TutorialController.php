<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Model\Tutorial;
use Blog\Form\TutorialForm;
use Blog\Model\Member;
use Blog\Form\MemberForm;

/**
 * Description of ArticleController
 *
 * @author sylvain
 */
class TutorialController extends AbstractActionController {

    protected $tutorialTable;
    protected $memberTable;
    protected $form;

    /**
     * 
     * function for reply tutorial Object
     * @var $sm
     * @return $this->tutorialTable
     * 
     */
    public function getTutorialTable() {

        if (!$this->tutorialTable) {
            $sm = $this->getServiceLocator();
            $this->tutorialTable = $sm->get('Blog\Model\TutorialTable');
        }
        return $this->tutorialTable;
    }

    /**
     * 
     * methode for reply Member Object
     * @var $sm
     * @return type 
     */
    
    public function getMemberTable() {

        if (!$this->memberTable) {
            $sm = $this->getServiceLocator();
            $this->memberTable = $sm->get('Blog\Model\MemberTable');
        }
        return $this->memberTable;
    }


     /**
     * 
     * Default Controller
     * @var $this->getTutorialTable(), $this->getMemberTable()
     * @return \Zend\View\Model\ViewModel
     *  
     */
    
    public function indexAction() {

        return new ViewModel(array(
            'tutorials' => $this->getTutorialTable()->fetchAll(),
            'members' => $this->getMemberTable()->fetchAll(),
        ));
    }


    /**
     * 
     * Controller for Add Tutorial
     * @var $this->getServiceLocator()->get('AuthService')->hasIdentity()
     * @var $member_id, $form, $request, $tutorial
     * @return $this->addAction()
     * 
     */
    
    public function addAction() {
         /**
         * 
         * we see if ppl was logged.
         * 
         */
        if (!$this->getServiceLocator()->get('AuthService')->hasIdentity()) {
            return $this->redirect()->toRoute('home');
        }

        $member_id = $this->getServiceLocator()->get('AuthService')->getIdentity()['id'];
        $form = new TutorialForm();
        $form->get('submit')->setValue('add');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $tutorial = new Tutorial();
            $form->setInputFilter($tutorial->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $tutorial->exchangeArray($form->getData());
                $this->getTutorialTable()->saveTutorial($tutorial, $member_id);
                // Redirect to list of Tutorial  
                return $this->redirect()->toRoute('home');
            }
        }
        return array('form' => $form);
    }

     /**
     * 
     * Controller Edit Tutorial
     * @var $this->getServiceLocator()->get('AuthService')->hasIdentity(),
     * @var $id, $tutorial, $form, $request, $redirect, $member_id
     * @return 
     * 
     */
    
    public function editAction() {
         /**
         * 
         * we see if ppl was logged.
         * 
         */
        if (!$this->getServiceLocator()->get('AuthService')->hasIdentity()) {
            return $this->redirect()->toRoute('home');
        }
        /**
         * 
         *  We take ID on Params to URL 
         * 
         */
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('tutorial_edit', array(
                        'action' => 'add'
            ));
        }

        /**
         * 
         * Get the Member with the specified id.  An exception is thrown
         * if it cannot be found, in which case go to the index page.
         * 
         */
        
        try {
            $tutorial = $this->getTutorialTable()->getTutorial($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('home', array(
                        'action' => 'index'
            ));
        }

        $form = new TutorialForm();
        $form->bind($tutorial);
        $form->get('submit')->setAttribute('value', 'edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $member_id = $this->getServiceLocator()->get('AuthService')->getIdentity()['id'];
            $form->setInputFilter($tutorial->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getTutorialTable()->saveTutorial($tutorial, $member_id);

                  /**
                 * 
                 *  Redirect to list of tutorial
                 * 
                 */
                return $this->redirect()->toRoute('home');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }
    /**
     * 
     * fucntion for delete Tutorial 
     * @var $redirect, $member_id, $id 
     * @var $this->getServiceLocator()->get('AuthService')->hasIdentity()
     * @var $this->getTutorialTable()->deleteTutorial($id)
     * @return type
     * 
     */
    public function deleteAction() {
        $redirect = 'home';
        if (!$this->getServiceLocator()->get('AuthService')->hasIdentity()) {
            return $this->redirect()->toRoute($redirect);
        }
        $member_id = $this->getServiceLocator()->get('AuthService')->getIdentity()['id'];

        $id = (int) $this->params()->fromRoute('id', 0);
        if ($member_id = !$id) {
            return $this->redirect()->toRoute($redirect);
        }
        if (!$id) {
            return $this->redirect()->toRoute($redirect);
        }
        $this->getTutorialTable()->deleteTutorial($id);
        return $this->redirect()->toRoute($redirect);
    }

}
