/**
 * Sklentr — Portfolio "Featured Works".
 * The left column is sticky; the right column is a scrolling stack of project
 * images. As each image crosses the viewport centre, the active index updates:
 * the matching name brightens, the big number rolls, and the title/description
 * panel swaps — mirroring the Novatra reference. Clicking a name scrolls to it.
 */
(function () {
	'use strict';

	var sec = document.querySelector('.pf-featured');
	if (!sec) {
		return;
	}

	var shots  = Array.prototype.slice.call(sec.querySelectorAll('[data-shot]'));
	var names  = Array.prototype.slice.call(sec.querySelectorAll('[data-name]'));
	var panels = Array.prototype.slice.call(sec.querySelectorAll('[data-panel]'));
	var track  = sec.querySelector('.pf-featured__num-track');
	if (!shots.length) {
		return;
	}

	var current = -1;

	function setActive(i) {
		if (i === current) {
			return;
		}
		current = i;
		names.forEach(function (n, ix) { n.classList.toggle('is-active', ix === i); });
		panels.forEach(function (p, ix) { p.classList.toggle('is-active', ix === i); });
		shots.forEach(function (s, ix) { s.classList.toggle('is-active', ix === i); });
		if (track) {
			track.style.setProperty('--i', i);
		}
	}

	setActive(0);

	// Click a name → scroll its image to centre.
	names.forEach(function (n, ix) {
		n.addEventListener('click', function () {
			if (shots[ix]) {
				shots[ix].scrollIntoView({ behavior: 'smooth', block: 'center' });
			}
		});
	});

	if (!('IntersectionObserver' in window)) {
		return; // Static fallback: first project stays active.
	}

	// A shot becomes active while it occupies the middle ~10% band of the viewport.
	var io = new IntersectionObserver(function (entries) {
		entries.forEach(function (entry) {
			if (entry.isIntersecting) {
				setActive(parseInt(entry.target.getAttribute('data-index'), 10) || 0);
			}
		});
	}, { rootMargin: '-45% 0px -45% 0px', threshold: 0 });

	shots.forEach(function (s) { io.observe(s); });
})();
