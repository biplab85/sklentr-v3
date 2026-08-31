/**
 * Sklentr — Services "What We Do": sticky tab nav ↔ scrolling cards.
 *  - Clicking a tab smooth-scrolls to its card (offset handled by CSS
 *    scroll-margin-top; jumps instantly under prefers-reduced-motion).
 *  - The active tab follows whichever card is crossing the viewport's middle
 *    band, via IntersectionObserver.
 */
(function () {
	'use strict';

	var root = document.querySelector('[data-svc-tabs]');
	if (!root) {
		return;
	}

	var tabs = Array.prototype.slice.call(root.querySelectorAll('[data-svc-tab]'));
	var cards = Array.prototype.slice.call(root.querySelectorAll('[data-svc-card]'));
	if (!tabs.length || !cards.length) {
		return;
	}

	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	function setActive(n) {
		var key = String(n);
		tabs.forEach(function (tab) {
			var on = tab.getAttribute('data-svc-tab') === key;
			tab.classList.toggle('is-active', on);
			if (on) {
				tab.setAttribute('aria-current', 'true');
			} else {
				tab.removeAttribute('aria-current');
			}
		});
	}

	// Tab click → smooth-scroll to the matching card.
	tabs.forEach(function (tab) {
		tab.addEventListener('click', function (e) {
			var sel = tab.getAttribute('href');
			var target = sel && document.querySelector(sel);
			if (!target) {
				return;
			}
			e.preventDefault();
			target.scrollIntoView({ behavior: reduce ? 'auto' : 'smooth', block: 'start' });
			setActive(tab.getAttribute('data-svc-tab'));
		});
	});

	// Active state follows the card nearest the viewport centre.
	if ('IntersectionObserver' in window) {
		var observer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					setActive(entry.target.getAttribute('data-svc-card'));
				}
			});
		}, { rootMargin: '-25% 0px -60% 0px', threshold: 0 });

		cards.forEach(function (card) { observer.observe(card); });
	}
})();
