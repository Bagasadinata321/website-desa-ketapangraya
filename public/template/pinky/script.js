console.log("JS terhubung!");

// ── PAGE OPEN ──
function openInvitation() {
  const p1 = document.getElementById("page1");
  const p2 = document.getElementById("page2");
  p1.style.transition = "opacity .5s ease";
  p1.style.opacity = "0";
  setTimeout(() => {
    p1.style.display = "none";
    p2.classList.add("active", "page-enter");
    window.scrollTo({ top: 0, behavior: "instant" });
    setTimeout(() => {
      startCountdown();
      initObs();
    }, 80);
  }, 500);
}

// ── COUNTDOWN ──
function startCountdown() {
  const target = new Date("2025-06-21T08:00:00");
  const update = () => {
    const diff = target - new Date();
    if (diff <= 0) {
      document.getElementById("cd-d").textContent =
        document.getElementById("cd-h").textContent =
        document.getElementById("cd-m").textContent =
        document.getElementById("cd-s").textContent =
          "00";
      return;
    }
    const pad = (n) => String(Math.floor(n)).padStart(2, "0");
    document.getElementById("cd-d").textContent = pad(diff / 86400000);
    document.getElementById("cd-h").textContent = pad(
      (diff % 86400000) / 3600000,
    );
    document.getElementById("cd-m").textContent = pad((diff % 3600000) / 60000);
    document.getElementById("cd-s").textContent = pad((diff % 60000) / 1000);
  };
  update();
  setInterval(update, 1000);
}

// ── INTERSECTION OBSERVER ──
function initObs() {
  const io = new IntersectionObserver(
    (entries) => {
      entries.forEach((e) => {
        if (e.isIntersecting) e.target.classList.add("in");
      });
    },
    { threshold: 0.12 },
  );
  document
    .querySelectorAll(".obs,.obs-left,.obs-right,.obs-scale")
    .forEach((el) => io.observe(el));

  // nav highlight
  const secs = [
    { id: "sec-countdown", nav: "bnav-countdown" },
    { id: "sec-couple", nav: "bnav-couple" },
    { id: "sec-detail", nav: "bnav-detail" },
    { id: "sec-rsvp", nav: "bnav-rsvp" },
    { id: "sec-gallery", nav: "bnav-gallery" },
  ];
  const navIO = new IntersectionObserver(
    (entries) => {
      entries.forEach((e) => {
        if (e.isIntersecting) {
          document
            .querySelectorAll(".bnav-btn")
            .forEach((b) => b.classList.remove("active"));
          document
            .getElementById(e.target.dataset.nav)
            ?.classList.add("active");
        }
      });
    },
    { threshold: 0.35 },
  );
  secs.forEach(({ id, nav }) => {
    const el = document.getElementById(id);
    if (el) {
      el.dataset.nav = nav;
      navIO.observe(el);
    }
  });
}

// ── SCROLL ──
function scrollSec(id, navId) {
  document
    .getElementById(id)
    ?.scrollIntoView({ behavior: "smooth", block: "start" });
}

// ── TABS ──
function switchTab(tab, btn) {
  document
    .querySelectorAll(".tab-btn")
    .forEach((b) => b.classList.remove("active"));
  document
    .querySelectorAll(".tab-panel")
    .forEach((p) => p.classList.remove("active"));
  btn.classList.add("active");
  document.getElementById("panel-" + tab).classList.add("active");
  if (tab === "gift") {
    setTimeout(
      () =>
        document
          .querySelectorAll(".gift-card")
          .forEach((el) => el.classList.add("in")),
      100,
    );
  }
}

// ── UCAPAN DATA ──
const ucapanData = [
  {
    name: "Sarah & Doni",
    msg: "Selamat ya Rizky & Aulia! Semoga menjadi keluarga yang sakinah, mawaddah wa rahmah.",
    hadir: true,
  },
  {
    name: "Keluarga Besar Santoso",
    msg: "Doa terbaik kami untuk kalian berdua. Semoga langgeng hingga akhir hayat.",
    hadir: true,
  },
  {
    name: "Teman SMA",
    msg: "Akhirnya! Semoga bahagia selalu, pasangan yang paling cocok.",
    hadir: false,
  },
];
function renderUcapan() {
  document.getElementById("ucapan-list").innerHTML = ucapanData
    .map(
      (u) => `
    <div class="ucapan-item">
      <div class="ucapan-name">${u.name}<span class="ucapan-badge${u.hadir ? " hadir" : ""}">${u.hadir ? "Hadir" : "Tidak Hadir"}</span></div>
      <div class="ucapan-text">${u.msg}</div>
    </div>`,
    )
    .join("");
}
function submitRSVP() {
  const name = document.getElementById("rsvp-name").value.trim();
  const msg = document.getElementById("rsvp-msg").value.trim();
  const hadir =
    document.querySelector('input[name="hadir"]:checked')?.value === "hadir";
  if (!name) {
    showToast("Nama tidak boleh kosong");
    return;
  }
  ucapanData.unshift({
    name,
    msg: msg || "Selamat menempuh hidup baru!",
    hadir,
  });
  renderUcapan();
  document.getElementById("rsvp-name").value = "";
  document.getElementById("rsvp-msg").value = "";
  showToast("Konfirmasi terkirim. Terima kasih");
}
function submitUcapan() {
  const name = document.getElementById("ucapan-name").value.trim();
  const text = document.getElementById("ucapan-text").value.trim();
  if (!name || !text) {
    showToast("Nama dan ucapan wajib diisi");
    return;
  }
  ucapanData.unshift({ name, msg: text, hadir: true });
  renderUcapan();
  document.getElementById("ucapan-name").value = "";
  document.getElementById("ucapan-text").value = "";
  showToast("Ucapan terkirim. Terima kasih");
}

// ── GALLERY LIGHTBOX ──
const galleryLabels = [
  "Foto Bersama",
  "Cincin Pernikahan",
  "Buket Pengantin",
  "Venue Akad",
  "Surat Undangan",
  "Senja Lamaran",
  "Wedding Cake",
  "Taman Bunga",
  "Forever Together",
];
function openLightbox(idx) {
  document.getElementById("lb-content").innerHTML = `
    <div style="margin-bottom:12px">
      <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
        <circle cx="24" cy="24" r="20" stroke="#EFC9C9" stroke-width="1.5"/>
        <circle cx="24" cy="24" r="12" stroke="#CDBFE8" stroke-width="1"/>
        <circle cx="24" cy="24" r="5" fill="#F5D7B5" opacity=".6"/>
      </svg>
    </div>
    <div style="font-family:'Cormorant Garamond',serif;font-style:italic;font-size:20px;color:var(--text-dark);margin-bottom:6px">${galleryLabels[idx]}</div>
    <div style="font-size:11px;color:var(--text-light);letter-spacing:2px">Foto ${idx + 1} / ${galleryLabels.length}</div>`;
  document.getElementById("lightbox").classList.add("open");
}
function closeLightbox() {
  document.getElementById("lightbox").classList.remove("open");
}

// ── UTILS ──
function copyText(text) {
  navigator.clipboard
    ?.writeText(text)
    .then(() => showToast("Nomor berhasil disalin"))
    .catch(() => showToast(text));
}
function openMaps() {
  window.open("https://maps.google.com/?q=Jakarta+Selatan", "_blank");
}
function showToast(msg) {
  const t = document.getElementById("toast");
  t.textContent = msg;
  t.classList.add("show");
  setTimeout(() => t.classList.remove("show"), 2800);
}

// ── INIT ──
document.addEventListener("DOMContentLoaded", renderUcapan);
