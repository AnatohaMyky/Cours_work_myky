// document.addEventListener("DOMContentLoaded", function () {
//     const elements = document.querySelectorAll('.fade-in');
//     elements.forEach((el, index) => {
//         el.classList.add(`delay-${index + 1}`);
//     });
// });

// Показати стрілочку при прокрутці вниз
window.addEventListener('scroll', function () {
    const backToTopButton = document.querySelector('.back-to-top');
    if (window.scrollY > 300) {
        backToTopButton.style.display = 'block';
    } else {
        backToTopButton.style.display = 'none';
    }
});

// Прокрутка до верху сторінки при натисканні на стрілочку
document.querySelector('.back-to-top').addEventListener('click', function (event) {
    event.preventDefault();
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});

document.addEventListener("DOMContentLoaded", function () {
    // Функція копіювання тексту
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            showTooltip("Скопійовано!");
        }).catch(err => console.error("Помилка копіювання: ", err));
    }

    // Показ сповіщення про копіювання
    function showTooltip(message) {
        const tooltip = document.createElement("div");
        tooltip.className = "custom-tooltip";
        tooltip.innerText = message;
        document.body.appendChild(tooltip);

        setTimeout(() => {
            tooltip.classList.add("show");
            setTimeout(() => {
                tooltip.classList.remove("show");
                setTimeout(() => document.body.removeChild(tooltip), 300);
            }, 1500);
        }, 10);
    }

    // Обробник натискання для копіювання тексту
    document.querySelectorAll(".copy-text").forEach(element => {
        element.addEventListener("click", function () {
            const textToCopy = this.getAttribute("data-copy");
            copyToClipboard(textToCopy);
        });
    });

    // Обробник натискання для відкриття Google Maps
    document.querySelector(".map-link").addEventListener("click", function () {
        const address = encodeURIComponent(this.getAttribute("data-address"));
        window.open(`https://www.google.com/maps/search/?api=1&query=${address}`, "_blank");
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const themeToggleBtn = document.getElementById("theme-toggle");
    const html = document.documentElement; // Змінюємо body на html

    function updateThemeIcon(isDark) {
        themeToggleBtn.textContent = isDark ? "🌙" : "☀️";
    }

    // Перевіряємо стан теми при завантаженні
    let isDark = localStorage.getItem("theme") === "dark";
    html.classList.toggle("dark-mode", isDark);
    updateThemeIcon(isDark);

    // Обробник кліку для перемикання теми
    themeToggleBtn.addEventListener("click", function () {
        isDark = !isDark;
        html.classList.toggle("dark-mode", isDark);
        localStorage.setItem("theme", isDark ? "dark" : "light");
        updateThemeIcon(isDark);
    });
});

// Додаємо класи перед завантаженням сторінки, щоб уникнути "спалаху"
(function () {
    if (localStorage.getItem("grayscale-active") === "true") {
        document.body.classList.add("grayscale-active");
    }
    if (localStorage.getItem("dyslexiaMode") === "enabled") {
        document.body.classList.add("dyslexia-mode");
    }
})();

document.addEventListener("DOMContentLoaded", function () {
    const body = document.body;
    const increaseFontBtn = document.getElementById("increase-font");
    const decreaseFontBtn = document.getElementById("decrease-font");
    const resetFontBtn = document.getElementById("reset-font");
    const grayscaleToggleBtn = document.getElementById("toggle-grayscale");
    const dyslexiaButton = document.getElementById("toggle-dyslexia-font");

    let currentFontSize = parseFloat(localStorage.getItem("font-size")) || 100;
    const minFontSize = 80;
    const maxFontSize = 120;

    function applyFontSize(size) {
        body.style.fontSize = `${size}%`;
        localStorage.setItem("font-size", size);
    }

    increaseFontBtn.addEventListener("click", () => {
        if (currentFontSize < maxFontSize) {
            currentFontSize += 10;
            applyFontSize(currentFontSize);
        }
    });

    decreaseFontBtn.addEventListener("click", () => {
        if (currentFontSize > minFontSize) {
            currentFontSize -= 10;
            applyFontSize(currentFontSize);
        }
    });

    resetFontBtn.addEventListener("click", () => {
        currentFontSize = 100;
        applyFontSize(currentFontSize);
    });

    let isGrayscale = localStorage.getItem("grayscale-active") === "true";
    body.classList.toggle("grayscale-active", isGrayscale);

    grayscaleToggleBtn.addEventListener("click", function () {
        isGrayscale = !isGrayscale;
        body.classList.toggle("grayscale-active", isGrayscale);
        localStorage.setItem("grayscale-active", isGrayscale ? "true" : "false");
    });

    let isDyslexiaMode = localStorage.getItem("dyslexiaMode") === "enabled";
    body.classList.toggle("dyslexia-mode", isDyslexiaMode);
    dyslexiaButton.classList.toggle("active", isDyslexiaMode);

    dyslexiaButton.addEventListener("click", function () {
        isDyslexiaMode = !isDyslexiaMode;
        body.classList.toggle("dyslexia-mode", isDyslexiaMode);
        dyslexiaButton.classList.toggle("active", isDyslexiaMode);
        localStorage.setItem("dyslexiaMode", isDyslexiaMode ? "enabled" : "disabled");
    });

    applyFontSize(currentFontSize);
});

document.addEventListener("DOMContentLoaded", function () {
    const specialFeaturesToggle = document.getElementById("specialFeaturesToggle");
    const specialFeaturesDropdown = document.getElementById("specialFeaturesDropdown");

    // Відкривати меню при наведенні
    specialFeaturesToggle.addEventListener("mouseenter", function () {
        specialFeaturesDropdown.classList.add("show");
    });

    // Закривати меню при виході мишки з кнопки або самого меню
    specialFeaturesToggle.addEventListener("mouseleave", function () {
        setTimeout(() => {
            if (!specialFeaturesDropdown.matches(":hover")) {
                specialFeaturesDropdown.classList.remove("show");
            }
        }, 200);
    });

    specialFeaturesDropdown.addEventListener("mouseleave", function () {
        specialFeaturesDropdown.classList.remove("show");
    });

    // Переконуємося, що меню залишається відкритим, якщо курсор над ним
    specialFeaturesDropdown.addEventListener("mouseenter", function () {
        specialFeaturesDropdown.classList.add("show");
    });
});

document.getElementById("toggleSecondaryNavbar").addEventListener("click", function () {
    let secondaryNavbar = document.getElementById("secondaryNavbar");
    if (secondaryNavbar.classList.contains("show")) {
        secondaryNavbar.classList.remove("show");
    } else {
        secondaryNavbar.classList.add("show");
    }
});
