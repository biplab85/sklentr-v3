/**
 * Sklentr — hero "launch panel" count-up.
 * Counts each [data-count] number up to its target, timed to land just as the
 * badge flips to "On track". Honors prefers-reduced-motion (jumps to final).
 */
(function () {
	'use strict';

	var nodes = document.querySelectorAll('.panel__count[data-to]');
	if (!nodes.length) {
		return;
	}

	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	function setFinal(el) {
		el.textContent = el.getAttribute('data-to');
	}

	if (reduce) {
		nodes.forEach(setFinal);
		return;
	}

	var DURATION = 1000; // ms
	var START_DELAY = 1900; // sync with the badge flip / progress fill

	function easeOut(t) {
		return 1 - Math.pow(1 - t, 3);
	}

	function animate(el) {
		var target = parseInt(el.getAttribute('data-to'), 10) || 0;
		var startTime = null;

		function tick(now) {
			if (startTime === null) {
				startTime = now;
			}
			var progress = Math.min((now - startTime) / DURATION, 1);
			el.textContent = Math.round(easeOut(progress) * target).toString();
			if (progress < 1) {
				window.requestAnimationFrame(tick);
			} else {
				setFinal(el);
			}
		}

		window.requestAnimationFrame(tick);
	}

	// Seed at 0 so the numbers don't flash their final value before starting.
	nodes.forEach(function (el) { el.textContent = '0'; });

	window.setTimeout(function () {
		nodes.forEach(animate);
	}, START_DELAY);
})();
