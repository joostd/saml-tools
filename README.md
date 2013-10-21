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
