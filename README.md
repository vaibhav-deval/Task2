```markdown
# Task 3: Enhanced CRUD Application

## Overview
This repository contains the source code for **Task 3** of my internship at ApexPlanet Software Pvt Ltd. Building on Task 2, this project is an enhanced web-based CRUD (Create, Read, Update, Delete) application developed using PHP, MySQL, and HTML, styled with Tailwind CSS, and hosted locally with WAMP Server. It includes advanced features such as user authentication (registration, login, logout), CRUD operations for personal posts, a public posts section for read-only access, search functionality, and pagination, demonstrating an evolution in functionality and design.

## Features
- **User Authentication**:
  - User registration with secure password hashing.
  - Login and logout functionality with session management.
  - Restricted access to private post management.
- **CRUD Operations**:
  - Create: Add new posts with title and content.
  - Read: View personal posts and public posts from all users.
  - Update: Edit existing personal posts.
  - Delete: Remove personal posts.
- **Public Posts Section**:
  - Read-only display of all posts with author names on the right side.
- **Search Functionality**:
  - Filter posts by title in both personal and public sections for efficient navigation.
- **Pagination**:
  - Navigate through posts with 5 per page in both sections to manage large datasets.
- **Security**:
  - SQL injection prevention with `real_escape_string()`.
  - XSS prevention with `htmlspecialchars()`.
  - User-specific post ownership checks.

## Technologies Used
- **Backend**: PHP 8.x (via WAMP Server)
- **Database**: MySQL (managed with phpMyAdmin)
- **Frontend**: HTML, Tailwind CSS
- **Tools**: WAMP Server, Visual Studio Code, Git, GitHub

## Installation and Setup
1. **Prerequisites**:
   - Install [WAMP Server](https://www.wampserver.com/) (Windows Apache MySQL PHP).
   - Ensure PHP and MySQL services are running (green tray icon).
2. **Clone the Repository**:
   - `git clone https://github.com/vaibhav-deval/Task3.git` (replace with your repo URL).
3. **Configure the Project**:
   - Copy the `Task3` folder to `C:\wamp64\www\`.
   - Access via `http://localhost:8080/Task3/` (use port 8080 if configured).
4. **Database Setup**:
   - Open `http://localhost:8080/phpmyadmin`.
   - Create a database named `blog`.
   - Import or manually create the tables using the schema below.
5. **Run the Application**:
   - Register a new user, log in, and start managing posts with search and pagination.

## Database Schema
```markdown
# Database Schema for Blog
- **users**:
  - `id` (INT, PK, AUTO_INCREMENT)
  - `username` (VARCHAR(50), NOT NULL, UNIQUE)
  - `password` (VARCHAR(255), NOT NULL)
- **posts**:
  - `id` (INT, PK, AUTO_INCREMENT)
  - `title` (VARCHAR(255), NOT NULL)
  - `content` (TEXT, NOT NULL)
  - `created_at` (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
  - `user_id` (INT, NOT NULL, INDEX, FOREIGN KEY to users.id)
```

## Files
- `index.php`: Main dashboard with CRUD operations, logout, public posts, search, and pagination.
- `login.php`: User login form and authentication.
- `register.php`: User registration form and logic.
- `README.md`: This documentation.
- `schema.md`: Detailed database schema (optional separate file).

## Usage
1. **Register**: Visit `register.php` to create a new account.
2. **Login**: Use `login.php` with your credentials to access the dashboard.
3. **Manage Posts**: Add, edit, or delete your posts on `index.php`, using the search bar to filter and pagination to navigate.
4. **View Public Posts**: See all users' posts in the right-side section, with search and pagination for convenience.

## Screenshots

- Registration Page
- Login Page
- Dashboard (Your Posts with Search/Pagination and Public Posts)

## Demo
- [GitHub Repository](https://github.com/vaibhav-deval/Task3)  
- [LinkedIn Post with Video Demo](https://www.linkedin.com/in/yourprofile/post-url) *(Update with your actual video link after uploading)*

## Challenges and Solutions
- **Challenge**: Posts from all users were visible to everyone initially.
  - **Solution**: Added `user_id` to the `posts` table and filtered queries to show only the logged-in user’s posts, with a separate public posts query.
- **Challenge**: "Add Post" button was missing in early versions.
  - **Solution**: Ensured proper form structure and added debug checks to confirm rendering.
- **Challenge**: Managing large post datasets.
  - **Solution**: Implemented search and pagination to improve usability and performance.

## Future Improvements
- Add a dedicated CSS file for custom Tailwind configurations.
- Implement input validation and error handling with try-catch blocks.
- Enhance pagination with page number links (e.g., 1, 2, 3) for direct navigation.
- Add sorting options for posts by date or title.

## Acknowledgments
- Thanks to ApexPlanet Software Pvt Ltd for this internship opportunity and guidance.
- Appreciation to the open-source community for tools like Tailwind CSS.

## License
This project is for educational purposes only and is not licensed for commercial use.

---
```

### Key Updates
- **Title and Overview**: Changed to "Task 3: Enhanced CRUD Application" and updated the overview to reflect the evolution from Task 2, mentioning new features (search, pagination) and Tailwind CSS.
- **Features**: Added "Search Functionality" and "Pagination" sections to highlight the enhancements.
- **Technologies Used**: Updated to specify Tailwind CSS as the frontend styling solution.
- **Usage**: Included instructions for using search and pagination.
- **Challenges and Solutions**: Added the challenge of managing large datasets and its solution (search and pagination).
- **Future Improvements**: Adjusted to include Tailwind customization and enhanced pagination options.
- **Consistency**: Ensured all sections align with the updated functionality and design approach.

### Steps to Implement
1. **Create or Update `README.md`**:
   - If starting Task 3 in a new `Task3` directory, create `README.md` there.
   - If continuing in `Task2`, rename the directory to `Task3` and update the repo URL.
   - Copy the above content into `README.md`.
2. **Update Links**:
   - Replace the GitHub URL with your actual Task 3 repo (`https://github.com/vaibhav-deval/Task3`).
   - Update the LinkedIn link after posting your new video demo.
3. **Add Screenshots**:
   - Capture updated pages (e.g., search in action, pagination navigation) and upload or link them.
4. **Commit and Push**:
   ```bash
   git add README.md
   git commit -m "Updated README.md for Task 3 with new features and Tailwind CSS"
   git push
   ```
5. **Submit**:
   - Provide the GitHub link and LinkedIn video link to ApexPlanet as required.

This README showcases your progress and technical growth for Task 3. Let me know if you’d like to refine any section or need help with the transition!