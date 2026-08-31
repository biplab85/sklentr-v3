/**
 * Sklentr — Portfolio manifesto section.
 * Scroll-scrubbed reveal: as the section moves through the viewport, a --p
 * progress value (0 → 1) is written to the section so the four flanking photos
 * fan out from behind the centered statement (driven in CSS via calc(--p)).
 * Honors prefers-reduced-motion (photos sit in their final spread, static).
 */
(function () {
	'use strict';

	var sec = document.querySelector('.pf-manifesto');
	if (!sec) {
		return;
	}

	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	if (reduce) {
		sec.style.setProperty('--p', '1');
		return;
	}

	var ticking = false;

	function update() {
		ticking = false;
		var rect = sec.getBoundingClientRect();
		var vh = window.innerHeight || document.documentElement.clientHeight;
		// The section is a tall track whose inner content pins (position:sticky).
		// Progress = how far we've scrolled through the pinned range:
		//   rect.top ranges 0 (pin starts, section top at viewport top)
		//   → -(offsetHeight - vh) (pin ends). p maps that to 0→1.
		// While the section is still entering (rect.top > 0) p clamps to 0, so
		// the photos stay tiny behind the text (text-only) until the pin engages.
		var track = sec.offsetHeight - vh;
		var p = track > 0 ? (-rect.top) / track : 1;
		p = Math.max(0, Math.min(1, p));
		sec.style.setProperty('--p', p.toFixed(4));
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
