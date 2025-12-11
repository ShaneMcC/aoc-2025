#!/usr/bin/php
<?php
	require_once(dirname(__FILE__) . '/../common/common.php');
	$input = getInputLines();

	$entries = [];
	foreach ($input as $line) {
		preg_match('#(.*): (.*)#ADi', $line, $m);
		[$all, $input, $ouputs] = $m;
		$entries[$input] = ['outputs' => explode(' ', $ouputs)];
	}

	$queue = new SplPriorityQueue();
	$queue->setExtractFlags(SplPriorityQueue::EXTR_DATA);

	$queue->insert('you', 0);

	$part1 = 0;

	while (!$queue->isEmpty()) {
		$next = $queue->extract();

		$outputs = $entries[$next]['outputs'];

		foreach ($outputs as $out) {
			if ($out == 'out') {
				$part1++;
			} else {
				$queue->insert($out, 0);
			}
		}
	}


	echo 'Part 1: ', $part1, "\n";

	// $part2 = 0;
	// echo 'Part 2: ', $part2, "\n";
