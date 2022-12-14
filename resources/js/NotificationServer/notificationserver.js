//npm install mysql
//npm install mysql-events
//npm install @rodrigogs/mysql-events@0.5.1
//add to my.cnf [mysqld]
//log-bin=mysql-bin

const http 			= require('http');
const mysql 		= require('mysql');
const MySQLEvents 	= require('@rodrigogs/mysql-events');
const net 			= require('net');

var event_result = {};
const hostname = '127.0.0.1';
const port = 3000;


const program = async () => {
  const connection = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'root',
  });

  const instance = new MySQLEvents(connection, {
    startAtEnd: true,
    excludedSchemas: {
      mysql: true,
    },
  });

  await instance.start();


  
  instance.addTrigger({
    name: 'icsolution_db.sys_notifications',
    expression: '*',
    statement: MySQLEvents.STATEMENTS.ALL,
    onEvent: (event) => { // You will receive the events here
      console.log(event.affectedRows);
      event_result = event;
    },
  });
  
  instance.on(MySQLEvents.EVENTS.CONNECTION_ERROR, console.error);
  instance.on(MySQLEvents.EVENTS.ZONGJI_ERROR, console.error);
};

program()
  .then(() => console.log('Waiting for database events...'))
  .catch(console.error);

 