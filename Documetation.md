# Bionic Recruitment System Plugin  

## Overview
The **Bionic Recruitment System** is a WordPress plugin designed to handle job application submissions with a multi-step form and an admin interface for managing data. It uses AJAX for seamless form submission and WPDB for database integration, ensuring security through input sanitization and validation.

## Key Features
- **Multi-Step Form**:
  - **Step 1**: Personal details (name, email, phone, about)
  - **Step 2**: NID, certificate number, CV upload (PDF, JPG, DOC, PNG)
  - Validations for email, phone, NID, certificate, and file formats.
  
- **Admin Interface**:
  - **Roles & Permissions**: Editors/Admins can view, update, delete, and filter entries. Lower roles can only view entries.
  - **Table**: View entries in a sortable, paginated table with filtering options.

- **Database Integration**: Custom table in the WordPress database with fields for personal details, NID, certificate number, CV file path, and creation date.

## Installation
1. **Upload** the plugin ZIP via WordPress admin under **Plugins** > **Add New** > **Upload Plugin**.
2. **Activate** the plugin. The database table is created automatically.
3. **Shortcode**: Add `[bionic_recruitment_form]` to any page or post to display the form.

## Form Fields
- **Step 1**: Name, Email, Phone, About
- **Step 2**: NID, Certificate Number, CV Upload

Form data is submitted via AJAX with validation for proper format and file type.

## Admin Panel
- **Access**: Admin and Editor roles can manage form entries under **Bionic Recruitment** in the WordPress admin menu.
- **Actions**: View, update, delete, and filter submissions.

## Database Structure
A custom table (`wp_bionic_recruitment_entries`) stores the following fields:
- ID, name, email, phone, about, NID, certificate number, CV file path, and date created.

## Security
- **Sanitization**: Input sanitization to prevent vulnerabilities.
- **Validation**: Ensures valid email, phone, NID, certificate number, and file types.

## Conclusion
The **Bionic Recruitment System** plugin provides an easy way to collect job applications and manage them securely from the WordPress admin panel.

 
