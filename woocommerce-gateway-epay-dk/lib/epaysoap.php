<?php

class EpaySoap
{
	private $pwd = "";
	private $client = null;

	function __construct($pwd = "", $subscription = false)
	{
		if($subscription)
        {
			$client = new SoapClient('https://ssl.ditonlinebetalingssystem.dk/remote/subscription.asmx?WSDL');
        }
        else
        {
			$client = new SoapClient('https://ssl.ditonlinebetalingssystem.dk/remote/payment.asmx?WSDL');
        }

		$this->pwd = $pwd;

		$this->client = $client;
	}

	public function authorize($merchantnumber, $subscriptionid, $orderid, $amount, $currency, $instantcapture, $group, $email)
	{
        try
        {
            $epay_params = array();
            $epay_params["merchantnumber"] = $merchantnumber;
            $epay_params["subscriptionid"] = $subscriptionid;
            $epay_params["orderid"] = $orderid;
            $epay_params["amount"] = (string)$amount;
            $epay_params["currency"] = $currency;
            $epay_params["instantcapture"] = $instantcapture;
            $epay_params["group"] = $group;
            $epay_params["email"] = $email;
            $epay_params["pwd"] = $this->pwd;
            $epay_params["fraud"] = 0;
            $epay_params["transactionid"] = 0;
            $epay_params["pbsresponse"] = "-1";
            $epay_params["epayresponse"] = "-1";

            $result = $this->client->authorize($epay_params);
        }
        catch(Exception $ex)
        {
            throw $ex;
        }

        return $result;
	}

	public function deletesubscription($merchantnumber, $subscriptionid)
	{
        try
        {
            $epay_params = array();
            $epay_params["merchantnumber"] = $merchantnumber;
            $epay_params["subscriptionid"] = $subscriptionid;
            $epay_params["pwd"] = $this->pwd;
            $epay_params["epayresponse"] = "-1";

            $result = $this->client->deletesubscription($epay_params);
        }
        catch(Exception $ex)
        {
            throw $ex;
        }

        return $result;
	}

	public function capture($merchantnumber, $transactionid, $amount)
	{
        try
        {
            $epay_params = array();
            $epay_params["merchantnumber"] = $merchantnumber;
            $epay_params["transactionid"] = $transactionid;
            $epay_params["amount"] = (string)$amount;
            $epay_params["pwd"] = $this->pwd;
            $epay_params["pbsResponse"] = "-1";
            $epay_params["epayresponse"] = "-1";

            $result = $this->client->capture($epay_params);
        }
        catch(Exception $ex)
        {
            throw $ex;
        }

        return $result;
	}

	public function credit($merchantnumber, $transactionid, $amount)
	{
        try
        {
            $epay_params = array();
            $epay_params["merchantnumber"] = $merchantnumber;
            $epay_params["transactionid"] = $transactionid;
            $epay_params["amount"] = (string)$amount;
            $epay_params["pwd"] = $this->pwd;
            $epay_params["epayresponse"] = "-1";
            $epay_params["pbsresponse"] = "-1";

            $result = $this->client->credit($epay_params);
        }
        catch(Exception $ex)
        {
            throw $ex;
        }

        return $result;
	}

	public function delete($merchantnumber, $transactionid)
	{
        try
        {
            $epay_params = array();
            $epay_params["merchantnumber"] = $merchantnumber;
            $epay_params["transactionid"] = $transactionid;
            $epay_params["pwd"] = $this->pwd;
            $epay_params["epayresponse"] = "-1";

            $result = $this->client->delete($epay_params);
        }
        catch(Exception $ex)
        {
            throw $ex;
        }

        return $result;
	}

	public function getEpayError($merchantnumber, $epay_response_code)
	{
        $res = "Unable to lookup errorcode";
        try
        {
            $epay_params = array();
            $epay_params["merchantnumber"] = $merchantnumber;
            $epay_params["pwd"] = $this->pwd;
            $epay_params["language"] = EpayHelper::get_language_code(get_locale());
            $epay_params["epayresponsecode"] = $epay_response_code;
            $epay_params["epayresponse"] = "-1";

            $result = $this->client->getEpayError($epay_params);

            if($result->getEpayErrorResult == "true")
            {
                if(array_key_exists("epayresponsestring", $result))
                {
                    $res = $result->epayresponsestring;
                }
                else
                {
                    $res = $result->epayResponseString;
                }
            }
        }
        catch(Exception $ex)
        {
            $res .= ' ' . $ex->getMessage();
        }

        return $res;
	}

	public function getPbsError($merchantnumber, $pbs_response_code)
	{
        $res = "Unable to lookup errorcode";
        try
        {
            $epay_params = array();
            $epay_params["merchantnumber"] = $merchantnumber;
            $epay_params["language"] = EpayHelper::get_language_code(get_locale());
            $epay_params["pbsresponsecode"] = $pbs_response_code;
            $epay_params["pwd"] = $this->pwd;
            $epay_params["epayresponse"] = "-1";

            $result = $this->client->getPbsError($epay_params);

            if($result->getPbsErrorResult == "true")
            {
                if(array_key_exists("pbsResponeString", $result))
                {
                    $res = $result->pbsResponeString;
                }
                else
                {
                    $res = $result->pbsresponestring;
                }
            }
        }
        catch(Exception $ex)
        {
            $res .= ' ' . $ex->getMessage();
        }

        return $res;
	}

	public function gettransaction($merchantnumber, $transactionid)
	{
        try
        {
            $epay_params = array();
            $epay_params["merchantnumber"] = $merchantnumber;
            $epay_params["transactionid"] = $transactionid;
            $epay_params["pwd"] = $this->pwd;
            $epay_params["epayresponse"] = "-1";

            $result = $this->client->gettransaction($epay_params);
        }
        catch(Exception $ex)
        {
            throw $ex;
        }

        return $result;
	}
}
?>