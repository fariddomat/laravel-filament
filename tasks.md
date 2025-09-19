To integrate the `Project` model into your CRM project (replacing `Client` with `Customer`), here’s what needs to be updated or added based on your existing schema and the provided model:

1. Create `projects` table migration #
2. Create `statuses` table migration (for `Status` model) #
3. Create `milestones` table migration (for `Milestone` model)
4. Create `project_user` pivot table migration (for `members` relationship)
5. Create `timesheets` table migration (for `Timesheet` model)
6. Create `invoices` table migration (for `Invoice` model)
7. Create `files` table migration (for `File` model with polymorphic `fileable`)
8. Create `tickets` table migration (for `Ticket` model)
9. Create `notes` table migration (for `Note` model with polymorphic `noteable`)
10. Create `discussions` table migration (for `Discussion` model)
11. Create `activity_logs` table migration (for `ActivityLog` model with polymorphic `loggable`)
12. Create `contracts` table migration (for `Contract` model)
13. Create `sales` table migration (for `Sale` model)
14. Create `customer_feedback` table migration (for `CustomerFeedback` model)
15. Update `customers` table to ensure compatibility with `client_id` foreign key (already exists as `customers` table)
16. Update `tasks` table to add `project_id` foreign key (for `tasks` relationship)
17. Create `Status` model
18. Create `Milestone` model
19. Create `Timesheet` model
20. Create `Invoice` model
21. Create `File` model (with polymorphic support)
22. Create `Ticket` model
23. Create `Note` model (with polymorphic support)
24. Create `Discussion` model
25. Create `ActivityLog` model (with polymorphic support)
26. Create `Contract` model
27. Create `Sale` model
28. Create `CustomerFeedback` model
29. Update Filament resources for new models (e.g., `ProjectResource`, `StatusResource`, etc.)
30. Configure `bezhansalleh/filament-shield` permissions for new models/resources
31. Update `Customer` model to reflect `hasMany` relationship with `Project`
32. Ensure database relationships (foreign keys) are constrained properly
33. Update any existing Filament pages/panels to include `Project` and related resources
34. Validate Laravel 12 compatibility for new migrations and models
35. Update seeders/factories for new models (optional, for testing)- **Database Schema**: Add `projects` table with columns for `name`, `customer_id` (foreign key to `customers`), `description`, `start_date` (date), `deadline` (date), `budget` (decimal), `total_billed` (decimal), `created_by` (foreign key to `users`), `status_id` (foreign key to new `statuses` table), `billing_type` (string/enum), `hourly_rate` (decimal), `is_visible_to_client` (boolean), `allow_client_comments` (boolean), plus timestamps and soft deletes; remove or ignore `custom_fields` json column if using pivot table approach like existing custom fields.

- **New Tables**: Create `statuses` table with `id`, `name`, `color` (optional), `position` (integer), timestamps.

- **Pivot Table**: Add `project_user` table for many-to-many relationship between projects and users, with optional `role` column.

- **New Models**: Generate `Status` model with fillable `name`, `color`, `position`; add relationships if needed (e.g., hasMany `projects`).

- **Additional Models for Relationships** (if not integrating with existing): `Milestone`, `Timesheet`, `Invoice`, `Ticket`, `Note` (polymorphic), `Discussion`, `ActivityLog` (polymorphic), `Contract`, `Sale`, `CustomerFeedback`; update `Task` model to include `project_id` foreign key and relationship; create `File` model for polymorphic uploads.

- **Model Updates**:
  - `Project`: Update `client()` to `customer()` with `belongsTo(Customer::class, 'customer_id')`; adjust other relationships to use existing tables where possible (e.g., reuse `tasks` with `project_id`, `documents` as files via polymorphic).
  - `Customer`: Add `hasMany` for `projects`.
  - `User`: Add `belongsToMany` for `projects` via `project_user`; update `tasks` if assigning to projects.
  - `Task`: Add `project_id` foreign key and `belongsTo(Project::class)` optional relationship.

- **Filament Resources**: Create `ProjectResource` with form fields for all fillable attributes, table columns including relations (customer name, status name), actions for view/edit/delete; integrate relations like `HasManyTasks`, `BelongsToCustomer`; add inline widgets for tasks, milestones, etc.

- **Filament Shield Permissions**: Run `php artisan shield:install` if not done, then generate permissions for `projects` resource (view, create, update, delete, etc.); assign to roles like super_admin; customize for related resources (e.g., `tasks.view_project`).

- **Custom Fields Integration**: If keeping `custom_fields` json, no change; else, update `custom_fields` and `custom_field_customers` to support `projects` via new foreign key or polymorphic (add `table_type` column).

- **File Uploads**: Configure Filament file upload for `files()` polymorphic, ensuring storage link and validation.

- **Notifications**: Ensure mail configuration for `Notifiable` trait (e.g., project status changes).

- **Seeding/Migrations**: Create migration for all new tables/pivots, seed initial statuses (e.g., 'In Progress', 'Completed'); update existing task migration if adding project_id retroactively.

- **Potential Conflicts**: Review `tasks` table (add nullable `project_id`); ensure no clashes with existing `customer_pipeline_stages` or `quotes` for project billing.



























    Create milestones table migration
    Create timesheets table migration
    Create invoices table migration
    Use existing documents table for files (already polymorphic)
Create tickets table migration (or reuse tasks if tickets are tasks)
Create notes table migration (polymorphic for noteable)
Create discussions table migration
Create activity_logs table migration (polymorphic for loggable)
Create contracts table migration
Create sales table migration
Create customer_feedback table migration
    Create Milestone model with belongsTo(Project::class)
    Create Timesheet model with belongsTo(Project::class)
    Create Invoice model with belongsTo(Project::class)
Create Ticket model with belongsTo(Project::class) (or update Task model if reusing)
Create Note model with morphTo for noteable
Create Discussion model with belongsTo(Project::class)
Create ActivityLog model with morphTo for loggable
Create Contract model with belongsTo(Project::class)
Create Sale model with belongsTo(Project::class)
Create CustomerFeedback model with belongsTo(Project::class)
Update Project model to add hasMany for milestones, timesheets, invoices, tickets (or reuse tasks), discussions, contracts, sales, feedback, and morphMany for notes, activities
Update Customer model to add hasMany for invoices, contracts, sales, feedback if they relate to customers too
Create Filament resources for new models (e.g., MilestoneResource, TimesheetResource, etc.)
Add relation managers to ProjectResource for managing milestones, timesheets, invoices, tickets, notes, discussions, activities, contracts, sales, feedback
Configure bezhansalleh/filament-shield permissions for new resources
Create factories/seeders for new models for testing
Update ProjectForm schema to include fields for new relationships (if needed)
Ensure polymorphic relationships (notes, activities) are properly constrained
Validate Laravel 12 compatibility for new migrations and models
Run migrations and test relationships in Filament UI
Optionally, add tabs to ListProjects for filtering by new relations (e.g., invoices by status)
