#!/usr/bin/perl

use strict;
use Storable;
use warnings;
use CGI;
use DBI;
use Data::Dumper;

my $q   = new CGI;
my $dbh = DBI->connect("dbi:mysql:dbname=xmatej52;host=localhost;mysql_socket=/var/run/mysql/mysql.sock", "xmatej52", "konbo7ur") || die "Could not connect to database: $DBI::errstr";
my $sort_order = 0; # something like presistent variable for sorting table

# retrieve variable or store it.
if (!($q->param)) {
	store(\$sort_order, 'sort_order_matejka_xzzzx');
}
else {
	$sort_order = ${retrieve('sort_order_matejka_xzzzx')};
}

sub print_table {
	my ($cmd) = @_;
	my $task = $dbh->prepare("$cmd");
	$task->execute();
	my $fields = join(';', @{ $task->{NAME_lc} });
	my @head = split(/;/, $fields);
	my @table;
	push(@table, \@head);
	while (my @row = $task->fetchrow_array) {  # retrieve one row
		my $str = join(";", @row); # Why do I have to join it
		my @line = split(/;/, $str); # and then split it??? Why it does not work without this
		push(@table, \@line);
	}
	print $q->table( {
			-border=>"1px solid black",
			-cellpadding=>"2",
			-cellspacing=>"0"
		},
		map {
			$q->Tr($q->th($_)), # table header
			map {
				$q->Tr($q->td($_)),
			} @table # rest of table (no headers becouse of shift...)
		} shift(@table) # retrieve only table header from file
	);
}

sub get_table {
	my ($cmd) = @_;
	my $task = $dbh->prepare("$cmd");
	$task->execute();
	my $fields = join(';', @{ $task->{NAME_lc} });
	my @head = split(/;/, $fields);
	my @table;
	push(@table, \@head);
	while (my @row = $task->fetchrow_array) {  # retrieve one row
		my $str = join(";", @row); # Why do I have to join it
		my @line = split(/;/, $str); # and then split it??? Why it does not work without this
		push(@table, \@line);
	}
	return @table;
}

sub print_crud_table {
	my ($prefix, $table_name, $sort) = @_;
	my $task = $dbh->prepare("select * from $table_name $sort") or die "Invalid syntax in SQL command";
	$task->execute();
	my @table;
	my $fields = join(';', @{ $task->{NAME_lc} }); # names of columns
	my @head = split(/;/, $fields);                # clumns as array
	my @foot = @head;                              # colums as array, will be used for insert
	$fields = join(';', @{ $task->{TYPE} });       # types of columns
	my @types = split(/;/, $fields);               # types as array
	my @grave = ($head[0]);                        # will be used for delete
	$task->finish();

	# processing insert
	my $i = 0;
	if ($q->param("${prefix}_new_commit_button")) {
		my $str = "";
		foreach my $item (@foot) {
			my $new  = $q->param("${prefix}_${item}_new");
			my $type = $types[$i];
			if ($type == 12) {
				$new = "'$new'";
			}
			if ($type == 9) {
				$new = "STR_TO_DATE('$new', '%Y-%m-%d')";
			}
			if ($i == 0) {
				$str = "${new}"
			}
			else {
				$str = "${str},${new}";
			}
			$i++;
		}
		$dbh->do(
			"INSERT INTO $table_name VALUES($str);"
		) or print "<b>ERROR, try again</b><br>";
		print "INSERT INTO $table_name VALUES($str);";

	}

	# procesing delete with binded variable
	if ($q->param("${prefix}_textfield_grave")) {
		my $str = $q->param("${prefix}_textfield_grave");
		my $delete = $dbh->prepare(
			"DELETE FROM $table_name WHERE id = ?;"
		);
		$delete->bind_param(1, "$str");
		$delete->execute();
		$delete->finish();
	}

	# select
	$task = $dbh->prepare("select * from $table_name $sort");
	$task->execute();

	# loading able into 2D array
	while (my @row = $task->fetchrow_array) {  # retrieve one row
		my $str = join(";", @row); # Why do I have to join it
		my @line = split(/;/, $str); # and then split it??? Why it does not work without this
		push(@table, \@line);
	}

	# procesing insert into table
	$i = 0;
	my $j = 0;
	my $tab_len  = @table;
	my $line_len = @head;
	my $item;
	while ($i < $tab_len) {
		$j = 0;
		while ($j < $line_len) {
			if ($q->param("${prefix}_textfield_${i}${j}")) {
				my $column = $head[$j];
				my $id     = $table[$i][0];
				my $new    = $q->param("${prefix}_textfield_${i}${j}");
				my $type   = $types[$j];
				if ($type == 12) {
					$new = "'$new'";
				}
				if ($type == 9) {
					$new = "STR_TO_DATE('$new', '%Y-%m-%d')";
				}
				$dbh->do(
					"UPDATE $table_name SET $column = $new WHERE id=$id;"
				) or print "<b>ERROR, try again</b><br>";
				print "UPDATE $table_name SET $column = $new WHERE id=$id;";
			}
			$j++;
		}
		$i++;
	}

	# Adding textfields and submit buttons into table
	$i = 0;
	while ($i < $tab_len) {
		$j = 0;
		while ($j < $line_len) {
			$table[$i][$j] = $q->textfield(
				-name=>"${prefix}_textfield_${i}${j}",
				-default=>"$table[$i][$j]",
				-size=>15
			);
			$j++;
		}
		$table[$i][$j] = $q->submit(-name=>"${prefix}_textfield_${i}${j}", -value=>"Update");
		$i++;
	}

	# adding head into table
	unshift(@table, \@head);
	$table[0][$j] = "commit changes";

	# adding line for deleting into teble
	$grave[0]     = $q->textfield(
		-name=>"${prefix}_textfield_grave",
		-default=>"$foot[0]",
		-size=>15
	);

	# adding line for inserting into table
	$i = 0;
	foreach my $item (@foot) {
		my $key = "${prefix}_${item}";
		$foot[$i] = $q->textfield(
			-name=>"${prefix}_${item}_new",
			-default=>"$item",
			-size=>15
		);
		$i++;
	}
	# submit button for insertion
	$foot[$i] = $q->submit(-name=>"${prefix}_new_commit_button", -value=>"Insert");
	push(@table, \@foot);

	# submit button for delete
	$grave[$i]    = $q->submit(-name=>"${prefix}_submit_grave", -value=>"delete");
	push(@table, \@grave);

	# printing table with forms for each line of table.
	print $q->table( {
			-border=>"1px solid black",
			-cellpadding=>"2",
			-cellspacing=>"0"
		},
		map {
			$q->start_form,
			$q->Tr($q->th($_)), # table header
			$q->end_form,
			map {
				$q->start_form,
				$q->Tr($q->td($_)),
				$q->end_form
			} @table # rest of table (no headers becouse of shift...)
		} shift(@table) # retrieve only table header from file
	);
}

sub print_sortable_table {
	# this function is similar to print_crud_table
	my ($prefix, $table_name) = @_;
	my $task = $dbh->prepare("select * from $table_name");
	$task->execute();
	my @table;
	my $fields = join(';', @{ $task->{NAME_lc} }); # columns names
	my @head = split(/;/, $fields);
	my @foot = @head;
	$fields = join(';', @{ $task->{TYPE} });       # types of columns
	my @types = split(/;/, $fields);
	my @grave = ($head[0]);
	$task->finish();

	# Inserting into table
	my $i = 0;
	if ($q->param("${prefix}_new_commit_button")) {
		my $str = "";
		foreach my $item (@foot) {
			my $new  = $q->param("${prefix}_${item}_new");
			my $type = $types[$i];
			if ($type == 12) {
				$new = "'$new'";
			}
			if ($type == 9) {
				$new = "STR_TO_DATE('$new', '%Y-%m-%d')";
			}
			if ($i == 0) {
				$str = "${new}"
			}
			else {
				$str = "${str},${new}";
			}
			$i++;
		}
		$dbh->do(
			"INSERT INTO $table_name VALUES($str);"
		) or print "<b>ERROR, try again</b><br>";
	}

	# deleting from table
	# Note the vulnerability for SQL injection.
	# I it intentionally, so I can "play" with it.
	# Note that in function print_crud_table is this part protected.
	if ($q->param("${prefix}_textfield_grave")) {
		my $str = $q->param("${prefix}_textfield_grave");
		$dbh->do(
			"DELETE FROM $table_name WHERE id = $str;"
		) or print "<b>ERROR, try again</b><br>";
	}

	#sorting table by specific column and order
	my $sort = "";
	foreach my $item (@head) {
		my $key = "${prefix}_$item";
		if ($q->param("${key}")) {
			if ($sort_order) {
				$sort = "order by $item desc";
				$sort_order = 0;
			}
			else {
				$sort = "order by $item";
				$sort_order = 1;
			}
			store(\$sort_order, 'sort_order_matejka_xzzzx');
			last;
		}
	}

	# Inserting into table, table can be now in different order, so I have to
	# load previous table.
	$i = 0;
	my @prev_table;
	if ($q->param()) {
		my $ref_prev_table = retrieve('prev_table_matejka_xzzzx');
		@prev_table = @{$ref_prev_table};
	}
	my $j = 0;
	my $tab_len  = @prev_table;
	my $line_len = @head;
	my $item;
	while ($i < $tab_len) {
		$j = 0;
		while ($j < $line_len) {
			if ($q->param("${prefix}_textfield_${i}${j}")) {
				my $column = $head[$j];
				my $id     = $prev_table[$i][0];
				my $new    = $q->param("${prefix}_textfield_${i}${j}");
				my $type   = $types[$j];
				if ($type == 12) {
					$new = "'$new'";
				}
				if ($type == 9) {
					$new = "STR_TO_DATE('$new', '%Y-%m-%d')";
				}
				$dbh->do(
					"UPDATE $table_name SET $column = $new WHERE id=$id;"
				) or print "<b>ERROR, try again</b><br>";
			}
			$j++;
		}
		$i++;
	}

	# New table that will be displayed
	$task = $dbh->prepare("select * from $table_name $sort");
	$task->execute();

	# loading table to 2D array
	while (my @row = $task->fetchrow_array) {  # retrieve one row
		my $str = join(";", @row); # Why do I have to join it
		my @line = split(/;/, $str); # and then split it??? Why it does not work without this
		push(@table, \@line);
	}

	# storing table into something like persistent variable
	store(\@table, 'prev_table_matejka_xzzzx');

	# Adding text fields and submit buttons into table
	$i = 0;
	$tab_len = @table;
	while ($i < $tab_len) {
		$j = 0;
		while ($j < $line_len) {
			$table[$i][$j] = $q->textfield(
				-name=>"${prefix}_textfield_${i}${j}",
				-default=>"$table[$i][$j]",
				-size=>15
			);
			$j++;
		}
		$table[$i][$j] = $q->submit(-name=>"${prefix}_textfield_${i}${j}", -value=>"Update");
		$i++;
	}

	# Ading submit buttons into head of a table
	$i = 0;
	foreach my $item (@head) {
		my $key = "${prefix}_${item}";
		$head[$i] = $q->submit(-name=>$key, -value=>$item);
		$i++;
	}
	unshift(@table, \@head);
	$table[0][$j] = "commit changes";

	# textfield for deleting in table
	$grave[0]     = $q->textfield(
		-name=>"${prefix}_textfield_grave",
		-default=>"$foot[0]",
		-size=>15
	);
	$i = 0;

	# textfields for inserting into table
	foreach my $item (@foot) {
		my $key = "${prefix}_${item}";
		$foot[$i] = $q->textfield(
			-name=>"${prefix}_${item}_new",
			-default=>"$item",
			-size=>15
		);
		$i++;
	}
	$foot[$i] = $q->submit(-name=>"${prefix}_new_commit_button", -value=>"Insert");
	push(@table, \@foot);
	$grave[$i]    = $q->submit(-name=>"${prefix}_submit_grave", -value=>"delete");
	push(@table, \@grave);

	# printing the table.
	print $q->table( {
			-border=>"1px solid black",
			-cellpadding=>"2",
			-cellspacing=>"0"
		},
		map {
			$q->start_form,
			$q->Tr($q->th($_)), # table header
			$q->end_form,
			map {
				$q->start_form,
				$q->Tr($q->td($_)),
				$q->end_form
			} @table # rest of table (no headers becouse of shift...)
		} shift(@table) # retrieve only table header from file
	);
}

sub task1 {
	$dbh->do(
		"CREATE TABLE IF NOT EXISTS studenti
		(
			id INT UNSIGNED PRIMARY KEY,
			jmeno VARCHAR(32) NOT NULL,
			prijmeni VARCHAR(32) NOT NULL,
			datum_narozeni DATE NOT NULL
		);"
	);
	$dbh->do(
		"CREATE TABLE IF NOT EXISTS predmety
		(
			id INT UNSIGNED PRIMARY KEY,
			jmeno VARCHAR(32) NOT NULL,
			pocet_kreditu INTEGER NOT NULL
		);"
	);
	$dbh->do(
		"CREATE TABLE IF NOT EXISTS student_predmet
		(
			id_student INT UNSIGNED,
			id_predmet INT UNSIGNED,
			znamka INT(1) UNSIGNED,
			PRIMARY KEY (id_student, id_predmet),
			FOREIGN KEY (id_student) REFERENCES studenti(id),
			FOREIGN KEY (id_predmet) REFERENCES predmety(id)
		);"
	);
}

sub task2_new {
	print "<p>";
	print $q->h2("Tabulka predmetu");
	# prints sorted table predmety
	print_crud_table("task2", "predmety", "order by jmeno");

	# note that I can print table studenti the same way with different order
	# and both tables will just works fine. (tables where is not id as primary
	# keys will be printed and can be inserted to them, but deletion and update_task2
	# is not working) If you dont belive me, just uncomment next lines.
	#print $q->h2("Tabulka studentu");
	#print_crud_table("task2_b", "studenti", "order by prijmeni desc");
	print "</p>";
}

sub task3 {
	print "<p>";
	print $q->h2("Tabulka studentu");
	# prints crud table that can be sorted by clicking on header
	print_sortable_table("task3_a", "studenti");

	# Same as in task2, you can print whatever you want to just by changing
	# arguments. Also same error here - in tables where id is not primary key
	# will be problems with delete and update. But insert shloud work just fine.
	#print $q->h2("Tabulka Student studuje predmet");
	#print_crud_table("task3_b", "student_predmet", ""); # uncomment to insert data (delete or update not working)
	#print_table("select * from student_predmet order by id_student"); # uncoment to display table

	# This is here so you can see what subject is who studing.
	print $q->h2("Prehledna tabulka studentu studujici predmety");
	print_table(
		"select studenti.id, studenti.jmeno, studenti.prijmeni, predmety.id as id_predmetu,
		predmety.jmeno as jmeno_predmetu, predmety.pocet_kreditu, znamka from studenti left join
		student_predmet on studenti.id = student_predmet.id_student left join
		predmety on student_predmet.id_predmet = predmety.id where predmety.id
		is not NULL order by studenti.id;"
	);
	print "</p>";
}

sub task4 {
	print "<p>";
	print $q->h2("Zaznamovy arch");
	# first i need all subjects
	my $task = $dbh->prepare("select id, jmeno from predmety");
	$task->execute();
	my @values = ("id; jmeno");

	# loads them into array
	while (my @row = $task->fetchrow_array) {
		my $str = join("; ", @row);
		push(@values, $str);
	}
	$task->finish();

	# and then show them in selection
	print $q->start_form;
	print $q->popup_menu(
		-name    => 'task4_select',
		-values  => \@values,
	);
	print $q->submit(-value=>"Potvrdit");
	print $q->end_form;
	print $q->br;

	# if something is selected
	if ($q->param('task4_select') || $q->param('task4_submit')) {
		# I retrieve what is selected
		my $chosen_one = $q->param('task4_select');
		# If only mark (znamka) was updated, I need to load
		# previous selected subject
		if (not ($chosen_one)) {
			$chosen_one = ${retrieve('chosen_one_matejka_xxyyzz')};
		}
		store(\$chosen_one, 'chosen_one_matejka_xxyyzz');

		# then retrieve id and name of subject from string
		my @values = split(/;/,$chosen_one);

		# asks for table with students studing chosen subject
		my $task = $dbh->prepare("select studenti.id, studenti.jmeno, znamka from studenti LEFT JOIN
			student_predmet ON studenti.id = student_predmet.id_student WHERE id_predmet = $values[0]"
		);
		$task->execute();
		my @head  = ("id studenta", "jmeno studenta", "znamka z predmetu $values[1]", "Zmenit znamku");
		my @table;
		push(@table, \@head);
		# loads students into table
		while (my @row = $task->fetchrow_array) {
			my $str = join("; ", @row);
			my @values = split(/;/,$str);
			push(@table, \@values);
		}

		# and then print table and update data (if mark(znamka) was changed)
		my $i = 1;
		my $tab_len  = @table;
		while ($i < $tab_len) {
			my $start = $q->start_form;
			my $id = $table[$i][0];
			# mark was changed
			if ($q->param("task4_select_znamka_$id")) {
				my $new = $q->param("task4_select_znamka_$id");
				if ($new eq "Smazat znamku") {
					$new = "NULL";
				}
				$table[$i][2] = $new; # replace old data
				$dbh->do (
					"UPDATE student_predmet SET znamka = $new
					WHERE id_student = $id AND id_predmet = $values[0];"
				) or print "<b> ERROR, try again<br></b>";
			}
			# selection for mark
			my $item = $q->popup_menu(
				-name    => "task4_select_znamka_$id",
				-values  => [ "1", "2", "3", "4", "5", "Smazat znamku" ],
			);
			# submit button
			my %label = ("Potvrdit" => $q->param('task4_select'));
			my $button = $q->submit(-name =>"task4_submit", -value => "Potvrdit");
			my $end = $q->end_form;
			# and adds selection and button into table
 			$table[$i][3] = "$start $item $button $end";
			$i++;
		}

		# then just print the table!
		print $q->table( {
				-border=>"1px solid black",
				-cellpadding=>"2",
				-cellspacing=>"0"
			},
			map {
				$q->Tr($q->th($_)), # table header
				map {
					$q->Tr($q->td($_)),
				} @table # rest of table (no headers becouse of shift...)
			} shift(@table) # retrieve only table header from file
		);
	}
	$q->h2("Celkove studium studentu");
	print_table(
		"SELECT studenti.id, studenti.jmeno, SUM(predmety.pocet_kreditu) as
		celkem_kreditu, sum(student_predmet.znamka)/count(student_predmet.znamka)
		as studijni_prumer, sum(student_predmet.znamka*predmety.pocet_kreditu)/sum(predmety.pocet_kreditu) as vazeny_prumer FROM (studenti JOIN student_predmet on studenti.id =
		student_predmet.id_student) JOIN predmety on id_predmet = predmety.id
		WHERE student_predmet.znamka <> 5 and student_predmet.znamka IS NOT NULL
		GROUP BY (studenti.id);"
	);
	print "</p>";
}

sub task5 {
	# since task5 was already completed in task1 by foregein keys...
}


print $q->header;
print $q->start_html();
# my @array = $q->param;
# foreach my $x (@array) {
# 	print "$x<br>";
# }
# print "$sort_order<br>";
&task1;
&task2_new;
&task3;
&task4;
&task5;
$dbh->disconnect();
print $q->end_html;

