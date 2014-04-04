<?php

namespace Blog\Model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Zend\Db\TableGateway\TableGateway;

/**
 * Description of ArticleTable
 *
 * @author sylvain
 */
class TutorialTable {

    Protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

//----------------------------------------------------------------------------->   
//Jointure on Tutorial and Member.
//    public function getTutorials() {
//    $select = $this->tableGateway->getSql()->select();
//    $select->columns(array('blog_id', 'interest_id', 'owner_id', 'title', 'date_created'));
//    $select->join('users', 'users.user_id = blogs_settings.owner_id', array('username'), 'left');
//
//    $resultSet = $this->tableGateway->selectWith($select);
//
//    return $resultSet;
//}
//----------------------------------------------------------------------------->   
//Find all result on Tutorial Table.

    public function fetchAll() {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'title', 'contente', 'date_pub', 'member_id'));
        $select->join('member', 'tutorial.member_id = member.id', array('name'));
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function getTutorial($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveTutorial(Tutorial $tutorial, $member_id) {

        $data = array(
            'title' => $tutorial->title,
            'contente' => $tutorial->contente,
            'date_pub' => $tutorial->date_pub,
            'member_id' => $member_id,
        );

        $id = (int) $tutorial->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getTutorial($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Member id does not exist');
            }
        }
    }

    public function deleteTutorial($id) {
        $this->tableGateway->delete(array('id' => (int) $id));
    }

}
