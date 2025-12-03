#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLines();

	function getBasicJoltage($line) {
		$len = strlen($line);
		$first = -1;
		$second = -1;
		for ($i = 0; $i < $len; $i++) {
			if ($i < ($len - 1)) {
				if ($line[$i] > $first) {
					$first = $line[$i];
					$second = $line[$i + 1];
					continue;
				}
			}

			if ($line[$i] > $second) {
				$second = $line[$i];
			}
		}

		return ($first * 10) + $second;
	}

	function getExtraJoltage($line, $amount = 12) {
		$len = strlen($line);

		$values = array_fill(1, $amount, -1);

		for ($i = 0; $i < $len; $i++) {
			$val = $line[$i];
			for ($j = 1; $j <= $amount; $j++) {
				if ($i < $len - ($amount - $j)) {
					if ($val > $values[$j]) {
						$values[$j] = $val;

						for ($k = $j + 1; $k <= $amount; $k++) {
							$values[$k] = -1;
						}

						continue 2;
					}
				}
			}
		}

		return (int)implode("", $values);
	}

	$part1 = 0;
	$part2 = 0;
	foreach ($input as $line) {
		$part1 += getBasicJoltage($line);
		$part2 += getExtraJoltage($line);
	}

	echo 'Part 1: ', $part1, "\n";
	echo 'Part 2: ', $part2, "\n";
