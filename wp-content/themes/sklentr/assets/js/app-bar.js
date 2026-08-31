/**
 * Sklentr — mobile app bar behaviour.
 *  - "Menu" tab opens the existing full nav overlay (reuses the header toggle).
 *  - Smart auto-hide: slides the bar away on scroll-down, back on scroll-up.
 *    Always visible near the top and bottom of the page.
 * All of this only matters ≤ 980px, where the bar is displayed (see SCSS).
 */
(function () {
	'use strict';

	var bar = document.querySelector('[data-app-bar]');
	if (!bar) {
		return;
	}

	/* ---- "Menu" tab → open the full nav overlay (reuse the header toggle) ---- */
	var more = bar.querySelector('[data-app-bar-more]');
	if (more) {
		more.addEventListener('click', function () {
			var toggle = document.querySelector('[data-nav-toggle]');
			if (toggle) {
				toggle.click();
			}
		});
	}

	/* ---- Smart auto-hide on scroll ---- */
	var lastY = window.scrollY || 0;
	var ticking = false;
	var EDGE = 120;   // stay visible within this many px of the top/bottom
	var DELTA = 6;    // ignore tiny scroll jitters

	function update() {
		var y = window.scrollY || 0;
		var max = document.documentElement.scrollHeight - window.innerHeight;
		var diff = y - lastY;

		if (Math.abs(diff) > DELTA) {
			var nearEdge = y < EDGE || y > max - EDGE;
			if (diff > 0 && !nearEdge) {
				bar.classList.add('is-hidden');   // scrolling down → hide
			} else {
				bar.classList.remove('is-hidden'); // scrolling up (or near edge) → show
			}
			lastY = y;
		}
		ticking = false;
	}

	window.addEventListener('scroll', function () {
		if (!ticking) {
			window.requestAnimationFrame(update);
			ticking = true;
		}
	}, { passive: true });

	/* Never leave it hidden after the user stops interacting at the top. */
	window.addEventListener('resize', function () {
		bar.classList.remove('is-hidden');
	});
})();
