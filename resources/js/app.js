/**
 * Optik SaaS — Frontend (Emerald Mint Soft UI)
 * Dynamic behavior: live clock/date, ARIA helpers, and soft toast notifications.
 */

document.addEventListener("DOMContentLoaded", () => {
  // ----- Live clock / date in header -----
  const clockEl = document.getElementById("live-clock");
  const dateEl = document.getElementById("live-date");

  const optionsTime = { hour: "2-digit", minute: "2-digit", hour12: false };
  const optionsDate = {
    weekday: "short",
    day: "numeric",
    month: "short",
    year: "numeric",
  };

  const updateClock = () => {
    const now = new Date();
    if (clockEl) clockEl.textContent = now.toLocaleTimeString("id-ID", optionsTime);
    if (dateEl) dateEl.textContent = now.toLocaleDateString("id-ID", optionsDate);
  };

  updateClock();
  setInterval(updateClock, 1000);

  // ----- Soft toast notifications -----
  window.showToast = ({ message = "", type = "success" } = {}) => {
    const container = document.getElementById("toast-container");
    if (!container) return;

    const toast = document.createElement("div");
    const tone =
      type === "error"
        ? "bg-error text-white border border-error/20"
        : type === "warning"
          ? "bg-warning text-white border border-warning/20"
          : "bg-emerald text-white border border-emerald/20";

    toast.className = `fixed bottom-5 right-5 z-50 px-4 py-3 rounded-xl border text-sm font-semibold shadow-soft transform transition-all duration-300 ${tone} translate-y-10 opacity-0`;
    toast.textContent = message;
    container.appendChild(toast);

    requestAnimationFrame(() => {
      toast.classList.remove("translate-y-10", "opacity-0");
    });

    setTimeout(() => {
      toast.classList.add("translate-y-10", "opacity-0");
      setTimeout(() => toast.remove(), 300);
    }, 4000);
  };
});

