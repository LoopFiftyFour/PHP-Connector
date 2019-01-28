<?php
namespace Loop54\API\RemoteClientInfo;

use DateTime;
use DateInterval;
use DomainException;
use Ramsey\Uuid\Uuid;

final class WebClient implements Client
{
    const USER_ID_COOKIE_KEY = 'Loop54User';

    /**
     * By default, return the user ID value set in cookies. If user ID is
     * specified as an argument, set the user ID cookie.
     *
     * @param string|null $userId
     *    Optional user ID. Sets the user ID cookie when specified.
     *
     * @return string
     */
    public function userId($userId = null)
    {
        if (isset($userId)) {
            $now = new DateTime();
            $oneYear = new DateInterval('P1Y');
            setcookie(
                self::USER_ID_COOKIE_KEY,
                $userId,
                $now->add($oneYear)->getTimestamp(),
                '/'
            );
            return $userId;
        } elseif (isset($_COOKIE[self::USER_ID_COOKIE_KEY])
                  || array_key_exists(self::USER_ID_COOKIE_KEY, $_COOKIE)
        ) {
            return $_COOKIE[self::USER_ID_COOKIE_KEY];
        } else {
            return null;
        }
    }

    /**
     * Tries to read the user IP address from various parts of the $_SERVER
     * array and return it. Will return the string "unknown" if no IP address
     * can be discovered.
     *
     * @param string|null $userIp
     *    Optional user IP. Simply returns this when specfied.
     *
     * @return string
     */
    public function userIp($userIp = null)
    {
        if (isset($userIp)) {
            return $userIp;
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
            return $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
            return $_SERVER['HTTP_FORWARDED'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        } else {
            return 'unknown';
        }
    }

    /**
     * Returns either the specified $userAgent or returns the value for
     * HTTP_USER_AGENT in the $_SERVER array.
     *
     * @param string|null $userAgent
     *    Optional user agent. Simply returns this when specfied.
     *
     * @return string
     */
    public function userAgent($userAgent = null)
    {
        return isset($userAgent) ? $userAgent : $_SERVER['HTTP_USER_AGENT'];
    }

    /**
     * Returns either the specified $referer or returns the value for
     * HTTP_REFERER in the $_SERVER array.
     *
     * @param string|null $referer
     *    Optional referer. Simply returns this when specfied.
     *
     * @return string
     */
    public function referer($referer = null)
    {
        return isset($referer) ? $referer : $_SERVER['HTTP_REFERER'];
    }
}
