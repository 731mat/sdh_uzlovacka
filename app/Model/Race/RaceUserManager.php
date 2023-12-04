<?php

namespace App\Model\Race;

use Nette;
use Nette\Database\Explorer;
use App\Model\DatabaseManager;


class RaceUserManager extends DatabaseManager
{
    use Nette\SmartObject;

    private const
        TableName = 'race__user',
        ColumnId = 'id',
        ColumnName = 'name',
        ColumnHash = 'hash';


    private function getTable(){
        return $this->database->table(self::TableName);
    }

    public function getUsers(){
        return $this->getTable()->fetchAll();
    }
    public function getUser($id){
        return $this->getTable()->where(self::ColumnId,$id)->fetch();
    }
    public function getUserByHash($hash){
        return $this->getTable()->where(self::ColumnHash,$hash);
    }
    public function editUser($id,$values){
        return $this->getTable()->where(self::ColumnId,$id)->update($values);
    }
    public function addUser($values){
        return $this->getTable()->insert($values);
    }
    public function dellUser($id){
        return $this->getTable()->where(self::ColumnId,$id)->delete();
    }
}