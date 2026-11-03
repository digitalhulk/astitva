/* Astitva front-end. Vanilla JS, no dependencies. */
(function () {
	'use strict';

	var doc = document.documentElement;
	var body = document.body;
	var header = document.getElementById('site-header');
	var toggle = document.querySelector('.menu-toggle');
	var nav = document.getElementById('primary-menu');

	/* Sticky island: stays visible in both scroll directions. */
	function onScroll() {
		var y = window.scrollY;
		if (header) {
			header.classList.toggle('is-stuck', y > 160);
		}
		body.classList.toggle('show-top', y > 500);
	}
	window.addEventListener('scroll', onScroll, { passive: true });
	onScroll();

	/* Mobile menu opens inline and pushes the page down. */
	if (toggle && nav) {
		toggle.addEventListener('click', function () {
			var open = toggle.getAttribute('aria-expanded') !== 'true';
			toggle.setAttribute('aria-expanded', String(open));
			nav.classList.toggle('is-open', open);
		});
		nav.querySelectorAll('.menu-item-has-children > a').forEach(function (link) {
			link.addEventListener('click', function (event) {
				if (window.matchMedia('(max-width: 1024px)').matches) {
					event.preventDefault();
					link.parentElement.classList.toggle('is-open');
				}
			});
		});
	}

	/* Launcher drawer. */
	var drawer = document.getElementById('agi-launcher');
	var lastFocus = null;
	function setDrawer(open) {
		if (!drawer) {
			return;
		}
		if (open) {
			lastFocus = document.activeElement;
		}
		drawer.setAttribute('aria-hidden', String(!open));
		body.classList.toggle('drawer-open', open);
		if (open) {
			drawer.focus();
		} else if (lastFocus) {
			lastFocus.focus();
		}
	}
	document.querySelectorAll('[data-agi-open]').forEach(function (el) {
		el.addEventListener('click', function () { setDrawer(true); });
	});
	document.querySelectorAll('[data-agi-close]').forEach(function (el) {
		el.addEventListener('click', function () { setDrawer(false); });
	});
	document.addEventListener('keydown', function (event) {
		if (event.key === 'Escape' && body.classList.contains('drawer-open')) {
			setDrawer(false);
		}
	});

	/* Back to top. */
	var top = document.querySelector('[data-agi-top]');
	if (top) {
		top.addEventListener('click', function () {
			var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
			window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' });
		});
	}

	/* Cookie banner. Storage may be blocked, so fall back to showing it. */
	var bar = document.getElementById('agi-cookie');
	if (bar) {
		var dismissed = false;
		try {
			dismissed = localStorage.getItem('agi_cookie') === '1';
		} catch (e) {}
		bar.hidden = dismissed;
		var close = bar.querySelector('[data-agi-cookie]');
		if (close) {
			close.addEventListener('click', function () {
				bar.hidden = true;
				try {
					localStorage.setItem('agi_cookie', '1');
				} catch (e) {}
			});
		}
	}

	doc.classList.add('js');
})();
