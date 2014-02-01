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
        $helper = Mage::helper('hmq');

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
            $helper->log("EmailSender Worker executed sendEmail() successfully");
        } catch (Exception $e) {
            $taskInfo = $task->getInfo();
            if ($taskInfo->getAge() < (int) $helper->getRetryTimeout()) {
                $task->retry();
                $helper->log("EmailSender Worker didn't execute sendEmail() successfully; a further attempt was scheduled");
            } else {
                $task->hold();
                $helper->log("EmailSender Worker aborted sendEmail() execution dued to following exception: '%s'", $e->getMessage());
            }
        }
    }
}