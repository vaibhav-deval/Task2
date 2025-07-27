```markdown
# Task 2: Basic CRUD Application

## Overview
This repository contains the source code for **Task 2** of my internship at ApexPlanet Software Pvt Ltd. The project is a web-based CRUD (Create, Read, Update, Delete) application built using PHP, MySQL, and HTML/CSS, hosted locally with WAMP Server. It includes user authentication (registration, login, logout) and a public posts section for read-only access, fulfilling the internship's objectives.

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
- **Security**:
  - SQL injection prevention with `real_escape_string()`.
  - XSS prevention with `htmlspecialchars()`.
  - User-specific post ownership checks.

## Technologies Used
- **Backend**: PHP 8.x (via WAMP Server)
- **Database**: MySQL (managed with phpMyAdmin)
- **Frontend**: HTML, CSS (inline styling with flexbox layout)
- **Tools**: WAMP Server, Visual Studio Code, Git, GitHub

## Installation and Setup
1. **Prerequisites**:
   - Install [WAMP Server](https://www.wampserver.com/) (Windows Apache MySQL PHP).
   - Ensure PHP and MySQL services are running (green tray icon).
2. **Clone the Repository**:
   - `git clone https://github.com/vaibhav-deval/Task2.git` (replace with your repo URL).
3. **Configure the Project**:
   - Copy the `Task2` folder to `C:\wamp64\www\`.
   - Access via `http://localhost:8080/Task2/` (use port 8080 if configured).
4. **Database Setup**:
   - Open `http://localhost:8080/phpmyadmin`.
   - Create a database named `blog`.
   - Import or manually create the tables using the schema below.
5. **Run the Application**:
   - Register a new user, log in, and start managing posts.

## Database Schema

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
- `index.php`: Main dashboard with CRUD operations, logout, and public posts.
- `register.php`: User registration form and logic.
- `login.php`: User login form and authentication.
- `README.md`: This documentation.
- `schema.md`: Detailed database schema (optional separate file).

## Usage
1. **Register**: Visit `register.php` to create a new account.
2. **Login**: Use `login.php` with your credentials to access the dashboard.
3. **Manage Posts**: Add, edit, or delete your posts on `index.php`.
4. **View Public Posts**: See all users' posts in the right-side section (read-only).

## Screenshots
*(Add screenshots of the interface after testing—e.g., registration page, dashboard with posts, public posts section. Upload to GitHub or link externally.)*
- Registration Page
- <img width="1101" height="467" alt="image" src="https://github.com/user-attachments/assets/32234312-97b8-4a95-a8fe-b52d23acec6b" />

- Login Page
- <img width="846" height="395" alt="image" src="https://github.com/user-attachments/assets/61247751-6aeb-40f5-ab94-f3d8618a6c71" />

- Dashboard (Your Posts and Public Posts)
- <img width="1331" height="614" alt="image" src="https://github.com/user-attachments/assets/aadbfb01-d7bb-4992-98e1-77db8888f9e6" />


## Demo
- [GitHub Repository](https://github.com/vaibhav-deval/Task2)  
- [LinkedIn Post with Video Demo](https://www.linkedin.com/in/vaibhavdeval004/) *(Update with your actual link after posting)*

## Challenges and Solutions
- **Challenge**: Posts from all users were visible to everyone initially.
  - **Solution**: Added `user_id` to the `posts` table and filtered queries to show only the logged-in user’s posts, with a separate public posts query.
- **Challenge**: "Add Post" button was missing.
  - **Solution**: Ensured proper form structure and added debug checks to confirm rendering.

## Future Improvements
- Add CSS file for better styling and maintainability.
- Implement input validation and error handling with try-catch.
- Add pagination for the public posts section if the number grows large.

## Acknowledgments
- Thanks to ApexPlanet Software Pvt Ltd for this internship opportunity.
- Gratitude to the xAI community and tools for support during development.

## License
This project is for educational purposes only and is not licensed for commercial use.

---
```
