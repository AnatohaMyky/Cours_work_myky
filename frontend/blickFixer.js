// Description: This file contains the JavaScript code for the BlickFixer extension.
(function () {
    let theme = localStorage.getItem("theme");
    if (theme === "dark") {
        document.documentElement.classList.add("dark-mode");
    }
})();

(function () {
    if (localStorage.getItem("grayscale-active") === "true") {
        document.documentElement.classList.add("grayscale-active");
    }
})();
