<?php
use Illuminate\Support\Facades\Mail;

/**
 * Created by PhpStorm.
 * User: shawnlegge
 * Date: 20/2/18
 * Time: 12:34 PM
 */
trait TestEmailsTrait
{
    /**
     * @var array
     */
    protected $emails = [];

    /**
     * adds an email to the email property
     *
     * @param Swift_Message $email
     */
    public function addEmail(Swift_Message $email)
    {
        $this->emails[] = $email;
    }

    /**
     * sets up the mail functionality
     *
     * @return $this
     */
    protected function setUpEmails()
    {
        Mail::getSwiftMailer()
            ->registerPlugin(new TestMailEvent($this));

        return $this;
    }

    /**
     * asserts if an email was sent
     *
     * @return $this
     */
    protected function seeEmailWasSent()
    {
        $this->assertNotEmpty($this->emails, "Did not expect any emails to be sent");
        return $this;
    }

    /**
     * asserts if an email was not sent
     *
     * @return $this
     */
    protected function seeEmailWasNotSent()
    {
        $this->assertEmpty($this->emails, "Emails have been sent");
        return $this;
    }

    /**
     * assert how many emails were sent
     * @param $count
     *
     * @return $this
     */
    protected function seeEmailsSent($count)
    {
        $actualCount = count($this->emails);

        $this->assertCount($count, $this->emails,
            "Expected $count emails to be sent, but $actualCount was sent");

        return $this;
    }

    /**
     * asserts the email was sent to the recipient
     *
     * @param $recipient
     * @param Swift_Message|null $message
     * @return $this
     */
    protected function seeEmailTo($recipient, Swift_Message $message = null)
    {
        $email = $message ?: end($this->emails);
        $to = key($email->getTo());

        $this->assertEquals($recipient, $to, "No email was sent to $recipient, it was sent to $to");

        return $this;
    }

    /**
     * asserts the email was sent from sender
     *
     * @param $sender
     * @param Swift_Message|null $message
     * @return $this
     */
    protected function seeEmailFrom($sender, Swift_Message $message = null)
    {
        $email = $message ?: end($this->emails);
        $from = key($email->getFrom());

        $this->assertEquals($sender, $from, "No email was sent from $sender, it was sent from $from");

        return $this;
    }

    /**
     * gets the last message
     *
     * @param Swift_Message|null $message
     * @return array
     */
    protected function getEmail(Swift_Message $message = null)
    {
        $this->seeEmailWasSent();

        return $message ?: $this->lastEmail();
    }

    /**
     * asserts the email subject equals
     *
     * @param $subject
     * @param Swift_Message|null $message
     * @return $this
     */
    protected function seeEmailSubject($subject, Swift_Message $message = null)
    {
        $email = $message ?: end($this->emails);

        $this->assertEquals($subject, $email->getSubject(),
            "Expected email subject to be $subject");

        return $this;
    }

    /**
     * asserts the email body equals
     *
     * @param $body
     * @param Swift_Message|null $message
     * @return $this
     */
    protected function seeEmailEquals($body, Swift_Message $message = null)
    {
        $this->assertEquals($body, $this->getEmail($message)->getBody(),
            "Expected email body to be $body");

        return $this;
    }


    /**
     * asserts the email body equals
     *
     * @param $body
     * @param Swift_Message|null $message
     * @return $this
     */
    protected function seeEmailContains($body, Swift_Message $message = null)
    {
        $this->assertContains($body, $this->getEmail($message)->getBody(),
            "Expected email body does not contain $body");

        return $this;
    }


    /**
     * gets the last email
     *
     * @return array
     */
    protected function lastEmail()
    {
        return end($this->emails);
    }
}

class TestMailEvent implements Swift_Events_EventListener
{
    protected $testClass;

    /**
     * TestMailEvent constructor.
     * @param $testClass
     */
    public function __construct($testClass)
    {
        $this->testClass = $testClass;
    }


    /**
     * sets the email property in the test email trait
     *
     * @param $event
     * @return void
     */
    public function beforeSendPerformed($event)
    {
        $this->testClass->addEmail($event->getMessage());
    }
}