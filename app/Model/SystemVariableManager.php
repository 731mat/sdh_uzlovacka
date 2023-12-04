<?php

namespace App\Model;

use Nette;
use Nette\Database\Explorer;


class SystemVariableManager extends DatabaseManager
{
    use Nette\SmartObject;

    private function getTable(){
        return $this->database->table('system_variable');
    }

    public function getVariables(){
        return $this->getTable()->fetchAll();
    }
    public function getVariable($id){
        return $this->getTable()->where('id',$id)->fetch();
    }
    public function editVariable($id,$values){
        return $this->getTable()->where('id',$id)->update($values);
    }
    public function addVariable($values){
        return $this->getTable()->insert($values);
    }
    public function dellVariable($id){
        return $this->getTable()->where('id',$id)->delete();
    }
}