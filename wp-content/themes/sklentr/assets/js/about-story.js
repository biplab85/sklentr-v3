/**
 * About · Our Story — scroll-driven depth parallax.
 * Each element carrying [data-story-speed] drifts vertically at its own rate as
 * the section moves through the viewport, giving the framed photo, floating cards
 * and decorations a layered, premium sense of depth. Honors prefers-reduced-motion
 * and disables on narrow screens (where the section stacks).
 */
(function () {
	'use strict';

	if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	var section = document.querySelector('.ab-story');
	if (!section) return;

	var items = Array.prototype.slice.call(section.querySelectorAll('[data-story-speed]'));
	if (!items.length) return;

	var speeds = items.map(function (el) {
		return parseFloat(el.getAttribute('data-story-speed')) || 0;
	});
	var ticking = false;
	var off = false; // whether transforms are currently cleared (mobile)

	function update() {
		ticking = false;

		if (window.innerWidth < 720) {
			if (!off) {
				for (var k = 0; k < items.length; k++) { items[k].style.transform = ''; }
				off = true;
			}
			return;
		}
		off = false;

		var rect = section.getBoundingClientRect();
		var vh = window.innerHeight || document.documentElement.clientHeight;
		var center = rect.top + rect.height / 2;
		var p = (vh / 2 - center) / (vh / 2 + rect.height / 2);
		if (p > 1) p = 1; else if (p < -1) p = -1;

		for (var i = 0; i < items.length; i++) {
			items[i].style.transform = 'translate3d(0,' + (speeds[i] * p).toFixed(1) + 'px,0)';
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
})();
