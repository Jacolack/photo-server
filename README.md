# L.A.M.P. Photo Server

Installation:
1. Get Linux. I used [Raspbian](https://www.raspbian.org/).
2. Get [Apache](https://httpd.apache.org/).
3. Get [MySQL](https://www.mysql.com/).
4. Get [PHP](https://www.php.net/).

5. Now, create two tables in your mysql database:

```sql
create table photos(
    id MEDIUMINT NOT NULL AUTO_INCREMENT,
    tags VARCHAR(1100) NOT NULL,
    parent MEDIUMINT NOT NULL,
    fileType CHAR(32) NOT NULL,
    uploaded DATETIME NOT NULL,
    PRIMARY KEY ( id )
);

create table folders(
    id INT NOT NULL AUTO_INCREMENT,
    name CHAR(32) NOT NULL,
    parent MEDIUMINT NOT NULL,
    created DATETIME NOT NULL,
    PRIMARY KEY ( id )
);
```

6. In your Apache root public folder, clone this repository.
7. Add a directory called images.
8. Copy assets/EXAMPLE_hidden.php to assets/hidden.php and add your credentials.


That's it! 
