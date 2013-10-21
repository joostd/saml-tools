saml-tools
==========

Simple tools for debugging SAML 

Install
-------

	make install

Use
---

	$ cat  vendor/xmlseclibs/tests/sign-basic-test.xml | php verify.php vendor/xmlseclibs/	tests/mycert.pem 
	signature ok
	$ cat  vendor/xmlseclibs/tests/sign-basic-test.xml | sed s/World/world/ | php verify.php 	vendor/xmlseclibs/tests/mycert.pem 
	problem validating XML signature - Reference validation failed

To extract a certificate from an XML file:

	$ curl -s https://wayf.surfnet.nl/federate/metadata/saml20/self | xpath '//ds:Signature//ds:X509Certificate/text()' | fold -w64 | openssl base64 -d  | openssl x509 -inform DER > cert.pem

	$ curl -s https://wayf.surfnet.nl/federate/metadata/saml20/self | php verify.php cert.pem
	signature ok
