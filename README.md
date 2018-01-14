textSearch
==========

A Symfony project that search text files in the given directory, building an in memory representation
 of the files and their contents, and then give a command prompt at which interactive searches can be performed.


Install
==========

Run composer install should install Symfony and all other project dependencies


Command
==========
php bin/console app:search fullpathToScan folder

example:
php bin/console app:search C:\xampp\htdocs\textSearch\tests\AppBundle\Command