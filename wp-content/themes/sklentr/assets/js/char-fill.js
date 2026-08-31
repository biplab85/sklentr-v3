/**
 * Sklentr — scroll-scrubbed character "fill" reveal for section headings.
 * Splits any [data-char-fill] heading into characters and sweeps them from
 * grey → ink (with a subtle rise) as the heading scrolls up through the
 * viewport — reversible, tied to scroll position. Dependency-free.
 *
 * Accessibility: the original text is kept as a screen-reader-only copy and the
 * split characters are hidden from assistive tech (aria-hidden). Under
 * prefers-reduced-motion the heading is left completely untouched.
 */
(function () {
	'use strict';

	var heads = document.querySelectorAll('[data-char-fill]');
	if (!heads.length) {
		return;
	}
	if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return; // leave headings as normal solid text
	}

	// Palettes: [ from-rgb, to-rgb ]. Light sweeps grey → ink; dark sweeps a
	// dim slate → white (for headings on dark sections). Opt in per heading
	// with data-char-fill="dark".
	var PALETTES = {
		light: [ [203, 213, 225], [11, 17, 32] ],   // slate-300 → ink-950
		dark:  [ [100, 116, 139], [255, 255, 255] ], // slate-500 → white
		gold:  [ [100, 116, 139], [243, 179, 81] ],  // slate-500 → brand gold
		green: [ [100, 116, 139], [30, 255, 133] ],  // slate-500 → brand green
	};
	var SPREAD = 8; // characters in the soft leading edge

	function splitHeading(el) {
		var text = el.textContent;
		var chars = [];

		var sr = document.createElement('span');
		sr.className = 'screen-reader-text';
		sr.textContent = text;

		var visual = document.createElement('span');
		visual.setAttribute('aria-hidden', 'true');

		text.split(/(\s+)/).forEach(function (token) {
			if (token === '') {
				return;
			}
			if (/^\s+$/.test(token)) {
				visual.appendChild(document.createTextNode(token));
				return;
			}
			var word = document.createElement('span');
			word.className = 'cf-word';
			for (var i = 0; i < token.length; i++) {
				var c = document.createElement('span');
				c.className = 'cf-char';
				c.textContent = token[i];
				word.appendChild(c);
				chars.push(c);
			}
			visual.appendChild(word);
		});

		el.textContent = '';
		el.appendChild(sr);
		el.appendChild(visual);
		return chars;
	}

	function paint(c, f, from, to) {
		var r = Math.round(from[0] + (to[0] - from[0]) * f);
		var g = Math.round(from[1] + (to[1] - from[1]) * f);
		var b = Math.round(from[2] + (to[2] - from[2]) * f);
		c.style.color = 'rgb(' + r + ',' + g + ',' + b + ')';
		c.style.opacity = (0.35 + 0.65 * f).toFixed(3);
		c.style.transform = 'translateY(' + ((1 - f) * 6).toFixed(1) + 'px)';
	}

	var items = [];
	heads.forEach(function (el) {
		var palette = PALETTES[ el.getAttribute('data-char-fill') ] || PALETTES.light;
		items.push({ el: el, chars: splitHeading(el), from: palette[0], to: palette[1] });
	});

	var ticking = false;
	function update() {
		ticking = false;
		var vh = window.innerHeight || document.documentElement.clientHeight;
		var startY = vh * 0.90; // begin filling when the top hits 90% of viewport
		var endY = vh * 0.45;   // fully filled by 45%
		items.forEach(function (it) {
			var rect = it.el.getBoundingClientRect();
			var p = (startY - rect.top) / (startY - endY);
			if (p < 0) { p = 0; } else if (p > 1) { p = 1; }
			var n = it.chars.length;
			var filled = p * (n + SPREAD);
			for (var i = 0; i < n; i++) {
				var f = (filled - i) / SPREAD;
				f = f < 0 ? 0 : (f > 1 ? 1 : f);
				paint(it.chars[i], f, it.from, it.to);
			}
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
