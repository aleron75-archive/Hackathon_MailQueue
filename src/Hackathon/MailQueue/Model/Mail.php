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
    /**
     * @var array Used to keep different recipient separately
     */
    protected $_myRecipients = array();

    /**
     * @var false|string Used to keep original HTML Body
     */
    protected $_myBodyHtml = false;

    /**
     * @var false|string Used to keep original TEXT Body
     */
    protected $_myBodyText = false;

    public function __construct($charset = null)
    {
        $this->_myRecipients['to'] = array();
        $this->_myRecipients['cc'] = array();
        $this->_myRecipients['bcc'] = array();
        parent::__construct($charset);
    }


    public function addTo($email, $name = '')
    {
        $this->_myRecipients['to'][$email] = $name;

        return parent::addTo($email, $name);
    }

    public function addCc($email, $name = '')
    {
        $this->_myRecipients['cc'][$email] = $name;

        return parent::addCc($email, $name);
    }

    public function addBcc($email)
    {
        $this->_myRecipients['bcc'][] = $email;

        return parent::addBcc($email);
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

    /**
     * Assign the task of sending the email to the Queue.
     *
     * @param Zend_Mail_Transport_Abstract $transport
     * @return $this|Zend_Mail
     */
    public function send($transport = null)
    {
        $queue = Mage::helper('lilqueue')->getQueue('email_queue');

        $data = array(
            'date'          => $this->getDate(),
            'from'          => $this->getFrom(),
            'recipients'    => $this->_myRecipients,
            'reply_to'      => $this->getReplyTo(),
            'subject'       => $this->getSubject(),
            'body_text'     => $this->_myBodyText,
            'body_html'     => $this->_myBodyHtml,
            'transport'     => $transport,
        );

        $task = Mage::helper('lilqueue')->createTask('sendEmail', $data);
        $queue->addTask($task);

        return $this;
    }
}