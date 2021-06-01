<?php

namespace Applications\App\Modules\Analytics;

class AnalyticsController extends \Library\BackController
{
    public function executeIndex(\Library\HTTPRequest $request)
    {
        $this->page->addVar("titles", "Analytics"); // Titre de la page
        $this->page->addVar('Debut', $request->postData('Debut'));
        $this->page->addVar('Fin', $request->postData('Fin'));
        $TotalVersement = 0;
        $TotalRetrait = 0;
        $Commission = 0;
        $CommissionRetrait = 0;
        if (!empty($request->postData('Debut')) && !empty($request->postData('Fin'))) {
            $Operations = $this->managers->getManagerOf('Analytics')->GetOperations($request->postData('Debut'), $request->postData('Fin'));
            $this->page->addVar('Operations', $Operations);
            $this->page->addVar('Debut', $request->postData('Debut'));
            $this->page->addVar('Fin', $request->postData('Fin'));
            foreach ($Operations as $Operation) {
                if ($Operation['RefType'] == 1) {
                    $TotalVersement += $Operation['MontantVersement'];
                    if ($TotalVersement <= (500000000)) {
                        $Commission = $TotalVersement * (0.002);
                    } elseif ($TotalVersement >= 500000001 && $TotalVersement <= 1000000000) {
                        $Commission = $TotalVersement * (0.0018);
                    } elseif ($TotalVersement >= 1000000001) {
                        $Commission = $TotalVersement * (0.0010);
                    }
                } elseif ($Operation['RefType'] == 2) {
                    $TotalRetrait += $Operation['MontantVersement'];
                    if ($TotalRetrait <= (500000000)) {
                        $CommissionRetrait = $TotalRetrait * (0.0015);
                    } elseif ($TotalRetrait >= 500000001 && $TotalRetrait <= 1000000000) {
                        $CommissionRetrait = $TotalRetrait * (0.00075);
                    } elseif ($TotalRetrait >= 1000000001 && $TotalRetrait <= 2000000000) {
                        $CommissionRetrait = $TotalRetrait * (0.0005);
                    }
                }
            }
        }
        $this->page->addVar('totalVersement', $TotalVersement);
        $this->page->addVar('totalRetrait', $TotalRetrait);
        $this->page->addVar('Commission', $Commission + $CommissionRetrait);
    }
}