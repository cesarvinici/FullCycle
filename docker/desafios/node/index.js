const axios = require('axios')
const express = require('express')
const mysql = require('mysql')


const mysqlConfig = {
    host: 'db',
    user: 'root',
    password: 'admin@123',
    database: 'nodedb'
};

function newConnection() {
    return mysql.createConnection(mysqlConfig);
}


async function createTable() {
    const sql = 'CREATE TABLE IF NOT EXISTS people (id integer auto_increment primary key, name varchar(255))';
    const connection = newConnection();
    connection.query(sql);
    connection.end();
}

async function getNameFromApi() {
    const response = await axios.get('https://gerador-nomes.wolan.net/nomes/1');
    return response.data
}

async function saveNewNameOnDatabase() {
    const name = await getNameFromApi();

    const connection = newConnection()
    const sql = `INSERT into people(name) values ("${name}")`;
    connection.query(sql);
    connection.end();
}

const app = express();
port = 3000;

app.get('/', async (req, res) => {

    await createTable();

    await saveNewNameOnDatabase();

    const connection = newConnection();

    const sql = `SELECT name from people`;
    connection.query(sql, async function (error, results, fields) {
        if (error) throw error;

        
        let htmlText = "<ul>"
        results.forEach(name => {
            htmlText += `<li>${name.name}</li>`
        });
        htmlText += "</ul>";
        res.send(`<h1>FullCycle</h1><br>${htmlText}`)
    })
    connection.end();

});


app.listen(port, () => {
    console.log(`Rodando na porta ${port}`)
})