sub task2 {
	my $str;
	if ($q->param('insert_task2')) {
		$str = $q->param('insert_task2');
		$dbh->do(
			"INSERT INTO predmety VALUES($str)"
		);
	}
	if ($q->param('delete_task2')) {
		$str = $q->param('delete_task2');
		$dbh->do(
			"DELETE FROM predmety WHERE ID = $str"
		);
	}
	if ($q->param('update_task2') && $q->param('update_task2_id')) {
		my $id = $q->param('update_task2_id');
		$dbh->do(
			"DELETE FROM predmety WHERE ID = $id"
		);
		$str = $q->param('update_task2');
		$dbh->do(
			"INSERT INTO predmety VALUES($str)"
		);
	}
	print "<p>Neserazena tabulka";
	print_table("predmety");
	print "<br>Serazena tabulka";
	print_table("predmety order by jmeno");
	print "</p>";
	print "<p>Insert:<br>";
	print "Example:<br>3,'Historie Gondoru',4";
	print $q->start_form;
	print $q->textfield(
		-name=>'insert_task2',
		-default=>'id,jmeno,pocet_creditu',
		-size=>50
	);
	print $q->br;
	print $q->submit(-value=>'Insert');
	print $q->end_form;

	print "</p><p>Delete:";
	print "<br>Example:<br>3";
	print $q->start_form;
	print $q->textfield(
		-name=>'delete_task2',
		-default=>'id',
		-size=>10
	);
	print $q->br;
	print $q->submit(-value=>'Delete');
	print $q->end_form;

	print "</p><p>Update:<br>";
	print "Example:<br>0<br>4,'Historie Rohanu',5";
	print $q->start_form;
	print $q->textfield(
		-name=>'update_task2_id',
		-default=>'id',
		-size=>10
	);
	print $q->br;
	print $q->textfield(
		-name=>'update_task2',
		-default=>'id,jmeno,pocet_creditu',
		-size=>50
	);
	print $q->br;
	print $q->submit(-value=>'Update');
	print $q->end_form;
	print "</p>"
}

# What if there is semicomma in table cell??? This might be solution but package
# I used is not on server. I skipped this problem, but if you wish to, I can
# solve it as well with different way. Package name is Text::CSV and its
# 3rd party extension.

# sub task3 {
# 	print $q->h2("Task 3");
# 	my $filename = "task3_file.csv";
# 	open(my $file, '<:encoding(UTF-8)', $filename) or die("Could not open task3_file.csv");
# 	my $csv = Text::CSV->new({ sep_char => ',' });
# 	@parsed_csv;
# 	while (my $line = <$file>) {
# 		chomp($file);
# 		if ($csv->parse($line)) {
# 			push(@parsed_csv, $csv->fields());
# 		}
# 		else {
# 			die("Unable to parse table from task3_file.csv");
# 		}
# 	}
# 	foreach my $line (@parsed_csv) {
# 		print("<tr>");
# 		foreach my $element (@$line) {
# 			print "<td>$element\n</td>";
# 		}
# 		print("</tr>");
# 	}
# 	print $q->h3("Table from csv file.");
# 	print "\n\n\n<table border=\"1px solid black\" cellpadding=\"2\" cellspacing=\"0\">";
# 	print "</table>";
# }