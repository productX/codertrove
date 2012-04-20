<?php
	require_once "inc/payments.php";
	require_once ("lib/paypal/paypalfunctions.php");
	
	$planID = $_GET['planID'];
	session_start();
	$_SESSION['Payment_Amount']=getPlanPrice($planID);
	
	//header( 'Location: http://www.codertrove.com/express_checkout.php' ) ;

	// ==================================
	// PayPal Express Checkout Module
	// ==================================

	//'------------------------------------
	//' The paymentAmount is the total value of 
	//' the shopping cart, that was set 
	//' earlier in a session variable 
	//' by the shopping cart page
	//'------------------------------------
	$paymentAmount = $_SESSION["Payment_Amount"];

	//'------------------------------------
	//' When you integrate this code 
	//' set the variables below with 
	//' shipping address details 
	//' entered by the user on the 
	//' Shipping page.
	//'------------------------------------
	$shipToName = "";
	$shipToStreet = "";
	$shipToStreet2 = ""; //Leave it blank if there is no value
	$shipToCity = "";
	$shipToState = "";
	$shipToCountryCode = ""; // Please refer to the PayPal country codes in the API documentation
	$shipToZip = "";
	$phoneNum = "";

	//'------------------------------------
	//' The currencyCodeType and paymentType 
	//' are set to the selections made on the Integration Assistant 
	//'------------------------------------
	$currencyCodeType = "USD";
	$paymentType = "Sale";

	//'------------------------------------
	//' The returnURL is the location where buyers return to when a
	//' payment has been succesfully authorized.
	//'
	//' This is set to the value entered on the Integration Assistant 
	//'------------------------------------
	$returnURL = "http://www.codertrove.com/pay_landing.php.php?done";

	//'------------------------------------
	//' The cancelURL is the location buyers are sent to when they hit the
	//' cancel button during authorization of payment during the PayPal flow
	//'
	//' This is set to the value entered on the Integration Assistant 
	//'------------------------------------
	$cancelURL = "http://www.codertrove.com/pay_landing.php.php?cancel";

	//'------------------------------------
	//' Calls the SetExpressCheckout API call
	//'
	//' The CallMarkExpressCheckout function is defined in the file PayPalFunctions.php,
	//' it is included at the top of this file.
	//'-------------------------------------------------
	$resArray = CallMarkExpressCheckout ($paymentAmount, $currencyCodeType, $paymentType, $returnURL,
																			  $cancelURL, $shipToName, $shipToStreet, $shipToCity, $shipToState,
																			  $shipToCountryCode, $shipToZip, $shipToStreet2, $phoneNum
	);

	$ack = strtoupper($resArray["ACK"]);
	if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING")
	{
			$token = urldecode($resArray["TOKEN"]);
			$_SESSION['reshash']=$token;
			RedirectToPayPal ( $token );
	} 
	else  
	{
			//Display a user friendly Error on the page using any of the following error information returned by PayPal
			$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
			$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
			$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
			$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
			
			echo "SetExpressCheckout API call failed. ";
			echo "Detailed Error Message: " . $ErrorLongMsg;
			echo "Short Error Message: " . $ErrorShortMsg;
			echo "Error Code: " . $ErrorCode;
			echo "Error Severity Code: " . $ErrorSeverityCode;
	}

?>
