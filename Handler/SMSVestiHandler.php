<?php
namespace Karser\SMSVestiBundle\Handler;


use Guzzle\Http\Client;
use Karser\SMSBundle\Entity\HlrInterface;
use Karser\SMSBundle\Entity\SMSTaskInterface;
use Karser\SMSBundle\Handler\AbstractHandler;
use Karser\SMSBundle\Handler\HandlerInterface;

class SMSVestiHandler extends AbstractHandler implements HandlerInterface
{
    protected $name = 'karser.handler.sms_vesti';

    protected $cost = 0.1;

    /** @var string */
    private $login;

    /** @var string */
    private $password;

    public function __construct($login, $password)
    {
        $this->login = $login;
        $this->password = $password;
    }

    private function postQuery($path, array $params = [])
    {
        $client = new Client('http://api.smsvesti.ru/interfaces/');
        $params = array_merge(['login'=> $this->login, 'pwd'=> $this->password], $params);
        $request = $client->post($path)->addPostFields($params);
        return $request->send();
    }

    public function supports($number, HlrInterface $hlr = null)
    {
        if ($hlr) {
            return $hlr->getOpsos() === 'МегаФон';
        }
        return parent::supports($number, $hlr);
    }

    public function getBalance()
    {
        $response = $this->postQuery('getbalance.ashx');
        $string = (string) $response->getBody();
        return (float) $string;
    }

    public function send(SMSTaskInterface $SMSTask)
    {
        $params = ['phones' => $SMSTask->getPhoneNumber(), 'message' => $SMSTask->getMessage(), 'sender' => $SMSTask->getSender()];
        $response = $this->postQuery('SendMessages.ashx', $params);
        $string = (string) $response->getBody();
        if (preg_match('/Ok:(.+?);/', $string, $matches) !== 1) {
            throw new \Exception('error');
        }
        return (int) $matches[1];
    }

    public function checkStatus($message_id)
    {
        $params = ['ids' => $message_id];
        $response = $this->postQuery('GetMessagesState.ashx', $params);
        $string = (string) $response->getBody();
        if (preg_match('/Ok:(.+?);/', $string, $matches) !== 1) {
            throw new \Exception('error');
        }
        $status = (float) $matches[1];
        switch ($status) {
            case 0:
            case 1:
            case 2:
                return SMSTaskInterface::STATUS_PROCESSING;
            case 3:
                return SMSTaskInterface::STATUS_SENT;
            default:
                return SMSTaskInterface::STATUS_FAIL;
        }
    }
}
