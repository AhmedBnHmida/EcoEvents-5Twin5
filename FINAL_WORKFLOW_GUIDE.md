# 🎯 FINAL Registration Workflow - Admin Controlled Role Change

## ✨ Complete Workflow Overview

### **Step-by-Step Process**

```
1. User (any role) registers to event
   ↓
2. Registration created with status = "PENDING"
   ↓
3. Admin sees registration in backoffice
   ↓
4. Admin reviews user details (name, email, current role)
   ↓
5. Admin changes status to "CONFIRMED"
   ↓
6. 🎭 USER ROLE AUTOMATICALLY CHANGES TO "PARTICIPANT"
   ↓
7. User can now access participant features
```

---

## 🔄 Detailed Flow

### **Phase 1: User Registration** 👤

**What happens:**

-   User logs in (can be any role: utilisateur, fournisseur, organisateur, etc.)
-   User browses events at `/events`
-   User clicks "Participer" button
-   User fills and submits registration form

**Result:**

-   ✅ Registration created
-   ✅ Status = "pending"
-   ✅ Ticket code generated
-   ✅ QR code generated
-   ❌ User role stays THE SAME (not changed yet)

**Success Message:**

> "Votre inscription a été enregistrée avec succès! Votre inscription est en attente de confirmation par l'administrateur."

---

### **Phase 2: Admin Review** 👨‍💼

**Admin Dashboard:** `/manage/registrations`

**What Admin Sees:**

```
┌──────────────────────────────────────────────────────────────┐
│ Utilisateur │ Événement │ Rôle        │ Code     │ Statut   │
├──────────────────────────────────────────────────────────────┤
│ John Doe    │ Event 1   │ Utilisateur │ ABC12345 │ Pending  │
│ john@ex.com │           │             │          │          │
└──────────────────────────────────────────────────────────────┘
```

**Admin Can:**

-   ✅ See user's current role (with colored badge)
-   ✅ See all registration details
-   ✅ Change status via dropdown
-   ✅ View full details
-   ✅ Delete registration

**Role Badge Colors:**

-   🟢 **Participant** - Green (already participant)
-   🔴 **Admin** - Red
-   🟡 **Fournisseur** - Yellow
-   🔵 **Organisateur** - Blue
-   ⚫ **Utilisateur** - Grey

---

### **Phase 3: Admin Confirms** ✅

**Action:** Admin changes status dropdown from "pending" to "confirmed"

**What Happens:**

```php
// Automatically triggered when status becomes "confirmed"
if ($newStatus === 'confirmed' && $oldStatus !== 'confirmed') {
    $user = $registration->user;
    if ($user->role !== 'participant' && $user->role !== 'admin') {
        $user->role = 'participant';
        $user->save();
    }
}
```

**Result:**

-   ✅ Registration status updated to "confirmed"
-   ✅ User role changed to "participant"
-   ✅ User can now access participant features

**Success Message:**

> "Inscription confirmée! L'utilisateur [Name] est maintenant un participant."

**Note:** Admins' roles are NOT changed to participant (protected)

---

### **Phase 4: User Access** 🎉

**User can now:**

-   ✅ View their registrations at `/my-registrations`
-   ✅ See ticket details with QR code
-   ✅ Access participant-only features
-   ✅ Register to more events (now as participant)
-   ✅ Cancel their registration if needed

---

## 📊 Status Management

### **Available Statuses**

| Status        | Who Sets                  | What Happens        | Role Change?                        |
| ------------- | ------------------------- | ------------------- | ----------------------------------- |
| **pending**   | Automatic on registration | Waiting for admin   | No                                  |
| **confirmed** | Admin                     | Approved            | ✅ **YES** - Changes to participant |
| **canceled**  | Admin or User             | Rejected/Cancelled  | No                                  |
| **attended**  | Admin                     | User attended event | No                                  |

### **Important Notes**

1. **Role changes ONLY when:**

    - Status changes FROM anything TO "confirmed"
    - User is NOT already a participant
    - User is NOT an admin (protection)

2. **Role does NOT change when:**
    - Status changes to "canceled"
    - Status changes to "attended"
    - Status changes FROM "confirmed" TO something else
    - User is already a participant

---

## 🎭 Role Change Logic

### **Protected Roles**

```php
// Admins are protected from role change
if ($user->role !== 'participant' && $user->role !== 'admin') {
    $user->role = 'participant';
    $user->save();
}
```

### **Which Roles Get Changed?**

| Original Role | After Confirmation | Notes               |
| ------------- | ------------------ | ------------------- |
| utilisateur   | → participant      | ✅ Changed          |
| fournisseur   | → participant      | ✅ Changed          |
| organisateur  | → participant      | ✅ Changed          |
| participant   | → participant      | No change (already) |
| admin         | → admin            | 🔒 Protected        |

---

## 🔒 Security & Permissions

### **What Any Logged-In User Can Do:**

-   ✅ Register to events
-   ✅ View their own registrations
-   ✅ Cancel their own registrations
-   ✅ View QR codes for their registrations

### **What Admins Can Do:**

-   ✅ View ALL registrations
-   ✅ See user roles and details
-   ✅ Change registration status
-   ✅ Confirm registrations (triggers role change)
-   ✅ Mark users as attended
-   ✅ Cancel any registration
-   ✅ Delete any registration

### **What Users CANNOT Do:**

-   ❌ Change their own status
-   ❌ Change their own role
-   ❌ View other users' registrations
-   ❌ Access admin dashboard

---

## 🧪 Testing the Complete Workflow

### **Test Case 1: Complete Registration Flow**

**Setup:**

```bash
php artisan tinker
```

```php
// Create a test user (not participant)
$user = App\Models\User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password'),
    'role' => 'utilisateur'  // Important: NOT participant
]);

// Verify role
echo $user->role; // Output: utilisateur
```

**Test Steps:**

1. **User Registers:**

    - Login as test@example.com
    - Go to `/events`
    - Click "Participer" on an event
    - Submit registration form
    - ✅ See success message
    - ✅ Registration created

2. **Verify Role NOT Changed Yet:**

    ```bash
    php artisan tinker
    ```

    ```php
    $user = App\Models\User::where('email', 'test@example.com')->first();
    echo $user->role; // Should still be: utilisateur
    ```

3. **Admin Reviews:**

    - Login as admin
    - Go to `/manage/registrations`
    - ✅ See the new registration
    - ✅ See user role badge: "Utilisateur" (grey)
    - ✅ Status shows: "Pending" (yellow)

4. **Admin Confirms:**

    - In the status dropdown, select "confirmed"
    - ✅ See message: "Inscription confirmée! L'utilisateur Test User est maintenant un participant."
    - ✅ Role badge changes to "Participant" (green)

5. **Verify Role Changed:**

    ```bash
    php artisan tinker
    ```

    ```php
    $user = App\Models\User::where('email', 'test@example.com')->first();
    echo $user->role; // Should now be: participant
    ```

6. **User Accesses Features:**
    - Login as test@example.com again
    - Go to `/my-registrations`
    - ✅ See confirmed registration
    - ✅ Can register to more events

---

### **Test Case 2: Admin Protection**

**Test if admin role is protected:**

1. Create admin user and register:

    ```php
    $admin = App\Models\User::create([
        'name' => 'Test Admin',
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);
    ```

2. Login as admin and register to an event

3. Login as different admin and confirm the registration

4. **Expected:** Original admin's role stays "admin" (not changed to participant)

---

## 📱 Admin Dashboard Features

### **Registration Table Columns:**

1. **Utilisateur** - Name, email, avatar
2. **Événement** - Event title, date
3. **Rôle** - Current user role (colored badge)
4. **Code** - Ticket code
5. **Date inscription** - Registration date/time
6. **Statut** - Status dropdown (interactive)
7. **Actions** - View details, Delete

### **Statistics Cards:**

```
┌─────────────┬─────────────┬─────────────┬─────────────┐
│ En attente  │ Confirmés   │ Présents    │ Annulés     │
│ 🟡 5        │ 🟢 12       │ 🔵 8        │ 🔴 2        │
└─────────────┴─────────────┴─────────────┴─────────────┘
```

### **Interactive Status Change:**

The status dropdown automatically submits when changed:

```html
<select onchange="this.form.submit()">
    <option value="pending">En attente</option>
    <option value="confirmed">Confirmé</option>
    <option value="canceled">Annulé</option>
    <option value="attended">Présent</option>
</select>
```

---

## 🚀 Quick Start

### **1. Ensure Database is Ready:**

```bash
php artisan migrate
```

### **2. Create Admin User:**

```bash
php artisan tinker
```

```php
App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@ecoevents.com',
    'password' => bcrypt('admin123'),
    'role' => 'admin'
]);
```

### **3. Create Test Event:**

```php
App\Models\Event::create([
    'title' => 'Test Event',
    'description' => 'Test Description',
    'start_date' => now()->addDays(7),
    'end_date' => now()->addDays(7)->addHours(3),
    'location' => 'Test Location',
    'capacity_max' => 100,
    'categorie_id' => 1, // Make sure category exists
    'status' => App\EventStatus::UPCOMING,
    'registration_deadline' => now()->addDays(5),
    'price' => 0,
    'is_public' => true
]);
```

### **4. Start Application:**

```bash
php artisan serve
```

### **5. Test URLs:**

-   Events: `http://localhost:8000/events`
-   My Registrations: `http://localhost:8000/my-registrations`
-   Admin Dashboard: `http://localhost:8000/manage/registrations`

---

## 📝 Success Messages

### **User Registration:**

> "Votre inscription a été enregistrée avec succès! Votre inscription est en attente de confirmation par l'administrateur."

### **Admin Confirms (with role change):**

> "Inscription confirmée! L'utilisateur [Name] est maintenant un participant."

### **Admin Updates Status:**

> "Le statut de l'inscription a été mis à jour."

### **Registration Canceled:**

> "Inscription à '[Event Name]' annulée avec succès."

---

## ⚡ Key Benefits

✅ **Admin has full control** over who becomes a participant
✅ **Clear review process** before granting participant status
✅ **Transparent workflow** - admin sees current role before confirming
✅ **Protected admin role** - admins won't accidentally become participants
✅ **Automatic role change** - no manual user management needed
✅ **Audit trail** - track who registered and when they were confirmed

---

## 🎉 Summary

**User Flow:**

1. Register → Status: Pending, Role: Unchanged
2. Wait for admin confirmation
3. Get confirmed → Role: Participant
4. Access participant features

**Admin Flow:**

1. View registrations with user roles
2. Review details
3. Confirm → User becomes participant automatically
4. Track statistics

**The system now provides a secure, admin-controlled workflow for participant management!** 🌟
