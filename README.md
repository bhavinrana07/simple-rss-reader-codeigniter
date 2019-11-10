# Simple RSS Feed Reader in Codeigniter

Project demonstrates the simple RSS FEED reader along with Codeigniter register and login forms using Codeigniter validations. 
## Installation

- drop the project in your server.
- change the `base_url` in `project/application/config/config.php`. it can be your root directory or a directory in root directory. 

```php
$config['base_url'] = 'http://website.com/your-project-folder/';
```
- create database : `rss_feed` (you can give any name)
- create `users` table 
```sql
CREATE TABLE `users` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`first_name` VARCHAR(45) DEFAULT NULL,
	`last_name` VARCHAR(45) DEFAULT NULL,
	`email` VARCHAR(32) NOT NULL,
	`password` VARCHAR(32) NOT NULL,
	PRIMARY KEY (`id`)
);
```
- database credentials : change the `hostname`, `username`, `password`, `database` in `project/application/config/database.php`. 


## Features

- Feeds Page : Extract data from RSS feed and show it on the page, collecting 10 most repeating words to create a top-menu 
- Register and Login form Fully with `ajax`
- Codeigniter from validator usage with `ajax`
- checking `email` on the fly

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.


## License
[MIT](https://choosealicense.com/licenses/mit/)