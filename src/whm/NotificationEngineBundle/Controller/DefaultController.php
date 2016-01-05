<?php

namespace whm\NotificationEngineBundle\Controller;

use Bauer\IncidentDashboard\CoreBundle\Controller\ProjectAwareController;
use Bauer\IncidentDashboard\CoreBundle\Entity\UserRole;
use Symfony\Component\HttpFoundation\Request;
use whm\NotificationEngineBundle\Entity\NotificationConfiguration;
use whm\NotificationEngineBundle\Sender\SlackSender;

class DefaultController extends ProjectAwareController
{
    private function getSenders()
    {
        return [
            'slack' => ['name' => 'Slack', 'description' => '', 'sender' => new SlackSender()],
        ];
    }

    public function indexAction()
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        $senders = $this->getSenders();

        $configs = $this->getDoctrine()->getRepository('whmNotificationEngineBundle:NotificationConfiguration')
            ->findBy(array('project' => $this->getProject()));

        return $this->render('whmNotificationEngineBundle:Default:index.html.twig', array('senders' => $senders, 'configs' => $configs));
    }

    public function deleteAction(NotificationConfiguration $notificationConfiguration)
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        $em = $this->getDoctrine()->getManager();

        $em->remove($notificationConfiguration);
        $em->flush();

        return $this->redirectToRoute('whm_notification_engine_home', ['project' => $this->getProject()->getIdentifier()]);
    }

    public function createAction($senderIdentifier)
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        $senders = $this->getSenders();
        $sender = $senders[$senderIdentifier]['sender'];

        $config = new NotificationConfiguration();
        $config->setSenderType($senderIdentifier);

        return $this->render('whmNotificationEngineBundle:Default:create.html.twig', array('sender' => $sender, 'config' => $config));
    }

    public function editAction(NotificationConfiguration $notificationConfiguration)
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        $senders = $this->getSenders();
        $sender = $senders[$notificationConfiguration->getSenderType()]['sender'];

        return $this->render('whmNotificationEngineBundle:Default:create.html.twig', array('sender' => $sender, 'config' => $notificationConfiguration));
    }

    public function storeAction(Request $request)
    {
        $this->assertUserRights(UserRole::ROLE_ADMIN);

        $em = $this->getDoctrine()->getManager();

        if($request->get('configurationId') > 0) {
            $configuration = $this->getDoctrine()->getRepository('whmNotificationEngineBundle:NotificationConfiguration')
                ->find($request->get('configurationId'));
        }else{
            $configuration = new NotificationConfiguration();
            $configuration->setNotifyAll(false);
        }

        $configuration->setOptions($request->get('options'));
        $configuration->setSenderType($request->get('senderIdentifier'));
        $configuration->setProject($this->getProject());
        $configuration->setName($request->get('name'));

        $em->persist($configuration);
        $em->flush();

        return $this->redirectToRoute('whm_notification_engine_home', ['project' => $this->getProject()->getIdentifier()]);
    }
}
