/**
 * Sklentr — Footer column accordion (MOBILE ONLY, <=560px).
 *
 * Progressive enhancement: the four footer columns (Services, Company,
 * Resources, Contact Us) collapse into tap-to-toggle accordions on small
 * screens. Above 560px the enhancement is fully torn down — attributes,
 * classes and listeners removed — so the desktop footer (UI, content, IDs)
 * is byte-for-byte unchanged. Without JS the columns stay expanded.
 */
(function () {
	'use strict';

	var cols = document.querySelector('.footer-cols');
	if (!cols) {
		return;
	}

	var columns = Array.prototype.slice.call( cols.querySelectorAll('.footer-col') );
	if (!columns.length) {
		return;
	}

	var mq = window.matchMedia('(max-width: 560px)');
	var built = false;
	var bindings = [];

	function toggle(col) {
		var title = col.querySelector('.footer-col__title');
		var open = col.classList.toggle('is-open');
		if (title) {
			title.setAttribute('aria-expanded', open ? 'true' : 'false');
		}
	}

	function build() {
		if (built) {
			return;
		}
		columns.forEach(function (col, i) {
			var title = col.querySelector('.footer-col__title');
			var panel = title ? title.nextElementSibling : null;
			if (!title || !panel) {
				return;
			}
			if (!panel.id) {
				panel.id = 'footer-acc-panel-' + i;
			}
			title.setAttribute('role', 'button');
			title.setAttribute('tabindex', '0');
			title.setAttribute('aria-expanded', 'false');
			title.setAttribute('aria-controls', panel.id);
			col.classList.remove('is-open');

			var onClick = function () {
				toggle(col);
			};
			var onKey = function (e) {
				if (e.key === 'Enter' || e.key === ' ' || e.key === 'Spacebar') {
					e.preventDefault();
					toggle(col);
				}
			};
			title.addEventListener('click', onClick);
			title.addEventListener('keydown', onKey);
			bindings.push({ title: title, onClick: onClick, onKey: onKey });
		});
		cols.classList.add('is-accordion');
		built = true;
	}

	function teardown() {
		if (!built) {
			return;
		}
		cols.classList.remove('is-accordion');
		columns.forEach(function (col) {
			col.classList.remove('is-open');
			var title = col.querySelector('.footer-col__title');
			if (title) {
				title.removeAttribute('role');
				title.removeAttribute('tabindex');
				title.removeAttribute('aria-expanded');
				title.removeAttribute('aria-controls');
			}
		});
		bindings.forEach(function (b) {
			b.title.removeEventListener('click', b.onClick);
			b.title.removeEventListener('keydown', b.onKey);
		});
		bindings = [];
		built = false;
	}

	function apply() {
		if (mq.matches) {
			build();
		} else {
			teardown();
		}
	}

	apply();
	if (mq.addEventListener) {
		mq.addEventListener('change', apply);
	} else if (mq.addListener) {
		mq.addListener(apply);
	}
	// Fallback: some environments don't fire the matchMedia change on resize.
	window.addEventListener('resize', apply, { passive: true });
})();
