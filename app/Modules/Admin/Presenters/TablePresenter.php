<?php

declare(strict_types=1);

namespace App\Module\Admin\Presenters;

use App\Model\CompetitorManager;
use App\Model\MatchesManager;
use Nette;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Utils\ArrayHash;


final class TablePresenter extends BasePresenter
{

    private MatchesManager $matchesManager;
    private CompetitorManager $competitorManager;

    public function __construct(MatchesManager $matchesManager,CompetitorManager $competitorManager)
    {
        parent::__construct();
        $this->matchesManager = $matchesManager;
        $this->competitorManager = $competitorManager;
    }

    public function actionSaveResult($id)
    {
        $httpRequest = $this->getHttpRequest();
        $dats = $this->getHttpRequest()->getPost();


        list($minutes, $seconds) = explode(':', $dats['first_competitor_time']);
        list($sec, $frac) = explode('.', $seconds);

        // Převedení na celkový čas v sekundách
        $dats['first_competitor_time'] = $minutes * 60 + $sec + $frac / 100;


        list($minutes, $seconds) = explode(':', $dats['second_competitor_time']);
        list($sec, $frac) = explode('.', $seconds);

        // Převedení na celkový čas v sekundách
        $dats['second_competitor_time'] = $minutes * 60 + $sec + $frac / 100;


        $this->matchesManager->editMatche($dats['match_id'],
            array(
                'first_competitor_time' => $dats['first_competitor_time'],
                'first_competitor_mistakes' => $dats['first_competitor_mistakes'],
                'second_competitor_time' => $dats['second_competitor_time'],
                'second_competitor_mistakes' => $dats['second_competitor_mistakes']
            ));

        $match = $this->matchesManager->getMatche($dats['match_id']);
        if (($dats['first_competitor_time'] + 1000 * $dats['first_competitor_mistakes']) < ($dats['second_competitor_time'] + 1000 * $dats['second_competitor_mistakes'])){
            $this->competitorManager->editUser($match['first_competitor'], array("round+=" => 1, "score+=" => 1));
            $this->competitorManager->editUser($match['second_competitor'], array("round+=" => 1));
        }else {
            $this->competitorManager->editUser($match['second_competitor'], array("round+=" => 1, "score+=" => 1));
            $this->competitorManager->editUser($match['first_competitor'], array("round+=" => 1));
        }
        $this->redirect(":default",$id);


    }

    public function renderDefault($id,$selected)
    {
        $this->template->id = $id;
        $this->template->matches = $this->matchesManager->getMatchesPerTable($id);
        foreach ($this->template->matches as $match){
            if ($selected != null){
                if ($match->id == $selected){
                    $this->template->first_match = $match;
                    break;
                }
            }else{
                if ($match->first_competitor_time == null){
                    $this->template->first_match = $match;
                    break;
                }
            }
        }
        $kl = array_keys($this->template->matches);
        for ($x = 0;$x < count($kl);$x++){
            if(isset($this->template->first_match) && $this->template->matches[$kl[$x]]['round'] != $this->template->first_match['round']){
                unset($this->template->matches[$kl[$x]]);
            }
        }
    }



}
