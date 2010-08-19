#!/usr/bin/perl

my $text = '';
while (<STDIN>) {
	$text .= $_;
}

$text =~ s#^.+END(.+)END.+$#\1#s;
$text =~ s#[\t ]+# #g;
$text =~ s#[\r\n]([^\r\n])#\1#g;
$text =~ s#--#\n#g;
$text =~ s#^\s+(.+?)\s+$#\1#s;

my $code = '';
$code .= "<?php\n";
$code .= 'return array('."\n";
foreach my $line (split(/[\r\n]/, $text)) {
	$line =~ s#\s+$##sg;

	my $elements = {};
	$elements->{'name'} = '';
	$elements->{'type'} = '';
	$elements->{'description'} = '';

	($elements->{'name'}, $elements->{'description'}) = split(/ /, $line, 2);
	if (length($line) <= 1) {
		$elements->{'type'} = 'break';
	} elsif ($elements->{'name'} =~ /=/) {
		($elements->{'name'}, undef) = split(/=/, $elements->{'name'}, 2);
		$elements->{'type'} = 'free';

		if ($elements->{'description'} =~ /^(.+), the valid values: (.+)$/) {
			$elements->{'type'} = 'multi';
			$elements->{'description'} = $1;

			my (@options) = split(/, /, $2);
			my $element = '';
			$element .= 'array(';
			foreach my $option (@options) {
				$element .= sprintf("'%s',", $option);
			}
			$element .= ')';
			$elements->{'options'} = $element;
		}
	} else {
		$elements->{'type'} = 'closed';
	}

	foreach my $k (qw(name type description)) {
		$elements->{$k} = sprintf("'%s'", $elements->{$k});
	}

	# Skip the first line (--help) and the newline following it
	if ($elements->{'name'} eq "'help'") {
		next;
		next;
	}

	$code .= "\tarray(\n";
	foreach my $k (keys %{$elements}) {
		$code .= sprintf("\t\t'%s' => %s,\n", $k, $elements->{$k});
	}
	$code .= "\t),\n";
}
$code .= ');'."\n";
$code .= "?>";

print "$code";
