/* Astitva widget behaviour: entrances, counters, carousels, lightbox. */
(function () {
	'use strict';

	var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	function count(el) {
		if (el.dataset.done) {
			return;
		}
		el.dataset.done = '1';
		var to = parseFloat(el.dataset.agiCount) || 0;
		var duration = parseInt(el.dataset.duration, 10) || 1000;
		if (reduce) {
			el.textContent = to;
			return;
		}
		var start = performance.now();
		function tick(now) {
			var p = Math.min(1, (now - start) / duration);
			var eased = 1 - Math.pow(1 - p, 3);
			el.textContent = Number.isInteger(to) ? Math.round(to * eased) : (to * eased).toFixed(1);
			if (p < 1) {
				requestAnimationFrame(tick);
			}
		}
		requestAnimationFrame(tick);
	}

	function reveal(el) {
		el.classList.add('is-in');
		el.querySelectorAll('[data-agi-count]').forEach(count);
	}

	function initObserver() {
		var items = document.querySelectorAll('[data-agi-anim], .agi-sliding, .agi-numbers, [data-agi-levels]');
		if (!('IntersectionObserver' in window)) {
			items.forEach(reveal);
			return;
		}
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) {
					return;
				}
				var delay = parseInt(entry.target.dataset.agiDelay, 10) || 0;
				setTimeout(function () { reveal(entry.target); }, reduce ? 0 : delay);
				io.unobserve(entry.target);
			});
		}, { threshold: 0.1, rootMargin: '0px 0px -5% 0px' });
		items.forEach(function (el) { io.observe(el); });

		// Fast scrolling can skip the observer, so never leave passed content hidden.
		var pending = Array.prototype.slice.call(items);
		var ticking = false;
		function sweep() {
			ticking = false;
			pending = pending.filter(function (el) {
				if (el.classList.contains('is-in')) {
					return false;
				}
				if (el.getBoundingClientRect().top < window.innerHeight) {
					reveal(el);
					return false;
				}
				return true;
			});
			if (!pending.length) {
				window.removeEventListener('scroll', onScroll);
			}
		}
		function onScroll() {
			if (!ticking) {
				ticking = true;
				requestAnimationFrame(sweep);
			}
		}
		window.addEventListener('scroll', onScroll, { passive: true });
		sweep();
	}

	function initCarousel(root) {
		var track = root.querySelector('.agi-carousel__track');
		var dots = root.querySelector('.agi-carousel__dots');
		if (!track || root.dataset.ready) {
			return;
		}
		root.dataset.ready = '1';
		var slides = Array.prototype.slice.call(track.children);
		function go(i) {
			track.scrollTo({ left: slides[i].offsetLeft - slides[0].offsetLeft, behavior: reduce ? 'auto' : 'smooth' });
		}
		var buttons = slides.map(function (slide, i) {
			var b = document.createElement('button');
			b.type = 'button';
			b.setAttribute('aria-label', 'Slide ' + (i + 1));
			b.addEventListener('click', function () { go(i); });
			dots.appendChild(b);
			return b;
		});
		function current() {
			var step = slides.length > 1 ? slides[1].offsetLeft - slides[0].offsetLeft : 1;
			return Math.round(track.scrollLeft / step);
		}
		function mark() {
			var c = current();
			buttons.forEach(function (b, i) { b.classList.toggle('is-on', i === c); });
		}
		track.addEventListener('scroll', mark, { passive: true });
		mark();

		var down = false, startX = 0, startLeft = 0;
		track.addEventListener('pointerdown', function (e) {
			if (e.pointerType !== 'mouse') {
				return;
			}
			down = true;
			startX = e.clientX;
			startLeft = track.scrollLeft;
			track.classList.add('is-drag');
		});
		window.addEventListener('pointermove', function (e) {
			if (down) {
				track.scrollLeft = startLeft - (e.clientX - startX);
			}
		});
		window.addEventListener('pointerup', function () {
			if (down) {
				down = false;
				track.classList.remove('is-drag');
				go(current());
			}
		});

		var wait = parseInt(root.dataset.autoplay, 10) || 0;
		if (wait && !reduce && slides.length > 1) {
			setInterval(function () {
				if (!down && !document.hidden && !root.matches(':hover')) {
					go((current() + 1) % slides.length);
				}
			}, wait);
		}
	}

	function openBox(node) {
		var box = document.createElement('div');
		box.className = 'agi-lightbox';
		box.setAttribute('role', 'dialog');
		box.setAttribute('aria-modal', 'true');
		var close = document.createElement('button');
		close.type = 'button';
		close.textContent = '×';
		close.setAttribute('aria-label', 'Close');
		function key(e) {
			if (e.key === 'Escape') {
				done();
			}
		}
		function done() {
			box.remove();
			document.removeEventListener('keydown', key);
		}
		close.addEventListener('click', done);
		box.addEventListener('click', function (e) {
			if (e.target === box) {
				done();
			}
		});
		document.addEventListener('keydown', key);
		box.appendChild(node);
		box.appendChild(close);
		document.body.appendChild(box);
		close.focus();
	}

	document.addEventListener('click', function (e) {
		var link = e.target.closest('[data-agi-lightbox]');
		if (link && link.getAttribute('href')) {
			e.preventDefault();
			var img = new Image();
			img.src = link.getAttribute('href');
			img.alt = '';
			openBox(img);
			return;
		}
		var play = e.target.closest('[data-agi-video]');
		if (play && play.dataset.agiVideo) {
			var url = play.dataset.agiVideo;
			var media;
			if (/\.(mp4|webm)(\?|$)/i.test(url)) {
				media = document.createElement('video');
				media.controls = true;
				media.autoplay = true;
				media.src = url;
			} else {
				media = document.createElement('iframe');
				media.src = url;
				media.allow = 'autoplay; fullscreen';
				media.title = play.getAttribute('aria-label') || 'Video';
			}
			openBox(media);
		}
	});

	function init() {
		initObserver();
		document.querySelectorAll('[data-agi-carousel]').forEach(initCarousel);
	}
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
