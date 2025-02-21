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

# Patching Release 0.0.1
If you are not downloading the main from scratch, you can patch it using the command:
```shell
php artisan setup:patch
```
This, of course, needs to be done inside the `app` container.

# Challenge Requirements

1. Use Laravel 10 or 11, and Vue.js 3 with Inertia.js.
2. Each page should have its own route and controller.
3. Create all database migrations.
4. Create all models with the correct relationships between tables.
5. Use any libraries you desire for development, UI/UX, etc.
6. Structure and style the web application to your liking using best practices
7. Support the following features,
a. Users
i. Users should be able to sign up, log in, and log out.
1. During sign-up, allow the user to specify if they are a Librarian or a
Customer.
b. Featured Books
i. Display a list of random books that includes the Title, Author, Description,
Cover Image, and Average User Rating of each book.
ii. Users can filter and sort books by Title, Author, and Availability.
c. View Book Details
i. When a user selects a book, they should view the book's complete details
including new fields such as Publisher, Publication Date, Category, ISBN,
Page Count, and Customer Reviews.

d. Search For Books
i. Implement a search functionality that allows users to search for books by
text that is partially contained in the book’s title.

e. Manage Books
i. A librarian can add a new book, edit an existing book, or remove a book
from the library.
f. Book Checkout
i. A customer can check out a book if it is available
1. A book is checked out for 5 days
2. There is only one copy of each book in this library
ii. Only a librarian can mark a book as returned to the library
g. Customer Reviews
i. A customer can leave a review for a book that consists of a short message
and a rating from 1-5 stars.
8. Ensure the Book table includes,
a. Title, Author, Description, Cover Image, Publisher, Publication Date, Category,
ISBN, Page Count
i. Include any other relevant columns as you see fit
