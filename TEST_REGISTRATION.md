# 🧪 Testing Registration System

## ✅ Fixed Issues

1. ✅ Removed auth middleware from `/registrations/create` route
2. ✅ Added proper session storage for intended URL
3. ✅ Added error/success/info messages to all pages
4. ✅ Added role check with error message showing current role

## 🧪 How to Test

### Step 1: Start the Application

Make sure MySQL is running and database is migrated:

```bash
php artisan migrate
php artisan serve
```

### Step 2: Create Test Users

You need at least one user with role "participant". You can:

**Option A: Register via the website**

-   Go to `/sign-up` or `/signup`
-   Create an account
-   **IMPORTANT**: Make sure the role is set to "participant"

**Option B: Use Tinker to create a test user**

```bash
php artisan tinker
```

Then run:

```php
$user = new App\Models\User();
$user->name = 'Test Participant';
$user->email = 'participant@test.com';
$user->password = bcrypt('password');
$user->role = 'participant';
$user->save();
```

Exit tinker with `exit`

### Step 3: Test the Registration Flow

#### Test Case 1: User Not Logged In

1. Go to: `http://localhost:8000/events`
2. Click **"Participer"** button on any event
3. ✅ **Expected**: You should be redirected to login page with message "Veuillez vous connecter..."
4. Login with participant account
5. ✅ **Expected**: After login, you should be redirected BACK to the registration form
6. ✅ **Expected**: You should SEE the registration form with event details

#### Test Case 2: User Logged In as Participant

1. Login first as participant user
2. Go to: `http://localhost:8000/events`
3. Click **"Participer"** on an event
4. ✅ **Expected**: You should see the registration form immediately
5. Check the checkbox for terms
6. Click **"Confirmer mon inscription"**
7. ✅ **Expected**: Registration successful, redirected to registration details page with ticket code

#### Test Case 3: User Not a Participant

1. Login as a user with different role (admin, fournisseur, etc.)
2. Go to event detail page
3. Click **"Participer"**
4. ✅ **Expected**: Error message: "Seuls les participants peuvent s'inscrire... Votre rôle actuel: [role]"

## 🔍 Debugging

### Check Current User Role

Add this temporarily to check the logged-in user's role:

```php
// In any blade view
@auth
<div class="alert alert-info">
    Current User: {{ auth()->user()->name }} | Role: {{ auth()->user()->role }}
</div>
@endauth
```

### Check Database

```bash
php artisan tinker
```

```php
// Check users
\App\Models\User::all(['id', 'name', 'email', 'role']);

// Check events
\App\Models\Event::all(['id', 'title', 'capacity_max']);

// Check registrations
\App\Models\Registration::with('user', 'event')->get();
```

## 🐛 Common Issues & Solutions

### Issue: "No registration form shows"

**Solution 1**: Check if you're logged in as a participant

-   Your role must be exactly "participant" (lowercase)

**Solution 2**: Check if event_id is passed

-   The URL should be: `/registrations/create?event_id=1`

**Solution 3**: Clear browser cache and sessions

```bash
php artisan cache:clear
php artisan session:clear
php artisan config:clear
```

### Issue: "Event not found" error

**Solution**: Make sure you have events in the database

```bash
php artisan tinker
```

```php
// Check if events exist
\App\Models\Event::count();

// If no events, check if categories exist first
\App\Models\Category::count();

// Create a test category if needed
$cat = new \App\Models\Category();
$cat->name = 'Test Category';
$cat->description = 'Test';
$cat->save();

// Create a test event
$event = new \App\Models\Event();
$event->title = 'Test Event';
$event->description = 'Test Description';
$event->start_date = now()->addDays(7);
$event->end_date = now()->addDays(7)->addHours(3);
$event->location = 'Test Location';
$event->capacity_max = 100;
$event->categorie_id = 1;
$event->status = \App\EventStatus::UPCOMING;
$event->registration_deadline = now()->addDays(5);
$event->price = 0;
$event->is_public = true;
$event->save();
```

### Issue: Role validation failing

**Solution**: Check and update user role

```bash
php artisan tinker
```

```php
// Find your user
$user = \App\Models\User::where('email', 'your@email.com')->first();

// Check current role
echo $user->role;

// Update to participant
$user->role = 'participant';
$user->save();
```

## 📝 URLs to Test

1. Public Events: `http://localhost:8000/events`
2. Event Details: `http://localhost:8000/events/1` (replace 1 with actual event ID)
3. Registration Form: `http://localhost:8000/registrations/create?event_id=1`
4. My Registrations: `http://localhost:8000/my-registrations`
5. Admin Dashboard: `http://localhost:8000/manage/registrations` (admin only)

## ✅ What Should Happen

When you click "Participer":

1. **If not logged in**:

    - Redirect to `/sign-in`
    - Show info message
    - After login → automatically redirect to registration form

2. **If logged in as participant**:

    - Show registration form with event details
    - Form has user info (name, email - disabled)
    - Terms checkbox (required)
    - Submit button

3. **After submitting form**:

    - Create registration with status "pending"
    - Generate unique ticket code (8 characters)
    - Generate QR code
    - Redirect to registration details page
    - Show ticket code and QR code

4. **Admin can**:

    - View all registrations at `/manage/registrations`
    - Change status via dropdown (pending → confirmed → attended)
    - Delete registrations

5. **Participant can**:
    - View their registrations at `/my-registrations`
    - See ticket details with QR code
    - Cancel their registration

---

If you still don't see the form, please:

1. Check your browser console for JavaScript errors
2. Share the exact error message you see
3. Share your user's role from the database
