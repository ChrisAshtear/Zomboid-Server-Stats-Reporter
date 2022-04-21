const express = require('express');
const mysql = require('mysql2');
const cors = require('cors');

const https = require('https');
const http = require('http');

const fs = require('fs');

const db = mysql.createPool({
  host: process.env.SQL_HOST, 
  port: process.env.SQL_PORT || 3306, 
  user: process.env.SQL_USER, 
  password: process.env.SQL_PASS,
  database: 'Zombo' 
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

const httpServer = http.createServer(app);
httpServer.listen(process.env.API_PORT, () => {
    console.log('HTTP Server running on port '+process.env.API_PORT);
});
