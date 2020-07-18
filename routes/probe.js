const express = require('express');
const router = express.Router();
const connection = require('../bin/database');

/* GET home page. */
router.get('/:id', (req, res) => {
  res.send(req.params.id);
});

router.post('/', (req, res) => {
  const post = {
    name: req.body.name ? req.body.name : null,
    user: req.body.user,
    category: req.body.category ? req.body.category : null,
    gps_lon: req.body.gps_lon ? req.body.gps_lon : null,
    gps_lat: req.body.gps_lat ? req.body.gps_lat : null,
  };
  connection.connect();
  connection.query('INSERT INTO probes (`name`, `user`, `category`, `gps_lon`, `gps_lat`, `state`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, ?, 1, ?, ?)',
    [post.name, post.user, post.category, post.gps_lon, post.gps_lat, new Date().toISOString().slice(0, 19).replace('T', ' '), new Date().toISOString().slice(0, 19).replace('T', ' ')],
    (error, results, fields) => {
      if (error) {
        res.send({
          response: null,
          error: error
        });
      }
      res.send({
        response: results,
        error: null
      })

    });
  connection.end();
})

module.exports = router;