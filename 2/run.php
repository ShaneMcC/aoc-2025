#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLine();
	$entries = [];
	foreach (explode(',', $input) as $entry) {
		[$start, $end] = explode('-', $entry);
		$entries[] = [$start, $end];
	}

	$part1 = $part2 = 0;
	foreach ($entries as [$start, $end]) {
		for ($i = $start; $i <= $end; $i++) {
			if (preg_match('/^(.*)\1$/', $i)) {
				$part1 += $i;
				$part2 += $i;
			} else if (preg_match('/^(.*)\1{1,}$/', $i)) {
				$part2 += $i;
			}
		}
	}

	echo 'Part 1: ', $part1, "\n";
	echo 'Part 2: ', $part2, "\n";
