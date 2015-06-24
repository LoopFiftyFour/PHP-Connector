<?php
class Loop54_Entity{
public $externalId;public $entityType;private $_attributes;function __construct($entityType, $externalId){
$this->entityType = $entityType;$this->externalId = $externalId;$this->_attributes = array();}
public function hasAttribute($key){
return isset($_attributes[$key]);}
public function getAttribute($key){
if(!array_key_exists($key,$this->_attributes))return null;return $this->_attributes[$key];}
public function getStringAttribute($key){
if(!array_key_exists($key,$this->_attributes))return "";$ret = "";foreach($this->_attributes[$key] as $value){
$ret .= $value . ", ";}
return rtrim(rtrim($ret,' '),',');}
public function addAttribute($key, $value){
if(!array_key_exists($key,$this->_attributes))$this->_attributes[$key] = array();$this->_attributes[$key][] = $value;}
public function setAttribute($key, $values){
if(is_array($values))$this->_attributes[$key] = $values;else{
$list = array();$list[] = $values;$this->_attributes[$key] = $list;}
}
public function removeAttribute($key){
unset($_attributes[$key]);}
public function serialize(){
$ret = "{";$ret .= "\"EntityType\":\"" . $this->entityType . "\",";$ret .= "\"ExternalId\":\"" . $this->externalId . "\"";if(count($this->_attributes)>0){
$ret .= ",\"Attributes\":{";foreach ($this->_attributes as $key=>$values){
$ret .= "\"" . $key . "\":[";foreach ($values as $value){
if($value===null)continue;if (is_string($value))$ret .= "\"" . Loop54_Utils::escape($value) . "\",";else if ($value instanceof DateTime)$ret .= "\"" . $value . "\",";else$ret .= $value . ",";}
$ret = rtrim($ret,',');$ret .= "],";}
$ret = rtrim($ret,',') . "}";}
$ret .= "}";return $ret;}
}
class Loop54_Event{
public $entity = null;public $string = null;public $revenue = 0;public $orderId;public $type;public $quantity = 1;public function serialize(){
$str = "{"."\"OrderId\":\"" . $this->orderId . "\"".",\"Type\":\"" . $this->type . "\"".",\"Revenue\":" . $this->revenue;",\"Quantity\":" . $this->quantity;if ($this->string != null){
$str .= ",\"String\":" . $this->string . "\"";}
if ($this->entity != null){
$str .= ",\"Entity\":" . $this->entity->serialize();}
$str .= "}";return $str;}
}
class Loop54_Options{
public $v22Collections = false;public $v25Url = false;public $timeout = 10;public $gzip = true;}
class Loop54_Request{
private $version = "2015-06-24 14:13:25";public $IP = null;public $userId = null;public $name = null;public $userAgent=null;public $url=null;public $referer=null;public $options = null;private $_data = array();function __construct($requestName,$options = null){
$this->name = $requestName;if($options)$this->options = $options;else$this->options = new Loop54_Options();}
public function setValue($key,$value){
$this->_data[$key] = $value;}
public function serialize(){
if ($this->userId === null)$this->userId = Loop54_Utils::getUser();if ($this->IP === null)$this->IP = Loop54_Utils::getIP();if ($this->userAgent === null)$this->userAgent = Loop54_Utils::getUserAgent();if ($this->url === null)$this->url = Loop54_Utils::getUrl();if ($this->referer === null)$this->referer = Loop54_Utils::getReferer();$ret = "{";if($this->options->v25Url)$ret .= "\"" . $this->name . "\":{";if ($this->IP !== null)$ret .= "\"IP\":\"" . Loop54_Utils::escape($this->IP) . "\",";if ($this->userId !== null)$ret .= "\"UserId\":\"" . Loop54_Utils::escape($this->userId) . "\",";if ($this->userAgent !== null)$ret .= "\"UserAgent\":\"" . Loop54_Utils::escape($this->userAgent) . "\",";if ($this->url !== null)$ret .= "\"Url\":\"" . Loop54_Utils::escape($this->url) . "\",";if ($this->referer !== null)$ret .= "\"Referer\":\"" . Loop54_Utils::escape($this->referer) . "\",";$ret .= "\"LibraryVersion\":" . Loop54_Utils::serializeObject($this->version) . ",";foreach ($this->_data as $key=>$value){
if($value===null)continue;$ret .= "\"" . $key . "\":" . Loop54_Utils::serializeObject($value) . ",";}
$ret = rtrim($ret,',');if($this->options->v25Url)$ret .= "}";$ret .= "}";return $ret;}
}
abstract class Loop54_RequestHandling{
public static function getResponse($engineUrl, $request){
if (!is_string($engineUrl)) {
throw new Exception("Argument engineUrl must be string.");}
$engineUrl = Loop54_Utils::fixEngineUrl($engineUrl);if(!$request->options->v25Url)$engineUrl .= "/" . $request->name;$data = $request->serialize();try {
$s = curl_init($engineUrl);curl_setopt($s,CURLOPT_POST,1); curl_setopt($s,CURLOPT_RETURNTRANSFER, 1 );curl_setopt($s,CURLOPT_POSTFIELDS,$data);curl_setopt($s,CURLOPT_TIMEOUT, $request->options->timeout);curl_setopt($s,CURLOPT_HTTPHEADER,array('Content-Type: text/plain; charset=UTF-8'));if($request->options->gzip)curl_setopt($s,CURLOPT_ENCODING , "gzip");$response = curl_exec($s);$length = curl_getinfo ($s,CURLINFO_CONTENT_LENGTH_DOWNLOAD );if(curl_errno($s)){
throw new Exception('Curl error: ' . curl_error($s));}
curl_close($s);}
catch(Exception $ex){
throw new Exception("Could not retrieve a response from " . $engineUrl);}
$ret = new Loop54_Response($response,$request);$ret->contentLength = $length;return $ret;}
}
class Loop54_EngineResponse{
public $success;public $errorCode;public $errorMessage;public $requestId;public $_data;public $options = null;public $contentLength = null;function __construct($stringData, $request){
$this->options = $request->options;try {
$json = json_decode($stringData);}
catch(Exception $ex){
throw new Exception("Engine returned incorrectly formed JSON " . $ex . ": " . $stringData);}
if($json === null){
throw new Exception("Engine returned incorrectly formed JSON: " . $stringData);}
$responseObj = $json;if ((bool)$responseObj->{"Success"} != true){
$this->success = false;$this->errorCode = (int)$responseObj->{"Error_Code"};$this->errorMessage = (string)$responseObj->{"Error_Message"};$this->requestId = (string)$responseObj->{"RequestId"};return;}
$data = $responseObj->{"Data"};if($this->options->v25Url)$this->_data = $data->{$request->name};else$this->_data = $data;$this->success = true;}
}
class Loop54_Response extends Loop54_EngineResponse{
public function hasData($key){
return isset($this->_data->{$key});}
public function getValue($key){
if(is_array($key))throw new Exception($key . " is a collection.");return $this->_data->{$key};}
public function getCollection($key){
$origVal = $this->_data->{$key};if(!is_array($origVal))throw new Exception($key . " is not a collection.");$ret = array();foreach($origVal as $item){
if(is_object($item)){
$i = new Loop54_Item();if($this->options->v22Collections){
if(isset($item->{"Entity"})){
$i->entity = $this->ParseEntity($item->{"Entity"});$i->key = $i->entity;}
if(isset($item->{"String"})){
$i->string = $item->{"String"};$i->key = $i->string;}
}
else{
if(isset($item->{"Key"})){
$val = $item->{"Key"};if(is_object($val) && property_exists($val,"ExternalId") && property_exists($val,"EntityType"))$i->key = $i->entity = $this->ParseEntity($val);else if(is_string($val))$i->key = $i->string = $val;else$i->key = $val;}
}
$i->value = $item->{"Value"};$ret[] = $i;}
else{
$ret[] = $item;}
}
return $ret;}
private function ParseEntity($value){
$entity = new Loop54_Entity($value->{"EntityType"},$value->{"ExternalId"});if(isset($value->{"Attributes"})){
if(is_object($value->{"Attributes"})){
foreach($value->{"Attributes"} as $attrName => $attrValue){
$entity->setAttribute($attrName,$attrValue);}
}
else if(is_array($value->{"Attributes"})){
foreach($value->{"Attributes"} as $obj){
$attrName = $obj->{"Key"};$attrValue = $obj->{"Value"};$entity->setAttribute($attrName,$attrValue);}
}
}
return $entity;}
}
class Loop54_Item {
public $entity;public $string;public $value;public $key;}
abstract class Loop54_Utils{
static function escape($val){
if (!is_string($val)) {
trigger_error("Argument val must be string.");return;}
$val = str_replace("\"","\\\"",$val);return $val;}
static function randomString($length){
if (!is_int($length)) {
trigger_error("Argument length must be int.");return;}
$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';$randomString = '';for ($i = 0; $i < $length; $i++) {
$randomString .= $characters[rand(0, strlen($characters) - 1)];}
return $randomString;}
static function getUser(){
$existingCookie = null;if(isset($_COOKIE{'Loop54User'}))$existingCookie = $_COOKIE{'Loop54User'};if($existingCookie !== null)return $existingCookie;$userId = str_replace(":","",Loop54_Utils::getIP()) . "_" . Loop54_Utils::randomString(10);setcookie('Loop54User',$userId,time() + (86400 * 365),"/"); $_COOKIE{'Loop54User'} = $userId; return $userId;}
static function getIP(){
if(isset($_SERVER{'REMOTE_ADDR'}))return $_SERVER{'REMOTE_ADDR'};return "";}
static function getUserAgent(){
if(isset($_SERVER{'HTTP_USER_AGENT'}))return $_SERVER{'HTTP_USER_AGENT'};return null;}
static function getUrl(){
if(isset($_SERVER{'REQUEST_URI'}))return $_SERVER{'REQUEST_URI'};return null;}
static function getReferer(){
if(isset($_SERVER{'HTTP_REFERER'}))return $_SERVER{'HTTP_REFERER'};return null;}
static function fixEngineUrl($url){
if (!is_string($url)) {
trigger_error("Argument url must be string.");return;}
$url = trim($url);if($url === ""){
trigger_error("Argument url cannot be empty.");return;}
$url = strtolower($url);if(!Loop54_Utils::stringBeginsWith($url,"http"))$url = "http://" . $url;if(!Loop54_Utils::stringEndsWith($url,"/"))$url = $url . "/";return $url;}
static function stringBeginsWith( $str, $sub ) {
return ( substr( $str, 0, strlen( $sub ) ) === $sub );}
static function stringEndsWith( $str, $sub ) {
return ( substr( $str, strlen( $str ) - strlen( $sub ) ) === $sub );}
static function serializeObject($data){
if ($data instanceof Loop54_Entity)return $data->serialize();else if ($data instanceof Loop54_Event)return $data->serialize();else if (is_array($data)){
if(Loop54_Utils::isAssoc($data)){
$ret = "{";foreach ($data as $key => $value){
$ret .= Loop54_Utils::serializeObject($key) . ":" . Loop54_Utils::serializeObject($value) . ",";}
$ret = rtrim($ret,',') . "}";return $ret;}
else{
$ret = "[";foreach($data as $dataVal){
$ret .= Loop54_Utils::serializeObject($dataVal) . ",";}
$ret = rtrim($ret,',') . "]";return $ret;}
}
else{
return json_encode($data);}
}
static function isAssoc($arr){
return array_keys($arr) !== range(0, count($arr) - 1);}
}

?>