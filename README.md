Hackathon MailQueue
===================

Introduction
------------
This module has been conceived during the **Magento Hackathon 2014** (31st Jan - 1st Feb). Read more on the Hackathon site: http://www.mage-hackathon.de/upcoming/online-hackathon-worldwide-31st-jan-1st-feb.html

The module depends on the Lilmuckers_Queue extension, a generic multi-adapter queuing system for Magento.

Read more about that on  https://github.com/lilmuckers/magento-lilmuckers_queue and be sure to have installed one of the supported Queue Backend.

Once enabled, this module defers all email sending tasks to the Queue.

**Pros:**

* the user doesn't have to wait for the email to be sent in order to get server response; thus email sending is no more a blocking task;
* in case of email sending error, the user doesn't get an error and processes depending on sending result aren't affected by the result;
* sending an email can fail in some circumstances; using a queue it ispossible to implement a policy to retry sending a certain number of times and in case of failure report the problem via log or other monitoring systems;
* sending emails process can be execeuted on a different server, gaining performance from the main server;

**Cons:**

* Queue Backend is an addictional piece of architecture that has to be maintained and that can potentially hang up.

Installation
------------
To install this module you need modman Module Manager: https://github.com/colinmollenhour/modman

After having installed modman on your system, you can clone this module on your Magento root folder by typing the following commands:

```
$ modman init
$ modman clone git@github.com:aleron75/Hackathon_MailQueue.git
```

Configuration
-------------
You can enable the functionality of sending emails through a Queue Backend under ```System > Configuration > ADVANCED > System > Mail Queue Settings```.

Usage
-----
Once enabled, the extensions works under the hood; it rewrites the ```Mage_Core_Email_Template``` and overrides the ```getMail()``` method in order to use a specialized version of ```Zend_Mail``` class which does the magic of deferring emails to a queue.

COMPATIBILITY
-------------
Here follows the list of versions the module has been tested on:

* Magento CS v 1.8.0.0

Note: if a version different from the ones listed above doesn't compare, it doesn't necessarily mean that the module won't work on that. If you try the module on different version and it works, please notify the author in order to update the compatibility list. It will be appreciated.

RELEASE NOTES
-------------
* 1.0.0 - the first implementation