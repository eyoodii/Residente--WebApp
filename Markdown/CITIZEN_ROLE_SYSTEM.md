# Barangay E-Services - Citizen/User Role System

## Overview

This document outlines the comprehensive Citizen/User role-based access control (RBAC) system implemented for the Barangay E-Services platform.

## System Architecture

### Core Modules

#### 1. Secure Access & Identity Management
- **Resident Registration**: Users register with name, date of birth, email, and secure password (minimum 8 characters)
- **Email Verification**: Mandatory email verification using Laravel's built-in verification system
- **Profile Database Matching**: System cross-references users with existing barangay database
- **Role-Based Access Control (RBAC)**: Four distinct roles with different access levels

#### 2. Role System

##### Visitor
- **Default role** assigned upon registration
- **Capabilities**:
  - View dashboard
  - Update personal profile
  - View announcements
  - View notifications
- **Restrictions**:
  - Cannot access e-services
  - Cannot submit service requests
- **Upgrade Path**: Visit Barangay Hall with valid ID for physical verification

##### Citizen
- **Verified resident** with confirmed physical residency
- **Capabilities**:
  - All Visitor capabilities
  - Access all e-services
  - Submit service requests
  - Track service request status
  - Manage household profile
  - Add household members
- **Requirements**:
  - Email verified
  - Profile matched with barangay database
  - Physical verification completed by admin

##### Admin
- **Barangay administrator** with full system access
- **Capabilities**:
  - All Citizen capabilities
  - Verify resident profiles
  - Promote users to Citizen or Admin
  - Revoke citizen status
  - View all activity logs
  - Monitor suspicious activities
  - Unlock locked accounts
  - Access admin dashboard with analytics

##### Super Admin
- **System administrator** with highest level of privileges
- **Capabilities**:
  - All Admin capabilities
  - Access Data Collection Dashboard (HN→HHN→HHM hierarchy)
  - Auto-linking resident family relationships
  - Approve/reject auto-linked family members
  - System-wide configuration and security settings
  - Promote users to Admin or Super Admin
  - Complete audit trail access across all barangays
  - Database management and bulk operations
- **Security Note**: Only one Super Admin account should exist per municipality

#### 3. Resident Dashboard (Main Hub)
- **Personalized Welcome View**: Shows user's full name, role, and verification status
- **Transaction Status Tracker**: Real-time tracking of:
  - Pending requests
  - In-progress requests
  - Ready for pickup documents
  - Completed requests
- **Notification Alerts**: Bell icon with unread notification count
- **Recent Activity**: Last 5 service requests
- **Profile Completion Status**: Alerts for incomplete profile information

#### 4. Integrated E-Services Directory
- **Clearance & Certification**
- **CEDULA**
- **Permit Processing**
- **Health Services**
- **Barangay Blotter**
- **Step-by-step Transparency**: Each service shows processing steps with time estimates
- **Status Tracking**: Real-time updates on service request progress

#### 5. Community Feed & Updates
- **LGU News & Updates**: Official announcements from local government
- **Barangay News**: Barangay-specific announcements
- **Memorandums & Ordinances**: Official documents and policies
- **Targeted Announcements**: Can be filtered by barangay

#### 6. Socio-Economic Profile Manager
- **Personal Information**: Demographics, contact details, occupation
- **Address Information**: Purok, Barangay, Municipality, Province
- **Household Profile**: 
  - Housing type and dwelling
  - Utilities and amenities
  - Income classification
  - Assets (vehicles, agricultural land)
- **Household Members**:
  - Family composition
  - Educational attainment
  - Employment status
  - Assistance program eligibility (4Ps, PWD, Senior Citizen, etc.)

### Security Protocols

#### 1. Role-Based Access Control (RBAC)
- Middleware enforces role requirements on routes
- `CheckRole`: Verifies user has required role(s)
- `CheckCitizenAccess`: Ensures only verified citizens access e-services
- Automatic redirects with informative error messages

#### 2. Account Security
- **Account Lockout**: 5 failed login attempts = 15-minute lockout
- **Login Tracking**: Records last login time and IP address
- **Failed Attempt Logging**: Tracks suspicious login patterns
- **Admin Unlock**: Admins can manually unlock accounts

#### 3. Encrypted Traffic
- Uses Laravel's HTTPS enforcement
- Secure session management
- CSRF protection on all forms
- Signed routes for email verification

#### 4. Audit Trails
Comprehensive activity logging system:
- **What**: Action performed (login, logout, create, update, delete, etc.)
- **Who**: User performing the action (with email preserved even if user deleted)
- **When**: Timestamp of action
- **Where**: IP address, user agent, request URL
- **Details**: Old and new values for updates
- **Severity**: Info, Warning, Critical
- **Suspicious Flag**: Automatic flagging of suspicious activities

### Notification System

Real-time notification system for residents:
- **Service Updates**: Status changes on service requests
- **Document Ready**: Alerts when documents are ready for pickup
- **Account Updates**: Role changes, verifications
- **Announcements**: Important barangay news
- **System Alerts**: Security notifications

Notification features:
- Unread badge count
- Priority levels (low, normal, high, urgent)
- Action buttons (deep links to relevant pages)
- Mark as read functionality
- Optional email notifications

## Database Schema

### New Tables

#### `household_members`
Stores family members of each resident
- Demographics (name, date of birth, gender)
- Relationship to head of household
- Socio-economic data (occupation, income, education)
- Assistance program flags (PWD, 4Ps, etc.)

#### `household_profiles`
Stores household-level information
- Housing and dwelling type
- Utilities and amenities
- Income classification
- Assets (vehicles, land)
- Special needs and assistance received

#### `activity_logs`
Comprehensive audit trail system
- User identification and action details
- Entity tracking (what was affected)
- Old and new values (for updates)
- Request metadata (IP, user agent, URL)
- Severity and suspicious activity flags

#### `notifications`
Custom notification system
- Title and message
- Type and priority
- Related entity references
- Action URLs for deep linking
- Read status and timestamps
- Email delivery status

### Updated Tables

#### `residents`
New fields added:
- `role`: SP, admin, citizen, visitor (ENUM - SP = Super Admin, added March 5, 2026)
- `profile_matched`: Boolean flag
- `profile_matched_at`: Timestamp of verification
- `verification_method`: manual, auto, biometric
- `last_login_at`: Last successful login
- `last_login_ip`: IP address of last login
- `failed_login_attempts`: Counter for failed logins
- `locked_until`: Account lockout expiration

#### `service_requests`
Updated status enum to include:
- pending
- in-progress
- ready-for-pickup (NEW)
- completed
- cancelled
- rejected (NEW)

## Controllers

### Citizen Controllers
- **CitizenProfileController**: Manage personal, address, and household information
- **NotificationController**: View and manage notifications
- **ActivityLogController**: View personal activity history
- **DashboardController**: Enhanced with statistics and notifications

### Admin Controllers
- **AdminDashboardController**: Admin analytics and statistics
- **ResidentManagementController**: Verify, promote, revoke, unlock residents
- **AdminActivityLogController**: Monitor all system activities

## Middleware

1. **CheckRole**: Restricts routes to specific roles
2. **CheckCitizenAccess**: Ensures only verified citizens access e-services
3. **PreventAccountLockout**: Blocks locked accounts
4. **LogActivity**: Automatically logs user activities (optional)

## Installation & Setup

### 1. Run Migrations

```bash
php artisan migrate
```

This will create all new tables:
- household_members
- household_profiles
- activity_logs
- notifications
- Update residents table with role fields
- Update service_requests status enum

### 2. Seed Admin User

```bash
php artisan db:seed --class=AdminUserSeeder
```

This creates three test users:
- **Admin**: admin@buguey.gov.ph / Admin@2026
- **Citizen**: citizen@test.com / Citizen@2026
- **Visitor**: visitor@test.com / Visitor@2026

**IMPORTANT**: Change the admin password after first login!

### 2.1 Create Super Admin Account (Optional)

```bash
php artisan db:seed --class=SuperAdminSeeder
```

This creates the super admin account:
- **Super Admin**: superadmin@buguey.gov.ph / SuperAdmin@2026

**CRITICAL**: This account has complete system access. Change the password immediately after first login!

Super Admin should only be used for:
- Initial system setup and configuration
- Data collection and household hierarchy management (HN→HHN→HHM)
- Database maintenance and bulk operations
- Emergency access to locked admin accounts

### 3. Configure Email (for verification)

Update `.env` file:
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@buguey.gov.ph
MAIL_FROM_NAME="Barangay E-Services"
```

### 4. Test the System

1. **Login as Admin**: Navigate to `/login` and use admin credentials
2. **Access Admin Dashboard**: Go to `/admin/dashboard`
3. **Test Visitor Account**: Login as visitor@test.com
   - Notice restricted access to e-services
4. **Verify Visitor**: As admin, go to Resident Management and verify the visitor
5. **Test Citizen Access**: Login as the newly verified citizen
   - Now has access to all e-services

## Usage Guide

### For Residents

#### Registration Process
1. Click "Register" on homepage
2. Fill out registration form (name, date of birth, email, password)
3. Submit - **redirected directly to Citizen Dashboard**
4. See prominent email verification reminder on dashboard
5. Check email and click verification link
6. After email verification, dashboard shows full features
7. Visit Barangay Hall with valid ID for physical verification to upgrade to Citizen role

#### After Verification
1. Login and notice role changed to "Citizen"
2. Access e-services from navigation menu
3. Submit service requests
4. Track request status in "My Requests"
5. Receive notifications for status updates
6. Complete socio-economic profile for better assistance

### For Admins

#### Verifying Residents
1. Login to admin dashboard at `/admin/dashboard`
2. Go to "Resident Management"
3. Filter by "Visitors" to see pending verifications
4. Click on resident to view details
5. Verify identity with physical documents
6. Click "Verify Profile"
7. Select verification method (manual, auto, biometric)
8. Submit - resident is now upgraded to "Citizen"

#### Monitoring Activities
1. Go to "Activity Logs" in admin menu
2. Filter by:
   - Action type (login, service_request, update, etc.)
   - Severity (info, warning, critical)
   - Date range
   - Specific resident
3. View "Suspicious Activities" for security monitoring
4. Check details of any suspicious activity

#### Managing Residents
- **Promote to Admin**: Give admin privileges to trusted staff
- **Revoke Citizen**: Downgrade to visitor if residency invalid
- **Unlock Account**: Reset account after failed login attempts
- **View History**: See complete activity log for any resident

## Security Best Practices

1. **Change Default Passwords**: Immediately change admin password after seeding
2. **Use HTTPS**: Always use HTTPS in production
3. **Monitor Suspicious Activities**: Regularly check admin activity logs
4. **Regular Backups**: Backup database regularly including activity logs
5. **Review Failed Logins**: Monitor for brute force attempts
6. **Keep Laravel Updated**: Apply security patches regularly

## Routes Summary

### Public Routes
- `/` - Homepage
- `/register` - Registration
- `/login` - Login
- `/news-events` - Public announcements

### Authenticated Routes (All Verified Users)
- `/dashboard` - Main dashboard
- `/citizen/profile/*` - Profile management
- `/notifications/*` - Notification management
- `/activity-logs` - Personal activity history

### Citizen Routes (Citizens & Admins Only)
- `/services` - E-services directory
- `/services/{slug}` - Service details
- `/services/{slug}/request` - Submit request
- `/my-requests` - Track requests
- `/service-request/{number}` - Request details

### Admin Routes (Admins Only)
- `/admin/dashboard` - Admin analytics
- `/admin/residents/*` - Resident management
- `/admin/activity-logs/*` - System monitoring

## API Endpoints

### Notification Count (AJAX)
```
GET /notifications/unread-count
Response: {"count": 5}
```

Use this endpoint to dynamically update notification badge without page reload.

## Future Enhancements

1. **SMS Notifications**: Add SMS alerts for important updates
2. **Biometric Verification**: Implement fingerprint/face recognition
3. **Mobile App**: Develop native mobile application
4. **Online Payment**: Integrate payment gateway for fees
5. **Document Upload**: Allow residents to upload requirements
6. **QR Code**: Generate QR codes for document tracking
7. **Analytics Dashboard**: Enhanced statistics for admin
8. **Export Reports**: PDF/Excel export of resident data

## Troubleshooting

### Issue: Migrations fail
**Solution**: Check database connection in `.env`, ensure MySQL is running

### Issue: Email verification not working
**Solution**: Configure mail settings in `.env`, use Mailtrap for testing

### Issue: Visitor can't access services
**Expected**: Visitors must be verified by admin first

### Issue: Account locked after registration
**Solution**: Admin should check for suspicious flag in activity logs

### Issue: Middleware not working
**Solution**: Clear cache with `php artisan route:clear` and `php artisan config:clear`

## Support

For questions or issues:
- Email: support@buguey.gov.ph
- Visit: Barangay Hall during office hours

---

**Version**: 1.0
**Last Updated**: February 27, 2026
**Developed for**: Barangay Buguey E-Services Platform
