/**
 * Sklentr — header & primary nav behaviour.
 *  - Adds .is-scrolled to the header once the page is scrolled.
 *  - Toggles the mobile menu (.is-open) with correct aria state + scroll lock.
 *  - Closes on Escape, on nav-link click, and when resizing up to desktop.
 */
(function () {
	'use strict';

	var header = document.querySelector('[data-header]');
	if (!header) {
		return;
	}

	var toggle = header.querySelector('[data-nav-toggle]');
	var nav = document.getElementById('site-nav');
	var scrim = document.querySelector('[data-nav-scrim]');
	var DESKTOP = 980;

	/* ---- Sticky / scrolled state ---- */
	var ticking = false;
	function updateScrolled() {
		header.classList.toggle('is-scrolled', window.scrollY > 8);
		ticking = false;
	}
	function onScroll() {
		if (!ticking) {
			window.requestAnimationFrame(updateScrolled);
			ticking = true;
		}
	}
	window.addEventListener('scroll', onScroll, { passive: true });
	updateScrolled();

	/* ---- Mobile menu ---- */
	function openMenu() {
		header.classList.add('is-open');
		document.body.classList.add('nav-open');
		if (toggle) {
			toggle.setAttribute('aria-expanded', 'true');
		}
	}
	function closeMenu() {
		header.classList.remove('is-open');
		document.body.classList.remove('nav-open');
		if (toggle) {
			toggle.setAttribute('aria-expanded', 'false');
		}
	}
	function isOpen() {
		return header.classList.contains('is-open');
	}

	if (toggle) {
		toggle.addEventListener('click', function () {
			if (isOpen()) {
				closeMenu();
			} else {
				openMenu();
			}
		});
	}

	/* Close when a menu link is tapped (mobile) */
	if (nav) {
		nav.addEventListener('click', function (e) {
			var link = e.target.closest('a');
			if (link && isOpen()) {
				closeMenu();
			}
		});
	}

	/* Close when the backdrop scrim is tapped (drawer) */
	if (scrim) {
		scrim.addEventListener('click', function () {
			if (isOpen()) {
				closeMenu();
			}
		});
	}

	/* Close via the in-drawer close (X) button */
	var closeBtn = document.querySelector('[data-nav-close]');
	if (closeBtn) {
		closeBtn.addEventListener('click', function () {
			closeMenu();
			if (toggle) {
				toggle.focus();
			}
		});
	}

	/* Close on Escape */
	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape' && isOpen()) {
			closeMenu();
			if (toggle) {
				toggle.focus();
			}
		}
	});

	/* Reset when resizing back up to desktop */
	var resizeTimer;
	window.addEventListener('resize', function () {
		window.clearTimeout(resizeTimer);
		resizeTimer = window.setTimeout(function () {
			if (window.innerWidth > DESKTOP && isOpen()) {
				closeMenu();
			}
		}, 150);
	});
})();
