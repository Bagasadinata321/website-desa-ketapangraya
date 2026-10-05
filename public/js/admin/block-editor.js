/* =========================================================
   BLOCK-EDITOR.JS
   Editor konten berbasis block untuk form-lengkap.php (admin).
   Menghasilkan JSON array of blocks, sama persis format yang
   dibaca renderContentBlock() di views/public/detail.php.

   Cara pakai (lihat form-lengkap.php):
     <div class="block-editor" data-block-editor>
       <div class="block-editor__list" data-block-list></div>
       <p class="block-editor__empty" data-block-empty>...</p>
       <div class="block-editor__add">
         <button data-add-type="paragraph">+ Paragraf</button>
         ...
       </div>
     </div>
     <script type="application/json" data-block-editor-initial>[...]</script>
     <input type="hidden" name="content" data-block-editor-output />
   ========================================================= */

(function () {
  "use strict";

  var LABELS = {
    paragraph: "Paragraf",
    heading: "Heading",
    image: "Gambar",
    quote: "Kutipan",
    list: "List",
    divider: "Divider",
  };

  var blockIdCounter = 0;

  function escapeHtml(str) {
    return String(str == null ? "" : str)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;");
  }

  /* -----------------------------------------------------
     SANITIZER INLINE HTML (client-side)
     Cuma izinkan <strong>/<em>/<u>/<a href>, buang tag & atribut
     lain (termasuk yang dihasilkan execCommand secara tidak
     konsisten, mis. <span style="...">). Ini lapisan UX/konsistensi
     saja — pertahanan sesungguhnya tetap di sanitizeInlineHtml()
     versi PHP saat render (lihat detail.php), karena client-side
     selalu bisa dilewati orang yang mengedit request langsung.
  ----------------------------------------------------- */
  var ALLOWED_INLINE_TAGS = ["STRONG", "EM", "U", "A", "BR"];

  function sanitizeInlineHtml(html) {
    var wrap = document.createElement("div");
    wrap.innerHTML = html;

    function clean(node) {
      Array.prototype.slice.call(node.childNodes).forEach(function (child) {
        if (child.nodeType === 1) {
          var tag = child.tagName;
          if (ALLOWED_INLINE_TAGS.indexOf(tag) === -1) {
            // Bukan tag yang diizinkan — bongkar (unwrap), simpan isinya saja.
            while (child.firstChild) node.insertBefore(child.firstChild, child);
            node.removeChild(child);
            clean(node);
            return;
          }
          // Tag diizinkan — buang semua atribut kecuali href pada <a>.
          Array.prototype.slice.call(child.attributes).forEach(function (attr) {
            if (!(tag === "A" && attr.name === "href")) {
              child.removeAttribute(attr.name);
            }
          });
          if (tag === "A") {
            var href = child.getAttribute("href") || "";
            if (!/^(https?:|mailto:)/i.test(href)) href = "#";
            child.setAttribute("href", href);
            child.setAttribute("target", "_blank");
            child.setAttribute("rel", "noopener noreferrer");
          }
          clean(child);
        } else if (child.nodeType !== 3) {
          node.removeChild(child); // buang comment node & sejenisnya
        }
      });
    }

    clean(wrap);
    return wrap.innerHTML;
  }

  /* -----------------------------------------------------
     FIELD RICH TEXT (dipakai paragraph & quote) — toolbar
     Bold/Italic/Underline/Link + area contenteditable.
  ----------------------------------------------------- */
  function richTextFieldHtml(initialHtml, placeholder) {
    return (
      '<div class="block-editor__richtext-wrap">' +
      '<div class="block-editor__richtext-toolbar">' +
      '<button type="button" data-cmd="bold" title="Bold"><strong>B</strong></button>' +
      '<button type="button" data-cmd="italic" title="Italic"><em>I</em></button>' +
      '<button type="button" data-cmd="underline" title="Underline"><u>U</u></button>' +
      '<button type="button" data-cmd="link" title="Link">🔗</button>' +
      "</div>" +
      '<div class="block-editor__richtext" contenteditable="true" data-field="text" ' +
      'data-richtext="1" data-placeholder="' +
      escapeHtml(placeholder || "") +
      '">' +
      sanitizeInlineHtml(initialHtml || "") +
      "</div>" +
      "</div>"
    );
  }

  /* -----------------------------------------------------
     FIELD TEMPLATES PER TIPE BLOCK
  ----------------------------------------------------- */
  function fieldsHtml(type, data) {
    data = data || {};

    switch (type) {
      case "paragraph":
        return richTextFieldHtml(data.text, "Tulis paragraf...");

      case "heading":
        return (
          '<div class="block-editor__row">' +
          '<select class="form-control block-editor__field-sm" data-field="level">' +
          '<option value="2"' +
          (data.level == 2 || !data.level ? " selected" : "") +
          ">Heading 2</option>" +
          '<option value="3"' +
          (data.level == 3 ? " selected" : "") +
          ">Heading 3</option>" +
          '<option value="4"' +
          (data.level == 4 ? " selected" : "") +
          ">Heading 4</option>" +
          "</select>" +
          '<input type="text" class="form-control" data-field="text" ' +
          'value="' +
          escapeHtml(data.text) +
          '" placeholder="Judul sub-bagian" />' +
          "</div>"
        );

      case "image":
        return (
          '<input type="text" class="form-control" data-field="url" ' +
          'value="' +
          escapeHtml(data.url) +
          '" placeholder="URL gambar (mis. /uploads/foto.jpg)" />' +
          '<input type="text" class="form-control" data-field="caption" ' +
          'value="' +
          escapeHtml(data.caption) +
          '" placeholder="Keterangan gambar (opsional)" ' +
          'style="margin-top:0.6rem;" />' +
          '<input type="text" class="form-control" data-field="alt" ' +
          'value="' +
          escapeHtml(data.alt) +
          '" placeholder="Teks alternatif / alt (opsional)" ' +
          'style="margin-top:0.6rem;" />' +
          '<p class="form-hint">Sementara diisi manual lewat URL — upload gambar langsung ' +
          "menyusul setelah fitur simpan gambar tersedia.</p>"
        );

      case "quote":
        return (
          richTextFieldHtml(data.text, "Isi kutipan...") +
          '<input type="text" class="form-control" data-field="cite" ' +
          'value="' +
          escapeHtml(data.cite) +
          '" placeholder="Sumber kutipan (opsional)" ' +
          'style="margin-top:0.6rem;" />'
        );

      case "list":
        var itemsText = Array.isArray(data.items) ? data.items.join("\n") : "";
        return (
          '<select class="form-control block-editor__field-sm" data-field="style" style="margin-bottom:0.6rem;">' +
          '<option value="unordered"' +
          (data.style !== "ordered" ? " selected" : "") +
          ">Bullet</option>" +
          '<option value="ordered"' +
          (data.style === "ordered" ? " selected" : "") +
          ">Bernomor</option>" +
          "</select>" +
          '<textarea class="form-control" rows="4" data-field="items" ' +
          'placeholder="1 baris = 1 item list">' +
          escapeHtml(itemsText) +
          "</textarea>"
        );

      case "divider":
        return '<p class="form-hint" style="margin:0;">Garis pemisah — tidak ada isian.</p>';

      default:
        return "";
    }
  }

  /* -----------------------------------------------------
     BUAT ELEMEN BLOCK DARI DATA
  ----------------------------------------------------- */
  function createBlockElement(block) {
    var type = block.type || "paragraph";
    var id = "block-" + ++blockIdCounter;

    var el = document.createElement("div");
    el.className = "block-editor__item";
    el.setAttribute("data-block-type", type);
    el.setAttribute("data-block-id", id);

    el.innerHTML =
      '<div class="block-editor__item-head">' +
      '<span class="block-editor__item-label">' +
      (LABELS[type] || type) +
      "</span>" +
      '<div class="block-editor__item-actions">' +
      '<button type="button" data-action="move-up" title="Naik" aria-label="Pindah ke atas">↑</button>' +
      '<button type="button" data-action="move-down" title="Turun" aria-label="Pindah ke bawah">↓</button>' +
      '<button type="button" data-action="remove" title="Hapus" aria-label="Hapus block">✕</button>' +
      "</div>" +
      "</div>" +
      '<div class="block-editor__item-body">' +
      fieldsHtml(type, block) +
      "</div>";

    return el;
  }

  /* -----------------------------------------------------
     BACA ISI SATU BLOCK DARI DOM -> OBJECT
  ----------------------------------------------------- */
  function readBlockData(el) {
    var type = el.getAttribute("data-block-type");
    var field = function (name) {
      var f = el.querySelector('[data-field="' + name + '"]');
      return f ? f.value : "";
    };
    var richTextField = function (name) {
      var f = el.querySelector('[data-field="' + name + '"][data-richtext]');
      return f ? sanitizeInlineHtml(f.innerHTML) : "";
    };

    switch (type) {
      case "heading":
        return {
          type: type,
          level: parseInt(field("level"), 10) || 2,
          text: field("text"),
        };

      case "image":
        return {
          type: type,
          url: field("url"),
          caption: field("caption"),
          alt: field("alt"),
        };

      case "quote":
        return { type: type, text: richTextField("text"), cite: field("cite") };

      case "list":
        var items = field("items")
          .split("\n")
          .map(function (s) {
            return s.trim();
          })
          .filter(function (s) {
            return s.length > 0;
          });
        return {
          type: type,
          style: field("style") || "unordered",
          items: items,
        };

      case "divider":
        return { type: type };

      case "paragraph":
      default:
        return { type: "paragraph", text: richTextField("text") };
    }
  }

  /* -----------------------------------------------------
     INIT SATU EDITOR INSTANCE
  ----------------------------------------------------- */
  function initEditor(root) {
    var list = root.querySelector("[data-block-list]");
    var emptyMsg = root.querySelector("[data-block-empty]");
    var initialScript = root.parentElement.querySelector(
      "[data-block-editor-initial]",
    );
    var output = root.parentElement.querySelector("[data-block-editor-output]");

    if (!list || !output) return;

    var initialBlocks = [];
    if (initialScript) {
      try {
        var parsed = JSON.parse(initialScript.textContent || "[]");
        if (Array.isArray(parsed)) initialBlocks = parsed;
      } catch (e) {
        console.error("Gagal parse initial block editor data:", e);
      }
    }

    function toggleEmptyState() {
      var hasBlocks = list.children.length > 0;
      if (emptyMsg) emptyMsg.style.display = hasBlocks ? "none" : "block";
    }

    function serialize() {
      var blocks = [];
      Array.prototype.forEach.call(list.children, function (el) {
        blocks.push(readBlockData(el));
      });
      output.value = JSON.stringify(blocks);
    }

    function addBlock(type, data, focus) {
      var el = createBlockElement(Object.assign({ type: type }, data || {}));
      list.appendChild(el);
      toggleEmptyState();
      serialize();

      if (focus) {
        var firstField = el.querySelector("textarea, input, select");
        if (firstField) firstField.focus();
      }
    }

    // Render block awal (dari database, lewat controller)
    initialBlocks.forEach(function (block) {
      if (block && typeof block === "object") {
        list.appendChild(createBlockElement(block));
      }
    });
    toggleEmptyState();
    serialize();

    // Tombol "+ Tipe" di toolbar
    root.querySelectorAll("[data-add-type]").forEach(function (btn) {
      btn.addEventListener("click", function () {
        addBlock(btn.getAttribute("data-add-type"), {}, true);
      });
    });

    // Delegasi event untuk aksi per-block (naik/turun/hapus) + live update saat mengetik
    list.addEventListener("click", function (e) {
      var actionBtn = e.target.closest("[data-action]");
      if (actionBtn) {
        var item = actionBtn.closest(".block-editor__item");
        var action = actionBtn.getAttribute("data-action");

        if (action === "remove") {
          item.remove();
          toggleEmptyState();
          serialize();
        } else if (action === "move-up") {
          var prev = item.previousElementSibling;
          if (prev) list.insertBefore(item, prev);
          serialize();
        } else if (action === "move-down") {
          var next = item.nextElementSibling;
          if (next) list.insertBefore(next, item);
          serialize();
        }
        return;
      }

      var cmdBtn = e.target.closest("[data-cmd]");
      if (cmdBtn) {
        var cmd = cmdBtn.getAttribute("data-cmd");
        var target = cmdBtn
          .closest(".block-editor__richtext-wrap")
          .querySelector("[data-richtext]");
        target.focus();

        if (cmd === "link") {
          var url = window.prompt("Masukkan URL link:", "https://");
          if (url) document.execCommand("createLink", false, url);
        } else {
          document.execCommand(cmd, false, null);
        }

        // Bersihkan hasil execCommand (kadang nyisipin <span style>, dsb)
        // sebelum disimpan, biar konsisten dgn 4 tag yang diizinkan.
        target.innerHTML = sanitizeInlineHtml(target.innerHTML);
        serialize();
      }
    });

    // Mousedown di tombol toolbar sengaja dicegah default-nya supaya fokus/seleksi
    // teks di area contenteditable tidak hilang sebelum execCommand sempat jalan.
    list.addEventListener("mousedown", function (e) {
      if (e.target.closest("[data-cmd]")) {
        e.preventDefault();
      }
    });

    list.addEventListener("input", serialize);
    list.addEventListener("change", serialize);

    // Safety net: pastikan hidden input ter-update tepat sebelum submit,
    // jaga-jaga kalau ada input yang belum sempat trigger event 'input'/'change'.
    var form = root.closest("form");
    if (form) {
      form.addEventListener("submit", serialize);
    }
  }

  document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("[data-block-editor]").forEach(initEditor);
  });
})();
