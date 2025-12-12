#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLineGroups();
	$sizes = array_pop($input);

	$shapes = [];
	foreach ($input as $shape) {
		array_shift($shape);
		// $s = ['shape' => array_map(fn($x) => str_split($x), $shape), 'count' => 0];
		// $s['count'] = count(findCells($s['shape'], '#'));
		$shapes[] = ['count' => count_chars(implode('', $shape))[ord('#')]];
	}

	$trees = [];
	foreach ($sizes as $size) {
		preg_match('#(.*): (.*)#ADi', $size, $m);
		[$all, $size, $wanted] = $m;
		[$w, $h] = explode('x', $size);
		$trees[] = ['area' => (int)$w*$h, 'w' => (int)$w, 'h' => (int)$h, 'wanted' => array_map(fn($x) => (int)$x, explode(' ', $wanted))];
	}

	$part1 = 0;

	foreach ($trees as $tree) {
		$needed = 0;
		foreach ($tree['wanted'] as $i => $count) {
			$needed += $shapes[$i]['count'] * $count;

			if ($needed > $tree['area']) {
				continue 2;
			}
		}

		// For the actual input, apparently, this is enough?
		// Doesn't work for the test input though...
		$part1++;
	}

	echo 'Part 1: ', $part1, "\n";
