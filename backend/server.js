const express = require('express');
const cors = require('cors');
const db = require('./db');

const app = express();
const PORT = 3000;

app.use(cors());
app.use(express.json());

app.get('/', (req, res) => {
    res.send('Сервер працює! API доступне на /api/news');
});


// Отримання всіх новин
app.get('/api/news', async (req, res) => {
    try {
        const [rows] = await db.query('SELECT * FROM news');
        res.json(rows);
    } catch (error) {
        console.error('Помилка при отриманні новин:', error);
        res.status(500).send('Помилка сервера');
    }
});

app.listen(PORT, () => {
    console.log(`Сервер запущено на http://localhost:${PORT}`);
});
