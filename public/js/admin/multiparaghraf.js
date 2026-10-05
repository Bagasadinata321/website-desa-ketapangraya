(function () {
  document.querySelectorAll("[data-repeater-add]").forEach(function (btn) {
    btn.addEventListener("click", function () {
      var key = btn.getAttribute("data-repeater-add");
      var container = document.querySelector('[data-repeater="' + key + '"]');
      var template = document.querySelector(
        '[data-repeater-template="' + key + '"]',
      );
      if (!container || !template) return;

      var index = container.children.length;
      var html = template.innerHTML.split("__INDEX__").join(String(index));
      var wrapper = document.createElement("div");
      wrapper.innerHTML = html.trim();
      container.appendChild(wrapper.firstElementChild);
    });
  });

  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("repeater__remove")) {
      var row = e.target.closest(".repeater__row");
      if (row) row.remove();
    }
  });
})();
