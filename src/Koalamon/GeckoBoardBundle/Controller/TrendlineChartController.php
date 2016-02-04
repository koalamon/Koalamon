<?php

namespace Koalamon\GeckoBoardBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Controller\ProjectAwareController;
use Bauer\IncidentDashboard\CoreBundle\Entity\EventIdentifier;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TrendlineChartController extends ProjectAwareController
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
            $limit = max($request->get('limit'), 3);
        } else {
            $limit = 3;
        }

        $conn = $this->container->get('database_connection');
        $sql = "SELECT value FROM `event` WHERE event_identifier_id=" . $eventIdentifier->getId() . " ORDER BY created desc limit " . $limit;

        $rows = $conn->query($sql)->fetchAll();

        // prepare first element
        $top = new \StdClass;
        if ($request->get('text')) {
            $top->text = $request->get('text');
        }

        if ($request->get('prefix')) {
            $top->prefix = $request->get('prefix');
        }

        if (($value = array_shift($rows)['value']) !== NULL) {
            $top->value = $value;
        }


        // prepare bottom element
        $bottom = [];
        foreach ($rows as $row) {
            $bottom[] = (string)round($row['value'], $decimal);
        }


        // merge top and bottom element
        $datas = [$top, $bottom];

        $chart = new \StdClass;
        $chart->item = $datas;

        $response = new JsonResponse($chart);
        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);

        return $response;
    }
}

