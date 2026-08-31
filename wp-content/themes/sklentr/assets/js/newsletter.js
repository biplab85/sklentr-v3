/**
 * Sklentr — newsletter opt-in.
 * Validates the email, POSTs it to admin-ajax (skl_subscribe), and swaps the
 * form row for the success message once it is stored. Only the address is
 * sent; storage lives in inc/newsletter.php.
 */
(function () {
	'use strict';

	var forms = document.querySelectorAll('[data-news-form]');
	if (!forms.length) {
		return;
	}

	var cfg = window.sklNewsletter || {};
	var i18n = cfg.i18n || {};

	function message(form, text, isError) {
		var ok = form.querySelector('[data-news-success]');
		if (!ok) {
			return;
		}
		if (text) {
			ok.textContent = text;
		}
		// The success paragraph doubles as the error slot; tint it when it fails.
		ok.style.color = isError ? '#f87171' : '';
		ok.hidden = false;
	}

	forms.forEach(function (form) {
		form.addEventListener('submit', function (e) {
			e.preventDefault();

			var input = form.querySelector('input[type="email"]');
			if (!input || !input.value || (input.checkValidity && !input.checkValidity())) {
				if (input) {
					input.reportValidity ? input.reportValidity() : input.focus();
				}
				return;
			}

			var row = form.querySelector('.news-form__row');
			var button = form.querySelector('button[type="submit"]');

			// No endpoint (script loaded without its data) — fail loudly rather
			// than pretending the address was saved.
			if (!cfg.ajaxUrl || !cfg.nonce) {
				message(form, i18n.error || 'Something went wrong. Please try again.', true);
				return;
			}

			if (button) {
				button.disabled = true;
			}

			var body = new URLSearchParams();
			body.append('action', 'skl_subscribe');
			body.append('nonce', cfg.nonce);
			body.append('email', input.value);

			fetch(cfg.ajaxUrl, {
				method: 'POST',
				credentials: 'same-origin',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
				body: body.toString()
			})
				.then(function (res) {
					return res.json().catch(function () {
						return { success: false };
					});
				})
				.then(function (res) {
					if (button) {
						button.disabled = false;
					}
					if (res && res.success) {
						if (row) {
							row.style.display = 'none';
						}
						// Keep the themed copy already in the markup.
						message(form, '', false);
						form.reset();
						return;
					}
					message(form, (res && res.data && res.data.message) || i18n.error, true);
				})
				.catch(function () {
					if (button) {
						button.disabled = false;
					}
					message(form, i18n.error || 'Something went wrong. Please try again.', true);
				});
		});
	});
})();
