<?php
 class Requests_Exception_HTTP_Unknown extends Requests_Exception_HTTP { protected $code = 0; protected $reason = 'Unknown'; public function __construct($reason = null, $data = null) { if ($data instanceof Requests_Response) { $this->code = $data->status_code; } parent::__construct($reason, $data); } } 