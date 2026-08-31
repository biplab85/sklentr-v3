/**
 * Sklentr — scroll-driven 3D tilt + parallax for the hero launch panel.
 * The panel starts reclined (rotateX) and flattens to 0° as it scrolls into a
 * comfortable viewing position, while drifting vertically (parallax) at a
 * fraction of the scroll for depth. Both are measured from the untransformed
 * stage element so the parallax offset never feeds back into the tilt maths.
 * Lerped for smoothness; honors prefers-reduced-motion (stays flat & still).
 */
(function () {
	'use strict';

	var tilt = document.querySelector('[data-hero-tilt]');
	if (!tilt) {
		return;
	}
	if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return; // stay flat (CSS default)
	}

	var stage = tilt.parentElement; // untransformed reference for measuring

	var MAX_TILT = 30;      // starting recline, degrees
	var PARALLAX = 0.06;    // vertical drift as a fraction of distance-from-center
	var PARALLAX_MAX = 40;  // px clamp on that drift

	var curAngle = MAX_TILT, tgtAngle = MAX_TILT;
	var curShift = 0, tgtShift = 0;
	var raf = null;

	function clamp(v, a, b) { return v < a ? a : (v > b ? b : v); }

	function computeTargets() {
		var rect = stage.getBoundingClientRect();
		var vh = window.innerHeight || document.documentElement.clientHeight;

		// Tilt: rect.top === vh → fully reclined (0) ; rect.top === 0.4*vh → flat (1)
		var tp = clamp((vh - rect.top) / (vh - vh * 0.4), 0, 1);
		tgtAngle = MAX_TILT * (1 - tp);

		// Parallax: drift relative to the viewport center.
		var center = rect.top + rect.height / 2;
		tgtShift = clamp((center - vh / 2) * PARALLAX, -PARALLAX_MAX, PARALLAX_MAX);
	}

	function apply() {
		tilt.style.transform =
			'translate3d(0,' + curShift.toFixed(1) + 'px,0) rotateX(' + curAngle.toFixed(2) + 'deg)';
	}

	function render() {
		curAngle += (tgtAngle - curAngle) * 0.16;
		curShift += (tgtShift - curShift) * 0.16;
		if (Math.abs(tgtAngle - curAngle) < 0.03) { curAngle = tgtAngle; }
		if (Math.abs(tgtShift - curShift) < 0.1) { curShift = tgtShift; }
		apply();
		raf = (curAngle !== tgtAngle || curShift !== tgtShift) ? window.requestAnimationFrame(render) : null;
	}

	function onScroll() {
		computeTargets();
		if (!raf) {
			raf = window.requestAnimationFrame(render);
		}
	}

	// Seed to the current scroll position without animating in.
	computeTargets();
	curAngle = tgtAngle;
	curShift = tgtShift;
	apply();

	window.addEventListener('scroll', onScroll, { passive: true });
	window.addEventListener('resize', onScroll, { passive: true });
})();
