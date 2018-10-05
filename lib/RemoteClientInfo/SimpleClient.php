<?php
namespace Loop54\API\RemoteClientInfo;

final class SimpleClient implements Client
{
    private $userId;
    private $userIp;
    private $userAgent;
    private $referer;

    public function __construct($userIp, $userAgent, $referer)
    {
        $this->userIp = $userIp;
        $this->userAgent = $userAgent;
        $this->referer = $referer;
    }

    public function userId($userId = null)
    {
        if (isset($userId)) {
            $this->userId = $userId;
            return $userId;
        } elseif (isset($this->userId)) {
            return $this->userId;
        } else {
            return null;
        }
    }

    public function userIp($userIp = null)
    {
        return isset($userIp) ? $userIp : $this->userIp;
    }

    public function userAgent($userAgent = null)
    {
        return isset($userAgent) ? $userAgent : $this->userAgent;
    }

    public function referer($referer = null)
    {
        return isset($referer) ? $referer : $this->referer;
    }
}
