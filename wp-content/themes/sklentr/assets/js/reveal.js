/**
 * Sklentr — generic scroll reveal.
 * Adds `.is-revealed` to any [data-reveal] element when it scrolls into view
 * (once). JS applies the pre-hidden `.reveal-init` state itself, so no-JS and
 * reduced-motion users always see the content (nothing is hidden by CSS alone).
 */
(function () {
	'use strict';

	var nodes = document.querySelectorAll('[data-reveal]');
	if (!nodes.length) {
		return;
	}

	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	if (reduce || !('IntersectionObserver' in window)) {
		return; // leave everything visible
	}

	nodes.forEach(function (n) { n.classList.add('reveal-init'); });

	var observer = new IntersectionObserver(function (entries) {
		entries.forEach(function (entry) {
			if (entry.isIntersecting) {
				entry.target.classList.add('is-revealed');
				observer.unobserve(entry.target);
			}
		});
	}, { threshold: 0.15, rootMargin: '0px 0px -8% 0px' });

	nodes.forEach(function (n) { observer.observe(n); });
})();
