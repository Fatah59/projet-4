<?php


namespace App\Service;

use App\Entity\Contact;
use Twig\Environment;

class MailerService
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function mailAction(Contact $contact)
    {
        $message = (new \Swift_Message('Vous avez un nouveau message !'))
            ->setFrom($contact->getEmail())
            ->setTo('projet4@derradjfatah.com')
            ->setBody(
                $this->twig->render(
                // templates/emails/registration.html.twig
                    'email/message.html.twig', [
                        'contact' => $contact
                    ]),
                'text/html');

            // you can remove the following code if you don't define a text version for your emails
//           ->addPart(
//                $this->renderView(
//                // templates/emails/registration.txt.twig
//                    'templates/email/message.txt.twig'
//
//                ),
//                'text/plain'
//            );

        $this->mailer->send($message);
    }
}