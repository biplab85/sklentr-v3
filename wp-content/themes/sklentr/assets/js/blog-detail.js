/**
 * Sklentr — Blog Details: builds the Table of Contents from the article's H2s,
 * highlights the section in view (scroll-spy), and wires the "copy link" button.
 * TOC links are plain #anchors, so smooth-anchors.js handles the smooth scroll
 * (with the fixed-header offset). Dependency-free.
 */
(function () {
	'use strict';

	var content = document.querySelector('[data-bd-content]');
	var tocNav  = document.querySelector('[data-bd-toc] .bd-toc__list');

	function slugify(s) {
		return s.toLowerCase().replace(/[^\w\s-]/g, '').trim().replace(/\s+/g, '-').slice(0, 60) || 'section';
	}

	// ---- Table of contents ----
	if (content && tocNav) {
		var headings = Array.prototype.slice.call(content.querySelectorAll('h2'));
		// WP post content headings have no ids — assign one (unique) from the text.
		var used = {};
		headings.forEach(function (h) {
			if (!h.id) {
				var base = slugify(h.textContent), id = base, n = 2;
				while (document.getElementById(id) || used[id]) { id = base + '-' + n++; }
				h.id = id;
			}
			used[h.id] = true;
		});

		if (!headings.length) {
			var toc = document.querySelector('[data-bd-toc]');
			if (toc) toc.hidden = true;
		} else {
			var links = [];
			headings.forEach(function (h) {
				var li = document.createElement('li');
				var a = document.createElement('a');
				a.href = '#' + h.id;
				a.textContent = h.textContent;
				a.className = 'bd-toc__link';
				li.appendChild(a);
				tocNav.appendChild(li);
				links.push(a);
			});

			// ---- Scroll-spy ----
			var ticking = false;
			function spy() {
				ticking = false;
				var offset = 140; // header + breathing room
				var current = headings[0];
				for (var i = 0; i < headings.length; i++) {
					if (headings[i].getBoundingClientRect().top - offset <= 0) {
						current = headings[i];
					} else {
						break;
					}
				}
				links.forEach(function (a) {
					var on = a.getAttribute('href') === '#' + current.id;
					a.classList.toggle('is-active', on);
				});
			}
			function onScroll() {
				if (!ticking) { ticking = true; window.requestAnimationFrame(spy); }
			}
			spy();
			window.addEventListener('scroll', onScroll, { passive: true });
			window.addEventListener('resize', onScroll);
		}
	}

	// ---- Copy link ----
	var copyBtn = document.querySelector('[data-bd-copy]');
	var copied  = document.querySelector('[data-bd-copied]');
	if (copyBtn) {
		copyBtn.addEventListener('click', function () {
			var url = window.location.href;
			var done = function () {
				if (copied) {
					copied.hidden = false;
					clearTimeout(copyBtn._t);
					copyBtn._t = setTimeout(function () { copied.hidden = true; }, 2000);
				}
			};
			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(url).then(done, done);
			} else {
				var ta = document.createElement('textarea');
				ta.value = url; document.body.appendChild(ta); ta.select();
				try { document.execCommand('copy'); } catch (e) {}
				document.body.removeChild(ta);
				done();
			}
		});
	}
})();
