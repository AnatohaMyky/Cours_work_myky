document.addEventListener("DOMContentLoaded", function () {
    const params = new URLSearchParams(window.location.search);
    const newsId = params.get("id");

    // Збережемо попередній URL сторінки, щоб пізніше повернутися до нього
    localStorage.setItem("previousPage", document.referrer);

    // Завантажуємо новини з API
    fetch("http://localhost:3000/api/news")
        .then(response => response.json())
        .then(newsData => {
            console.log("Отримані новини:", newsData); // Лог для перевірки
            const news = newsData.find(n => n.id == newsId);
            
            if (news) {
                // Виводимо дані новини на сторінку
                document.getElementById("news-title").textContent = news.title;
                document.getElementById("news-date").textContent = formatDate(news.date); // Форматуємо дату
                document.getElementById("news-image").src = news.image;
                document.getElementById("news-content").textContent = news.content;

                // Змінюємо посилання для кнопки "Назад до новин" на попередню сторінку
                const backLink = document.querySelector(".news-details-back-link");
                if (backLink) {
                    const previousPage = localStorage.getItem("previousPage");
                    if (previousPage) {
                        backLink.href = previousPage; // Встановлюємо шлях до попередньої сторінки
                    }
                }
            } else {
                document.querySelector(".news-details-container").innerHTML = "<p>Новина не знайдена</p>";
            }
        })
        .catch(error => console.error("Помилка завантаження новини:", error));

    // Функція для форматування дати в зручний формат
    function formatDate(dateString) {
        const date = new Date(dateString);
        const year = date.getFullYear();
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const day = date.getDate().toString().padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
});
