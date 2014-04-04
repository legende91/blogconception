<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Model\Member;
use Blog\Form\MemberForm;
use Blog\Model\Tutorial;
use Blog\Form\TutorialForm;

class IndexController extends AbstractActionController {

    /**
     *
     * @var $memberTable, $form , $storage, $authservice, $tutorialTable
     * 
     */
    protected $memberTable;
    protected $form;
    protected $storage;
    protected $authservice;
    protected $tutorialTable;

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
     * function for Add new member
     * @var $form, $member, $request
     * @return type
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
        $form = new MemberForm();
        $form->get('submit')->setValue('addMember');

        $request = $this->getRequest();

        /**
         * 
         * we see if forumlaire is ok else we redirect to Formulaire
         * 
         */
        if ($request->isPost()) {
            $member = new Member();
            $form->setInputFilter($member->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $member->exchangeArray($form->getData());
                $this->getMemberTable()->saveMember($member);
                /**
                 * 
                 *  Redirect to list of Member 
                 * @return $this->redirect()->toRoute('home') return to Home_page
                 *  
                 */
                return $this->redirect()->toRoute('home');
            }
        }

        return array('form' => $form);
    }

    /**
     * 
     * function for Auth member
     * @var $this->authservice
     * @return $this->authservice
     * 
     */
    public function getAuthService() {

        if (!$this->authservice) {

            $this->authservice = $this->getServiceLocator()
                    ->get('AuthService');
        }

        return $this->authservice;
    }

    /**
     * 
     * function for Storage to service on zend
     * @var $this->storage
     * @return $this->storage Stockage of data
     * 
     */
    public function getSessionStorage() {
        if (!$this->storage) {
            $this->storage = $this->getServiceLocator()
                    ->get('Blog\Model\MyAuthStorage');
        }
        return $this->storage;
    }

    /**
     * 
     * function for login
     * @var $this->getAuthService()->hasIdentity()
     * @return $this->redirect()->toRoute('success')
     * 
     */
    public function loginAction() {
        /**
         * 
         * if already login, redirect to success page
         * 
         */
        if ($this->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('success');
        }
    }

    //----------------------------------------------------------------------------->
// 

    /**
     * 
     * function for Autentification
     * @var $redirect, $request, $this->getAuthService(), $result, $userConnected
     * @return $this->redirect()->toRoute($redirect)
     * 
     */
    public function authenticateAction() {
        $redirect = 'home';
        $request = $this->getRequest();
        if ($request->isPost()) {
            /**
             * 
             * check authentication...
             * 
             */
            $this->getAuthService()->getAdapter()
                    ->setIdentity($request->getPost('name'))
                    ->setCredential($request->getPost('password'));

            $result = $this->getAuthService()->authenticate();
            foreach ($result->getMessages() as $message) {
                /**
                 * 
                 * save message temporary into flashmessenger
                 * 
                 */
                $this->flashmessenger()->addMessage($message);
            }
            if ($result->isValid()) {
                $redirect = 'success';
                /**
                 * 
                 * check if it has rememberMe :
                 * 
                 */
                if ($request->getPost('rememberme') == 1) {
                    $this->getSessionStorage()
                            ->setRememberMe(1);
                    /**
                     * 
                     * set storage again
                     * 
                     */
                    $this->getAuthService()->setStorage($this->getSessionStorage());
                }

                $userConnected = $this->getAuthService()->getAdapter()->getResultRowObject();

                $this->getAuthService()->getStorage()->write(array(
                    'id' => $userConnected->id,
                    'name' => $userConnected->name,
                    'mail' => $userConnected->mail,
                    'date_creat' => $userConnected->date_creat,
                    'tel' => $userConnected->tel,
                    'logo' => $userConnected->logo,
                    'level' => $userConnected->level,
                    'adress' => $userConnected->adress,
                    'skype' => $userConnected->skype,
                ));
            }
        }
        return $this->redirect()->toRoute($redirect);
    }

    /**
     * 
     * public function logout
     * @var $this->getSessionStorage(), $this->getAuthService(),$this->flashmessenger()
     * @return $this->redirect()->toRoute('home')
     *  
     */
    public function logoutAction() {
        $this->getSessionStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();
        $this->flashmessenger()->addMessage("Vous etes bien déconeté.");
        return $this->redirect()->toRoute('home');
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
     * Controller for Add member
     * @var $this->getServiceLocator()->get('AuthService')->hasIdentity()
     * @return $this->addAction()
     * 
     */
    public function addMemberAction() {
        /**
         * 
         * we see if ppl was logged.
         * 
         */
        if (!$this->getServiceLocator()->get('AuthService')->hasIdentity()) {
            return $this->redirect()->toRoute('home');
        }

        return $this->addAction();
    }

    /**
     * 
     * Controller for Delete Member
     * @var $id, $this->getMemberTable(), $home
     * @var $this->getServiceLocator()->get('AuthService')->hasIdentity()
     * @return type
     * 
     */
    public function deleteMemberAction() {
        /**
         * 
         * we see if ppl was logged.
         * 
         */
        $home = 'home';
        if (!$this->getServiceLocator()->get('AuthService')->hasIdentity()) {
            return $this->redirect()->toRoute($home);
        }
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute($home);
        }
        $this->getMemberTable()->deleteMember($id);
        return $this->redirect()->toRoute($home);
    }

    /**
     * 
     * Controller Edit Member
     * @var $this->getServiceLocator()->get('AuthService')->hasIdentity(),
     * @var $id, $member, $form, $request, $redirect
     * @return 
     * 
     */
    public function editMemberAction() {
        /**
         * 
         * we see if ppl was logged.
         * 
         */
        $redirect = 'home';
        if (!$this->getServiceLocator()->get('AuthService')->hasIdentity()) {
            return $this->redirect()->toRoute($redirect);
        }
        /**
         * 
         *  We take ID on Params to URL 
         * 
         */
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('blog_editMember', array(
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
            $member = $this->getMemberTable()->getMember($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('home', array(
                        'action' => 'index'
            ));
        }

        $form = new MemberForm();
        $form->bind($member);
        $form->get('submit')->setAttribute('value', 'editMember');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($member->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getMemberTable()->saveMember($member);

                /**
                 * 
                 *  Redirect to list of Member
                 * 
                 */
                return $this->redirect()->toRoute($redirect);
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    /**
     * 
     * Controller Profil Member
     * @var $this->getServiceLocator()->get('AuthService')->hasIdentity(), $member_id
     * @var $form, $request
     * 
     */
    public function profilMemberAction() {
        /**
         * 
         * we see if ppl was logged.
         * 
         */
        $redirect = 'home';
        if (!$this->getServiceLocator()->get('AuthService')->hasIdentity()) {
            return $this->redirect()->toRoute($redirect);
        }
        /**
         * 
         *  We take ID on Member Auth. 
         * 
         */
        $member_id = $this->getServiceLocator()->get('AuthService')->getIdentity()['id'];
        if (!$member_id) {
            return $this->redirect()->toRoute('blog_editMember', array(
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
            $member = $this->getMemberTable()->getMember($member_id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('home', array(
                        'action' => 'index'
            ));
        }

        $form = new MemberForm();
        $form->bind($member);
        $form->get('submit')->setAttribute('value', 'editMember');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($member->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getMemberTable()->saveMember($member);

                /**
                 *
                 * Redirect to list of Member
                 * 
                 */
                return $this->redirect()->toRoute($redirect);
            }
        }

        return array(
            'id' => $member_id,
            'form' => $form,
        );
    }

}
