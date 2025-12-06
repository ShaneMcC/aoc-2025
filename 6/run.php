#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLines();

	$part1 = 0;
	$problems = [];
	foreach ($input as $line) {
		$bits = preg_split('/\s+/', trim($line));
		for ($i = 0; $i < count($bits); $i++) {
			if (is_numeric($bits[$i])) {
				$problems[$i]['values'][] = $bits[$i];
			} else {
				$problems[$i]['operation'] = $bits[$i];

				if ($bits[$i] == '+') {
					$problems[$i]['total'] = array_sum($problems[$i]['values']);
				} else if ($bits[$i] == '*') {
					$problems[$i]['total'] = array_product($problems[$i]['values']);
				}

				$part1 += $problems[$i]['total'];
			}
		}
	}
	echo 'Part 1: ', $part1, "\n";



	// $part2 = 0;
	// echo 'Part 2: ', $part2, "\n";
