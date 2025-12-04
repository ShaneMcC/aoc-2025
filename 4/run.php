#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$map = getInputMap();

	$part1 = 0;

	function removeRolls(&$map) {
		$removals = [];

		foreach (cells($map) as [$x, $y, $cell]) {
			if ($cell != '@') { continue; }

			$adjacentRolls = 0;
			foreach (getAllAdjacentCells($map, $x,$y, true) as [$aX, $aY]) {
				if (($map[$aY][$aX] ?? '.') == '@') {
					$adjacentRolls++;
				}
			}

			if ($adjacentRolls < 4) {
				$removals[] = [$x, $y];
			}
		}

		foreach ($removals as [$x, $y]) {
			$map[$y][$x] = '.';
		}

		return count($removals);
	}

	$part1 = removeRolls($map);
	echo 'Part 1: ', $part1, "\n";

	$part2 = $count = $part1;

	do {
		$count = removeRolls($map);
		$part2 += $count;
	} while ($count != 0);
	echo 'Part 2: ', $part2, "\n";
