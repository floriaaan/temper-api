var express = require('express');
var router = express.Router();

/* GET home page. */
router.get('/', (req, res) => {
  res.send('ExpressJS version ' + require('express/package').version);
});

module.exports = router;
