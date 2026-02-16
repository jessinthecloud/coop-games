Rewrite of [Build a Video Game Aggregator](https://laracasts.com/series/build-a-video-game-aggregator)

## Running in Docker

- Boot Docker and start a container
  - make sure docker is using WSL and the correct Linux distro:
    - Docker -> settings -> resources -> WSL integration -> Enable integration with additional distros
- Open windows terminal with a Linux tab
  - To open a new tab using Linux, use the caret symbol next to the `+`
- `cd` to the working directory you want
  - for example from `/mnt/c/Users/Jess/Documents/` to `/mnt/g/Jess/Code`
  - Access the current folder via Windows with `explorer.exe .`
- run the [Laravel install command](https://laravel.com/docs/8.x/installation#getting-started-on-windows)
- run the command given to start Laravel Sail
  - For instance, `cd coop-games && ./vendor/bin/sail up`
  - > The first time you run the Sail up command, Sail's application containers will be built on your machine. 
    > This could take several minutes. Don't worry, subsequent attempts to start Sail will be much faster.
  - Once the application's Docker containers have been started, you can access the application in your web browser at: http://localhost


