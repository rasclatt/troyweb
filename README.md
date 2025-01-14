# Instructions to Get This Project Running

1. **Download/Install Docker**

2. **Open your terminal and navigate to the root project folder**

3. **Build the Docker containers**
   ```sh
   docker-compose build
   ```

4. **Start the Docker containers**
   ```sh
   docker-compose up -d
   ```

   The Docker setup will build all the required assets for the project, which include:
   - SQLite
   - Apache2
   - PHP/Laravel
   - Node

5. **Optional: Start fresh with a new database**
   ```sh
   docker-compose exec app bash
   ```

6. **Run the reset command inside the app container**
   ```sh
   php artisan setup:first-run
   ```

   This will rollback the database, install the tables again, seed the tables, and clear the cache.

7. **Access the application with the following admin credentials:**
   - **Username:** admin@example.com
   - **Password:** admin
  
8. It's important to note that the `.env.public` should be renamed to just `.env`. It contains all localhost settings for the project.