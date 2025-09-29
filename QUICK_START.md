# 🚀 Quick Start - EcoEvents Registration System

## ✅ What's Been Implemented

Complete participant registration system with admin-controlled role management!

---

## 🔧 Fixed Issues

### **Latest Fix: Sidebar Link** ✅

-   **Problem**: Clicking "Inscriptions" in admin sidebar showed nothing
-   **Solution**: Updated sidebar route from `inscriptions.index` → `registrations.index`
-   **File**: `resources/views/components/app/sidebar.blade.php`

---

## 🎯 How It Works

### **1. User Registration Flow**

```
User (any role) → Clicks "Participer" → Fills form → Submits
↓
Status: PENDING | Role: Unchanged
```

### **2. Admin Confirmation Flow**

```
Admin → Sidebar: "Inscriptions" → Reviews registrations
↓
Changes status to "CONFIRMED"
↓
User role automatically becomes "PARTICIPANT"
```

---

## 🖥️ Admin Dashboard

### **Access**: Click **"Inscriptions"** in the left sidebar

### **What You'll See**:

-   User name and email
-   Event details
-   **User's current role** (colored badge)
-   Ticket code
-   Registration date
-   **Status dropdown** (interactive)
-   Action buttons (View, Delete)

### **Statistics Cards**:

-   🟡 En attente (Pending)
-   🟢 Confirmés (Confirmed)
-   🔵 Présents (Attended)
-   🔴 Annulés (Canceled)

---

## 🎭 Role Change Process

### **When Does It Happen?**

Only when admin changes status from ANY → "confirmed"

### **Who Gets Changed?**

-   ✅ utilisateur → participant
-   ✅ fournisseur → participant
-   ✅ organisateur → participant
-   ❌ admin → stays admin (protected)
-   ❌ participant → stays participant

---

## 🧪 Test It Now

### **Step 1: Start Server**

```bash
# Make sure MySQL is running first!
php artisan serve
```

### **Step 2: Create Test User**

```bash
php artisan tinker
```

```php
App\Models\User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password'),
    'role' => 'utilisateur'
]);
```

### **Step 3: User Registers**

1. Go to: `http://localhost:8000/events`
2. Login as test@example.com / password
3. Click "Participer" on any event
4. Submit the form
5. ✅ Registration created with status "pending"

### **Step 4: Admin Confirms**

1. Login as admin
2. Click **"Inscriptions"** in sidebar
3. See the new registration
4. Change status dropdown to "confirmed"
5. ✅ User becomes participant automatically!

---

## 📱 Key URLs

| Page                | URL                                   | Role Required |
| ------------------- | ------------------------------------- | ------------- |
| Public Events       | `/events`                             | Any           |
| Event Details       | `/events/{id}`                        | Any           |
| Register Form       | `/registrations/create?event_id={id}` | Logged in     |
| My Registrations    | `/my-registrations`                   | Logged in     |
| **Admin Dashboard** | `/manage/registrations`               | **Admin**     |
| Admin Events        | `/manage/events`                      | Admin         |
| Admin Categories    | `/manage/categories`                  | Admin         |

---

## 🎨 Visual Guide

### **Admin Dashboard Layout**

```
┌─────────────────────────────────────────────────────────────┐
│ 🏠 Dashboard                                                 │
│ 👥 Users                                                     │
│ 📅 Events                                                    │
│ 📁 Categories                                                │
│ 💬 Feedback                                                  │
│ ⭐ Evaluations                                               │
│ 📦 Ressources                                                │
│ 🚚 Fournisseurs                                              │
│ ✅ Inscriptions  ← Click Here!                              │
│ 🤝 Partenaires                                               │
│ 💰 Sponsoring                                                │
└─────────────────────────────────────────────────────────────┘
```

### **Registration Table**

```
┌────────────┬──────────┬─────────────┬─────────┬────────────┬──────────┐
│ User       │ Event    │ Role        │ Code    │ Date       │ Status   │
├────────────┼──────────┼─────────────┼─────────┼────────────┼──────────┤
│ John Doe   │ Event 1  │🔘Utilisateur│ ABC123  │ 29/09/2025 │ Pending ▼│
│ john@...   │ 01/10/25 │             │         │ 14:30      │          │
└────────────┴──────────┴─────────────┴─────────┴────────────┴──────────┘

After clicking "Confirmed":
┌────────────┬──────────┬─────────────┬─────────┬────────────┬──────────┐
│ John Doe   │ Event 1  │🟢Participant│ ABC123  │ 29/09/2025 │Confirmed ▼│
└────────────┴──────────┴─────────────┴─────────┴────────────┴──────────┘
```

---

## ⚠️ Common Issues & Solutions

### **Issue 1: "Nothing shows in admin sidebar"**

✅ **FIXED** - Sidebar now points to correct route

### **Issue 2: Can't see registrations**

**Solution**: Make sure you're logged in as admin

```php
// Check user role
php artisan tinker
$user = App\Models\User::where('email', 'your@email.com')->first();
$user->role = 'admin';
$user->save();
```

### **Issue 3: No events to register**

**Solution**: Create a test event

```php
php artisan tinker

// Make sure you have a category first
$cat = App\Models\Category::first();
if (!$cat) {
    $cat = App\Models\Category::create([
        'name' => 'Test Category',
        'description' => 'Test'
    ]);
}

// Create event
App\Models\Event::create([
    'title' => 'Test Event',
    'description' => 'Test Description',
    'start_date' => now()->addDays(7),
    'end_date' => now()->addDays(7)->addHours(3),
    'location' => 'Test Location',
    'capacity_max' => 100,
    'categorie_id' => $cat->id,
    'status' => App\EventStatus::UPCOMING,
    'registration_deadline' => now()->addDays(5),
    'price' => 0,
    'is_public' => true
]);
```

### **Issue 4: MySQL not running**

**Solution**: Start your MySQL server

-   **XAMPP**: Open control panel → Start MySQL
-   **WAMP**: Start WAMP services
-   **Standalone**: `net start MySQL80` (Windows)

---

## 📚 Full Documentation

For detailed documentation, see:

-   `FINAL_WORKFLOW_GUIDE.md` - Complete workflow explanation
-   `REGISTRATION_GUIDE.md` - Feature overview

---

## 🎉 Success Checklist

Before testing, make sure:

-   ✅ MySQL is running
-   ✅ Database migrated: `php artisan migrate`
-   ✅ You have at least one admin user
-   ✅ You have at least one public event
-   ✅ Server is running: `php artisan serve`

Then test:

1. ✅ User can register to event
2. ✅ Registration appears in `/manage/registrations`
3. ✅ Admin can see user's current role
4. ✅ Admin can change status to "confirmed"
5. ✅ User's role changes to "participant"
6. ✅ Success message appears

---

## 💡 Pro Tips

1. **Use different browsers** for testing admin and user at the same time
2. **Check user role** before and after confirmation using tinker
3. **Use statistics cards** to quickly see registration counts
4. **Status dropdown** auto-submits - no need to click save!

---

## 🆘 Need Help?

If something doesn't work:

1. Check browser console for JavaScript errors
2. Check Laravel logs: `storage/logs/laravel.log`
3. Clear cache: `php artisan cache:clear`
4. Verify routes: `php artisan route:list | grep registration`

---

**Ready to test? Click "Inscriptions" in the admin sidebar!** 🚀
