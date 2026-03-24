---
name: rbac-setup
description: 'Implement Role-Based Access Control (RBAC) in Laravel. Use when: adding permissions system, creating roles table, setting up role_permission pivot, protecting routes with middleware, building a permissions management UI, or checking user access with hasPermission(). Covers migrations, models, seeders, middleware, controller, and Blade UI.'
argument-hint: 'Describe the roles and modules to protect (e.g. "admin/staff/resident roles with Residents, Documents, Reports modules")'
---

# RBAC Setup — Laravel Role-Permission System

## What This Produces

A complete, working RBAC system: database tables → models → seeder → middleware → admin controller → Blade permissions UI → in-view permission checks.

## Architecture

```
roles         permissions          role_permission (pivot)
─────         ───────────          ───────────────────────
id            id                   role_id
name          name (slug)          permission_id
display_name  module
color         action
              display_name

User → belongsTo → Role → belongsToMany → Permission
```

Permission slug format: `module.action` (e.g. `residents.view`, `reports.export`)

---

## Procedure

Execute each step in order. Load the linked reference for full code.

| Step | What | Reference |
|------|------|-----------|
| 1 | **Migrations** — Create `roles`, `permissions`, `role_permission` tables + add `role_id` to `users` | [migrations.md](./references/migrations.md) |
| 2 | **Models** — `Role`, `Permission` models with `belongsToMany`; add `hasPermission()` / `hasRole()` to `User` | [models.md](./references/models.md) |
| 3 | **Seeder** — Define modules → actions map, create permissions, seed roles with attached permissions | [seeder.md](./references/seeder.md) |
| 4 | **Middleware** — `CheckPermission` middleware, register as `permission` alias, guard routes | [middleware.md](./references/middleware.md) |
| 5 | **Controller & Routes** — `RolePermissionController` with `index` / `update`, admin-only routes | [controller-and-routes.md](./references/controller-and-routes.md) |
| 6 | **Blade UI** — Permissions page (checkboxes grouped by module per role) + in-view `@can` checks | [blade-ui.md](./references/blade-ui.md) |

---

## Layer Summary

| Layer | Purpose |
|---|---|
| `roles` + `permissions` tables | Stores all roles and named permission slugs |
| `role_permission` pivot | Many-to-many link between roles and permissions |
| `User::hasPermission()` | Single method to check access anywhere |
| `CheckPermission` middleware | Guards routes at the HTTP level |
| `RolePermissionController` | Handles the permissions page logic |
| `permissions.blade.php` | Toggle UI grouped by module per role |
| `@can` / `hasPermission()` | Hides/shows UI elements per user |

---

## Completion Checklist

- [ ] Three migrations created and run (`roles`, `permissions`, `role_permission`)
- [ ] `role_id` column added to `users` table
- [ ] `Role`, `Permission` models created with relationships
- [ ] `User` model has `role()`, `hasPermission()`, `hasRole()` methods
- [ ] `PermissionSeeder` run — verify rows exist in all three tables
- [ ] `CheckPermission` middleware registered as `permission` alias
- [ ] At least one route protected with `->middleware('permission:x.y')`
- [ ] `RolePermissionController` created and routed
- [ ] Permissions Blade view renders checkboxes grouped by module
- [ ] Admin role is read-only in the UI (no Save button, `pointer-events-none`)
