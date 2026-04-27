(function () {
  const body = document.body;
  const toggle = document.querySelector("[data-admin-sidebar-toggle]");
  const backdrop = document.querySelector("[data-admin-backdrop]");
  const sidebar = document.querySelector("[data-admin-sidebar]");

  function openSidebar() {
    body.classList.add("admin-sidebar-open");
    if (toggle) toggle.setAttribute("aria-expanded", "true");
  }

  function closeSidebar() {
    body.classList.remove("admin-sidebar-open");
    if (toggle) toggle.setAttribute("aria-expanded", "false");
  }

  if (toggle) {
    toggle.addEventListener("click", function () {
      if (body.classList.contains("admin-sidebar-open")) {
        closeSidebar();
      } else {
        openSidebar();
      }
    });
  }

  if (backdrop) {
    backdrop.addEventListener("click", closeSidebar);
  }

  window.addEventListener("resize", function () {
    if (window.matchMedia("(min-width: 900px)").matches) {
      closeSidebar();
    }
  });

  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      closeSidebar();
      closeMenu();
    }
  });

  const menu = document.querySelector("[data-admin-user-menu]");
  const menuBtn = menu ? menu.querySelector(".admin-user-menu__btn") : null;
  const panel = menu ? menu.querySelector(".admin-user-menu__panel") : null;

  function closeMenu() {
    if (!menuBtn || !panel) return;
    menuBtn.setAttribute("aria-expanded", "false");
    panel.hidden = true;
  }

  function openMenu() {
    if (!menuBtn || !panel) return;
    menuBtn.setAttribute("aria-expanded", "true");
    panel.hidden = false;
  }

  if (menuBtn && panel) {
    menuBtn.addEventListener("click", function (e) {
      e.stopPropagation();
      if (panel.hidden) {
        openMenu();
      } else {
        closeMenu();
      }
    });
    document.addEventListener("click", function (e) {
      if (menu && !menu.contains(e.target)) {
        closeMenu();
      }
    });
  }
})();
