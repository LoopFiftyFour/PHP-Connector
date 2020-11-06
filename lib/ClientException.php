<?php
namespace Loop54\API;

class ClientException extends \RuntimeException
{
    private $category;
    private $title;
    private $details;

    public function __construct($code, $category, $title, $details)
    {
        $this->category = $category;
        $this->title = $title;
        $this->details = $details;
        parent::__construct(
            "[{$code} {$category}] {$title}: " . $details,
            $code
        );
    }

    public static function unknown($details)
    {
        return new ClientException(
            -1,
            "UnknownError",
            "Something went wrong",
            $details
        );
    }

    public static function http($errorResponse)
    {
        $details = '';
        if(isset($errorResponse->detail))
            $details = $errorResponse->detail;
        
        if (isset($errorResponse->parameter)) {
            $details .= " (Applies to: {$errorResponse->parameter})";
        }
        return new ClientException(
            $errorResponse->code,
            $errorResponse->status,
            $errorResponse->title,
            $details
        );
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDetails()
    {
        return $this->details;
    }
}
