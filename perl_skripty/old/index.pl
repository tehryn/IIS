#!/usr/bin/perl

use strict;
use warnings;
use CGI;
#use Text::CSV; # not on server :(

my $q = new CGI;

=item csv2array()
 	Function opens csv file (filename is given as parametr). Data
	of table must be seperated by semicomma (;).
	Returns 2D array representing table.
=cut
sub csv2array {
	my ($filename) = @_;
	open(my $file, '<:encoding(UTF-8)', $filename) or die("Could not open '$filename'");
	my @table;
	while (my $line = <$file>) {
		my @arr = split(/;/,$line); # reading one line of table (one tr)
		push(@table, \@arr); # adding table line (tr) into array
	}
	close($file);
	return @table;
}

=item print_table()
	Function prints html table that is represented by 2D array. This 2D array
	expected as parametr.
=cut
sub print_table {
	my (@table) = @_;
	print $q->table( {
			-border=>"1px solid black",
			-cellpadding=>"2",
			-cellspacing=>"0"
		},
		map {
			$q->Tr($q->th($_)), # table header
			map {
				$q->Tr($q->td($_))
			} @table # rest of table (no headers becouse of shift...)
		} shift(@table) # retrieve only table header from file
	);
}

sub array2csv {
	my ($filename, @arr) = @_;
	my $len1 = @arr;
	open(my $out, '>', $filename) or die "Unable to open '$filename' for writing";
	if ($len1 <= 0) {
		# leave this empty
	}
	else {
		my $len2 = @{$arr[0]};
		my $i = 0;
		while ($i < $len1) {
			my $j = 0;
			while ($j < $len2) {
				if ($j == ($len2 - 1)) {
					if ($i == ($len1 - 1)) {
						print $out "$arr[$i][$j]";
					}
					else {
						print $out "$arr[$i][$j]\n";
					}
				}
				else {
					print $out "$arr[$i][$j];";
				}
				$j++;
			}
			$i++;
		}
	}
	close($out);
}

sub insert_line {
	my (@table, @line) = @_;
	if (@{$table[0]} != @line) {
		return @table;
	}
	else {
		push(@table, \@line);
		return @table;
	}
}

sub sort_table {
	my (@table, $column) = @_;
	my @head  = shift(@table);
	my $index = -1;
	foreach my $key (@head) {
		$index++;
		if ($key eq $column) {
			last;
		}
	}
	if ($index >= @head || $index < 0) {
		return @table;
	}
	else {
		return sort { $a->[$index] > $b->[$index] } @table;
	}
}

=item task1()
	Solution of 1st task.
=cut
sub task1 {
	print $q->h2("Task 1");
	my %tab = (
		"C", 5,
		"B", 8,
		"A", 2,
		"E", 7,
		"D", 6
		); # My table

	print $q->h3("Just a table");
	print $q->table( {
			-border=>"1px solid black",
			-width=>"100px",
			-cellpadding=>"2",
			-cellspacing=>"0"
		},
		$q->Tr($q->th("key"), $q->th("val")), # table header
		map {
			$q->Tr(
				$q->td($_) ,
				$q->td($tab{$_})
			)
		} keys %tab # rest of table
	);

	print $q->h3("Sorted table by keys");
	print $q->table( {
			-border=>"1px solid black",
			-width=>"100px",
			-cellpadding=>"2",
			-cellspacing=>"0"
		},
		$q->Tr($q->th("key"), $q->th("val")), # table header
 		map {
 			$q->Tr(
 				$q->td($_) ,
 				$q->td($tab{$_})
 			)
 		} sort keys %tab # rest of table (sorted by keys)
	);

	# Sorting by values
	print $q->h3("Sorted table by values (Integers)");
	print "\n\n\n<table border=\"1px solid black\" cellpadding=\"2\" cellspacing=\"0\" width=\"100px\">";
	print "<tr><th>key</th><th>val</th>"; # table header
	#rest of table
	foreach my $key (sort { $tab{$a} <=> $tab{$b} } keys %tab) {
		print "<tr>";
		print "<td>$key</td> <td>$tab{$key}</td>";
		print "</tr>";
	}
	print "</table>";

	#Sorting by values no. 2
	$tab{"C"} = "Halestorm";
	$tab{"B"} = "halestorm";
	$tab{"A"} = "The pretty reckless";
	$tab{"E"} = "Arch Enemy";
	$tab{"D"} = "Montionless in White";
	print $q->h3("Sorted table by values (Strings)");
	print "\n\n\n<table border=\"1px solid black\" cellpadding=\"2\" cellspacing=\"0\">";
	print "<tr><th>key</th><th>val</th>"; #table header
	# rest of table
	foreach my $key (sort { $tab{$a} cmp $tab{$b} } keys %tab) {
		print "<tr>";
		print "<td>$key</td> <td>$tab{$key}</td>";
		print "</tr>";
	}
	print "</table>";
}

=item task2()
	Solution of 2nd task.
=cut
sub task2 {
	print $q->h2("Task 2");
	my $filename = "task2_file.txt";
	open(my $file, '<:encoding(UTF-8)', $filename) or die("Could not open task2_file.txt");
	print $q->h3("Table from file.");
	print "\n\n\n<table border=\"1px solid black\" cellpadding=\"2\" cellspacing=\"0\">";
	my $line_num = 0;
	while (my $line = <$file>) {
		my @arr = split(/,/,$line); # splits readed line by comma and store it into array
		print "<tr>";
		# table header
		if ($line_num) {
			foreach my $element (@arr) {
				print "<td>$element</td>";
			}
		}
		# rest of table
		else {
			foreach my $element (@arr) {
				print "<th>$element</th>";
			}
		}
		print "</tr>";
		$line_num++;
	}
	print "</table>";
	close($file);
}

=item task3()
	Solution of 3rd task.
=cut
sub task3 {
	print $q->h2("Task 3");
	print $q->h3("Table from scv file: 'task3_file.csv'.");
	my @table = csv2array("task3_file.csv"); # convert csv into array
	print_table(@table); # prints table
}

=item task4()
	Solution of 4th task.
=cut
sub task4 {
	print $q->h2("Task 4");
	print $q->h3("Searching for item in table by indexes.");
	my @table = csv2array("task3_file.csv");
	print_table(@table);
	print $q->br;
	print $q->start_form;
	print $q->textfield(
		-name=>'textfield_task4',
		-default=>'Enter 2 indexes seperated by comma (example: 1,2)',
		-size=>50,
		-maxlength=>3);
	print $q->br;
	print $q->submit(-value=>'Just Do It!');
	print $q->end_form;
	if ($q->param('textfield_task4')) {
		my @values = split(/,/, $q->param('textfield_task4'));
		if ((@values == 2) && ($values[0] =~ /^\d+$/) && ($values[1] =~ /^\d+$/)) {
			my $element = $table[$values[0]][$values[1]];
			if ($element) {
				print "<p>You are looking for \"$element\"</p>";
			}
			else {
				print "<p>Cell is empty. Or just do not exist. Deal with it.";
			}
		}
		else {
			print "<p>Invalid input</p>";
		}
	}
}

=item task5()
	Solution of 5th task.
=cut
sub task5 {
	print $q->h2("Task 5");
	print $q->h3("Operations with table");
	my @table = csv2array("task5_file.csv");
	print_table(@table);
	print $q->start_form;
	print $q->scrolling_list(
		-name => 'select_task5',
		-values => [
			"Sum of Ages",
			"Sum of Payments",
			"Average of Ages",
			"Average of Payments",
			"Highlight Max"
		],
		-size=>3,
		-multiple => 'true'
	); # scrolling list, checkbox might be better... should I do it with it?
	print $q->br;
	print $q->submit(-value=>'Just Do It!');
	print $q->end_form;
	if ($q->param('select_task5')) {
		my @selected = $q->param('select_task5');
		foreach my $sel (@selected) {
			# counts sum of ages
			if ($sel eq "Sum of Ages") {
				my $sum = 0;
				foreach my $line (@table) {
					if ($line) {
						$sum += @$line[3];
					}
				}
				print "<p>$sel: $sum</p>";
			}
			# counts sum of payments
			if ($sel eq "Sum of Payments") {
				my $sum = 0;
				foreach my $line (@table) {
					if ($line) {
						$sum += @$line[4];
					}
				}
				print "<p>$sel: $sum</p>";
			}
			# counts average of ages
			if ($sel eq "Average of Ages") {
				my $sum   = 0;
				my $count = 0;
				foreach my $line (@table) {
					if ($line) {
						$sum += @$line[3];
						$count++;
					}
				}
				my $result = $sum/$count;
				print "<p>$sel: $result</p>";
			}
			# counts average of payments
			if ($sel eq "Average of Payments") {
				my $sum   = 0;
				my $count = 0;
				foreach my $line (@table) {
					if ($line) {
						$sum += @$line[4];
						$count++;
					}
				}
				my $result = $sum/$count;
				print "<p>$sel: $result</p>";
			}
			# prints new table with Highlighted values of ages and payments
			if ($sel eq "Highlight Max") {
				my @indexes = (1,1);
				my $i = 0;
				foreach my $line (@table) {
					# Maximum of ages
					if (@$line[3] > $table[$indexes[0]][3]) {
						$indexes[0] = $i;
					}
					# Maximum of payements
					if (@$line[4] > $table[$indexes[1]][4]) {
						$indexes[1] = $i;
					}
					$i++;
			}
			# Highlighting Maximums
			$table[$indexes[0]][3] = "<strong>" . $table[$indexes[0]][3] . "</strong>";
			$table[$indexes[1]][4] = "<strong>" . $table[$indexes[1]][4] . "</strong>";
			print_table(@table); # printing new table
			}
		}
	}
}

sub task6 {
	my @studenti = ( [ "id", "jméno", "příjmení", "datum narození" ] );
	my @predmety = ( [ "id", "název", "počet kreditů" ] );
	my @student_predmet = ( [ "id_studenta", "id_predmetu", "známka" ] );
	print "<p>LOADING INTO TABLE studenti(id, jméno, příjmení, datum narození)\n<br>";
	print "EXAMPLE:\n<br>0;Gandalf;Šedý;1. 1. 1000\n<br>1;Frodo;Pytlík;12. 6. 1650\n<br>";
	while (my $in = <>) {
		chomp($in);
		my @line = split(/;/, $in);
		@studenti = insert_line(@studenti, \@line);
	}
	print_table(@studenti);
	print "</p>";
	print "<p>LOADING INTO TABLE předměty(id, název, počet kreditů)\n<br>";
	print "EXAMPLE:\n<br>0;Nekromancie;4\n<br>1;Kapsářství;6\n<br>";
	while (my $in = <>) {
		chomp($in);
		my @line = split(/;/, $in);
		@predmety = insert_line(@predmety, \@line);
	}
	print_table(@predmety);
	print "</p>";
	print "<p>LOADING INTO TABLE 'student studuje předmět'(id_studenta, id_predmetu, známka (1-5))\n<br>";
	print "EXAMPLE:\n<br>0;0;5\n<br>1;1;4\n<br>";
	while (my $in = <>) {
		chomp($in);
		my @line = split(/;/, $in);
		if (@line == 3 && $line[2] > 0 && $line[2] < 6) {
			@student_predmet = insert_line(@student_predmet, \@line);
		}
	}
	print_table(@student_predmet);
	print "</p>";
	array2csv("studenti.csv", @studenti);
	array2csv("predmety.csv", @predmety);
	array2csv("student_predmet.csv", @student_predmet);
}

sub task7 {
	my @predmety = csv2array("predmety.csv");
	print_table(@predmety);
	

}

print $q->header;
print $q->start_html(-title => "Ukoly", -encoding => "utf-8");;
#&task1;
#&task2;
#&task3;
#&task4;
#&task5;
#&task6;
&task7;
print $q->end_html;

