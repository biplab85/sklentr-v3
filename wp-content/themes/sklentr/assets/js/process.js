/**
 * Sklentr — How We Work: cumulative scroll-triggered reveal.
 *
 * The existing staircase is untouched. On natural scroll, the steps reveal
 * one at a time with a fade + slide — the first step first, then the rest —
 * each previously revealed step STAYING visible (they accumulate). Scrolling
 * back up hides them one at a time in reverse (Step 1 always stays). Scrubbed
 * to scroll position, so it is fully reversible.
 *
 * Runs for EVERY `.process__steps` staircase on the page — the homepage
 * "How We Work" section and the Pricing page's "Guarantees" section reuse the
 * same component, so both animate identically.
 *
 * Under prefers-reduced-motion / no-JS the container never gets `.is-reveal`,
 * so all steps are simply shown (CSS default) — design + functionality
 * preserved.
 */
(function () {
	'use strict';

	var wraps = document.querySelectorAll('.process__steps');
	if (!wraps.length) {
		return;
	}
	if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return; // leave all steps visible
	}

	function setup(stepsWrap) {
		var steps = stepsWrap.querySelectorAll('.process-step');
		var n = steps.length;
		if (!n) {
			return;
		}

		// Opt in to the hidden/entering state (Step 1 revealed immediately below).
		stepsWrap.classList.add('is-reveal');

		var ticking = false;
		function update() {
			ticking = false;
			var vh = window.innerHeight || document.documentElement.clientHeight;
			var rect = stepsWrap.getBoundingClientRect();

			// Progress as the steps area rises through the viewport:
			//   p = 0 when its top sits at 88% of the viewport height,
			//   p = 1 when its top reaches 30%.
			var startY = vh * 0.88;
			var endY = vh * 0.30;
			var p = (startY - rect.top) / (startY - endY);
			if (p < 0) { p = 0; } else if (p > 1) { p = 1; }

			// Step 1 is always shown; steps 2..n reveal at evenly spread thresholds
			// (i/n → 0.25, 0.50, 0.75 for four steps) and hide again below them.
			var shownCount = 0;
			for (var i = 0; i < n; i++) {
				var show = (i === 0) || (p >= i / n);
				steps[i].classList.toggle('is-shown', show);
				if (show) { shownCount++; }
			}
			// Connector line width tracks the revealed count (25 → 100%), animating
			// in sync with the step reveal/hide.
			stepsWrap.style.setProperty('--seq', (shownCount / n).toFixed(4));
		}
		function onScroll() {
			if (!ticking) {
				ticking = true;
				window.requestAnimationFrame(update);
			}
		}

		update();
		window.addEventListener('scroll', onScroll, { passive: true });
		window.addEventListener('resize', onScroll, { passive: true });
	}

	for (var w = 0; w < wraps.length; w++) {
		setup(wraps[w]);
	}
})();
