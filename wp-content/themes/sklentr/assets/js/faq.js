/**
 * Sklentr — FAQ accordion (single-open).
 * JS adds `.is-enhanced` to the list, which collapses the panels (CSS animates
 * grid-template-rows). Clicking a question opens it and closes the others;
 * clicking the open one closes it. Without JS every answer stays visible.
 */
(function () {
	'use strict';

	var list = document.querySelector('[data-faq-list]');
	if (!list) {
		return;
	}
	var items = list.querySelectorAll('.faq-item');
	if (!items.length) {
		return;
	}

	list.classList.add('is-enhanced'); // now CSS collapses the panels

	items.forEach(function (item) {
		var btn = item.querySelector('.faq-item__q');
		if (!btn) {
			return;
		}
		btn.addEventListener('click', function () {
			var wasOpen = item.classList.contains('is-open');
			// Close everything (single-open).
			items.forEach(function (o) {
				o.classList.remove('is-open');
				var b = o.querySelector('.faq-item__q');
				if (b) { b.setAttribute('aria-expanded', 'false'); }
			});
			// Open this one unless it was already open.
			if (!wasOpen) {
				item.classList.add('is-open');
				btn.setAttribute('aria-expanded', 'true');
			}
		});
	});
})();
