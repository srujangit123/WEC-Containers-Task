const express = require("express");
const { Pool } = require("pg");
const HOST = '0.0.0.0';
const PORT = 3000;

const app = express();
const pool = new Pool({ user: 'postgres', host: 'db', password: 'password', database: 'tempdb', port: '5432' });

// const pool = new Pool({ user: 'srujan', password: "srujan@nitkcse", host: 'localhost', database: 'tempdb' });
app.get('/', (req, res) => {
    pool.query('SELECT * FROM apparel', (error, response) => {
        console.log(response.rows);
        console.log(typeof response.rows);
        res.json(response.rows);
    });
});

app.listen(PORT, HOST);
console.log(`Running on http://${HOST}:${PORT}`);