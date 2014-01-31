<?php
class Hackathon_MailQueue_Model_QueueHandler extends Lilmuckers_Queue_Model_Queue_Abstract
{
    public function __construct($queue = null)
    {
        parent::__construct(array('queue' => 'email_queue'));
    }
}