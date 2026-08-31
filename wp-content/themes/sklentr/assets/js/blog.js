/**
 * Sklentr — Blog index: category filter chips + "Load More".
 * Filters cards by [data-cat] against the active chip's [data-filter], with a
 * soft fade. "Load More" reveals cards beyond an initial batch (hidden when
 * there are none). Dependency-free.
 */
(function () {
	'use strict';

	var grid = document.querySelector('[data-blog-grid]');
	if (!grid) return;

	var cards = Array.prototype.slice.call(grid.querySelectorAll('.bl-card'));
	var chips = Array.prototype.slice.call(document.querySelectorAll('.bl-chip'));
	var moreBtn = document.querySelector('[data-blog-more]');
	var BATCH = 6;                 // how many to show before "Load More"
	var shown = BATCH;
	var filter = 'all';

	function matches(card) {
		return filter === 'all' || card.getAttribute('data-cat') === filter;
	}

	function apply() {
		var visibleCount = 0;
		cards.forEach(function (card) {
			var ok = matches(card);
			if (ok && visibleCount < shown) {
				card.classList.remove('is-hidden');
				visibleCount++;
			} else {
				card.classList.add('is-hidden');
			}
		});
		// how many match the filter in total (to decide if "Load More" is needed)
		var totalMatching = cards.filter(matches).length;
		if (moreBtn) {
			if (totalMatching > shown) {
				moreBtn.hidden = false;
			} else {
				moreBtn.hidden = true;
			}
		}
	}

	chips.forEach(function (chip) {
		chip.addEventListener('click', function () {
			chips.forEach(function (c) {
				c.classList.remove('is-active');
				c.setAttribute('aria-pressed', 'false');
			});
			chip.classList.add('is-active');
			chip.setAttribute('aria-pressed', 'true');
			filter = chip.getAttribute('data-filter') || 'all';
			shown = BATCH;          // reset paging when the filter changes
			apply();
		});
	});

	if (moreBtn) {
		moreBtn.addEventListener('click', function () {
			shown += BATCH;
			apply();
		});
	}

	apply();
})();
