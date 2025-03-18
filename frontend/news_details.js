document.addEventListener("DOMContentLoaded", function () {
    const params = new URLSearchParams(window.location.search);
    const newsId = params.get("id");

    fetch("news.json")
        .then(response => response.json())
        .then(newsData => {
            const news = newsData.find(n => n.id == newsId);
            if (news) {
                document.getElementById("news-title").textContent = news.title;
                document.getElementById("news-date").textContent = news.date;
                document.getElementById("news-image").src = news.image;
                document.getElementById("news-content").textContent = news.content;
            } else {
                document.querySelector(".news-details-container").innerHTML = "<p>Новина не знайдена</p>";
            }
        })
        .catch(error => console.error("Помилка завантаження новини:", error));
});
