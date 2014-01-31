<?php
class Hackathon_MailQueue_Model_Worker_EmailSender extends Lilmuckers_Queue_Model_Worker_Abstract
{
    public function sendEmail(Lilmuckers_Queue_Model_Queue_Task $task)
    {
        //get the store assigned with the task (defaults to the store that was running when the task was assigned)
        $store = $task->getStore();

        //get the queue handler for this queue
        $queue = $task->getQueue();

        //get the data assigned with the task
        $data = $task->getData();  // $task->getSpecificData();

        Mage::log('sendEmail executed', null, 'email_queue.log', true);

        //This task ended properly
        $task->success();

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