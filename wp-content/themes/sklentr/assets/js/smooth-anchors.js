/**
 * Sklentr — smooth scrolling scoped to intentional in-page anchor clicks only.
 *
 * The global CSS `scroll-behavior: smooth` was removed because it also animates
 * scroll RESTORATION (on reload / back-forward) and every programmatic scroll,
 * which — combined with the site's scroll-driven effects (char-fill, parallax,
 * the services wave) — reads as an unwanted jump/glide. Here we smooth-scroll
 * ONLY when a user clicks a same-page "#id" link, offsetting the fixed header.
 * Honors prefers-reduced-motion (instant jump).
 */
(function () {
	'use strict';

	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	function headerOffset() {
		var header = document.querySelector('.site-header');
		return header ? header.getBoundingClientRect().height : 0;
	}

	document.addEventListener('click', function (e) {
		var a = e.target.closest && e.target.closest('a[href^="#"]');
		if (!a) return;

		var hash = a.getAttribute('href');
		if (!hash || hash === '#' || hash.length < 2) return;

		var target;
		try {
			target = document.getElementById(hash.slice(1)) || document.querySelector(hash);
		} catch (err) {
			return; // invalid selector
		}
		if (!target) return;

		e.preventDefault();
		var top = target.getBoundingClientRect().top + window.scrollY - headerOffset() - 12;
		window.scrollTo({ top: Math.max(0, top), behavior: reduce ? 'auto' : 'smooth' });

		if (history.pushState) {
			history.pushState(null, '', hash);
		}
	}, false);
})();
