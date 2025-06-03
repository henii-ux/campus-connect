const mysql = require('mysql');

const connection = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'yourpassword', // Change this
    database: 'testdb'         // Change this
});

connection.connect((err) => {
    if (err) {
        console.error('Error connecting:', err);
        return;
    }
    console.log('Connected to MySQL database!');
});
