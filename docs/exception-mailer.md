The exception handler uses the observer pattern to allow objects to be notified of exceptions.

The exception mailer can be attached to the exception handler so that it sends an email every time an error or exception occurs.

```php
/**
 * Set up error emailing
 */
public function init()
{
    $recipient = $this->dic->registry->ADMIN;
    $mailer = new \Xframe\Exception\Mailer($recipient);
    $this->dic->exceptionHandler->attach($mailer);
}
```
