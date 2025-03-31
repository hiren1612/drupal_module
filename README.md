# Custom Drupal Module

## Introduction
The **Property Menu** module is a custom Drupal module that creates a menu with submenus upon installation. This module allows users to manage property data using a simple CRUD (Create, Read, Update, Delete) interface. It also creates a dedicated database table upon installation and removes it upon uninstallation.

## Features
- Creates a custom menu named **"Property Menu"** with submenus:
  - **Property Form**: Allows users to add new properties.
  - **Property Data**: Displays a list of entered properties with options to edit or delete.
- Automatically creates a database table **drqx_mymenu_data** upon installation.
- Provides a user-friendly interface for managing property records.
- Deletes the database table when the module is uninstalled.
- Includes search and pagination functionality in Property Data to search property records efficiently.

## Installation Guide
1. **Download and Place the Module:**
   - Download the custom module directory.
   - Place the directory inside your Drupal installation under:
     ```
     public_html/modules/
     ```
2. **Enable the Module:**
   - Navigate to **Extend** in the Drupal admin panel.
   - Search for **Property** or locate it under **Custom**.
   - Enable and install the module.

## Usage
1. **Accessing the Menu:**
   - After installation, navigate to **Structure -> Menus -> Administration**.
   - Click on **Edit Menu**, and you will see the **Property Menu** with its submenus (**Property Form** and **Property Data**).
   - You can place this menu anywhere you want on your website.

2. **Working with Property Data:**
   - Click on **Property Menu** in the main menu.
   - Under **Property Form**, you can add new properties.
   - Under **Property Data**, you can view, edit, and delete properties.
   - Search & Pagination: You can search for property data and navigate through records using pagination.

## Uninstallation
- To uninstall the module, go to **Extend -> Uninstall** and remove the **Property Menu** module.
- This will delete the **drqx_mymenu_data** table from the database.

## License
This module is open-source and can be freely modified and distributed.

## Author
Developed by Hiren Patel.
