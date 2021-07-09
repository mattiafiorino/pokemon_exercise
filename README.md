## How to run
	Exec these commands in the exact order in order to start the service :
	
	* git clone https://github.com/mattiafiorino/pokemon_exercise.git
	* docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d
	* docker exec -i snce-docker-php-fpm php web/bin/console doctrine:migrations:migrate
	
	Once you have executed these commands you can find the main page of the web application at the address http://127.0.0.1 on the web browser.
	The main page is only a summary of the exposed features of the application.
	At the "create" page you can create a new pokemon team selecting a name and adding as many random pokemon as you want.
	At the "list" page you can check all the created teams and their main stats. You can also access to the "update" interface for the selected team or "delete" a tema.
	At the "update" page you can modify the name of a team or add new random pokemon.
	
	Exercise by Mattia Fiorino.
	  