(function () {
  'use strict';

  function placeHeaderMenu(toggle, nav) {
    if (!toggle || !nav) {
      return;
    }

    var rect = toggle.getBoundingClientRect();
    var width = Math.min(220, Math.max(190, window.innerWidth - 32));
    var left = rect.left + (rect.width / 2) - (width / 2);

    left = Math.max(16, Math.min(left, window.innerWidth - width - 16));

    nav.style.setProperty('--ukits-menu-top', Math.round(rect.bottom + 6) + 'px');
    nav.style.setProperty('--ukits-menu-left', Math.round(left) + 'px');
    nav.style.setProperty('--ukits-menu-width', Math.round(width) + 'px');
  }

  function closeHeaderMenu(header) {
    if (!header) {
      return;
    }

    var nav = header.querySelector('.header-nav');
    var cta = header.querySelector('.header-cta');
    var toggle = header.querySelector('.header-menu-toggle');

    header.classList.remove('is-menu-open');

    if (nav) {
      nav.classList.remove('is-open', 'is-fixed-open');
      nav.style.removeProperty('--ukits-menu-top');
      nav.style.removeProperty('--ukits-menu-left');
      nav.style.removeProperty('--ukits-menu-width');
    }

    if (cta) {
      cta.classList.remove('is-open');
    }

    if (toggle) {
      toggle.setAttribute('aria-expanded', 'false');
    }
  }

  function initTestimonialsCarousels(context) {
    var root = context || document;

    Array.prototype.forEach.call(root.querySelectorAll('#testimonials .testimonials-grid'), function (track) {
      if (track.dataset.ukitsCarouselReady === 'true') {
        return;
      }

      var section = track.closest('#testimonials');
      Array.prototype.forEach.call(track.querySelectorAll('.testimonial-card.is-carousel-clone'), function (clone) {
        clone.parentNode.removeChild(clone);
      });

      var cards = Array.prototype.slice.call(track.querySelectorAll('.testimonial-card:not(.is-carousel-clone)'));
      var dots = section ? Array.prototype.slice.call(section.querySelectorAll('.testimonial-dot')) : [];
      var previousButton = section ? section.querySelector('.testimonial-arrow-prev') : null;
      var nextButton = section ? section.querySelector('.testimonial-arrow-next') : null;
      var isDragging = false;
      var dragStartX = 0;
      var dragStartScroll = 0;
      var dragTargetScroll = 0;
      var dragFrame = null;
      var originalCount = cards.length;
      var originalStart = originalCount > 1 ? originalCount : 0;
      var allCards = [];

      if (!section || cards.length < 1) {
        return;
      }

      track.dataset.ukitsCarouselReady = 'true';

      if (originalCount > 1) {
        cards.slice().reverse().forEach(function (card) {
          var clone = card.cloneNode(true);
          clone.classList.add('is-carousel-clone');
          clone.setAttribute('aria-hidden', 'true');
          track.insertBefore(clone, track.firstElementChild);
        });

        cards.forEach(function (card) {
          var clone = card.cloneNode(true);
          clone.classList.add('is-carousel-clone');
          clone.setAttribute('aria-hidden', 'true');
          track.appendChild(clone);
        });
      }

      allCards = Array.prototype.slice.call(track.querySelectorAll('.testimonial-card'));

      allCards.forEach(function (card, cardIndex) {
        card.dataset.testimonialRealIndex = String(((cardIndex - originalStart) % originalCount + originalCount) % originalCount);
      });

      function getRealIndex(cardIndex) {
        return ((cardIndex - originalStart) % originalCount + originalCount) % originalCount;
      }

      function setDots(index) {
        dots.forEach(function (dot, dotIndex) {
          dot.classList.toggle('is-active', dotIndex === index);
          dot.setAttribute('aria-current', dotIndex === index ? 'true' : 'false');
        });
      }

      function setVisualActive(cardIndex) {
        var realIndex = getRealIndex(cardIndex);

        allCards.forEach(function (card, index) {
          card.classList.toggle('is-active', index === cardIndex);
        });

        setDots(realIndex);
      }

      function scrollCardToCenter(cardIndex, behavior) {
        var target = allCards[cardIndex];
        var left = 0;

        if (!target) {
          return;
        }

        left = target.offsetLeft - ((track.clientWidth - target.offsetWidth) / 2);
        left = Math.max(0, Math.round(left));

        if (typeof track.scrollTo === 'function') {
          track.scrollTo({
            left: left,
            behavior: behavior || 'smooth'
          });
          return;
        }

        track.scrollLeft = left;
      }

      function normalizeLoop(cardIndex) {
        if (originalCount < 2) {
          return cardIndex;
        }

        if (cardIndex < originalStart) {
          cardIndex += originalCount;
          scrollCardToCenter(cardIndex, 'auto');
        } else if (cardIndex >= originalStart + originalCount) {
          cardIndex -= originalCount;
          scrollCardToCenter(cardIndex, 'auto');
        }

        return cardIndex;
      }

      function setActive(index, shouldScroll) {
        index = Math.max(0, Math.min(index, originalCount - 1));

        setVisualActive(originalStart + index);

        if (shouldScroll) {
          scrollCardToCenter(originalStart + index, 'smooth');
        }
      }

      track.ukitsSetTestimonialActive = function (index) {
        setActive(index, true);
      };

      track.ukitsRefreshTestimonialCarousel = function () {
        window.requestAnimationFrame(function () {
          scrollCardToCenter(normalizeLoop(getClosestIndex()), 'auto');
        });
      };

      function getClosestIndex() {
        var trackRect = track.getBoundingClientRect();
        var trackCenter = trackRect.left + (trackRect.width / 2);
        var closestIndex = 0;
        var closestDistance = Infinity;

        allCards.forEach(function (card, index) {
          var cardRect = card.getBoundingClientRect();
          var cardCenter = cardRect.left + (cardRect.width / 2);
          var distance = Math.abs(trackCenter - cardCenter);

          if (distance < closestDistance) {
            closestDistance = distance;
            closestIndex = index;
          }
        });

        return closestIndex;
      }

      var ticking = false;

      track.addEventListener('scroll', function () {
        if (ticking) {
          return;
        }

        ticking = true;

        window.requestAnimationFrame(function () {
          var closestIndex = getClosestIndex();

          setVisualActive(closestIndex);

          if (!isDragging) {
            normalizeLoop(closestIndex);
          }

          ticking = false;
        });
      });

      track.addEventListener('pointerdown', function (event) {
        if (event.pointerType === 'touch') {
          return;
        }

        event.preventDefault();
        isDragging = true;
        dragStartX = event.clientX;
        dragStartScroll = track.scrollLeft;
        dragTargetScroll = dragStartScroll;
        track.classList.add('is-dragging');
        track.setPointerCapture(event.pointerId);
      });

      track.addEventListener('pointermove', function (event) {
        if (!isDragging) {
          return;
        }

        event.preventDefault();
        dragTargetScroll = dragStartScroll - (event.clientX - dragStartX);

        if (dragFrame) {
          return;
        }

        dragFrame = window.requestAnimationFrame(function () {
          track.scrollLeft = dragTargetScroll;
          dragFrame = null;
        });
      });

      function stopDragging(event) {
        if (!isDragging) {
          return;
        }

        isDragging = false;
        track.classList.remove('is-dragging');

        if (dragFrame) {
          window.cancelAnimationFrame(dragFrame);
          dragFrame = null;
        }

        if (event && track.hasPointerCapture(event.pointerId)) {
          track.releasePointerCapture(event.pointerId);
        }

        scrollCardToCenter(normalizeLoop(getClosestIndex()), 'smooth');
      }

      track.addEventListener('pointerup', stopDragging);
      track.addEventListener('pointercancel', stopDragging);

      function navigateBy(direction) {
        var closestIndex = normalizeLoop(getClosestIndex());

        scrollCardToCenter(closestIndex + direction, 'smooth');
      }

      if (previousButton) {
        previousButton.addEventListener('click', function () {
          navigateBy(-1);
        });
      }

      if (nextButton) {
        nextButton.addEventListener('click', function () {
          navigateBy(1);
        });
      }

      window.setTimeout(function () {
        var initialIndex = 0;

        setVisualActive(originalStart + initialIndex);
        scrollCardToCenter(originalStart + initialIndex, 'auto');
      }, 80);
    });
  }

  function resetTestimonialsCarousels(context) {
    var root = context || document;

    Array.prototype.forEach.call(root.querySelectorAll('#testimonials .testimonials-grid'), function (track) {
      delete track.dataset.ukitsCarouselReady;
      track.ukitsSetTestimonialActive = null;
      track.ukitsRefreshTestimonialCarousel = null;

      Array.prototype.forEach.call(track.querySelectorAll('.testimonial-card.is-carousel-clone'), function (clone) {
        clone.parentNode.removeChild(clone);
      });
    });
  }

  function ready(callback) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', callback);
      return;
    }

    callback();
  }

  ready(function () {
    initTestimonialsCarousels(document);
  });

  window.addEventListener('load', function () {
    initTestimonialsCarousels(document);
  });

  if (window.elementorFrontend && window.elementorFrontend.hooks) {
    window.elementorFrontend.hooks.addAction('frontend/element_ready/ukits_testimonials.default', function ($scope) {
      var scope = $scope && $scope[0] ? $scope[0] : document;

      resetTestimonialsCarousels(scope);
      initTestimonialsCarousels(scope);
    });
  }

  document.addEventListener('click', function (event) {
    var testimonialDot = event.target.closest('#testimonials .testimonial-dot');

    if (testimonialDot) {
      var testimonialsSection = testimonialDot.closest('#testimonials');
      var testimonialsTrack = testimonialsSection ? testimonialsSection.querySelector('.testimonials-grid') : null;
      var testimonialIndex = parseInt(testimonialDot.dataset.testimonialIndex, 10) || 0;

      event.preventDefault();

      if (testimonialsTrack && testimonialsTrack.dataset.ukitsCarouselReady !== 'true') {
        initTestimonialsCarousels(testimonialsSection);
      }

      if (testimonialsTrack && typeof testimonialsTrack.ukitsSetTestimonialActive === 'function') {
        testimonialsTrack.ukitsSetTestimonialActive(testimonialIndex);
      }

      return;
    }

    var menuLink = event.target.closest('#header .ukits-header-menu-link');

    if (menuLink) {
      var href = menuLink.getAttribute('href') || '';
      var hash = '';

      try {
        var url = new URL(href, window.location.href);

        if (url.pathname === window.location.pathname && url.hash) {
          hash = url.hash;
        }
      } catch (error) {
        if (href.charAt(0) === '#') {
          hash = href;
        }
      }

      if (hash && hash !== '#') {
        var target = document.querySelector(hash);

        if (target) {
          event.preventDefault();
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
          window.history.pushState(null, '', hash);

          var header = menuLink.closest('#header');
          var nav = header ? header.querySelector('.header-nav') : null;
          var cta = header ? header.querySelector('.header-cta') : null;
          var toggle = header ? header.querySelector('.header-menu-toggle') : null;

          closeHeaderMenu(header);
        }
      }
    }

    var toggle = event.target.closest('.ukits-custom-element .faq-toggle');

    if (toggle) {
      event.preventDefault();

      var item = toggle.closest('.faq-item') || toggle.parentElement;
      var answer = item ? item.querySelector('.faq-answer') : null;
      var icon = toggle.querySelector('img');
      var expanded = toggle.getAttribute('aria-expanded') === 'true';
      var section = toggle.closest('.ukits-custom-element');
      var plusIcon = section && section.getAttribute('data-plus-icon')
        ? section.getAttribute('data-plus-icon')
        : UKITSCustomElement.assetsUrl + 'img/plus-white.svg';
      var minusIcon = section && section.getAttribute('data-minus-icon')
        ? section.getAttribute('data-minus-icon')
        : UKITSCustomElement.assetsUrl + 'img/minus-white.svg';

      if (!expanded && item) {
        var list = item.closest('.faq-list');

        if (list) {
          Array.prototype.forEach.call(list.querySelectorAll('.faq-item'), function (otherItem) {
            if (otherItem === item) {
              return;
            }

            var otherToggle = otherItem.querySelector('.faq-toggle');
            var otherAnswer = otherItem.querySelector('.faq-answer');
            var otherIcon = otherToggle ? otherToggle.querySelector('img') : null;

            if (otherToggle) {
              otherToggle.setAttribute('aria-expanded', 'false');
            }

            if (otherAnswer) {
              otherAnswer.classList.add('hidden');
            }

            if (otherIcon) {
              otherIcon.src = plusIcon;
            }
          });
        }
      }

      toggle.setAttribute('aria-expanded', expanded ? 'false' : 'true');

      if (answer) {
        answer.classList.toggle('hidden', expanded);
      }

      if (icon) {
        icon.src = expanded
          ? plusIcon
          : minusIcon;
      }

      return;
    }

    var menuToggle = event.target.closest('.ukits-custom-element .header-menu-toggle');

    if (menuToggle) {
      var header = menuToggle.closest('#header');
      var nav = header ? header.querySelector('.header-nav') : null;
      var cta = header ? header.querySelector('.header-cta') : null;
      var isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';

      menuToggle.setAttribute('aria-expanded', isExpanded ? 'false' : 'true');

      if (header) {
        header.classList.toggle('is-menu-open', !isExpanded);
      }

      if (nav) {
        if (!isExpanded) {
          placeHeaderMenu(menuToggle, nav);
        }

        nav.classList.toggle('is-open', !isExpanded);
        nav.classList.toggle('is-fixed-open', !isExpanded);
      }

      if (cta) {
        cta.classList.toggle('is-open', !isExpanded);
      }
    }
  });

  window.addEventListener('resize', function () {
    var openNav = document.querySelector('#header .header-nav.is-fixed-open');
    var header = openNav ? openNav.closest('#header') : null;
    var toggle = header ? header.querySelector('.header-menu-toggle') : null;

    if (openNav && toggle) {
      placeHeaderMenu(toggle, openNav);
    }

    Array.prototype.forEach.call(document.querySelectorAll('#testimonials .testimonials-grid'), function (track) {
      if (typeof track.ukitsRefreshTestimonialCarousel === 'function') {
        track.ukitsRefreshTestimonialCarousel();
      }
    });
  });
}());
