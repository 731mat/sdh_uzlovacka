<?php

namespace App\Model;

use Nette;
use Nette\Database\Explorer;
use App\Model\DatabaseManager;
use Mpdf\Mpdf;


class CompetitorManager extends DatabaseManager
{
    use Nette\SmartObject;

    public const
        TableName = 'competitor',
        ColumnId = 'id',
        ColumnName = 'first_name',
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
        $values['start_number'] = $this->getNewStartNumber() + 1;
        return $this->getTable()->insert($values);
    }
    public function dellUser($id){
        return $this->getTable()->where(self::ColumnId,$id)->delete();
    }
    public function getNewStartNumber(){
        return $this->getTable()->select("MAX(start_number) newNumber")->fetch()['newNumber'];
    }

    public function generateDiplom($category){
        $mpdf = new Mpdf([
            'tempDir' => __DIR__ . '/temp/',
            'mode' => 'utf-8',
            'format' => [148, 210],
            'default_font' => 'Poppins,sans-serif'
            //'orientation' => 'L'
        ]);

        $pagecount = $mpdf->SetSourceFile(__DIR__ ."/pdf/diplom_generator_a5.pdf");
        $tplId = $mpdf->ImportPage($pagecount);
        $mpdf->UseTemplate($tplId);

        $results = $this->database->query("SELECT * FROM competitor WHERE category = ? ORDER BY vysledne_poradi, score DESC, ((SELECT IFNULL(SUM((first_competitor_mistakes*1000)+first_competitor_time),0) FROM matches WHERE first_competitor = competitor.id) + (SELECT IFNULL(SUM((second_competitor_mistakes*1000)+second_competitor_time),0) FROM matches WHERE second_competitor = competitor.id))",$category);

        $i = 1;
        foreach ($results as $usr){
            //$mpdf->WriteHTML();
            $mpdf->UseTemplate($tplId);

            $mpdf->WriteFixedPosHTML('<div style="font-size: 70px;font-weight: bolder;text-align: center; " width="100%">' . $i++ . '.</div>', 50, 49, 50, 30, 'auto');
            $mpdf->WriteFixedPosHTML('<div style="font-size: 27px;font-weight: bold;text-align: center;" width="100%">' . $usr['first_name'] . ' '. $usr['last_name'] . '</div>', 35, 79, 80, 30, 'auto');
            $mpdf->WriteFixedPosHTML('<div style="font-size: 23px;" width="100%">' .array(1=>'Mladší',2=>'Starší',3=>'Dorost',99=>"-")[$usr['category']]. '</div>', 63, 136, 50, 20, 'auto');
            //vysledky
            $result_per_user = $this->database->table("matches")->whereOr(["first_competitor" => $usr['id'], "second_competitor"=>$usr['id']]);
            $str_out = "";
            $y = 1;
            foreach ($result_per_user as $usr_res){
                $str_out.= ($y).".kolo: ".$usr_res->ref('competitor', 'first_competitor')->first_name." ".$usr_res->ref('competitor', 'first_competitor')->last_name." ". $usr_res->first_competitor_time."s [".$usr_res->first_competitor_mistakes." špatně]  <> ". $usr_res->ref('competitor', 'second_competitor')->first_name." ".$usr_res->ref('competitor', 'second_competitor')->last_name." ". $usr_res->second_competitor_time."s [".$usr_res->second_competitor_mistakes." špatně]<br>";
                $y++;
                //$str_out.= $y.".kolo: ".$usr_res['first_competitor_time']."[".$usr_res['first_competitor_mistakes']."]";
            }



            $mpdf->WriteFixedPosHTML('<div style="font-size: 11px;" width="100%" height="100%">' .$str_out. '</div>', 15, 168, 120, 70, 'auto');

            $mpdf->AddPage();
        }

        $mpdf->Output();
    }

    public function getResults($category){
        return $this->database->query("SELECT *,((SELECT IFNULL(SUM((first_competitor_mistakes*1000)+first_competitor_time),0) FROM matches WHERE first_competitor_time IS NOT NULL and first_competitor = competitor.id) + (SELECT IFNULL(SUM((second_competitor_mistakes*1000)+second_competitor_time),0) FROM matches WHERE second_competitor_time IS NOT NULL AND second_competitor = competitor.id)) as koef FROM competitor WHERE category = ? ORDER BY vysledne_poradi,score DESC, ((SELECT SUM((first_competitor_mistakes*1000)+first_competitor_time) FROM matches WHERE first_competitor = competitor.id) + (SELECT SUM((second_competitor_mistakes*1000)+second_competitor_time) FROM matches WHERE second_competitor = competitor.id))",$category);
    }

}