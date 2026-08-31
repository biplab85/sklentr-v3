/**
 * Sklentr — scroll-triggered count-up for any [data-count-to] element.
 * Animates 0 → target when the element scrolls into view (once).
 * Honors prefers-reduced-motion (jumps straight to the final value).
 */
(function () {
	'use strict';

	var nodes = document.querySelectorAll('[data-count-to]');
	if (!nodes.length) {
		return;
	}

	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	function setFinal(el) {
		el.textContent = el.getAttribute('data-count-to');
	}

	if (reduce || !('IntersectionObserver' in window)) {
		nodes.forEach(setFinal);
		return;
	}

	var DURATION = 1400; // ms

	function easeOut(t) {
		return 1 - Math.pow(1 - t, 3);
	}

	function animate(el) {
		var target = parseInt(el.getAttribute('data-count-to'), 10) || 0;
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

	// Seed at 0 so nothing flashes the final value before entering view.
	nodes.forEach(function (el) { el.textContent = '0'; });

	var observer = new IntersectionObserver(function (entries) {
		entries.forEach(function (entry) {
			if (entry.isIntersecting) {
				animate(entry.target);
				observer.unobserve(entry.target);
			}
		});
	}, { threshold: 0.4 });

	nodes.forEach(function (el) { observer.observe(el); });
})();
