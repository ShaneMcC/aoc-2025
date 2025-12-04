#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$map = getInputMap();

	$part1 = 0;

	function removeRolls($map) {
		$count = 0;
		$newMap = $map;

		foreach (cells($map) as [$x, $y, $cell]) {
			$counts = [];
			foreach (getAdjacentCells($map, $x,$y, true) as [$aX, $aY]) {
				$aC = $map[$aY][$aX] ?? '.';
				$counts[$aC] = ($counts[$aC] ?? 0) + 1;
			}

			if ($cell == '@' && ($counts['@'] ?? 0) < 4) {
				$count++;
				$newMap[$y][$x] = '.';
			}
		}

		return [$count, $newMap];
	}

	$part1 = removeRolls($map)[0];
	echo 'Part 1: ', $part1, "\n";

	$part2 = 0;
	$count = 0;

	do {
		[$count, $map] = removeRolls($map);
		$part2 += $count;
	} while ($count != 0);
	echo 'Part 2: ', $part2, "\n";
