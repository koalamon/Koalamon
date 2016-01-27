<?php

namespace Koalamon\GeckoBoardBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Controller\ProjectAwareController;
use Bauer\IncidentDashboard\CoreBundle\Entity\EventIdentifier;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LineChartController extends ProjectAwareController
{
    public function renderAction(Request $request, EventIdentifier $eventIdentifier)
    {
        if ($request->get('interval')) {
            $intervalType = $request->get('interval');
        } else {
            $intervalType = 'd';
        }

        if ($request->get('decimal')) {
            $decimal = $request->get('decimal');
        } else {
            $decimal = 0;
        }

        if ($request->get('labelInterval')) {
            $labelInterval = $request->get('labelInterval');
        } else {
            $labelInterval = 1;
        }


        switch ($intervalType) {
            case 'h':
                $groupDateForm = "%Y%m%d%H";
                $selectDateForm = "%h %p";
                $interval = 'P1D';
                break;
            case 'd':
                $groupDateForm = "%Y%m%d";
                $selectDateForm = "%d.%m.%Y";
                $interval = 'P1M';
                break;
        }

        $startDate = new \DateTime('now');
        $startDate->sub(new \DateInterval($interval));

        $conn = $this->container->get('database_connection');
        $sql = "SELECT AVG(value) as avgValue, DATE_FORMAT(created, '" . $selectDateForm . "') as timespan FROM `event` WHERE event_identifier_id=" . $eventIdentifier->getId() . " and created > '" . $startDate->format('Y-m-d H:i:s') . "' GROUP BY DATE_FORMAT(created, '" . $groupDateForm . "')";
        $rows = $conn->query($sql)->fetchAll();

        $chart = new \StdClass;
        $chart->x_axis = ['labels' => []];

        $datas = [];

        $i = 0;
        foreach ($rows as $row) {
            if ($i % $labelInterval == 0) {
                $chart->x_axis['labels'][] = strtolower($row['timespan']);
            } else {
                $chart->x_axis['labels'][] = '';
            }
            $i++;
            $datas[] = round($row['avgValue'], $decimal);
        }

        $chart->series = [['data' => $datas]];

        $response = new JsonResponse($chart);
        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);

        return $response;
    }
}
