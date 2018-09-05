## Fix phpUnit XML for Jenkins

**Fix PHPUnit's jUnit output so it works with Jenkins' xunit/junit parser**


#Background

PHPUnit can produce a variety of reports that can be used by your CI tools to generate statistics and graphs of your test results and code coverage.

This is awesome, and I've been using it this way with Jenkins for a long time. Jenkins is capable of displaying graphs showing your test results and code coverage over time, so you can tell at a glance that your project is improving.

The junit.xml file that PHPUnit produces is not totally compliant with the JUnit file format. PHPUnit adds several additional pieces of information to its XML file which are not in the standard format.

Historically this was not a problem, as the Jenkins parser just ignored these attributes. However recently the Jenkins JUnit parser has been upgraded and is now strictly enforcing the XML doctype for JUnit files. This means that the JUnit files produced by PHPUnit are no longer working in Jenkins.

This issue has been reported to the developers of both the Jenkins JUnit plugin and phpUnit.

In the case of the Jenkins plugin, the developers stated that they've never supported PHPUnit, and it's not their fault if PHPUnit isn't conforming to the file format.

In the case of phpUnit, the developers stated that they could change the output, but that it would cause backward compatibility issues for applications that expected the additional attributes, so they don't intend to change it for now.

Both of these responses are understandable, but neither actually solves the problem.


#A Solution (an interim one, anyway)

In the absence of an official fix, I have written a stop-gap solution that reads in phpUnit's junit.xml file, and outputs a modified version of it with the compatibility issues fixed. You should now be able to parse this file in Jenkins.

This utility is written in PHP. I have seen another solution written in XSLT, which is a reasonable way to do it. However I had trouble using that solution as I didn't have a suitable XSLT parser on my Jenkins box, and for various reasons I couldn't add one. I did have PHP on the box, obviously, as I was running phpUnit, so a PHP-based utility solves the problem without requiring additional software to be installed. (it's also a lot easier to see what the code is doing with PHP than XSLT)


#How to use it?

* Copy the phpinit_junit_compat.php file to your repository (I may add a composer.json file in the future, but for now it's just a simple PHP file to copy).
* If necessary, modify the php code to set the filenames for the junit.xml and junit_fixed.xml so that they match the structure of your build.
* Mofify your Jenkins config such that the JUnit publisher reads the junit_fixed.xml file instead of the file output from phpUnit.


#What else?

Similar issues to this have been reported for other CI tools. I have only tested this fix for Jenkins, but it is possible that it could be helpful for others.

If at any point an official fix is issued for this issue, either by Jenkins or phpUnit, then this utility will become redundant.

Licensed GPL3, because.
