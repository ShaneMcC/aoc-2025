#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$map = getInputMap();

	$part1 = 0;

	function getWantedNeighbours($map, $previousRemovals) {
		$alreadyYielded = [];
		foreach ($previousRemovals as [$x, $y]) {
			foreach (getAllAdjacentCells($map, $x,$y, true) as [$aX, $aY]) {
				if (isset($alreadyYielded["{$aX}, {$aY}"])) { continue; }

				if (($map[$aY][$aX] ?? '.') == '@') {
					$alreadyYielded["{$aX}, {$aY}"] = true;
					yield [$aX, $aY];
				}
			}
		}
	}

	function removeRolls(&$map, $previousRemovals = null) {
		$removals = [];

		if ($previousRemovals == null) {
			$loop = wantedCells($map, '@');
		} else {
			$loop = getWantedNeighbours($map, $previousRemovals);
		}

		foreach ($loop as [$x, $y]) {
			$adjacentRolls = 0;
			foreach (getAllAdjacentCells($map, $x,$y, true) as [$aX, $aY]) {
				if (($map[$aY][$aX] ?? '.') == '@') {
					$adjacentRolls++;
					if ($adjacentRolls >= 4) { break; }
				}
			}

			if ($adjacentRolls < 4) {
				$removals[] = [$x, $y];
			}
		}

		foreach ($removals as [$x, $y]) {
			$map[$y][$x] = '.';
		}

		return $removals;
	}

	$removals = removeRolls($map, null);
	$part1 = count($removals);
	echo 'Part 1: ', $part1, "\n";

	$part2 = $count = $part1;

	do {
		$removals = removeRolls($map, $removals);
		$count = count($removals);
		$part2 += $count;
	} while ($count != 0);
	echo 'Part 2: ', $part2, "\n";
