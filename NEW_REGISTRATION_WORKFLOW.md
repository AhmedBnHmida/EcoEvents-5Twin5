# 🎯 New Registration Workflow - Auto Participant Role

## ✨ What Changed

### **Previous Workflow** ❌
1. User must be a "participant" to register
2. Admin had to manually assign participant role
3. Users couldn't register until role was changed

### **New Workflow** ✅
1. **ANY logged-in user** can register to an event
2. **User's role automatically changes to "participant"** when they register
3. Registration status is "pending" - **admin must confirm**
4. User becomes a participant immediately upon registration

---

## 🔄 Complete Registration Flow

### **Step 1: User Registers**
```
1. User logs in (any role: utilisateur, fournisseur, etc.)
2. Browses events at /events
3. Clicks "Participer" button
4. Fills registration form
5. Submits registration
```

### **Step 2: Automatic Role Change** 🎭
```
✅ User's role automatically changes to "participant"
✅ Registration is created with status = "pending"
✅ Ticket code is generated
✅ QR code is generated
```

### **Step 3: Admin Confirmation** 👨‍💼
```
Admin goes to: /manage/registrations
Admin sees the new registration with status "pending"
Admin changes status to:
  - ✅ "Confirmed" → User can attend event
  - ❌ "Canceled" → Registration rejected
  - 👤 "Attended" → User attended the event
```

### **Step 4: User Access** 👤
```
User can view their registrations at: /my-registrations
User can see ticket code and QR code
User can cancel their registration if needed
```

---

## 🎭 Role Change Details

### When Does Role Change?
- **Trigger**: When user submits registration form
- **Location**: `RegistrationController@store` method (line 100-105)
- **Condition**: Only if user's current role is NOT already "participant"

### Code Implementation
```php
// AUTOMATICALLY CHANGE USER ROLE TO PARTICIPANT
$user = Auth::user();
if ($user->role !== 'participant') {
    $user->role = 'participant';
    $user->save();
}
```

### What Roles Can Register?
- ✅ utilisateur → becomes participant
- ✅ fournisseur → becomes participant  
- ✅ organisateur → becomes participant
- ✅ participant → stays participant (no change)
- ⚠️  admin → can register but becomes participant (might want to prevent this)

---

## 📋 Registration Statuses

| Status | Description | Who Can Set | Color |
|--------|-------------|-------------|-------|
| **pending** | Waiting for admin confirmation | Automatic on registration | 🟡 Yellow |
| **confirmed** | Admin approved the registration | Admin only | 🟢 Green |
| **canceled** | Registration was canceled | Admin or User | 🔴 Red |
| **attended** | User attended the event | Admin only | 🔵 Blue |

---

## 👥 User Permissions

### Any Logged-In User Can:
- ✅ View public events
- ✅ Register to an event
- ✅ View their own registrations
- ✅ Cancel their own registrations
- ✅ Automatically become a participant

### Participant Can:
- ✅ All of the above (same as any user)
- ✅ View registration details with QR code

### Admin Can:
- ✅ View all registrations
- ✅ Change registration status
- ✅ Confirm or cancel registrations
- ✅ Mark users as "attended"
- ✅ Delete any registration

---

## 🧪 Testing the New Workflow

### Test Case 1: New User Registration

**Setup**: Create a user with role "utilisateur"

```bash
php artisan tinker
```

```php
$user = new App\Models\User();
$user->name = 'Test User';
$user->email = 'testuser@example.com';
$user->password = bcrypt('password');
$user->role = 'utilisateur';
$user->save();
```

**Test Steps**:
1. Login as testuser@example.com
2. Go to `/events`
3. Click "Participer" on any event
4. Submit registration form
5. ✅ **Expected**: User role changes to "participant"
6. ✅ **Expected**: Registration created with status "pending"
7. ✅ **Expected**: Success message appears

**Verify Role Change**:
```php
$user = App\Models\User::where('email', 'testuser@example.com')->first();
echo $user->role; // Should output: participant
```

### Test Case 2: Admin Confirms Registration

**Setup**: Login as admin

**Test Steps**:
1. Go to `/manage/registrations`
2. Find the pending registration
3. Change status dropdown to "confirmed"
4. ✅ **Expected**: Status updates automatically
5. ✅ **Expected**: Success message appears

### Test Case 3: User Views Their Registration

**Test Steps**:
1. Login as the participant
2. Go to `/my-registrations`
3. ✅ **Expected**: See your registration with status badge
4. Click "Détails"
5. ✅ **Expected**: See ticket code and QR code

### Test Case 4: User Cancels Registration

**Test Steps**:
1. On registration details page
2. Click "Annuler mon inscription"
3. Confirm deletion
4. ✅ **Expected**: Registration deleted
5. ✅ **Expected**: Redirected back with success message

---

## 🔒 Security Considerations

### Protection Against Multiple Registrations
```php
// Check if user is already registered
$existingRegistration = Registration::where('user_id', Auth::id())
    ->where('event_id', $event->id)
    ->first();

if ($existingRegistration) {
    return redirect()->back()
        ->with('info', 'Vous êtes déjà inscrit à cet événement.');
}
```

### Capacity Check
```php
// Check if event has capacity
if ($event->registrations()->count() >= $event->capacity_max) {
    return redirect()->back()
        ->with('error', 'Cet événement est complet.');
}
```

### Access Control
- Users can only view/delete their own registrations
- Admins can view/modify all registrations
- Registration details require authentication

---

## ⚠️ Important Notes

### Admin Registration
Currently, **admins can register** and their role will change to "participant". If you want to prevent this:

```php
// Add this check before role change in store() method
if (Auth::user()->isAdmin()) {
    return redirect()->back()
        ->with('error', 'Les administrateurs ne peuvent pas s\'inscrire aux événements.');
}
```

### Role Preservation
Once a user becomes a "participant", they stay a participant. There's no automatic role reversal when:
- Registration is canceled
- Registration is deleted
- Event ends

### Recommended: Email Notifications
Consider adding email notifications when:
- User registers (confirmation email)
- Admin confirms registration (approval email)
- Admin cancels registration (cancellation email)

---

## 📊 Database Changes

No migration needed! The existing schema supports this workflow:

```sql
-- users table already has 'role' column
-- registrations table already has 'status' column with:
--   'pending', 'confirmed', 'canceled', 'attended'
```

---

## 🚀 Quick Start Commands

### Run the Application
```bash
# Make sure MySQL is running first!
php artisan migrate
php artisan serve
```

### Create Test Data
```bash
php artisan tinker
```

```php
// Create a test user
$user = App\Models\User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => bcrypt('password'),
    'role' => 'utilisateur'
]);

// Check events exist
App\Models\Event::count(); // Should be > 0
```

### Access URLs
- Public Events: `http://localhost:8000/events`
- My Registrations: `http://localhost:8000/my-registrations`
- Admin Dashboard: `http://localhost:8000/manage/registrations`

---

## 📝 Success Messages

Users will see clear feedback:

**After Registration**:
> "Votre inscription a été enregistrée avec succès! Vous êtes maintenant un participant. Votre inscription est en attente de confirmation par l'administrateur."

**After Admin Confirmation**:
> "Le statut de l'inscription a été mis à jour."

**After Cancellation**:
> "Inscription à '[Event Name]' annulée avec succès."

---

## 🎉 Summary

✅ **Any user can register** to events
✅ **Role automatically changes** to participant
✅ **Admin must confirm** registrations
✅ **Users can manage** their own registrations
✅ **Clear workflow** with proper status management
✅ **Secure** with proper access controls

**The system now provides a smooth, automated workflow for event participation!**
