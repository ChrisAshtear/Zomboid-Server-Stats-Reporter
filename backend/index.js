const express = require('express');
const mysql = require('mysql2');
const cors = require('cors');

const https = require('https');
const http = require('http');

const fs = require('fs');

const db = mysql.createPool({
  host: 'hostname', // the host name MYSQL_DATABASE: node_mysql
  user: 'username', // database user MYSQL_USER: MYSQL_USER
  password: 'password', // database user password MYSQL_PASSWORD: MYSQL_PASSWORD
  database: 'Zombo' // database name MYSQL_HOST_IP: mysql_db
})

const app = express();
app.use(cors())

app.use(express.json())
app.use(express.urlencoded({ extended: true }));

app.get('/', (req, res) => {
  res.send('Hi There')
});

app.get('/getplayers', (req, res) => {
  const SelectQuery = " SELECT * FROM Zombo.Players";
  db.query(SelectQuery, (err, result) => {
    res.send(result)
  })
})

app.get('/getserver', (req, res) => {
  const SelectQuery = " SELECT * FROM Zombo.Game";
  db.query(SelectQuery, (err, result) => {
    res.send(result)
  })
})

const httpsServer = https.createServer({
  key: fs.readFileSync('privkey1.pem'),
  cert: fs.readFileSync('fullchain1.pem'),
}, app);

httpsServer.listen(3001, () => {
    console.log('HTTPS Server running on port 3001');
});