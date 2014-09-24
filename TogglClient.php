<?php

class TogglClient {
	const TOGGL_API_PROFILE					= 'https://www.toggl.com/api/v8/me';
	const TOGGL_API_EXTENDED_PROFILE		= 'https://www.toggl.com/api/v8/me?with_related_data=true';
	const REPORTS_API_BASE_URL 				= 'https://toggl.com/reports/api/v2';
	const REPORTS_API_WEEKLY_REPORT_URL 	= 'https://toggl.com/reports/api/v2/weekly';
	const REPORTS_API_DETAILED_REPORT_URL 	= 'https://toggl.com/reports/api/v2/details';
	const REPORTS_API_SUMMARY_REPORT_URL 	= 'https://toggl.com/reports/api/v2/summary';

	//Report "billable" values
	const REPORTS_BILLABLE_YES				= 'yes';
	const REPORTS_BILLABLE_NO				= 'no';
	const REPORTS_BILLABLE_BOTH				= 'both';

	//Report "without_description" values
	const REPORTS_WITHOUT_DESCRIPTION_TRUE	= true;
	const REPORTS_WITHOUT_DESCRIPTION_FALSE	= false;

	//Report "distinct_rates" values
	const REPORT_DISTINCT_RATES_ON			= 'on';
	const REPORT_DISTINCT_RATES_OFF			= 'off';

	//Report "rounding" values
	const REPORT_ROUNDING_ON				= 'on';
	const REPORT_ROUNDING_OFF				= 'off';

	//Report "display_hours" values
	const REPORT_ROUNDING_DECIMAL			= 'decimal';
	const REPORT_ROUNDING_MINUTES			= 'minutes';

	//Report "order_desc" values
	const REPORT_ORDER_DESC					= 'ON';
	const REPORT_ORDER_ASC					= 'OFF';

	//HTTP request types
	const HTTP_TYPE_POST					= 'POST';
	const HTTP_TYPE_GET						= 'GET';

	protected $_apiKey;
	protected $_authUser;
	protected $_authPassword;
	protected $_requestType;
	protected $_requestUrl;
	protected $_requestPostParams;
	protected $_requestGetParams;
	protected $_responseTimestamp;
	protected $_responseHeader;
	protected $_responseBody;
	protected $_errorArray;

	public function __construct($options = null) {
		//option array given
		if (is_array($options)) {
			if (array_key_exists('api_key', $options)) {
				$this->setApiKey($options['api_key']);
			}
			if (array_key_exists('auth_user', $options)) {
				$this->setAuthUser($options['auth_user']);
			}
			if (array_key_exists('auth_password', $options)) {
				$this->setAuthPassword($options['auth_password']);
			}
		}

		//only Api key given
		if (is_string($options)) {
			$this->setApiKey($options);
		}
	}

	/**
	 * @return mixed
	 */
	public function getAuthPassword() {
		return $this->_authPassword;
	}

	/**
	 * @param mixed $authPassword
	 */
	public function setAuthPassword($authPassword) {
		$this->_authPassword = $authPassword;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getAuthUser() {
		return $this->_authUser;
	}

	/**
	 * @param mixed $authUser
	 */
	public function setAuthUser($authUser) {
		$this->_authUser = $authUser;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getApiKey() {
		return $this->_apiKey;
	}

	/**
	 * @param mixed $apiKey
	 */
	public function setApiKey($apiKey) {
		$this->_apiKey = $apiKey;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getErrorArray() {
		return $this->_errorArray;
	}

	/**
	 * @param mixed $errorArray
	 */
	public function setErrorArray($errorArray) {
		$this->_errorArray = $errorArray;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getRequestUrl() {
		return $this->_requestUrl;
	}

	/**
	 * @param mixed $requestUrl
	 */
	public function setRequestUrl($requestUrl) {
		$this->_requestUrl = $requestUrl;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getRequestPostParams()
	{
		return $this->_requestPostParams;
	}

	/**
	 * @param mixed $requestPostParams
	 */
	public function setRequestPostParams($requestPostParams)
	{
		$this->_requestPostParams = $requestPostParams;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getRequestGetParams()
	{
		return $this->_requestGetParams;
	}

	/**
	 * @param mixed $requestGetParams
	 */
	public function setRequestGetParams($requestGetParams)
	{
		$this->_requestGetParams = $requestGetParams;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getRequestType()
	{
		return $this->_requestType;
	}

	/**
	 * @param mixed $requestType
	 */
	public function setRequestType($requestType)
	{
		$this->_requestType = $requestType;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getResponseTimestamp() {
		return $this->_responseTimestamp;
	}

	/**
	 * @param mixed $responseTimestamp
	 */
	public function setResponseTimestamp($responseTimestamp) {
		$this->_responseTimestamp = (int) $responseTimestamp;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getResponseBody() {
		return $this->_responseBody;
	}

	/**
	 * @param mixed $responseBody
	 */
	public function setResponseBody($responseBody) {
		$this->_responseBody = $responseBody;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getResponseHeader() {
		return $this->_responseHeader;
	}

	/**
	 * @param mixed $responseHeader
	 */
	public function setResponseHeader($responseHeader) {
		$this->_responseHeader = $responseHeader;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getResponseBodyObject() {
		return json_decode($this->getResponseBody());
	}

	public function getDataObject() {
		$body = $this->getResponseBodyObject();
		if (property_exists($body, 'data')) {
			return $body->data;
		}
		return false;
	}

	public function getErrorObject() {
		$body = $this->getResponseBodyObject();
		if (property_exists($body, 'error')) {
			return $body->error;
		}
		return false;
	}

	public function getProfile() {
		$this->setRequestUrl(self::TOGGL_API_PROFILE);
		$this->setRequestType(self::HTTP_TYPE_GET);
		return $this->_sendRequest();
	}

	public function getExtendedProfile() {
		$this->setRequestUrl(self::TOGGL_API_EXTENDED_PROFILE);
		$this->setRequestType(self::HTTP_TYPE_GET);
		return $this->_sendRequest();
	}

	/**
	 * Get the weekly report via toggl API filtered by option. See toggle API for more information.
	 *
	 * @see https://github.com/toggl/toggl_api_docs/blob/master/reports/weekly.md
	 * @param $options
	 * @return bool
	 * @throws Exception
	 */
	public function getWeeklyReport($options) {
		$this->setRequestUrl(self::REPORTS_API_WEEKLY_REPORT_URL);
		$this->setRequestType(self::HTTP_TYPE_GET);
		$this->setRequestGetParams($options);
		return $this->_sendRequest();
	}

	/**
	 * Get the detailed report via toggl API filtered by option. See toggle API for more information.
	 *
	 * @param $options
	 * @return bool
	 * @throws Exception
	 */
	public function getDetailedReport($options) {
		$this->setRequestUrl(self::REPORTS_API_DETAILED_REPORT_URL);
		$this->setRequestType(self::HTTP_TYPE_GET);
		$this->setRequestGetParams($options);
		return $this->_sendRequest();
	}

	/**
	 * @param $options
	 * @return bool
	 * @throws Exception
	 */
	public function getSummaryReport($options) {
		$this->setRequestUrl(self::REPORTS_API_SUMMARY_REPORT_URL);
		$this->setRequestType(self::HTTP_TYPE_GET);
		$this->setRequestGetParams($options);
		return $this->_sendRequest();
	}

	public function __call($param1, $param2) {
		throw new Exception('Not implemented yet.');
	}

	protected function _sendRequest() {
		$apiKey = $this->getApiKey();
		$authUser = $this->getAuthUser();
		$authPassword = $this->getAuthPassword();
		$requestUrl = $this->getRequestUrl();
		$requestGetParams = $this->getRequestGetParams();
		$requestPostParams = $this->getRequestPostParams();
		$requestType = $this->getRequestType();

		if (empty($apiKey) && empty($authUser) && empty($authPassword)) {
			throw new Exception('No toggl API credentials given.');
		}

		if (empty($requestUrl)) {
			throw new Exception('No URL for toggl API request given.');
		}

		//get cURL resource
		$curl = curl_init();

		//build curl array
		$curlOptions = array();
		$curlOptions[CURLOPT_RETURNTRANSFER] = 1;
		$curlOptions[CURLOPT_USERAGENT] = 'cURL';
		$curlOptions[CURLOPT_CUSTOMREQUEST] = $requestType;
		$curlOptions[CURLOPT_HEADER] = array(
			'Content-Type' => 'application/json',
		);
		$curlOptions[CURLOPT_USERPWD] = (!empty($apiKey)) ? $apiKey . ':api_token' : $authUser . ':' . $authPassword;


		//prepare get data
		if (!empty($requestGetParams)) {
			$curlOptions[CURLOPT_URL] = $requestUrl . '?' . http_build_query($requestGetParams);
		} else {
			$curlOptions[CURLOPT_URL] = $requestUrl;
		}

		//prepare post data
		if (!empty($requestPostParams)) {
			echo '$requestPostParams';
			$curlOptions[CURLOPT_POSTFIELDS] = json_encode($requestPostParams);
		}

		//set curl options
		curl_setopt_array($curl, $curlOptions);

		//set some options - we are passing in a useragent too here
		/*
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => 'http://testcURL.com',
			CURLOPT_USERAGENT => 'Codular Sample cURL Request',
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => array(
				item1 => 'value',
				item2 => 'value2'
			)
		));
		*/

		//send the request & get response
		$rawResponse = curl_exec($curl);

		//Close request to clear up some resources
		curl_close($curl);

		//toggl API returns nothing
		if (empty($rawResponse)) {
			$this->setResponseBody(null);
			$this->setResponseHeader(null);
			$this->setResponseData(null);
			$this->setResponseTimestamp(null);
			throw new Exception('toggl API returns nothing. The request seems to be corrupt. Maybe not all necessary params given or something went wrong on the toggl servers');
			return false;
		}

		//extract body and header
		list($header, $body) = explode("\r\n\r\n", $rawResponse, 2);
		$this->setResponseBody($body);
		$this->setResponseHeader($header);


		echo '<pre>';
		var_dump(json_decode($body));
		echo '</pre>';

		//return error
		if ($error = $this->getErrorObject()) {
			$this->setErrorArray($error);
			throw new Exception('toggl API throws an error. Call getErrorArray method on ' . get_class($this) . ' object.', $error->code);
			return $error;
		}

		//set response timestamp
		$response = $this->getResponseBodyObject();
		if (property_exists($response, 'since')) {
			$this->setResponseTimestamp($response->since);
		} else {
			$this->setResponseTimestamp(null);
		}

		//return data
		if ($data = $this->getDataObject()) {
			return $data;
		}
	}
}