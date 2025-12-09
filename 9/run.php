#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLines();

	$tiles = [];
	foreach ($input as $line) {
		preg_match('#(.*),(.*)#ADi', $line, $m);
		[$all, $x, $y] = $m;
		$tiles[] = [$x, $y];
	}

	function area($x1, $y1, $x2, $y2) {
		return (abs($x1 - $x2) + 1) * (abs($y1 - $y2) + 1);
	}

	$part1 = PHP_INT_MIN;

	for ($i = 0; $i < count($tiles); $i++) {
		for ($j = $i + 1; $j < count($tiles); $j++) {
			[$iX, $iY] = $tiles[$i];
			[$jX, $jY] = $tiles[$j];

			$part1 = max($part1, area($iX, $iY,$jX, $jY));
		}
	}

	echo 'Part 1: ', $part1, "\n";

	// $part2 = 0;
	// echo 'Part 2: ', $part2, "\n";
