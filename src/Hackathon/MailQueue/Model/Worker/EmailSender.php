<?php
class Hackathon_MailQueue_Model_Worker_EmailSender extends Lilmuckers_Queue_Model_Worker_Abstract
{
    public function sendEmail(Lilmuckers_Queue_Model_Queue_Task $task)
    {
        Mage::log('task data: ' . print_r($task->getData(), true), null, 'email_queue.log', true);

        $mail = new Zend_Mail('utf-8');

        $mail->setDate($task->getDate());
        $mail->setFrom($task->getFrom());
        foreach ($task->getTo() as $email => $name) {
            $mail->addTo($email, $name);
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

        //This task ended properly
        //$task->success();

        //this task needs to be repeated
        //$task->retry();

        //this task errored and we should drop it from the queue for later examination
        //$task->hold();

        //this worker is taking a long time, we should extend the time we're allowed to use it
        //$task->touch();
    }

    public function sendOrderUpdateEmail(Lilmuckers_Queue_Model_Queue_Task $task)
    {
        $orderId = $task->getOrderId();
        /** @var Mage_Sales_Model_Order $order */
        $order = Mage::getModel('sales/order')->load($orderId);
        if (! $order->getId()) {
            $task->hold();
            return;
        }

        try {
            $order->sendOrderUpdateEmail(true, 'This was sent from a wonderful queue engine!');
            Mage::log('sendOrderUpdateEmail executed', null, 'email_queue.log', true);
            $task->success();
            return;
        } catch (Exception $e) {
            Mage::log('sendOrderUpdateEmail delayed', null, 'email_queue.log', true);
            $task->retry();
        }
    }
}