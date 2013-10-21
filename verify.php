<?php

include_once('vendor/xmlseclibs/xmlseclibs.php');

function utils_saml_verify($dom, $cert, $id_name, $throw_exception_on_failure = TRUE) {
	$objXMLSecDSig = new XMLSecurityDSig();
	$objXMLSecDSig->idKeys[] = $id_name;

	$signatureElement = $objXMLSecDSig->locateSignature($dom);	// retrieves *first* signature node only!
	if  (!$signatureElement) {
		throw new Exception('Could not locate XML Signature element.');
	}
	
	$xpath = new DOMXPath($dom);
	$xpath->registerNamespace('secdsig', XMLSecurityDSig::XMLDSIGNS);
	$query = "string(./secdsig:SignedInfo/secdsig:SignatureMethod/@Algorithm)";
	$algorithm = $xpath->evaluate($query, $signatureElement);
	if (!$algorithm) {
		throw new Exception('Could not locate Signature algorithm attribute.');
	}
	
	$objXMLSecDSig->canonicalizeSignedInfo();
	if (!$objXMLSecDSig->validateReference()) {
		throw new Exception('XMLsec: digest validation failed');
	}
	if (!file_exists($cert)) {
		throw new Exception('Could not find verification certificate file: ' . $cert);
	}
	$objKey = new XMLSecurityKey($algorithm, array('type'=>'public'));
	$objKey->loadKey($cert, TRUE, TRUE);
	$result = $objXMLSecDSig->verify($objKey);
	if (($result !== 1) and ($throw_exception_on_failure === TRUE)) {
		throw new Exception('Unable to validate Signature');
	}
        $validNodes = $objXMLSecDSig->getValidatedNodes();
	if($result === 1) return $validNodes; else return NULL;	// return NULL if nothing was correctly signed

}

$xmlString = file_get_contents('php://stdin');
$dom = new DOMDocument();
$dom->loadXML($xmlString);
$cert = 'cert.pem';
$cert = $argv[1];

try {
	$valid_nodes = utils_saml_verify($dom, $cert, 'ID');	// valid nodes were correctly signed
	if( $valid_nodes === NULL ) {
		throw new Exception('document contains no signed elements');
	}
	echo "signature ok\n";
} catch (Exception $x) {
	echo "problem validating XML signature - " . $x->getMessage() . "\n";
}
