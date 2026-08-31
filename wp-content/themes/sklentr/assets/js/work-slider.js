/**
 * Sklentr — Featured Work: pinned scroll-driven horizontal slider + a
 * cursor-following "View" badge (mirrors the Ritovex "Explore Our Real Woks").
 *
 *  - The cards row pins to the viewport (`.work__sticky`) while you scroll
 *    through a tall spacer (`.work__scroll`); vertical scroll is mapped 1:1 to
 *    the row's horizontal translate, so every card slides through. Reversible.
 *  - Hovering a card hides the native cursor and shows a round "View" badge
 *    that eases toward the pointer.
 *
 * Runs only on a fine-pointer desktop with motion allowed. Otherwise the row is
 * a native horizontal swipe (see _work.scss) and the cursor stays default.
 */
(function () {
	'use strict';

	var section = document.getElementById('work');
	if (!section) {
		return;
	}
	var scroll = section.querySelector('.work__scroll');
	var sticky = section.querySelector('.work__sticky');
	var track = section.querySelector('.work__grid');
	var cursor = section.querySelector('.work__cursor');
	if (!scroll || !sticky || !track) {
		return;
	}

	var mqDesktop = window.matchMedia('(min-width: 901px) and (pointer: fine)');
	var mqReduce = window.matchMedia('(prefers-reduced-motion: reduce)');
	function enabled() {
		return mqDesktop.matches && !mqReduce.matches;
	}
	function travel() {
		return Math.max(0, track.scrollWidth - window.innerWidth);
	}

	// ---------- Layout: size the scroll spacer to the horizontal travel ----------
	function layout() {
		if (enabled()) {
			section.classList.add('work--live');
			scroll.style.height = ( window.innerHeight + travel() ) + 'px';
		} else {
			section.classList.remove('work--live');
			scroll.style.height = '';
			track.style.transform = '';
		}
		slideUpdate();
	}

	// ---------- Scroll → horizontal translate ----------
	var ticking = false;
	function slideUpdate() {
		ticking = false;
		if (!enabled()) {
			return;
		}
		var t = travel();
		var scrollable = scroll.offsetHeight - window.innerHeight;
		if (t <= 0 || scrollable <= 0) {
			track.style.transform = '';
			return;
		}
		var p = ( -scroll.getBoundingClientRect().top ) / scrollable;
		if (p < 0) { p = 0; } else if (p > 1) { p = 1; }
		track.style.transform = 'translate3d(' + ( -p * t ).toFixed(1) + 'px,0,0)';
	}
	function onScroll() {
		if (!ticking) {
			ticking = true;
			window.requestAnimationFrame(slideUpdate);
		}
	}

	// ---------- Cursor-following "View" badge ----------
	var mx = 0, my = 0, px = 0, py = 0, s = 0, ts = 0, running = false;
	function tick() {
		px += (mx - px) * 0.2;
		py += (my - py) * 0.2;
		s += (ts - s) * 0.2;
		cursor.style.transform = 'translate3d(' + px.toFixed(1) + 'px,' + py.toFixed(1) + 'px,0) translate(-50%,-50%) scale(' + s.toFixed(3) + ')';
		if (ts > 0 || s > 0.005 || Math.abs(mx - px) > 0.5 || Math.abs(my - py) > 0.5) {
			window.requestAnimationFrame(tick);
		} else {
			running = false;
		}
	}
	function startTick() {
		if (!running) {
			running = true;
			window.requestAnimationFrame(tick);
		}
	}
	if (cursor) {
		var links = track.querySelectorAll('.work-card__link');
		for (var i = 0; i < links.length; i++) {
			links[i].addEventListener('mouseenter', function () {
				if (!enabled()) { return; }
				ts = 1;
				startTick();
			});
			links[i].addEventListener('mouseleave', function () {
				ts = 0;
				startTick();
			});
		}
		sticky.addEventListener('mousemove', function (e) {
			mx = e.clientX;
			my = e.clientY;
			startTick();
		}, { passive: true });
	}

	layout();
	window.addEventListener('scroll', onScroll, { passive: true });
	window.addEventListener('resize', function () { layout(); }, { passive: true });
	window.addEventListener('load', layout);
	if (mqDesktop.addEventListener) {
		mqDesktop.addEventListener('change', layout);
		mqReduce.addEventListener('change', layout);
	}
})();
