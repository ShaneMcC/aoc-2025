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

	$paths['SVR-to-FFT'] = getPaths('svr', 'fft');
	$paths['FFT-to-DAC'] = getPaths('fft', 'dac');
	$paths['DAC-to-OUT'] = getPaths('dac', 'out');
	$order = 'SVR-to-FFT-to-DAC-to-OUT';
	$part2 = array_product($paths);

	if ($part2 == 0) {
		$paths = [];
		$paths['SVR-to-DAC'] = getPaths('svr', 'dac');
		$paths['DAC-to-FFT'] = getPaths('dac', 'fft');
		$paths['FFT-to-OUT'] = getPaths('fft', 'out');
		$order = 'SVR-to-DAC-to-FFT-to-OUT';
		$part2 = array_product($paths);
	}

	echo 'Part 2: ', $part2, ' (' ,$order, ')', "\n";

	if (isDebug()) {
		echo json_encode($paths, JSON_PRETTY_PRINT), "\n";
	}
