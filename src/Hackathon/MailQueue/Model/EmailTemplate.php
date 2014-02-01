<?php
class Hackathon_MailQueue_Model_EmailTemplate extends Mage_Core_Model_Email_Template
{
//    public function send($email, $name = null, array $variables = array())
//    {
//        $queue = Mage::helper('lilqueue')->getQueue('email_queue');
//
//        $data = array(
//            'object_data' => $this->getData(),
//            'email' => $email,
//            'name' => $name,
//            'variables' => $variables
//        );
//
//        Mage::log('send variables: ' . print_r($variables, true) , null, 'email_queue.log', true);
//
//        $task = Mage::helper('lilqueue')->createTask(
//            'sendEmail',
//            $data,
//            Mage::app()->getStore()
//        );
//
//        $queue->addTask($task);
//
//        return true;
//    }

    public function getMail()
    {
        if (is_null($this->_mail)) {
            $this->_mail = new Hackathon_MailQueue_Model_Mail('utf-8');
        }
        return $this->_mail;
    }

}