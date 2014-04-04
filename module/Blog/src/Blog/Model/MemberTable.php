<?php

namespace Blog\Model;

use Zend\Db\TableGateway\TableGateway;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MemberTable
 *
 * @author sylvain
 */
class MemberTable {

    Protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getMember($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveMember(Member $member) {
        $data = array(
            'name' => $member->name,
            'mail' => $member->mail,
            'date_creat' => $member->date_creat,
            'tel' => $member->tel,
            'logo' => $member->logo,
            'level' => $member->level,
            'adress' => $member->adress,
            'skype' => $member->skype,
        );

        $id = (int) $member->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getMember($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Member id does not exist');
            }
        }
    }

    public function deleteMember($id) {
        $this->tableGateway->delete(array('id' => (int) $id));
    }

}
