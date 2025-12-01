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
	$dialSize = 100; // 0 - 99

	$part1 = $part2 = 0;
	$value = $startValue;

	foreach ($rotations as $r) {
		$repeats = floor($r[1] / $dialSize);
		$r[1] -= $repeats * $dialSize;
		$part2 += $repeats;
		$oldValue = $value;

		if ($r[0] == 'R') {
			$value += $r[1];
		} else {
			$value -= $r[1];
		}

		if ($oldValue != 0 && ($value < 0 || $value >= $dialSize || $value == 0)) {
			$part2++;
		}

		$value = wrapmod($value, $dialSize );

		if ($value == 0) { $part1++; }
	}

	echo 'Part 1: ', $part1, "\n";
	echo 'Part 2: ', $part2, "\n";
