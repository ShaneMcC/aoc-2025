#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLines();

	$entries = [];
	foreach ($input as $line) {
		preg_match('#(.*): (.*)#ADi', $line, $m);
		[$all, $input, $ouputs] = $m;
		$entries[$input] = ['outputs' => explode(' ', $ouputs), 'inputs' => []];
	}

	function getPaths($start, $end, $exclude = []) {
		global $entries;

		$result = 0;
		if (!isset($entries[$start])) { return 0; }

		$counts[$start] = 1;
		while (!empty($counts)) {
			$loc = key($counts);
			$paths = array_shift($counts);

			if (!isset($entries[$loc])) { continue; }

			foreach ($entries[$loc]['outputs'] as $next) {
				if (in_array($next, $exclude)) { continue; }
				if ($next == $end) {
					$result += $paths;
				} else {
					$counts[$next] = ($counts[$next] ?? 0) + $paths;
				}
			}
		}

		return $result;
	}

	$part1 = getPaths('you', 'out');
	echo 'Part 1: ', $part1, "\n";

	$pathsToFFT = getPaths('svr', 'fft');
	$pathsToDAC = getPaths('fft', 'dac');
	$pathsToOUT = getPaths('dac', 'out');

	$part2 = $pathsToFFT * $pathsToDAC * $pathsToOUT;
	echo 'Part 2: ', $part2, "\n";

	if (isDebug()) {
		echo "\t", 'pathsToFFT: ', $pathsToFFT, "\n";
		echo "\t", 'pathsToDAC: ', $pathsToDAC, "\n";
		echo "\t", 'pathsToOUT: ', $pathsToOUT, "\n";
	}
