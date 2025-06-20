document.addEventListener("DOMContentLoaded", () => {
  const loader = document.createElement("div");
  loader.id = "page-loader";
  loader.innerHTML = `<div class="spinner"></div>`;
  document.body.appendChild(loader);

  const style = document.createElement("style");
  style.textContent = `
    #page-loader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: white;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
    }

    .spinner {
      width: 50px;
      height: 50px;
      border: 6px solid white;
      border-top: 6px solid black;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }
  `;
  document.head.appendChild(style);

  window.addEventListener("load", () => {
    const loader = document.getElementById("page-loader");
    if (loader) {
      loader.style.opacity = "0";
      loader.style.transition = "opacity 0.7s ease";
      setTimeout(() => loader.remove(), 1200);
    }
  });
});
