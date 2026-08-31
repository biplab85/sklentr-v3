/**
 * Sklentr — "shuffle" card reveal (Synex .advance-wrap effect, no GSAP).
 * When a [data-advance] grid crosses 60% of the viewport, toggles `.is-in`
 * so its cards slide/rotate from a scattered CSS "from" state into their
 * aligned row — and reverses when scrolled back up above the trigger
 * (mirrors GSAP toggleActions "play none none reverse").
 *
 * JS applies the armed/hidden state itself (`.advance-armed`), so no-JS and
 * reduced-motion users always see the cards in place.
 */
(function () {
	'use strict';

	var grids = document.querySelectorAll('[data-advance]');
	if (!grids.length) {
		return;
	}
	if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return; // leave cards in place
	}

	grids.forEach(function (g) { g.classList.add('advance-armed'); });

	var ticking = false;
	function update() {
		ticking = false;
		var trigger = (window.innerHeight || document.documentElement.clientHeight) * 0.6;
		grids.forEach(function (g) {
			g.classList.toggle('is-in', g.getBoundingClientRect().top <= trigger);
		});
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
})();
