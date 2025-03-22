document.addEventListener("DOMContentLoaded", function () {
    const params = new URLSearchParams(window.location.search);
    const newsId = params.get("id");

    fetch("news.json")
        .then(response => response.json())
        .then(newsData => {
            console.log("Отримані новини:", newsData);
            const news = newsData.find(n => n.id == newsId);

            if (news) {
                document.getElementById("news-title").textContent = news.title;
                document.getElementById("news-date").textContent = formatDate(news.date);
                document.getElementById("news-image").src = news.image;
                document.getElementById("news-content").textContent = news.content;
            } else {
                document.querySelector(".news-details-container").innerHTML = "<p>Новина не знайдена</p>";
            }
        })
        .catch(error => console.error("Помилка завантаження новини:", error));

    const backButton = document.querySelector(".news-details-back-link");
    if (backButton) {
        const previousPage = sessionStorage.getItem("previousPage");
        backButton.href = previousPage ? previousPage : "news.html";
    }

    // Функція для форматування дати (вилучає зайві дані)
    function formatDate(dateString) {
        return dateString.split("T")[0]; // Відрізаємо час, залишаючи лише "YYYY-MM-DD"
    }
});
