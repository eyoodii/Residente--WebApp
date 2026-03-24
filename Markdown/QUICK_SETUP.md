# Quick Setup Guide - Citizen Role System

## Immediate Setup Steps

### 1. Run Migrations
```bash
php artisan migrate
```

Expected output: Creates 5 new tables and updates existing ones

### 2. Create Admin User
```bash
php artisan db:seed --class=AdminUserSeeder
```

Default credentials created:
- **Admin**: admin@buguey.gov.ph / Admin@2026
- **Test Citizen**: citizen@test.com / Citizen@2026
- **Test Visitor**: visitor@test.com / Visitor@2026

⚠️ **Change admin password after first login!**

### 3. Clear Cache
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 4. Test Access

#### Test as Visitor (visitor@test.com)
1. Login at `/login`
2. View dashboard - will show verification prompts
3. Try to access `/services` - should see "need verification" message
4. Can view dashboard and update profile (after email verification)

#### Test as Citizen (citizen@test.com)
1. Login at `/login`
2. Can access all `/services`
3. Can submit service requests
4. Can manage household profile

#### Test as Admin (admin@buguey.gov.ph)
1. Login at `/login`
2. Access admin dashboard at `/admin/dashboard`
3. Go to `/admin/residents` to verify visitors
4. Monitor activities at `/admin/activity-logs`

## Key Routes

### For All Users
- `/dashboard` - Main dashboard
- `/citizen/profile` - Profile management
- `/notifications` - View notifications

### For Citizens & Admins
- `/services` - E-services directory
- `/my-requests` - Track service requests

### For Admins Only
- `/admin/dashboard` - Analytics dashboard
- `/admin/residents` - Manage residents
- `/admin/activity-logs` - Monitor activities

## Verifying a Visitor (Admin Task)

1. Login as admin
2. Go to `/admin/residents`
3. Filter by role: "Visitor"
4. Click on visitor name
5. Click "Verify Profile" button
6. Select verification method
7. Submit
8. Visitor is now upgraded to "Citizen"!

## What Was Created

### Database
- ✅ 5 new migrations
- ✅ Role system (visitor, citizen, admin)
- ✅ Activity logging
- ✅ Notifications
- ✅ Household profiles
- ✅ Security tracking (lockouts, login attempts)

### Backend
- ✅ 4 new middleware (role checking, citizen access, lockout prevention)
- ✅ 7 new controllers (citizen profile, notifications, admin management)
- ✅ 4 new models (HouseholdProfile, HouseholdMember, ActivityLog, Notification)
- ✅ Enhanced Resident model with role methods
- ✅ Activity logging throughout the system
- ✅ Automatic notifications on key events

### Security
- ✅ Account lockout after 5 failed attempts (15 min)
- ✅ Login tracking (time, IP address)
- ✅ Comprehensive audit trails
- ✅ Suspicious activity flagging
- ✅ Role-based route protection

### Features
- ✅ Profile database matching
- ✅ Email verification requirement
- ✅ Transaction status tracking
- ✅ Notification system
- ✅ Socio-economic profile management
- ✅ Household member management
- ✅ Admin resident verification
- ✅ Activity log monitoring

## Next Steps (Optional Views)

The backend is complete! You may want to create views for:

1. **Dashboard enhancements** - Show notification panel
2. **Citizen profile pages** - Forms for profile editing
3. **Admin interfaces** - Resident verification forms
4. **Notification UI** - Notification dropdown/page

The controllers are ready and will work once you create the corresponding Blade views.

## Configuration Tips

### Email Setup (for verification)
```env
# In .env file
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@buguey.gov.ph
```

### For Development (use Mailtrap)
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
```

## Troubleshooting

**Error: "Class 'DB' not found"**
```bash
php artisan config:clear
composer dump-autoload
```

**Error: "Middleware not found"**
```bash
php artisan route:clear
php artisan cache:clear
```

**Migration fails**
- Check database connection in `.env`
- Ensure MySQL is running
- Try: `php artisan migrate:fresh` (⚠️ deletes all data!)

**Can't login as admin**
- Re-run seeder: `php artisan db:seed --class=AdminUserSeeder`
- Check email: admin@buguey.gov.ph
- Check password: Admin@2026

## Documentation

📖 Full documentation: `CITIZEN_ROLE_SYSTEM.md`

---

**Ready to test!** 🚀
