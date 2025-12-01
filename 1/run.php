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

	foreach ($rotations as [$d, $r]) {
		$repeats = floor($r / $dialSize);
		$r -= $repeats * $dialSize;
		$part2 += $repeats;
		$oldValue = $value;
		$value += ($d == 'R') ? $r : 0 - $r;

		if ($oldValue != 0 && ($value <= 0 || $value >= $dialSize)) {
			$part2++;
		}

		$value = wrapmod($value, $dialSize );

		if ($value == 0) { $part1++; }
	}

	echo 'Part 1: ', $part1, "\n";
	echo 'Part 2: ', $part2, "\n";
