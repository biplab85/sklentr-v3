/**
 * Sklentr — Services hero: typewriter reveal for the H1 on load.
 * Types the headline character-by-character, preserving the gold/green accent
 * segment and normalizing the template's whitespace. Skipped entirely under
 * prefers-reduced-motion (the heading stays as normal solid text).
 */
(function () {
	'use strict';

	var title = document.querySelector('.svc-hero__title');
	if (!title) {
		return;
	}
	if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return; // leave the heading untouched
	}

	// Build normalized segments from the existing markup (text node + accent span).
	var segs = [];
	Array.prototype.forEach.call(title.childNodes, function (node) {
		var accent = node.nodeType === 1 && node.classList && node.classList.contains('svc-hero__accent');
		var text = (node.textContent || '').replace(/\s+/g, ' ');
		if (text) {
			segs.push({ text: text, accent: accent });
		}
	});
	if (!segs.length) {
		return;
	}
	segs[0].text = segs[0].text.replace(/^\s+/, '');
	segs[segs.length - 1].text = segs[segs.length - 1].text.replace(/\s+$/, '');

	var tokens = [];
	segs.forEach(function (s) {
		for (var c = 0; c < s.text.length; c++) {
			tokens.push({ ch: s.text.charAt(c), accent: s.accent });
		}
	});
	if (!tokens.length) {
		return;
	}

	// Keep the full text accessible; hide the animated build from assistive tech.
	var full = tokens.map(function (t) { return t.ch; }).join('');
	title.setAttribute('aria-label', full);

	title.textContent = '';
	var typed = document.createElement('span');
	typed.setAttribute('aria-hidden', 'true');
	var caret = document.createElement('span');
	caret.className = 'svc-hero__caret';
	caret.setAttribute('aria-hidden', 'true');
	title.appendChild(typed);
	title.appendChild(caret);

	var curSpan = null;
	var curAccent = null;
	var i = 0;

	function step() {
		if (i >= tokens.length) {
			window.setTimeout(function () {
				if (caret && caret.parentNode) {
					caret.parentNode.removeChild(caret);
				}
			}, 1400);
			return;
		}
		var tk = tokens[i++];
		if (!curSpan || tk.accent !== curAccent) {
			curSpan = document.createElement('span');
			if (tk.accent) {
				curSpan.className = 'svc-hero__accent';
			}
			typed.appendChild(curSpan);
			curAccent = tk.accent;
		}
		curSpan.appendChild(document.createTextNode(tk.ch));
		window.setTimeout(step, tk.ch === ' ' ? 45 : 58);
	}

	window.setTimeout(step, 380);
})();
