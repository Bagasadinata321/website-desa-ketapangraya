/* =========================================================
   ADMIN.JS — Shared script for the Desa Ketapang Raya
   admin panel. Reused across every admin page.
   ========================================================= */

(function () {
  "use strict";

  /* -----------------------------------------------------
     1. SIDEBAR TOGGLE (desktop collapse / mobile drawer)
  ----------------------------------------------------- */
  function initSidebarToggle() {
    var toggle = document.getElementById("sidebarToggle");
    var shell = document.querySelector(".admin-shell");
    if (!toggle || !shell) return;

    toggle.addEventListener("click", function () {
      shell.classList.toggle("sidebar-collapsed");
    });

    // Close mobile drawer when clicking outside of it
    document.addEventListener("click", function (e) {
      var isMobile = window.innerWidth <= 780;
      if (!isMobile) return;
      var sidebar = document.querySelector(".admin-sidebar");
      if (
        shell.classList.contains("sidebar-collapsed") &&
        sidebar &&
        !sidebar.contains(e.target) &&
        !toggle.contains(e.target)
      ) {
        shell.classList.remove("sidebar-collapsed");
      }
      console.log("di klik");
    });
  }

  /* -----------------------------------------------------
     2. ACTIVE NAV HIGHLIGHT
     Reads data-page on <body> and matches against
     data-nav-key on each sidebar link.
  ----------------------------------------------------- */
  function initActiveNav() {
    var page = document.body.getAttribute("data-page");
    if (!page) return;
    document.querySelectorAll(".admin-nav__item").forEach(function (link) {
      if (link.getAttribute("data-nav-key") === page) {
        link.classList.add("is-active");
      }
    });
  }

  /* -----------------------------------------------------
     3. USER MENU DROPDOWN (topbar)
  ----------------------------------------------------- */
  function initUserMenu() {
    var trigger = document.getElementById("userMenuTrigger");
    var menu = document.getElementById("userMenuDropdown");
    if (!trigger || !menu) return;

    trigger.addEventListener("click", function (e) {
      e.stopPropagation();
      menu.classList.toggle("is-open");
    });
    document.addEventListener("click", function () {
      menu.classList.remove("is-open");
    });
  }

  /* -----------------------------------------------------
     4. SECTION DRAG-REORDER (Kelola Halaman → urutan section)
     Native HTML5 drag & drop on .section-row elements inside
     a .section-list container. Updates the visible numbering
     and a hidden input (name="order") with the new id sequence
     so a single form submit ("Simpan Urutan") persists it.
  ----------------------------------------------------- */
  function initSectionReorder() {
    var list = document.querySelector(".section-list");
    if (!list) return;

    var dragEl = null;

    list.querySelectorAll(".section-row").forEach(function (row) {
      row.setAttribute("draggable", "true");

      row.addEventListener("dragstart", function () {
        dragEl = row;
        row.classList.add("is-dragging");
      });

      row.addEventListener("dragend", function () {
        row.classList.remove("is-dragging");
        renumberSections(list);
        syncOrderInput(list);
      });

      row.addEventListener("dragover", function (e) {
        e.preventDefault();
        if (!dragEl || dragEl === row) return;
        var rect = row.getBoundingClientRect();
        var isAfter = e.clientY > rect.top + rect.height / 2;
        list.insertBefore(dragEl, isAfter ? row.nextSibling : row);
      });
    });
  }

  function renumberSections(list) {
    list.querySelectorAll(".section-row__num").forEach(function (el, i) {
      el.textContent = i + 1;
    });
  }

  function syncOrderInput(list) {
    var hidden = document.getElementById("sectionOrderInput");
    if (!hidden) return;
    var ids = Array.prototype.map.call(
      list.querySelectorAll(".section-row"),
      function (row) {
        return row.getAttribute("data-section-id");
      },
    );
    hidden.value = ids.join(",");
  }

  /* -----------------------------------------------------
     5. IMAGE PICKER PREVIEW
     For any <input type="file" data-preview-target="#id">,
     shows a live preview in the target element (replacing
     a .ph placeholder or a previous <img> if present).
  ----------------------------------------------------- */
  function initImagePreview() {
    document
      .querySelectorAll("input[type=file][data-preview-target]")
      .forEach(function (input) {
        input.addEventListener("change", function () {
          var file = input.files && input.files[0];
          if (!file) return;
          var target = document.querySelector(
            input.getAttribute("data-preview-target"),
          );
          if (!target) return;

          var reader = new FileReader();
          reader.onload = function (e) {
            target.innerHTML = "";
            var img = document.createElement("img");
            img.src = e.target.result;
            img.style.width = "100%";
            img.style.height = "100%";
            img.style.objectFit = "cover";
            target.appendChild(img);
          };
          reader.readAsDataURL(file);
        });
      });

    // Hidden file inputs triggered by a visible button (e.g. "Ganti Gambar")
    document.querySelectorAll("[data-trigger-upload]").forEach(function (btn) {
      btn.addEventListener("click", function () {
        var input = document.querySelector(
          btn.getAttribute("data-trigger-upload"),
        );
        if (input) input.click();
      });
    });
  }

  /* -----------------------------------------------------
     6. DELETE CONFIRMATION
     Any element with data-confirm="pesan" asks before
     letting its default action (link navigation / submit) proceed.
  ----------------------------------------------------- */
  function initConfirmActions() {
    document.querySelectorAll("[data-confirm]").forEach(function (el) {
      el.addEventListener("click", function (e) {
        // Hentikan aksi default (pindah halaman atau submit form)
        e.preventDefault();

        const message =
          el.getAttribute("data-confirm") || "Yakin ingin melanjutkan?";
        const targetUrl = el.getAttribute("href");
        const targetForm = el.closest("form");

        Swal.fire({
          title: "Konfirmasi Aksi",
          text: message,
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Ya, Lanjutkan",
          cancelButtonText: "Batal",
          reverseButtons: true,
          customClass: {
            popup: "swal2-rounded", // Opsional: untuk styling tambahan
          },
        }).then((result) => {
          if (result.isConfirmed) {
            // Jika elemen adalah tag <a> / Link
            if (
              targetUrl &&
              targetUrl !== "#" &&
              !targetUrl.startsWith("javascript:")
            ) {
              window.location.href = targetUrl;
            }
            // Jika elemen berada di dalam <form> atau tombol submit
            else if (targetForm) {
              targetForm.submit();
            }
          }
        });
      });
    });
  }

  /* -----------------------------------------------------
     7. TOGGLE SWITCH → auto-submit-ish visual feedback
     (purely presentational here; wire to real endpoint later)
  ----------------------------------------------------- */
  function initStatusSwitches() {
    document
      .querySelectorAll(".switch input[data-status-switch]")
      .forEach(function (input) {
        input.addEventListener("change", function () {
          var badge = document.querySelector(
            input.getAttribute("data-status-switch"),
          );
          if (!badge) return;
          if (input.checked) {
            badge.textContent = "Aktif";
            badge.className = "badge badge--success";
          } else {
            badge.textContent = "Nonaktif";
            badge.className = "badge badge--neutral";
          }
        });
      });
  }

  /* -----------------------------------------------------
     INIT
  ----------------------------------------------------- */
  document.addEventListener("DOMContentLoaded", function () {
    initSidebarToggle();
    initActiveNav();
    initUserMenu();
    initSectionReorder();
    initImagePreview();
    initConfirmActions();
    initStatusSwitches();
  });
})();
