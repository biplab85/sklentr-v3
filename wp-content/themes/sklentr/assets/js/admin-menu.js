/**
 * Sklentr — collapsible admin submenu.
 *
 * The Sklentr menu carries 18 child items, which pushes Appearance/Plugins/etc.
 * far down the sidebar. This adds a chevron toggle on the "Sklentr" row that
 * folds the child list away; the choice is remembered in localStorage.
 *
 * Collapsing simply drops WordPress's own `wp-menu-open` class, so the menu
 * falls back to core's standard behaviour for a non-current menu: the list is
 * hidden inline and still available as a hover flyout. No core CSS is fought.
 */
(function () {
	'use strict';

	var KEY = 'sklentrMenuCollapsed';

	document.addEventListener('DOMContentLoaded', function () {
		var li = document.getElementById('toplevel_page_sklentr-settings');
		if (!li) {
			return;
		}

		var sub = li.querySelector('.wp-submenu');
		var top = li.querySelector('a.menu-top');
		if (!sub || !top) {
			return;
		}

		if (!sub.id) {
			sub.id = 'skl-submenu';
		}

		// Only the inline (expanded) menu is collapsible. When WordPress is
		// already showing this as a flyout — i.e. we're on a non-Sklentr screen,
		// or the sidebar is folded — there is nothing to fold away.
		var isInline = li.classList.contains('wp-menu-open');

		var btn = document.createElement('button');
		btn.type = 'button';
		btn.className = 'skl-menu__toggle';
		btn.setAttribute('aria-controls', sub.id);
		btn.innerHTML = '<span class="screen-reader-text">Toggle Sklentr submenu</span>' +
			'<span class="skl-menu__chev" aria-hidden="true"></span>';

		function apply(collapsed, animate) {
			if (animate) {
				li.classList.add('skl-menu--animating');
				window.setTimeout(function () {
					li.classList.remove('skl-menu--animating');
				}, 200);
			}

			li.classList.toggle('skl-menu--collapsed', collapsed);
			// Hand control back to core: without wp-menu-open it behaves exactly
			// like any other non-current menu (hidden inline, flyout on hover).
			li.classList.toggle('wp-menu-open', !collapsed);
			btn.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
			btn.title = collapsed ? 'Expand Sklentr menu' : 'Collapse Sklentr menu';
		}

		var stored = null;
		try {
			stored = window.localStorage.getItem(KEY);
		} catch (e) {
			stored = null;
		}

		if (isInline) {
			li.appendChild(btn);
			apply(stored === '1', false);

			btn.addEventListener('click', function (e) {
				e.preventDefault();
				e.stopPropagation();

				var collapsed = !li.classList.contains('skl-menu--collapsed');
				apply(collapsed, true);

				try {
					window.localStorage.setItem(KEY, collapsed ? '1' : '0');
				} catch (err) {
					/* Private mode — the toggle still works for this page view. */
				}
			});
		}
	});
})();
