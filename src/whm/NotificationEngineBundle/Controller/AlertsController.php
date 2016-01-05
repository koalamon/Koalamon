<?php

namespace whm\NotificationEngineBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Controller\ProjectAwareController;
use Bauer\IncidentDashboard\CoreBundle\Entity\Tool;
use Bauer\IncidentDashboard\CoreBundle\Entity\UserRole;
use Symfony\Component\HttpFoundation\Request;
use whm\NotificationEngineBundle\Entity\NotificationConfiguration;
use whm\NotificationEngineBundle\Sender\SlackSender;

class AlertsController extends ProjectAwareController
{
    public function indexAction()
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        $configs = $this->getDoctrine()->getRepository('whmNotificationEngineBundle:NotificationConfiguration')
            ->findBy(array('project' => $this->getProject()), ["name" => "ASC"]);

        return $this->render('whmNotificationEngineBundle:Alerts:index.html.twig', array('configs' => $configs));
    }

    public function editAction(NotificationConfiguration $notificationConfiguration)
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        $tools = $this->getDoctrine()->getRepository('BauerIncidentDashboardCoreBundle:Tool')
            ->findBy(array('project' => $this->getProject(), 'active' => true), ["name" => "ASC"]);

        return $this->render('whmNotificationEngineBundle:Alerts:edit.html.twig', array('config' => $notificationConfiguration, 'tools' => $tools));
    }

    public function storeAction(NotificationConfiguration $notificationConfiguration, Request $request)
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        if ($request->get('notify_all') === "true") {
            $notificationConfiguration->setNotifyAll(true);
            $notificationConfiguration->clearConnectedTools();
        } else {
            $notificationConfiguration->setNotifyAll(false);
            $notificationConfiguration->clearConnectedTools();

            foreach ($request->get('tools') as $toolId => $value) {
                $tool = $this->getDoctrine()->getRepository('BauerIncidentDashboardCoreBundle:Tool')
                    ->find((int)$toolId);
                /** @var Tool $tool */

                if ($tool->getProject() == $this->getProject()) {
                    $notificationConfiguration->addConnectedTool($tool);
                }
            }
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($notificationConfiguration);
        $em->flush();

        return $this->redirectToRoute('whm_notification_engine_alerts_home', ['project' => $this->getProject()->getIdentifier()]);
    }
}
