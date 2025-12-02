#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLine();
	$entries = [];
	foreach (explode(',', $input) as $entry) {
		[$start, $end] = explode('-', $entry);
		$entries[] = [$start, $end];
	}

	$part1 = 0;
	foreach ($entries as [$start, $end]) {
		for ($i = $start; $i <= $end; $i++) {
			if (strlen($i) % 2 != 0) { continue; }

			$first = substr($i, 0, strlen($i) / 2);
			$second = substr($i, strlen($i) / 2);

			if ($first == $second) { $part1 += $i; }
		}
	}

	echo 'Part 1: ', $part1, "\n";

	// $part2 = 0;
	// echo 'Part 2: ', $part2, "\n";
