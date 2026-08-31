/**
 * Sklentr — Startup Visa "deal-out" cards (mirrors the Ritovex "Working
 * Process" section). The three feature cards start piled on top of each other
 * (first card in front) and slide DOWN into their column one by one as the
 * section scrolls; scrolling back up re-stacks them. Scroll-scrubbed and fully
 * reversible, dependency-free.
 *
 * Mechanic: each card i is offset by `-(naturalTop_i - naturalTop_0)` when
 * stacked (translateY that pulls it up onto the first card), scrubbed toward 0
 * as the section scrolls through. Because later cards have a larger offset,
 * they finish sliding later → the "one by one" cascade.
 *
 * Accessibility: under prefers-reduced-motion, or below the desktop breakpoint,
 * no transforms are applied (cards just flow as a normal column). No-JS shows
 * the same natural column.
 */
(function () {
	'use strict';

	var section = document.getElementById('startup-visa');
	if (!section) {
		return;
	}
	var cards = section.querySelectorAll('.visa-feature');
	if (cards.length < 2) {
		return;
	}

	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var stackTY = [];

	function measure() {
		var base = cards[0].offsetTop;
		for (var i = 0; i < cards.length; i++) {
			stackTY[i] = -(cards[i].offsetTop - base);
		}
	}

	function enabled() {
		return !reduce && window.innerWidth >= 900;
	}

	function clearTransforms() {
		for (var i = 0; i < cards.length; i++) {
			cards[i].style.transform = '';
		}
	}

	function update() {
		if (!enabled()) {
			clearTransforms();
			return;
		}
		var vh = window.innerHeight || document.documentElement.clientHeight;
		var rect = section.getBoundingClientRect();
		// Stacked while the section top is still in the lower half of the viewport;
		// fully dealt out by the time it nears the top. The window is kept short of
		// the viewport top so the deal-out completes with scroll room to spare
		// (this is the last section before the footer).
		var start = vh * 0.5;
		var end = -vh * 0.1;
		var p = (start - rect.top) / (start - end);
		if (p < 0) { p = 0; } else if (p > 1) { p = 1; }

		for (var i = 0; i < cards.length; i++) {
			var ty = stackTY[i] * (1 - p);
			cards[i].style.transform = 'translateY(' + ty.toFixed(1) + 'px)';
		}
	}

	var ticking = false;
	function onScroll() {
		if (!ticking) {
			ticking = true;
			window.requestAnimationFrame(function () {
				ticking = false;
				update();
			});
		}
	}

	measure();
	update();
	window.addEventListener('scroll', onScroll, { passive: true });
	window.addEventListener('resize', function () {
		measure();
		update();
	}, { passive: true });
})();
