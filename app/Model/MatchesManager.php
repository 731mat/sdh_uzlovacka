<?php

namespace App\Model;

use App\Model\DatabaseManager;
use Nette;
use Nette\Database\Explorer;


class MatchesManager extends DatabaseManager
{
    use Nette\SmartObject;

    public const
        TableName = 'matches',
        ColumnId = 'id';


    private function getTable(){
        return $this->database->table(self::TableName);
    }

    public function getMatches(){
        return $this->getTable()->fetchAll();
    }
    public function getMatche($id){
        return $this->getTable()->where(self::ColumnId,$id)->fetch();
    }
    public function editMatche($id,$values){
        return $this->getTable()->where(self::ColumnId,$id)->update($values);
    }
    public function addMatche($values){
        return $this->getTable()->insert($values);
    }
    public function dellMatche($id){
        return $this->getTable()->where(self::ColumnId,$id)->delete();
    }
    public function getMatchesPerTable($id){
        return $this->getTable()->where("table_id",$id)->fetchAll();
    }





    public function generateRound($category){

        $vyradit = $this->database->query("SELECT * FROM competitor WHERE category = ? AND (round - score) >= 2 AND vysledne_poradi = 0 ORDER BY score DESC, ((SELECT SUM((first_competitor_mistakes*1000)+first_competitor_time) FROM matches WHERE first_competitor = competitor.id) + (SELECT SUM((second_competitor_mistakes*1000)+second_competitor_time) FROM matches WHERE second_competitor = competitor.id))",$category);
        $nove_poradi = $this->database->query("SELECT min(vysledne_poradi) as x FROM competitor WHERE category = ? AND vysledne_poradi != 0",$category)->fetch();

        if ($nove_poradi == null || !isset($nove_poradi['x'])){
            $nove_poradi_int=1000;
        }else{
            $nove_poradi_int = $nove_poradi['x'];
        }
        foreach ($vyradit as $vyrad){
            $nove_poradi_int--;
            $this->database->table("competitor")->where("id", $vyrad['id'])->update(["vysledne_poradi"=>$nove_poradi_int]);
        }

        function werePreviouslyPaired($player1, $player2, $previousPairings) {
            foreach ($previousPairings as $pair) {
                if (($player1->id == $pair->first_competitor && $player2->id == $pair->second_competitor)
                    || ($player2->id == $pair->first_competitor && $player1->id == $pair->second_competitor)) {
                    return true;
                }
            }
            return false;
        }

        $playerZEROO = $this->database->query("SELECT * from ".CompetitorManager::TableName." WHERE category = 99")->fetch();



        $isFirstRound = $this->database->query("SELECT MAX(round) as x FROM competitor WHERE category = ?",$category)->fetch()['x'] == 0?1:0;
        if($isFirstRound){
            $competitors = $this->database->query("SELECT * from ".CompetitorManager::TableName." WHERE category = ? ORDER BY first_time",$category);

            $data = $competitors->fetchAll();

            usort($data, function($a, $b) {
                return $b->score - $a->score;
            });

            $matchedPlayers = [];
            $pairings = [];

            $previousPairings = $this->database->query("SELECT first_competitor,second_competitor FROM ".self::TableName."")->fetchAll();


            $count = count($data);
            $half = ceil($count / 2);

            for ($i = 0; $i < $half; $i++) {
                $player1 = $data[$i];
                for ($j = $half; $j < $count; $j++) {
                    $player2 = $data[$j];
                    if (!in_array($player2->id, $matchedPlayers)
                        && !werePreviouslyPaired($player1, $player2, $previousPairings))
                    {
                        $pairings[] = [$player1, $player2];
                        $matchedPlayers[] = $player1->id;
                        $matchedPlayers[] = $player2->id;
                        break;
                    }
                }
            }

            //echo "Parovani pro toto kolo:\n<br>";
            foreach ($pairings as $pair) {
                $random = rand(0,1);
                $player1 = $pair[$random];
                $player2 = $pair[$random==1?0:1];
                //echo "{$player1->first_name} {$player1->last_name} vs. {$player2->first_name} {$player2->last_name}\n<br>";
                $ins = [
                    'id' => null,
                    'table_id' => $category,
                    'round' => 0,
                    'first_competitor' => $player1->id,
                    'second_competitor' => $player2->id,
                    'first_competitor_mistakes' => null,
                    'second_competitor_mistakes' => null,
                    'first_competitor_time' => null,
                    'second_competitor_time' =>null,
                ];
                $this->getTable()->insert($ins);
            }

        }else{
            $freeUsers = [];
            $pairings = [];


            for ($tt = 0; $tt < 100;$tt++){
                $competitors = $this->database->query("SELECT * from ".CompetitorManager::TableName." WHERE vysledne_poradi = 0 and category = ? and score = ? ORDER BY score, ((SELECT SUM((first_competitor_mistakes*1000)+first_competitor_time) FROM matches WHERE first_competitor = competitor.id) + (SELECT SUM((second_competitor_mistakes*1000)+second_competitor_time) FROM matches WHERE second_competitor = competitor.id))",$category,$tt);
                $data = $competitors->fetchAll();

                usort($data, function($a, $b) {
                    return $b->score - $a->score;
                });

                $matchedPlayers = [];

                $previousPairings = $this->database->query("SELECT first_competitor,second_competitor FROM ".self::TableName."")->fetchAll();


                $count = count($data);
                $half = ceil($count / 2);

                for ($i = 0; $i < $half; $i++) {
                    $player1 = $data[$i];
                    for ($j = $half; $j < $count; $j++) {
                        $player2 = $data[$j];
                        if (!in_array($player2->id, $matchedPlayers)
                            && !werePreviouslyPaired($player1, $player2, $previousPairings))
                        {
                            $pairings[] = [$player1, $player2];
                            $matchedPlayers[] = $player1->id;
                            $matchedPlayers[] = $player2->id;
                            break;
                        }
                    }
                    if (!in_array($player1->id, $matchedPlayers)){
                        $freeUsers[] = $player1;
                        $matchedPlayers[] = $player1->id;
                    }
                }


                //echo "Parovani pro toto kolo:\n<br>";

            }
            for($pp = 0; $pp < count($freeUsers);$pp++){
                if (isset($freeUsers[$pp+1])){
                    $pairings[] = [$freeUsers[$pp], $freeUsers[++$pp]];
                }else{
                    $pairings[] = [$freeUsers[$pp], $playerZEROO];
                    break;
                }
            }

            foreach ($pairings as $pair) {
                $player1 = $pair[0];
                $player2 = $pair[1];
                //echo "{$player1->first_name} {$player1->last_name} vs. {$player2->first_name} {$player2->last_name}\n<br>";
                $ins = [
                    'id' => null,
                    'table_id' => $category,
                    'round' => $player1->round+1,
                    'first_competitor' => $player1->id,
                    'second_competitor' => $player2->id,
                    'first_competitor_mistakes' => null,
                    'second_competitor_mistakes' => null,
                    'first_competitor_time' => null,
                    'second_competitor_time' =>null,
                ];
                $this->getTable()->insert($ins);
            }
        }



    }


}