<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alessandro
 * Date: 1/31/14
 * Time: 11:45 PM
 * To change this template use File | Settings | File Templates.
 */

class Hackathon_MailQueue_Model_Mail extends Zend_Mail
{
    protected $_myRecipients = array();
    protected $_myBodyHtml = false;
    protected $_myBodyText = false;

    public function addTo($email, $name = '')
    {
        $this->_myRecipients[$email] = $name;

        return parent::addTo($email, $name);
    }

    public function setBodyHtml(
        $html,
        $charset = null,
        $encoding = Zend_Mime::ENCODING_QUOTEDPRINTABLE
    ) {
        $this->_myBodyHtml = $html;

        return parent::setBodyHtml(
            $html,
            $charset,
            $encoding
        );
    }

    public function setBodyText(
        $txt,
        $charset = null,
        $encoding = Zend_Mime::ENCODING_QUOTEDPRINTABLE
    ) {
        $this->_myBodyText = $txt;

        return parent::setBodyText(
            $txt,
            $charset,
            $encoding
        );
    }


    public function send($transport = null)
    {
        $queue = Mage::helper('lilqueue')->getQueue('email_queue');

        $data = array(
            'date'      => $this->getDate(),
            'from'      => $this->getFrom(),
            'to'        => $this->_myRecipients,
            'reply_to'  => $this->getReplyTo(),
            'subject'   => $this->getSubject(),
            'body_text' => $this->_myBodyText,
            'body_html' => $this->_myBodyHtml,
            'transport' => $transport,
        );

        $task = Mage::helper('lilqueue')->createTask(
            'sendEmail',
            $data,
            Mage::app()->getStore()
        );

        $queue->addTask($task);

        return $this;
    }
}