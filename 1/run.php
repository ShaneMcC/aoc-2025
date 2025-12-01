#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLines();

	$rotations = [];
	foreach ($input as $line) {
		preg_match('#([LR])(.*)#ADi', $line, $m);
		[$all, $direction, $amount] = $m;
		$rotations[] = [$direction, $amount];
	}

	$startValue = 50;
	$dialSize = 99;

	$part1 = 0;
	$value = $startValue;

	foreach ($rotations as $r) {
		if ($r[0] == 'R') {
			$value += $r[1];
		} else {
			$value -= $r[1];
		}
		$value = wrapmod($value, $dialSize + 1);
		if ($value == 0) { $part1++; }
	}

	echo 'Part 1: ', $part1, "\n";

	// $part2 = 0;
	// echo 'Part 2: ', $part2, "\n";
