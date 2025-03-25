document.addEventListener("DOMContentLoaded", function () {
    const newsContainer = document.querySelector(".news-container");

    // Виконати запит до PHP для отримання новин із бази даних
    fetch("../backend/fetch_news.php")
        .then(response => response.json())
        .then(data => {
            console.log("Отримані новини:", data);
            if (Array.isArray(data) && data.length > 0) {
                renderLatestNews(data);
            } else {
                console.error("Помилка: База даних не містить новин.");
            }
        })
        .catch(error => console.error('Помилка завантаження новин:', error));

    // Функція для форматування дати
    function formatDate(dateString) {
        return dateString.split("T")[0]; // Відрізаємо все після "T"
    }

    // Функція для відображення новин
    function renderLatestNews(news) {
        if (!newsContainer) return;

        newsContainer.innerHTML = '';

        const limitedNews = news.slice(0, 4); // Вивести лише 4 новини

        limitedNews.forEach(item => {
            const newsItem = `
<div class="news-item">
    <img class="news-img" src="${item.image}" alt="${item.title}">
    <div class="news-content">
        <p>${item.description}</p>
        <p class="news-meta"><i class="fa fa-calendar"></i> ${formatDate(item.date)}</p>
        <a href="${item.social_link}" class="news-link" target="_blank">Детальніше</a>
    </div>
</div>

            `;
            newsContainer.innerHTML += newsItem;
        });

        document.querySelectorAll(".news-link").forEach(link => {
            link.addEventListener("click", function () {
                sessionStorage.setItem("previousPage", window.location.href);
            });
        });
    }
});
