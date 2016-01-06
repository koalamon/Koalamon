<?php

namespace Koalamon\InformationBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Entity\Project;
use Koalamon\InformationBundle\Entity\Information;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class WebhookController extends Controller
{
    const STATUS_SUCCESS = "success";
    const STATUS_FAILURE = "failure";
    const STATUS_SKIPPED = "skipped";

    public function addAction(Request $request)
    {
        $payload = file_get_contents('php://input');

        file_put_contents("/tmp/koalamon/webhook_information.log", $payload);

        $project = $this->getProject($request->get("api_key"));

        if ($project == null) {
            return $this->getJsonRespone(self::STATUS_FAILURE, "No project with api_key " . $request->get("api_key") . ' found.');
        }

        $rawInformation = json_decode($payload);

        $information = new Information();
        $information->setMessage($rawInformation->message);
        $information->setSeverity(Information::SEVERITY_NOTICE);
        $information->setProject($project);

        $endDate = new \DateTime();
        $endDate->add(new \DateInterval('PT' . $rawInformation->duration . 'M'));

        $information->setEndDate($endDate);

        $em = $this->getDoctrine()->getManager();
        $em->persist($information);
        $em->flush();

        return $this->getJsonRespone(self::STATUS_SUCCESS);
    }

    private function getProject($apiKey)
    {
        $project = $this->getDoctrine()
            ->getRepository('BauerIncidentDashboardCoreBundle:Project')
            ->findOneBy(array("apiKey" => $apiKey));

        return $project;
    }

    private function getJsonRespone($status, $message = "")
    {
        return new JsonResponse(array('status' => $status, 'message' => $message));
    }
}
