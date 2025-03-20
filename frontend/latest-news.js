document.addEventListener("DOMContentLoaded", function () {
    const newsContainer = document.querySelector(".news-container"); // Шукаємо контейнер для новин

    // Завантажуємо останні 4 новини з API
    fetch('http://localhost:3000/api/news?limit=4') // Запит з параметром для обмеження результатів
        .then(response => response.json())
        .then(data => {
            console.log("Отримані новини:", data);
            if (Array.isArray(data) && data.length > 0) {
                renderLatestNews(data); // Викликаємо функцію для відображення новин
            } else {
                console.error("Помилка: API не повернуло новини.");
            }
        })
        .catch(error => console.error('Помилка завантаження новин:', error));

    // Функція для відображення новин
    function renderLatestNews(news) {
        if (!newsContainer) return;

        // Сортуємо новини від новіших до старіших (за id)
        news.sort((a, b) => b.id - a.id);

        newsContainer.innerHTML = ''; // Очищаємо контейнер перед заповненням

        // Обмежуємо кількість новин до 4
        const limitedNews = news.slice(0, 4);

        limitedNews.forEach(item => {
            const newsItem = `
                <div class="news-item">
                    <img class="news-img" src="${item.image}" alt="${item.title}">
                    <div class="news-content">
                        <p>${item.description}</p>
                        <a href="news_details.html?id=${item.id}">Детальніше</a>
                    </div>
                </div>
            `;
            newsContainer.innerHTML += newsItem; // Додаємо новину в контейнер
        });
    }
});
