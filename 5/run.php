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
	usort($freshRanges, fn ($a, $b) => ($a[0] <=> $b[0]));

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

	$part2 = 0;

	// First range is always valid.
	[$start, $end] = $freshRanges[0];
	$lastValidRange = [$start, $end];
	$part2 += ($end - $start) + 1;

	// Go through all the rest, and adjust/count.
	for ($i = 1; $i < count($freshRanges); $i++) {
		[$start, $end] = $freshRanges[$i];
		[$otherStart, $otherEnd] = $lastValidRange;

		// If our end is before the previous end, no need to worry about this range.
		if ($end <= $otherEnd) {
			continue;
		}

		// If our start is before the previous end, then adjust it to be after.
		if ($start <= $otherEnd) {
			$start = $otherEnd + 1;
		}

		$lastValidRange = [$start, $end];
		$part2 += ($end - $start) + 1;
	}

	echo 'Part 2: ', $part2, "\n";
