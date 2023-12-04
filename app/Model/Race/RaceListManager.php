<?php

namespace App\Model\Race;

use Nette;
use Nette\Database\Explorer;
use App\Model\DatabaseManager;


class RaceListManager extends DatabaseManager
{
    use Nette\SmartObject;

    private const
        TableName = 'race__list',
        ColumnId = 'id',
        ColumnName = 'name';

    private function getTable(){
        return $this->database->table(self::TableName);
    }

    public function getRaces(){
        return $this->getTable()->fetchAll();
    }
    public function getRace($id){
        return $this->getTable()->where(self::ColumnId,$id)->fetch();
    }
    public function editRace($id,$values){
        return $this->getTable()->where(self::ColumnId,$id)->update($values);
    }
    public function addRace($values){
        return $this->getTable()->insert($values);
    }
    public function dellRace($id){
        return $this->getTable()->where(self::ColumnId,$id)->delete();
    }
}