<?php

namespace AppBundle\Service;

use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManager;

class UserService
{

    const SIGNATURE = "<br><br>Regards<br>Cordialement<br> تحياتي";

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var array
     */
    private $emailFrom;

    public function __construct(\Swift_Mailer $mailer, EntityManager $em, $emailFrom)
    {
        $this->mailer = $mailer;
        $this->em = $em;
        $this->emailFrom = $emailFrom;
    }

    /**
     * @param $data email details
     */
    function sendEmailToAllUsers($data)
    {
        $subject = $data["subject"];
        $body = $data["content"] . self::SIGNATURE;
        $recipients = $this->em->createQueryBuilder()
            ->from("AppBundle:User", "u")
            ->select("u.email")
            ->where("u.enabled = 1")
            ->getQuery()
            ->getScalarResult();

        $message = $this->mailer->createMessage();

        $message->setSubject($subject)
            ->setFrom([$this->emailFrom[0] => $this->emailFrom[1]])
            ->setBody($body, 'text/html');

        foreach ($recipients as $recepient) {
            $message->setTo($recepient["email"]);
            $this->mailer->send($message);
        }
    }
}
