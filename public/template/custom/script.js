console.log("JS terhubung!");
function resetAnimation(section) {
  const items = section.querySelectorAll("[class*='fade'], [class*='anim']");
  console.log("elemen ditemukan:", items.length, section.id);
  items.forEach((el) => {
    el.style.animation = "none";
    el.offsetHeight;
    el.style.animation = "";
  });
}
// Sembunyikan section default jika addon penggantinya aktif
if (HAS_ADDON && ADDON_HIDES) {
  const el = document.getElementById(ADDON_HIDES);
  if (el) el.style.display = "none";
}
function nextLayer(targetId) {
  const current = document.querySelector(".layer.active");

  if (targetId === 3 && HAS_ADDON) {
    const layer2 = document.getElementById("layer2");
    // Belok ke layer 2 HANYA jika sekarang di layer 1
    if (layer2 && current.id === "layer1") {
      targetId = 2;
    }
  }

  const next = document.getElementById("layer" + targetId);

  if (current === next) return;

  current.classList.remove("active");

  setTimeout(() => {
    next.classList.add("active");
    resetAnimation(next);
  }, 1000);

  if (targetId === 2 || (targetId === 3 && !HAS_ADDON)) {
    bgAudio.play().catch(() => {});
  }
}
document.addEventListener("DOMContentLoaded", function () {
  const countdownEl = document.getElementById("countdown");

  // Ambil timestamp dari data attribute
  const targetDate = parseInt(countdownEl.dataset.date);

  // Format biar selalu 2 digit (01, 02, dst)
  const format = (num) => num.toString().padStart(2, "0");

  const daysEl = document.getElementById("days");
  const hoursEl = document.getElementById("hours");
  const minutesEl = document.getElementById("minutes");
  const secondsEl = document.getElementById("seconds");

  const countdown = setInterval(() => {
    const now = new Date().getTime();
    const distance = targetDate - now;

    // Jika waktu habis
    if (distance <= 0) {
      clearInterval(countdown);
      countdownEl.innerHTML = "<p>Acara Dimulai!</p>";
      return;
    }

    // Perhitungan waktu
    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance / (1000 * 60 * 60)) % 24);
    const minutes = Math.floor((distance / (1000 * 60)) % 60);
    const seconds = Math.floor((distance / 1000) % 60);

    // Render ke HTML
    daysEl.textContent = format(days);
    hoursEl.textContent = format(hours);
    minutesEl.textContent = format(minutes);
    secondsEl.textContent = format(seconds);
  }, 1000);
  console.log(countdownEl.dataset.date);
});

const elements = document.querySelectorAll(".fade-item");
const observer = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("show");
      } else {
        entry.target.classList.remove("show");
      }
    });
  },
  {
    threshold: 0.2,
  },
);

elements.forEach((el) => observer.observe(el));

// ── Utility ─────────────────────────────────────────────────
function escapeHTML(str) {
  if (!str) return "";
  return str.replace(
    /[&<>"']/g,
    (m) =>
      ({
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#39;",
      })[m],
  );
}

function renderUcapan(item) {
  const initial = item.guest_name.charAt(0).toUpperCase();
  return `
        <div class="ucapan-item fade-item anim-soft">
            <div class="ucapan-avatar">${initial}</div>
            <div class="ucapan-content">
                <p class="ucapan-nama">${escapeHTML(item.guest_name)}</p>
                <p class="ucapan-pesan">${escapeHTML(item.message)}</p>
            </div>
        </div>
    `;
}
// ── Load Ucapan ──────────────────────────────────────────────
function loadUcapan() {
  const clientId = document.querySelector('[name="client_id"]').value;

  fetch(`${BASE_URL}ucapan/list?client_id=${clientId}`)
    .then((res) => res.json())
    .then((result) => {
      if (result.status !== "success") return;

      const list = document.querySelector(".ucapan-list");
      list.innerHTML = result.data.map(renderUcapan).join("");

      // Daftarkan elemen baru ke observer
      list.querySelectorAll(".fade-item").forEach((el) => observer.observe(el));
    });
}

// ── Submit RSVP ──────────────────────────────────────────────
document
  .querySelector(".rsvp-form")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    const res = await fetch(this.action, {
      method: "POST",
      body: new FormData(this),
    });
    const result = await res.json();

    showAlert(
      result.status === "success" ? "success" : "error",
      result.message,
    );
    if (result.status === "success") this.reset();
  });

function showAlert(type, message) {
  // Hapus alert lama jika ada
  const existing = document.getElementById("custom-alert");
  if (existing) existing.remove();

  const icon =
    type === "success"
      ? `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`
      : `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`;

  const color =
    type === "success"
      ? { bg: "#f0fdf4", border: "#bbf7d0", icon: "#16a34a", text: "#166534" }
      : { bg: "#fef2f2", border: "#fecaca", icon: "#dc2626", text: "#991b1b" };

  const alert = document.createElement("div");
  alert.id = "custom-alert";
  alert.innerHTML = `
    <div class="alert-icon">${icon}</div>
    <div class="alert-body">
      <p class="alert-title">${type === "success" ? "Berhasil!" : "Gagal!"}</p>
      <p class="alert-msg">${message}</p>
    </div>
    <button class="alert-close" onclick="this.parentElement.classList.add('alert-hide'); setTimeout(() => this.parentElement.remove(), 400)">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  `;

  Object.assign(alert.style, {
    position: "fixed",
    top: "24px",
    right: "24px",
    zIndex: "9999",
    display: "flex",
    alignItems: "flex-start",
    gap: "12px",
    background: color.bg,
    border: `1px solid ${color.border}`,
    borderRadius: "14px",
    padding: "16px 18px",
    boxShadow: "0 8px 30px rgba(0,0,0,0.1)",
    maxWidth: "320px",
    width: "calc(100% - 48px)",
    animation: "alertSlideIn 0.4s cubic-bezier(0.16,1,0.3,1) forwards",
  });

  // Inject style sekali saja
  if (!document.getElementById("custom-alert-style")) {
    const style = document.createElement("style");
    style.id = "custom-alert-style";
    style.textContent = `
      @keyframes alertSlideIn {
        from { opacity: 0; transform: translateX(60px); }
        to   { opacity: 1; transform: translateX(0); }
      }
      .alert-hide {
        animation: alertSlideOut 0.4s ease forwards !important;
      }
      @keyframes alertSlideOut {
        from { opacity: 1; transform: translateX(0); }
        to   { opacity: 0; transform: translateX(60px); }
      }
      #custom-alert .alert-icon svg {
        width: 22px; height: 22px;
        stroke: ${color.icon};
        flex-shrink: 0; margin-top: 1px;
      }
      #custom-alert .alert-body { flex: 1; }
      #custom-alert .alert-title {
        font-size: 14px; font-weight: 600;
        color: ${color.text}; margin-bottom: 2px;
      }
      #custom-alert .alert-msg {
        font-size: 13px; color: ${color.text};
        opacity: 0.85; line-height: 1.5;
      }
      #custom-alert .alert-close {
        background: none; border: none; cursor: pointer;
        padding: 2px; flex-shrink: 0; opacity: 0.5;
        transition: opacity 0.2s;
      }
      #custom-alert .alert-close:hover { opacity: 1; }
      #custom-alert .alert-close svg {
        width: 16px; height: 16px; stroke: ${color.text};
      }
    `;
    document.head.appendChild(style);
  }

  document.body.appendChild(alert);

  // Auto dismiss setelah 4 detik
  setTimeout(() => {
    if (document.getElementById("custom-alert")) {
      alert.classList.add("alert-hide");
      setTimeout(() => alert.remove(), 400);
    }
  }, 4000);
}

// ── Submit Ucapan ────────────────────────────────────────────
document
  .querySelector(".ucapan-form")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    const res = await fetch(this.action, {
      method: "POST",
      body: new FormData(this),
    });
    const result = await res.json();

    if (result.status === "success") {
      this.reset();
      loadUcapan(); // reload list setelah ucapan terkirim
    } else {
      alert(result.message);
    }
  });

// ── Load awal ────────────────────────────────────────────────
loadUcapan();
window.addEventListener("load", function () {
  const loader = document.getElementById("loading-screen");
  // efek fade out
  loader.style.opacity = "0";

  setTimeout(() => {
    loader.style.display = "none";
  }, 500);
});
document.addEventListener("DOMContentLoaded", () => {
  let index = 0;
  const slider = document.querySelector(".bg-images");
  const total = slider.children.length;

  setInterval(() => {
    index++;
    if (index >= total) index = 0;

    slider.style.transform = `translateX(-${index * 100}%)`;
  }, 8000);
});
const layer = document.getElementById("layer2");

if (layer) {
  layer.addEventListener("scroll", () => {
    const timeline = document.getElementById("timeline");
    if (!timeline) return;

    const fill = document.getElementById("lineFill");
    const circles = document.querySelectorAll(".circle");

    const containerHeight = layer.clientHeight;
    const centerLine = containerHeight / 1.1;

    const timelineRect = timeline.getBoundingClientRect();
    const progressPosition = centerLine - timelineRect.top;

    let percent = (progressPosition / timeline.offsetHeight) * 100;
    percent = Math.max(0, Math.min(100, percent));

    fill.style.height = percent + "%";

    circles.forEach((circle) => {
      const circleTop = circle.offsetTop;

      if (progressPosition >= circleTop) {
        circle.classList.add("active");
      } else {
        circle.classList.remove("active");
      }
    });
  });
}
function setTimelineHeight() {
  const timeline = document.getElementById("timeline");
  const lastIntro = document.querySelectorAll(".ws-intro");

  if (!timeline || lastIntro.length === 0) return;

  const lastItem = lastIntro[lastIntro.length - 1];
  const lastDesc = lastItem.querySelector(".ws-desc");

  // posisi parent (grid kanan)
  const parent = lastItem.parentElement;
  const parentRect = parent.getBoundingClientRect();

  const descRect = lastDesc.getBoundingClientRect();

  // hitung tinggi sampai atas ws-desc terakhir
  const height = descRect.top - parentRect.top;

  timeline.style.height = height + "px";
}

window.addEventListener("load", setTimelineHeight);
window.addEventListener("resize", setTimelineHeight);
