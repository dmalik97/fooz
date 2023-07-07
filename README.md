How to get started with docker-compose.yml

1. Run command in terminal 'docker-compose up -d'
2. Install database:
   - docker exec -i wordpress_db mysql -u wordpress_user -pwordpress_password wordpress < ./database/database.sql
   
How to login do dasbord:
1. Open browser
2. Enter url localhost:8080/wp-admin
3. Login:
   - Username: fooz / e-mail: developer@foozagency.com
   - Password: password123.
