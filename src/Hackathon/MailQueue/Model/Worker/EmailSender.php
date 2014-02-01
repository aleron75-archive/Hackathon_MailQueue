<?php
class Hackathon_MailQueue_Model_Worker_EmailSender extends Lilmuckers_Queue_Model_Worker_Abstract
{
    /**
     * This task ended properly
     * $task->success();
     *
     * This task needs to be repeated
     * $task->retry();
     *
     * This task errored and we should drop it from the queue for later examination
     * $task->hold();
     *
     * This worker is taking a long time, we should extend the time we're allowed to use it
     * $task->touch();
     *
     * @param Lilmuckers_Queue_Model_Queue_Task $task
     */
    public function sendEmail(Lilmuckers_Queue_Model_Queue_Task $task)
    {
        Mage::log('task data: ' . print_r($task->getData(), true), null, 'email_queue.log', true);

        $mail = new Zend_Mail('utf-8');

        $mail->setDate($task->getDate());
        $mail->setFrom($task->getFrom());

        foreach ($task->getRecipients() as $type => $recipients) {
            switch ($type) {
                case 'cc':
                    foreach ($recipients as $email => $name) {
                        $mail->addCc($email, $name);
                    }
                    break;

                case 'bcc':
                    foreach ($recipients as $email) {
                        $mail->addBcc($email);
                    }
                    break;

                case 'to':
                    // break intentionally omitted
                default:
                    foreach ($recipients as $email => $name) {
                        $mail->addTo($email, $name);
                    }
                    break;
            }
        }
        $mail->setReplyTo($task->getReplyTo());
        $mail->setSubject($task->getSubject());
        if ($task->getBodyText()) $mail->setBodyText($task->getBodyText());
        if ($task->getBodyHtml()) $mail->setBodyHtml($task->getBodyHtml());

        $transport = $task->getTransport();

        try {
            $mail->send($transport);
            $task->success();
            Mage::log('sendEmail executed', null, 'email_queue.log', true);
        } catch (Exception $e) {
            $task->hold();
            Mage::log('Exception: ' . $e->getMessage(), null, 'email_queue.log', true);
            Mage::log('sendEmail deferred', null, 'email_queue.log', true);
        }
    }
}