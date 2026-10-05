/* ─────────────────────────────────────────
   LAVENDER GARDEN — script.js
   ───────────────────────────────────────── */

/* ── Loading Screen ── */
window.addEventListener("load", () => {
  const ls = document.getElementById("loading-screen");
  if (ls) {
    setTimeout(() => {
      ls.classList.add("hidden");
      setTimeout(() => ls.remove(), 700);
    }, 800);
  }
});

/* ── Sembunyikan section default jika addon penggantinya aktif ── */
if (HAS_ADDON && ADDON_HIDES) {
  const el = document.getElementById(ADDON_HIDES);
  if (el) el.style.display = "none";
}

/* ── Reset animasi saat layer aktif ── */
function resetAnimation(section) {
  const items = section.querySelectorAll("[class*='fade'], [class*='anim']");
  console.log("elemen ditemukan:", items.length, section.id);
  items.forEach((el) => {
    el.style.animation = "none";
    el.offsetHeight;
    el.style.animation = "";
  });
}

/* ── Transisi antar layer ── */
function nextLayer(targetId) {
  const current = document.querySelector(".layer.active");
  if (!current) return;

  if (targetId === 3 && HAS_ADDON) {
    const layer2 = document.getElementById("layer2");
    if (layer2 && current.id === "layer1") {
      targetId = 2;
    }
  }

  const next = document.getElementById("layer" + targetId);
  if (!next || current === next) return;

  current.classList.remove("active");

  setTimeout(() => {
    next.classList.add("active");
    resetAnimation(next);
    window.scrollTo({ top: 0, behavior: "smooth" });
  }, 400);

  if (targetId === 2 || (targetId === 3 && !HAS_ADDON)) {
    bgAudio.play().catch(() => {});
  }
}

/* ── Countdown ── */
function initCountdown() {
  const el = document.getElementById("countdown");
  if (!el) return;

  const target = parseInt(el.dataset.date);

  function update() {
    const now = Date.now();
    const diff = target - now;

    if (diff <= 0) {
      ["days", "hours", "minutes", "seconds"].forEach((id) => {
        const span = document.getElementById(id);
        if (span) span.textContent = "0";
      });
      return;
    }

    const d = Math.floor(diff / 86400000);
    const h = Math.floor((diff % 86400000) / 3600000);
    const m = Math.floor((diff % 3600000) / 60000);
    const s = Math.floor((diff % 60000) / 1000);

    const days = document.getElementById("days");
    const hours = document.getElementById("hours");
    const minutes = document.getElementById("minutes");
    const seconds = document.getElementById("seconds");

    if (days) days.textContent = d;
    if (hours) hours.textContent = String(h).padStart(2, "0");
    if (minutes) minutes.textContent = String(m).padStart(2, "0");
    if (seconds) seconds.textContent = String(s).padStart(2, "0");
  }

  update();
  setInterval(update, 1000);
}

/* ── Intersection Observer untuk fade-item ── */
function initScrollAnimations() {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.style.animationPlayState = "running";
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.15 },
  );

  document.querySelectorAll(".fade-item").forEach((el) => {
    el.style.animationPlayState = "paused";
    observer.observe(el);
  });
}

/* ── Copy rekening ── */
function copyRekening(nomor) {
  navigator.clipboard
    .writeText(nomor)
    .then(() => {
      const btn = document.querySelector(".btn-copy");
      if (!btn) return;
      const orig = btn.innerHTML;
      btn.innerHTML = '<i class="ti ti-check"></i> Tersalin!';
      setTimeout(() => {
        btn.innerHTML = orig;
      }, 2000);
    })
    .catch(() => {
      const ta = document.createElement("textarea");
      ta.value = nomor;
      document.body.appendChild(ta);
      ta.select();
      document.execCommand("copy");
      document.body.removeChild(ta);
    });
}

/* ── Lightbox ── */
function openLightbox(src) {
  const lb = document.getElementById("lightbox");
  const img = document.getElementById("lightbox-img");
  if (!lb || !img) return;
  img.src = src;
  lb.classList.add("open");
  document.body.style.overflow = "hidden";
}

function closeLightbox() {
  const lb = document.getElementById("lightbox");
  if (!lb) return;
  lb.classList.remove("open");
  document.body.style.overflow = "";
}

document.addEventListener("keydown", (e) => {
  if (e.key === "Escape") closeLightbox();
});

/* ── Init ── */
document.addEventListener("DOMContentLoaded", () => {
  initCountdown();
  initScrollAnimations();
});
