/**
 * About · Our Values — scroll-driven diagonal parallax on the 2×2 value cards.
 * Mirrors the Ritovex "Who We Are" interaction: as the section scrolls through the
 * viewport, the four cards converge toward centre and spread back out on a diagonal.
 * Offsets are written to CSS custom props (--px/--py) so the card's hover-lift
 * transform composes cleanly with the parallax. Honors prefers-reduced-motion.
 */
(function () {
	'use strict';

	if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	// Per-index direction (grid order: 0 = top-left, 1 = top-right, 2 = bottom-left, 3 = bottom-right).
	// Positive p pulls each card toward the centre; negative p pushes it outward.
	var DIRS = [ [1, 1], [-1, 1], [1, -1], [-1, -1] ];
	var AMP_X = 44; // px
	var AMP_Y = 26; // px

	function initGrid(grid) {
		var cards = Array.prototype.slice.call(grid.querySelectorAll('[data-value-card]'));
		if (!cards.length) return;

		var section = grid.closest('.ab-values') || grid;
		var ticking = false;

		function update() {
			ticking = false;

			// On narrow screens the cards stack 1-col; horizontal parallax would
			// push them past the viewport edge — disable and reset.
			if (window.innerWidth < 720) {
				for (var j = 0; j < cards.length; j++) {
					cards[j].style.setProperty('--px', '0px');
					cards[j].style.setProperty('--py', '0px');
				}
				return;
			}

			var rect = section.getBoundingClientRect();
			var vh = window.innerHeight || document.documentElement.clientHeight;
			var center = rect.top + rect.height / 2;
			// -1 (entering from below) → 0 (centred) → 1 (leaving above)
			var p = (vh / 2 - center) / (vh / 2 + rect.height / 2);
			if (p > 1) p = 1; else if (p < -1) p = -1;

			for (var i = 0; i < cards.length; i++) {
				var d = DIRS[i % DIRS.length];
				cards[i].style.setProperty('--px', (d[0] * AMP_X * p).toFixed(1) + 'px');
				cards[i].style.setProperty('--py', (d[1] * AMP_Y * p).toFixed(1) + 'px');
			}
		}

		function onScroll() {
			if (!ticking) {
				ticking = true;
				window.requestAnimationFrame(update);
			}
		}

		window.addEventListener('scroll', onScroll, { passive: true });
		window.addEventListener('resize', onScroll);
		update();
	}

	function boot() {
		Array.prototype.forEach.call(
			document.querySelectorAll('[data-values-parallax]'),
			initGrid
		);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
})();
