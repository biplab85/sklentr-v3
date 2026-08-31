/**
 * About · Top Services — Swiper carousel with a staggered "valley" wave
 * (iteck "Discover our top services").
 *
 * centeredSlides + infinite loop + autoplay. The vertical stagger + centre-scale
 * spotlight are driven continuously from each slide's Swiper `progress`, applied
 * to the inner .ab-svc-card (Swiper owns the slide's own transform):
 *   • centre (progress 0) → rises to the top + scale 1.1 + soft shadow
 *   • cards descend (+50 → +100px) and shrink (1.0 → 0.9) toward the edges
 * The CSS transition on the card eases each move, so the whole row waves smoothly
 * as it autoplays. Autoplay + wave are disabled under prefers-reduced-motion.
 */
(function () {
	'use strict';

	if (typeof window.Swiper === 'undefined') {
		return;
	}

	var el = document.querySelector('.ab-svc__slider');
	if (!el) {
		return;
	}

	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	var DROP = 50;   // px of descent per slide-step away from centre (max 2 steps = 100px)
	var SCALE_STEP = 0.1;

	var GAP = 24; // desired visual gap (px) between adjacent cards

	function applyWave(sw) {
		// On narrow screens (1 per view) a scaled-up card would overflow — keep flat.
		var flat = window.innerWidth < 640;
		// Read slide width once (border-box → constant regardless of padding) to avoid layout thrash.
		var slideW = (sw.slides.length && sw.slides[0].offsetWidth) || 0;

		for (var i = 0; i < sw.slides.length; i++) {
			var slide = sw.slides[i];
			var card = slide.querySelector('.ab-svc-card');
			if (!card) continue;

			if (flat) {
				card.style.transform = 'translateY(0) scale(1)';
				card.style.filter = 'none';
				slide.style.paddingLeft = slide.style.paddingRight = (GAP / 2).toFixed(1) + 'px';
				continue;
			}

			var p = slide.progress || 0;               // 0 = centred, ±1 = one slide away…
			var c = Math.min(Math.abs(p), 2);
			var scale = 1.1 - c * SCALE_STEP;          // 1.1 → 0.9
			var y = (c * DROP).toFixed(1);             // 0 → 100px (down)
			card.style.transform = 'translateY(' + y + 'px) scale(' + scale.toFixed(3) + ')';

			// Inset the card so the visual gap stays even as scaled-up cards overhang.
			var overhang = Math.max(0, scale - 1) * slideW / 2;
			var pad = (GAP / 2 + overhang).toFixed(1) + 'px';
			slide.style.paddingLeft = pad;
			slide.style.paddingRight = pad;

			// soft shadow fades in as the card approaches centre
			var near = Math.max(0, 1 - Math.abs(p));
			card.style.filter = near > 0.02
				? 'drop-shadow(5px 40px 30px rgba(7,57,114,' + (0.10 * near).toFixed(3) + '))'
				: 'none';
		}
	}

	// eslint-disable-next-line no-new
	new window.Swiper(el, {
		slidesPerView: 1,
		spaceBetween: 0,
		centeredSlides: true,
		loop: true,
		speed: 1000,
		grabCursor: true,
		watchSlidesProgress: true,
		autoplay: reduce ? false : {
			delay: 4000,
			disableOnInteraction: true,
		},
		// Scale the visible count with the viewport so cards stay ~320px+ (6-across
		// only reads well on very wide screens; laptops get 4–5).
		breakpoints: {
			640:  { slidesPerView: 2 },
			900:  { slidesPerView: 3 },
			1200: { slidesPerView: 4 },
			1600: { slidesPerView: 5 },
			1900: { slidesPerView: 6 },
		},
		a11y: {
			enabled: true,
			prevSlideMessage: 'Previous service',
			nextSlideMessage: 'Next service',
		},
		on: reduce ? {} : {
			init: applyWave,
			setTranslate: applyWave,
			resize: applyWave,
		},
	});
})();
