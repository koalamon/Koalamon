<?php
/**
 * Created by PhpStorm.
 * User: nils.langner
 * Date: 30.12.15
 * Time: 13:56
 */

namespace whm\NotificationEngineBundle\Sender;


class SenderFactory
{
    static public function getSenders()
    {
        return [
            'slack' => new SlackSender(),
        ];
    }

    /**
     * @param string $senderType
     * @return Sender
     */
    static public function getSender($senderType)
    {
        $sender = self::getSenders();
        return $sender[$senderType];
    }
}