<?php
namespace Loop54\API\RemoteClientInfo;

final class CustomClient implements Client
{
    private $baseClient;

    private $customUserId;
    private $customUserIp;
    private $customUserAgent;
    private $customReferer;

    /**
     * Object allowing individual RemoteClientInfo operations to be overridden
     * by custom callables. If a method does not have an override configured,
     * the base client is queried instead.
     *
     * @param Client $baseClient
     */
    public function __construct($baseClient)
    {
        $this->baseClient = $baseClient;
    }

    /**
     * Replace the userId method with a custom callable.
     *
     * @param callable $customUserId
     *
     * @return CustomClient
     */
    public function customUserId($customUserId)
    {
        $this->customUserId = $customUserId;
        return $this;
    }

    /**
     * Replace the userIp method with a custom callable.
     *
     * @param callable $customUserIp
     *
     * @return CustomClient
     */
    public function customUserIp($customUserIp)
    {
        $this->customUserIp = $customUserIp;
        return $this;
    }

    /**
     * Replace the userAgent method with a custom callable.
     *
     * @param callable $customUserAgent
     *
     * @return CustomClient
     */
    public function customUserAgent($customUserAgent)
    {
        $this->customUserAgent = $customUserAgent;
        return $this;
    }

    /**
     * Replace the referer method with a custom callable.
     *
     * @param callable $customReferer
     *
     * @return CustomClient
     */
    public function customReferer($customReferer)
    {
        $this->customReferer = $customReferer;
        return $this;
    }

    public function userId($userId = null)
    {
        if (isset($this->customUserId)) {
            return ($this->customUserId)($userId);
        } else {
            return $this->baseClient->userId($userId);
        }
    }

    public function userIp($userIp = null)
    {
        if (isset($this->customUserIp)) {
            return ($this->customUserIp)($userIp);
        } else {
            return $this->baseClient->userIp($userIp);
        }
    }

    public function userAgent($userAgent = null)
    {
        if (isset($this->customUserAgent)) {
            return ($this->customUserAgent)($userAgent);
        } else {
            return $this->baseClient->userAgent($userAgent);
        }
    }

    public function referer($referer = null)
    {
        if (isset($this->customReferer)) {
            return ($this->customReferer)($referer);
        } else {
            return $this->baseClient->referer($referer);
        }
    }
}
