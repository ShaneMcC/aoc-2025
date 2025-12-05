#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLineGroups();

	$freshRanges = [];
	foreach ($input[0] as $line) {
		preg_match('#(.*)-(.*)#ADi', $line, $m);
		[$all, $start, $end] = $m;
		$freshRanges[] = [$start, $end];
	}

	function isFresh($freshRanges, $ingredient) {
		foreach ($freshRanges as [$start, $end]) {
			if ($start <= $ingredient && $ingredient <= $end) {
				return true;
			}
		}
		return false;
	}

	$ingredients = $input[1];

	$part1 = 0;

	foreach ($ingredients as $ingredient) {
		if (isFresh($freshRanges, $ingredient)) {
			$part1++;
		}
	}
	echo 'Part 1: ', $part1, "\n";

	$newRanges = [];

	for ($i = 0; $i < count($freshRanges); $i++) {
		[$start, $end] = $freshRanges[$i];
		if ($start == null) { continue; }

		// Compare us to every other range that isn't us.
		for ($k = $i + 1; $k < count($freshRanges); $k++) {
			[$otherStart, $otherEnd] = $freshRanges[$k];
			if ($otherStart == null) { continue; }

			// If the other range is entirely contained within us, then just remove it.
			if ($start <= $otherStart && $otherStart <= $end) {
				if ($start <= $otherEnd && $otherEnd <= $end) {
					$freshRanges[$k] = [null, null];
					continue;
				}
			}

			// If our start is within this range, then we need to adjust it to be outside.
			if ($otherStart <= $start && $start <= $otherEnd) {
				$start = $otherEnd + 1;
			}

			// If our end is within this range, then we need to adjust it to be outside.
			if ($otherStart <= $end && $end <= $otherEnd) {
				$end = $otherStart - 1;
			}

			// If our range has now ended up invalid somehow, then just remove us.
			if ($start > $end) {
				$start = null;
				$end = null;
			}
		}

		$freshRanges[$i] = [$start, $end];
	}

	$part2 = 0;
	foreach ($freshRanges as [$start, $end]) {
		if ($start == null) { continue; }

		$part2 += ($end - $start) + 1;
	}
	echo 'Part 2: ', $part2, "\n";
