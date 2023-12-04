<?php

namespace App\Model\Race;

use Nette;
use Nette\Database\Explorer;
use App\Model\DatabaseManager;


class RaceCategoryManager extends DatabaseManager
{
    use Nette\SmartObject;

    private const
        TableName = 'race__category',
        ColumnId = 'id',
        ColumnName = 'name',
        ColumnRaceId = 'race_id';

    private function getTable(){
        return $this->database->table(self::TableName);
    }

    public function getCategorys(){
        return $this->getTable()->fetchAll();
    }
    public function getCategoryByRace($raceId){
        return $this->getTable()->where(self::ColumnRaceId,$raceId)->fetchAll();
    }
    public function getCategoryByRaceAsc($raceId){
        return $this->getTable()->where(self::ColumnRaceId,$raceId)->select('id,name');
    }
    public function getCategory($id){
        return $this->getTable()->where(self::ColumnId,$id)->fetch();
    }
    public function editCategory($id,$values){
        return $this->getTable()->where(self::ColumnId,$id)->update($values);
    }
    public function addCategory($values){
        return $this->getTable()->insert($values);
    }
    public function dellCategory($id){
        return $this->getTable()->where(self::ColumnId,$id)->delete();
    }
}