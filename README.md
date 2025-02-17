# Backend Documentation 

## 📚 Documentation

This documentation will guide you through setting up and running the updated backend after upgrading Laravel from version 8 to 9, and then from 9 to 10. It also explains the article revision feature implemented in the backend.

---

## 📌 1. Setting Up and Running the Upgraded Backend


### 🚀 Requirements

- **PHP 8.1 or higher**
- **Composer** (for PHP dependency management)
- **Sqlite** (for database)
- **Node.js** (for front-end dependency management)

### 📥 Installation Steps
1. **Clone the Repository**
    ```bash
    git clone https://github.com/Khaled-Amin/laravel-realworld-task.git
    cd laravel-realworld-task
    ```
    *Navigate to the Project Directory**
    ```bash
    cd laravel-realworld-task
    ```
    *Install PHP Dependencies**
    ```bash
    composer install
    ```
    *Install Node.js Dependencies**
    ```bash
    npm install
    ```
    *Set up the environment:**
    ```bash
    cp .env.example .env
    ```
    *Generate an application key:**
    ```bash
    php artisan key:generate
    ```
    *Run the database migrations:**
    ```bash
    php artisan migrate
    ```
    *Start the development
    ```bash
    php artisan serve
    ```
 2. Article Revision Feature
    The article revision feature allows for tracking changes made to articles. When an article is updated, a new revision is created instead of directly modifying the article. Each revision stores the article’s previous state, including:
    - Title
    - Description
    - Body
    - User who made the Change (user_id)
    - Creation date
    🔄 Reverting to a Specific Revision
    To revert to a specific revision, you can use the following command:
    ```bash
    put /api/articles/{id}/revisions/{revision_id}/revert
    ```
    This will revert the article to the state of the specified revision.
    
    🎯 Assumptions and Design Decisions :)
    - Automatic Create of Revisions: Each time an article is updated, a revision is automatically created. No manual    intervention is needed.
    - Article Restoration: Any user with the necessary permissions (e.g., article author or admin) can revert an article to a previous revision.
    - No Deletion of Revisions: Revisions are not deleted. They are preserved for tracking and audit purposes.
    - Revision Storage: Each revision stores:
        *Article title
        *Article description
        *Article body
        *The user who made the update (user_id)
        * created and updated (Timestamps)

## 📌 5. Automated Tests for Revert Revision Feature
The backend includes automated tests to ensure the correct functionality of the revert revision feature. These tests cover various scenarios and edge cases to ensure the feature works as expected.
### 📋 Test Scenarios
### Key Point for exec test:
Mentions where the tests are located (`tests/Feature/ArticleRevisionTest.php`).

- **Revert to a Specific Revision:** Test the ability to revert an article to a specific revision.
- **List of Revisions:** Verify that the list of revisions for an article is correctly displayed.
- **Display Revision Details:** Ensure that the details of a specific revision are displayed correctly.
- **Ensure Proper Authorization:** Test that only authorized users (e.g., article authors or admins) can revert articles.

## 📌 6. Running Tests
To run the automated tests, use the following command:
```bash
php artisan test
```

## 📌 7. Conclusion
The updated backend provides a robust and efficient solution for managing articles, revisions, and user authentication. The article revision feature allows for tracking changes and reverting to previous states. The automated tests ensure the correct functionality of the revert revision feature.
---




