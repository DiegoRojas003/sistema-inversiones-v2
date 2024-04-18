const express = require('express');
const morgan = require('morgan');
const path = require('path');
const app = express();
const mysql =require('mysql');
const myConnection = require('express-myconnection');

//Importando rutas



//Static Files


//SETTINGS

app.set('port', process.env.PORT || 5000);
app.set('view engine','ejs');
app.set('views',path.join(__dirname,'views'));


//middlewards

app.use(morgan('dev'));
app.use(myConnection(mysql,{
    host: 'localhost',
    user: 'root',
    password: '1234',
    port: 3306,
    database:'sistemainversiones'
}, 'single'));

//routers


//Iniciando el servidor
app.listen(app.get('port'),()=>{
    console.log('Server on port 5000');
});