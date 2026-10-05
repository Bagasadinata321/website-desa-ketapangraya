function openEnvelope() {
  const env = document.getElementById("envelope");
  const paper = document.getElementById("letterPaper");
  if (env.classList.contains("opening")) return;
  env.classList.add("opening");
  paper.classList.add("out");
  setTimeout(() => {
    document.getElementById("detail").classList.add("visible");
  }, 2500);
}

function updateCountdown() {
  const target = new Date("2026-06-14T08:00:00+08:00");
  const now = new Date();
  const diff = target - now;
  if (diff <= 0) {
    ["cd-days", "cd-hours", "cd-mins", "cd-secs"].forEach(
      (id) => (document.getElementById(id).textContent = "00"),
    );
    return;
  }
  document.getElementById("cd-days").textContent = String(
    Math.floor(diff / 86400000),
  ).padStart(2, "0");
  document.getElementById("cd-hours").textContent = String(
    Math.floor((diff % 86400000) / 3600000),
  ).padStart(2, "0");
  document.getElementById("cd-mins").textContent = String(
    Math.floor((diff % 3600000) / 60000),
  ).padStart(2, "0");
  document.getElementById("cd-secs").textContent = String(
    Math.floor((diff % 60000) / 1000),
  ).padStart(2, "0");
}
updateCountdown();
setInterval(updateCountdown, 1000);

const colors1 = ["#C9D8E8", "#D4C8DC", "#D4E8D4", "#E8D4C8", "#D4DDE8"];
const colors2 = [
  "#E8D4D4",
  "#D4E8E4",
  "#E8E4D4",
  "#DCD4E8",
  "#D4E8D4",
  "#E8D8C8",
];
const positionSets = {
  2: [
    { x: -25, rot: -5 },
    { x: 25, rot: 4 },
  ],
  3: [
    { x: -50, rot: -7 },
    { x: 0, rot: 3 },
    { x: 50, rot: -2 },
  ],
  4: [
    { x: -60, rot: -8 },
    { x: -20, rot: 5 },
    { x: 20, rot: -3 },
    { x: 60, rot: 6 },
  ],
};

function buildStack(containerId, fotoUrls) {
  const count = fotoUrls.length;
  if (count === 0) return;
  if (!positionSets[count]) {
    console.warn(`positionSets tidak punya config untuk ${count} foto`);
    return;
  }

  const container = document.getElementById(containerId);
  if (!container) return;

  const positions = positionSets[count];
  let order = Array.from({ length: count }, (_, i) => i);
  let locked = false;
  const els = {};

  function getTransform(stackPos, lifted = false) {
    const pos = positions[stackPos];
    if (lifted) return `translateX(${pos.x}px) translateY(-170px) rotate(0deg)`;
    return `translateX(${pos.x}px) rotate(${pos.rot}deg)`;
  }

  function init() {
    container.innerHTML = "";
    order.forEach((photoIdx, stackPos) => {
      const el = document.createElement("div");
      el.className = "stack-photo";
      el.style.zIndex = stackPos;
      el.style.transform = getTransform(stackPos);
      el.style.transition = "none";
      el.style.boxShadow =
        stackPos === order.length - 1
          ? "4px 4px 12px rgba(60,30,5,0.25)"
          : "2px 2px 6px rgba(60,30,5,0.15)";

      const img = document.createElement("img");
      img.src = fotoUrls[photoIdx];
      img.alt = `foto ${photoIdx + 1}`;
      img.draggable = false;
      el.appendChild(img);

      el.addEventListener("click", () => handleClick(photoIdx));
      els[photoIdx] = el;
      container.appendChild(el);
    });
  }

  function handleClick(photoIdx) {
    if (locked) return;
    const clickedStackPos = order.indexOf(photoIdx);
    if (clickedStackPos === order.length - 1) return;
    locked = true;
    const el = els[photoIdx];
    const newOrder = [...order];
    newOrder.splice(clickedStackPos, 1);
    newOrder.push(photoIdx);
    el.style.transition = "transform 0.28s ease-in, box-shadow 0.28s ease";
    el.style.transform = getTransform(clickedStackPos, true);
    el.style.zIndex = 999;
    el.style.boxShadow = "6px 12px 24px rgba(60,30,5,0.3)";
    order.forEach((pIdx) => {
      if (pIdx === photoIdx) return;
      const newStackPos = newOrder.indexOf(pIdx);
      const el2 = els[pIdx];
      el2.style.transition = "transform 0.28s ease, box-shadow 0.28s ease";
      el2.style.transform = getTransform(newStackPos);
      el2.style.zIndex = newStackPos;
      el2.style.boxShadow =
        newStackPos === order.length - 1
          ? "4px 4px 12px rgba(60,30,5,0.25)"
          : "2px 2px 6px rgba(60,30,5,0.15)";
    });
    setTimeout(() => {
      order = newOrder;
      el.style.transition =
        "transform 0.32s cubic-bezier(.34,1.2,.64,1), box-shadow 0.3s ease";
      el.style.transform = getTransform(order.length - 1);
      el.style.zIndex = order.length - 1;
      el.style.boxShadow = "4px 4px 12px rgba(60,30,5,0.25)";
      setTimeout(() => {
        locked = false;
      }, 350);
    }, 300);
  }

  init();
}

if (typeof fotoUrls1 !== "undefined" && fotoUrls1.length > 0)
  buildStack("stack-1", fotoUrls1);
if (typeof fotoUrls2 !== "undefined" && fotoUrls2.length > 0)
  buildStack("stack-2", fotoUrls2);
if (typeof fotoUrls3 !== "undefined" && fotoUrls3.length > 0)
  buildStack("stack-3", fotoUrls3);

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
    threshold: 0.5,
  },
);

elements.forEach((el) => observer.observe(el));
window.addEventListener("load", function () {
  const loader = document.getElementById("loading-screen");
  // efek fade out
  loader.style.opacity = "0";

  setTimeout(() => {
    loader.style.display = "none";
  }, 500);
});
