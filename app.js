const express = require('express');

//const express_reload = require('express-reload')
//const path = require('path');
const cookieParser = require('cookie-parser');
const logger = require('morgan');

const indexRouter = require('./routes/index');
const usersRouter = require('./routes/users');
const probeRouter = require('./routes/probe');

const app = express();

const path = __dirname + '\\bin\\www';

//app.use(express_reload(path));
app.use(logger('dev'));
app.use(express.json());
app.use(express.urlencoded({
    extended: false
}));
app.use(cookieParser());


app.use('/', indexRouter);
app.use('/api/v1/user', usersRouter);
app.use('/api/v1/probe', probeRouter);

module.exports = app;