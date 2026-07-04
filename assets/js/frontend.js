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

  document.addEventListener('click', function (event) {
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
  });
}());
