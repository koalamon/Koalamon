<?php

namespace Koalamon\GeckoBoardBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Controller\ProjectAwareController;
use Bauer\IncidentDashboard\CoreBundle\Entity\EventIdentifier;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class NumberChartController extends ProjectAwareController
{
    /**
     * @param Request $request
     * @param EventIdentifier $eventIdentifier
     * @return JsonResponse
     */
    public function renderAction(Request $request, EventIdentifier $eventIdentifier)
    {
        if ($request->get('decimal')) {
            $decimal = $request->get('decimal');
        } else {
            $decimal = 0;
        }

        if ($request->get('limit')) {
            $limit = max($request->get('limit'), 2);
        } else {
            $limit = 2;
        }

        $conn = $this->container->get('database_connection');
        $sql = "SELECT value FROM `event` WHERE event_identifier_id=" . $eventIdentifier->getId() . " ORDER BY created desc limit " . $limit;

        $rows = $conn->query($sql)->fetchAll();

        $chart = new \StdClass;
        $datas = [];

        foreach ($rows as $row) {
            $datas[]['value'] = round($row['value'], $decimal);
        }

        $chart->item = $datas;

        $response = new JsonResponse($chart);
        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);

        return $response;
    }
}

