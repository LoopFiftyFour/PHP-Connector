<?php
namespace Loop54\API\RemoteClientInfo;

interface Client
{
    public function userId($userId = null);
    public function userIp($userIp = null);
    public function userAgent($userAgent = null);
    public function referer($referer = null);
}
