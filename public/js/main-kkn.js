/* =========================================================
   MAIN.JS — Shared script (reused across all pages)
   Desa Ketapang Raya
   ========================================================= */

(function () {
  "use strict";

  /* -----------------------------------------------------
     1. LOAD GLOBAL PARTIALS (navbar & footer)
     NOTE: fetch() needs the page to be served over http(s),
     e.g. VS Code "Live Server" or `php -S localhost:8000`.
     Opening the .html file directly (file://) will fail
     due to browser CORS restrictions.
  ----------------------------------------------------- */
  function loadPartial(selector, url) {
    var target = document.querySelector(selector);
    if (!target) return Promise.resolve();

    return fetch(url)
      .then(function (res) {
        if (!res.ok) throw new Error("Gagal memuat " + url);
        return res.text();
      })
      .then(function (html) {
        target.innerHTML = html;
      })
      .catch(function (err) {
        console.error(err);
        target.innerHTML =
          '<p style="padding:1rem;color:#b54848;">Gagal memuat komponen: ' +
          url +
          "</p>";
      });
  }

  function loadGlobalPartials() {
    var navbarPromise = loadPartial("#navbar-placeholder", "navbar-kkn.php");
    var footerPromise = loadPartial("#footer-placeholder", "footer-kkn.html");

    Promise.all([navbarPromise, footerPromise]).then(function () {
      initNavbarScroll();
      initMobileMenu();
      initActiveLink();
    });
  }

  /* -----------------------------------------------------
     2. NAVBAR / TOPBAR SCROLL BEHAVIOR
     - Topbar hides once user scrolls down.
     - Navbar sticks to top and gains a white background
       once the user scrolls past the hero section.
  ----------------------------------------------------- */
  function initNavbarScroll() {
    var topbar = document.getElementById("topbar");
    var navbar = document.getElementById("navbar");
    var hero = document.querySelector(".hero");
    if (!topbar || !navbar) return;

    var topbarHeight = topbar.offsetHeight;
    var heroHeight = hero ? hero.offsetHeight : 0;
    var ticking = false;

    function update() {
      var y = window.scrollY;

      // Hide topbar after a small scroll threshold
      topbar.classList.toggle("is-hidden", y > topbarHeight * 0.6);

      // Navbar pins to the very top once topbar is hidden
      navbar.classList.toggle("is-pinned", y > topbarHeight * 0.6);

      // Navbar becomes solid/white once we scroll past the hero
      var solidThreshold = heroHeight > 0 ? heroHeight - 90 : 40;
      navbar.classList.toggle("is-scrolled", y > solidThreshold);

      ticking = false;
    }

    window.addEventListener(
      "scroll",
      function () {
        if (!ticking) {
          window.requestAnimationFrame(update);
          ticking = true;
        }
      },
      { passive: true },
    );

    window.addEventListener("resize", function () {
      heroHeight = hero ? hero.offsetHeight : 0;
    });

    update();
  }

  /* -----------------------------------------------------
     3. MOBILE MENU TOGGLE
  ----------------------------------------------------- */
  function initMobileMenu() {
    var toggle = document.getElementById("navToggle");
    var menu = document.getElementById("navMenu");
    if (!toggle || !menu) return;

    toggle.addEventListener("click", function () {
      var isOpen = menu.classList.toggle("is-open");
      toggle.classList.toggle("is-open", isOpen);
      toggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
    });

    menu.querySelectorAll("a").forEach(function (link) {
      link.addEventListener("click", function () {
        menu.classList.remove("is-open");
        toggle.classList.remove("is-open");
        toggle.setAttribute("aria-expanded", "false");
      });
    });
  }

  /* -----------------------------------------------------
     4. ACTIVE NAV LINK
     Reads data-page on <body> and matches it against
     data-nav on each navbar link.
  ----------------------------------------------------- */
  function initActiveLink() {
    var page = document.body.getAttribute("data-page");
    if (!page) return;
    document.querySelectorAll(".navbar__menu a").forEach(function (link) {
      if (link.getAttribute("data-nav") === page) {
        link.classList.add("is-active");
      }
    });
  }

  /* -----------------------------------------------------
     5. SCROLL REVEAL (fade / slide / scale) WITH STAGGER
     Add [data-reveal] to any element. Elements sharing the
     same [data-stagger-group] animate in sequence.
  ----------------------------------------------------- */
  function initScrollReveal() {
    var items = document.querySelectorAll("[data-reveal]");
    if (!items.length || !("IntersectionObserver" in window)) {
      items.forEach(function (el) {
        el.classList.add("is-visible");
      });
      return;
    }

    var groupCounters = {};

    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;
          var el = entry.target;
          var group = el.getAttribute("data-stagger-group");
          var delay = 0;

          if (group) {
            groupCounters[group] = groupCounters[group] || 0;
            delay = groupCounters[group] * 110;
            groupCounters[group]++;
          }

          setTimeout(function () {
            el.classList.add("is-visible");
          }, delay);

          observer.unobserve(el);
        });
      },
      { threshold: 0.15, rootMargin: "0px 0px -60px 0px" },
    );

    items.forEach(function (el) {
      observer.observe(el);
    });
  }

  /* -----------------------------------------------------
     6. ANIMATED COUNTERS
     Add data-count="2856" and data-count-suffix (optional)
     to any .stat__number element.
  ----------------------------------------------------- */
  function initCounters() {
    var counters = document.querySelectorAll("[data-count]");
    if (!counters.length) return;

    function animateCounter(el) {
      var target = parseFloat(el.getAttribute("data-count"));
      var suffix = el.getAttribute("data-count-suffix") || "";
      var duration = 1600;
      var startTime = null;

      function easeOutQuart(t) {
        return 1 - Math.pow(1 - t, 4);
      }

      function step(timestamp) {
        if (startTime === null) startTime = timestamp;
        var progress = Math.min((timestamp - startTime) / duration, 1);
        var eased = easeOutQuart(progress);
        var value = Math.round(target * eased);
        el.textContent = value.toLocaleString("id-ID") + suffix;
        if (progress < 1) window.requestAnimationFrame(step);
      }

      window.requestAnimationFrame(step);
    }

    if (!("IntersectionObserver" in window)) {
      counters.forEach(animateCounter);
      return;
    }

    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            animateCounter(entry.target);
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.5 },
    );

    counters.forEach(function (el) {
      observer.observe(el);
    });
  }

  /* -----------------------------------------------------
     7. HERO PARALLAX
     Moves [data-parallax] backgrounds slower than scroll
     while they're within the viewport.
  ----------------------------------------------------- */
  function initParallax() {
    var layers = document.querySelectorAll("[data-parallax]");
    if (!layers.length) return;

    var ticking = false;

    function update() {
      layers.forEach(function (layer) {
        var rect = layer.parentElement.getBoundingClientRect();
        var speed = parseFloat(layer.getAttribute("data-parallax")) || 0.25;
        if (rect.bottom > 0 && rect.top < window.innerHeight) {
          var offset = rect.top * speed;
          layer.style.transform = "translateY(" + offset + "px) scale(1.12)";
        }
      });
      ticking = false;
    }

    window.addEventListener(
      "scroll",
      function () {
        if (!ticking) {
          window.requestAnimationFrame(update);
          ticking = true;
        }
      },
      { passive: true },
    );

    update();
  }
  /* -----------------------------------------------------
     8. THEME TOGGLE
     Reads/writes localStorage("theme"), applies
     data-theme on <html>, and builds a fixed
     bottom-right toggle button + option panel.
     Add new options to THEMES to support more palettes —
     each `value` must match a [data-theme="value"] block
     defined in one of your root-*.css files.
  ----------------------------------------------------- */
  var THEMES = [

  { value: "default", label: "Hijau Klasik", swatch: "#36543b" },

  { value: "earth", label: "Biru", swatch: "#1f5c86" },

  { value: "sunset", label: "Terracotta", swatch: "#a15a2c" },

  { value: "dark", label: "Dark", swatch: "#9b7cc4" }

];
  var THEME_STORAGE_KEY = "theme";

  function getStoredTheme() {
    try {
      return localStorage.getItem(THEME_STORAGE_KEY);
    } catch (e) {
      return null;
    }
  }

  function storeTheme(value) {
    try {
      localStorage.setItem(THEME_STORAGE_KEY, value);
    } catch (e) {
      /* localStorage unavailable (private mode, etc.) — theme just won't persist */
    }
  }

  function applyTheme(value) {
    if (value === "default") {
      document.documentElement.removeAttribute("data-theme");
    } else {
      document.documentElement.setAttribute("data-theme", value);
    }
  }

  function initThemeToggle() {
    var current = getStoredTheme() || "default";
    applyTheme(current); // safety net; also applied earlier inline in <head> to avoid flash

    var wrap = document.createElement("div");
    wrap.className = "theme-toggle";
    wrap.innerHTML =
      '<div class="theme-toggle__panel" id="themePanel"></div>' +
      '<button class="theme-toggle__btn" id="themeToggleBtn" aria-label="Ganti tema warna" aria-expanded="false">' +
      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">' +
      '<circle cx="12" cy="12" r="4"/><path d="M12 2v3M12 19v3M4.2 4.2l2.1 2.1M17.7 17.7l2.1 2.1M2 12h3M19 12h3M4.2 19.8l2.1-2.1M17.7 6.3l2.1-2.1"/>' +
      "</svg></button>";
    document.body.appendChild(wrap);

    var btn = wrap.querySelector("#themeToggleBtn");
    var panel = wrap.querySelector("#themePanel");

    function renderOptions() {
      panel.innerHTML = "";
      THEMES.forEach(function (theme) {
        var opt = document.createElement("button");
        opt.type = "button";
        opt.className =
          "theme-toggle__option" + (theme.value === current ? " is-active" : "");
        opt.innerHTML =
          '<span class="theme-toggle__swatch"></span><span>' +
          theme.label +
          "</span>";
        opt.querySelector(".theme-toggle__swatch").style.background = theme.swatch;
        opt.addEventListener("click", function () {
          current = theme.value;
          applyTheme(current);
          storeTheme(current);
          renderOptions();
        });
        panel.appendChild(opt);
      });
    }

    renderOptions();

    btn.addEventListener("click", function () {
      var isOpen = panel.classList.toggle("is-open");
      btn.classList.toggle("is-open", isOpen);
      btn.setAttribute("aria-expanded", isOpen ? "true" : "false");
    });

    document.addEventListener("click", function (e) {
      if (!wrap.contains(e.target)) {
        panel.classList.remove("is-open");
        btn.classList.remove("is-open");
        btn.setAttribute("aria-expanded", "false");
      }
    });
  }

  /* -----------------------------------------------------
     INIT
  ----------------------------------------------------- */
  document.addEventListener("DOMContentLoaded", function () {
    loadGlobalPartials();
    initScrollReveal();
    initCounters();
    initParallax();
    initThemeToggle();
  });
})();
