/**
 * Sklentr — "Why Sklentr" cards: stacked-deck ↔ expand, scrubbed to scroll.
 * The six cards overlap at the grid's centre (a single stack) and fan out to
 * their natural equal-spaced positions as the section moves through the middle
 * of the viewport — folding back into the stack as it leaves, in EITHER scroll
 * direction (so the overlap is visible scrolling up as well as down).
 * Transforms only — layout/spacing are never touched. Skipped under
 * prefers-reduced-motion.
 */
(function () {
	'use strict';

	var grid = document.querySelector('.svc-why__grid');
	if (!grid) {
		return;
	}

	var cards = Array.prototype.slice.call(grid.querySelectorAll('.svc-perk'));
	if (cards.length < 2) {
		return;
	}

	if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return; // leave the cards in their normal positions
	}

	var offsets = [];       // per-card {dx,dy} to reach the grid centre
	var lastP = -1;
	var ticking = false;

	// Measure each card's offset to the grid centre (cards at natural position).
	function measure() {
		cards.forEach(function (c) { c.style.transition = 'none'; c.style.transform = 'none'; });
		void grid.offsetWidth;
		var gr = grid.getBoundingClientRect();
		var cx = gr.left + gr.width / 2;
		var cy = gr.top + gr.height / 2;
		offsets = cards.map(function (c) {
			var r = c.getBoundingClientRect();
			return { dx: Math.round(cx - (r.left + r.width / 2)), dy: Math.round(cy - (r.top + r.height / 2)) };
		});
	}

	// 0 = fully stacked, 1 = fully expanded — based on the grid's position in
	// the viewport (peaks while centred, falls off toward either edge).
	function progress() {
		var vh = window.innerHeight || document.documentElement.clientHeight;
		var r = grid.getBoundingClientRect();
		var span = vh * 0.6;
		var enter = (vh - r.top) / span;   // rises as the grid enters from the bottom
		var exit = r.bottom / span;        // falls as the grid exits past the top
		var p = Math.min(enter, exit);
		return p < 0 ? 0 : (p > 1 ? 1 : p);
	}

	function apply(p) {
		var e = p * p * (3 - 2 * p); // smoothstep for a softer settle
		for (var i = 0; i < cards.length; i++) {
			var c = cards[i];
			var o = offsets[i];
			if (e >= 0.999) {
				// Fully expanded → hand transform back to CSS so hover lift works.
				if (c.style.transform !== '') {
					c.style.transform = '';
					c.style.transition = '';
				}
			} else {
				var s = (0.94 + 0.06 * e).toFixed(4);
				c.style.transition = 'none';
				c.style.transform = 'translate(' + (o.dx * (1 - e)).toFixed(1) + 'px, ' + (o.dy * (1 - e)).toFixed(1) + 'px) scale(' + s + ')';
			}
		}
	}

	function update() {
		ticking = false;
		var p = progress();
		if (Math.abs(p - lastP) < 0.002) {
			return;
		}
		lastP = p;
		apply(p);
	}

	function onScroll() {
		if (!ticking) {
			ticking = true;
			window.requestAnimationFrame(update);
		}
	}

	measure();
	update();
	window.addEventListener('scroll', onScroll, { passive: true });
	window.addEventListener('resize', function () {
		measure();
		lastP = -1;
		update();
	}, { passive: true });
})();
