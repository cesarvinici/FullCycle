const express = require('express')
const mysql = require('mysql')


const app = express();
const port = 3000;

const config = {
    host: 'db',
    user: 'root',
    password: 'admin@123',
    database: 'nodedb'
};

const connection = mysql.createConnection(config)

const sql = 'INSERT into people(name) values ("Cesar")';
connection.query(sql);
connection.end();


app.get('/', (req, res) => {
    res.send('<h1>FullCycle</h1>')
});

app.listen(port, () => {
    console.log(`Rodando na porta ${port}`)
})