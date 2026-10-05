const btnBuka = document.getElementById("bukaUndangan");
const layer2 = document.getElementById("layer2");
const main = document.getElementsByTagName("main")[0];
btnBuka.addEventListener("click", () => {
  layer2.classList.add("show");
  main.style.maxHeight = "unset";
});
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
