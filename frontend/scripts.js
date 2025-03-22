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

document.getElementById("toggleSecondaryNavbar").addEventListener("click", function () {
    let secondaryNavbar = document.getElementById("secondaryNavbar");
    if (secondaryNavbar.classList.contains("show")) {
        secondaryNavbar.classList.remove("show");
    } else {
        secondaryNavbar.classList.add("show");
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const newsContainer = document.getElementById("news-container");
    const prevButton = document.getElementById("prev-page");
    const nextButton = document.getElementById("next-page");
    const pageNumbersContainer = document.querySelector(".pagination");

    let newsData = [];
    let currentPage = 1;
    const itemsPerPage = 4;

    function formatDate(dateString) {
        return dateString.split("T")[0]; // Відрізаємо час, залишаючи тільки "YYYY-MM-DD"
    }

    function displayNews() {
        if (!newsContainer) return;

        const limitedNews = newsData.slice(0, 40); // Лише 40 новин

        newsContainer.innerHTML = "";
        let start = (currentPage - 1) * itemsPerPage;
        let end = start + itemsPerPage;
        let paginatedNews = limitedNews.slice(start, end);

        paginatedNews.forEach(news => {
            const shortDescription = news.description.length > 150
                ? news.description.substring(0, 150) + "..."
                : news.description;
            const formattedDate = formatDate(news.date);

            const newsItem = `
                <div class="news-item d-flex flex-column flex-md-row shadow-sm p-3 rounded">
                    <div class="news-image-container">
                        <img src="${news.image}" alt="Зображення новини" class="news-image img-fluid rounded">
                    </div>
                    <div class="news-content ps-md-3">
                        <h3 class="news-title">
                            <a href="news_details.html?id=${news.id}" class="news-link">${news.title}</a>
                        </h3>
                        <p class="news-meta">
                            <i class="fa fa-calendar"></i> ${formattedDate}
                            <span class="news-category"><i class="fa fa-bookmark"></i> ${news.category}</span>
                        </p>
                        <p class="news-description">${shortDescription}</p>
                        <a href="news_details.html?id=${news.id}" class="news-read-more">Читати далі ></a>
                    </div>
                </div>
            `;
            newsContainer.innerHTML += newsItem;
        });

        const totalPages = Math.ceil(limitedNews.length / itemsPerPage);
        let pagination = '';

        pagination += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${currentPage - 1}">&laquo;</a>
        </li>`;

        for (let i = 1; i <= totalPages; i++) {
            pagination += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>`;
        }

        pagination += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${currentPage + 1}">&raquo;</a>
        </li>`;

        pageNumbersContainer.innerHTML = pagination;

        document.querySelectorAll(".page-link[data-page]").forEach(link => {
            link.addEventListener("click", function (e) {
                e.preventDefault();
                const newPage = parseInt(e.target.getAttribute("data-page"));
                if (newPage > 0 && newPage <= totalPages) {
                    currentPage = newPage;
                    displayNews();
                }
            });
        });

        if (prevButton) prevButton.parentElement.classList.toggle("disabled", currentPage === 1);
        if (nextButton) nextButton.parentElement.classList.toggle("disabled", currentPage === totalPages);
    }

    fetch("../data/news.json")
        .then(response => response.json())
        .then(data => {
            newsData = data;
            displayNews();
        })
        .catch(error => console.error("Помилка завантаження новин:", error));

    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("news-read-more")) {
            sessionStorage.setItem("previousPage", window.location.href);
        }
    });
});
