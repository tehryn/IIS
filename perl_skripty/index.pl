#!/usr/bin/perl

use strict;
use Storable;
use warnings;
use Data::Dumper;
use DBI;
use CGI;
my $db = DBI->connect("dbi:mysql:dbname=xmatej52;host=localhost;mysql_socket=/var/run/mysql/mysql.sock", "xmatej52", "konbo7ur");

