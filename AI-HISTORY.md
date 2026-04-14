# AI Development Log - ActTogether

## Project Genesis
- **Date:** April 2026
- **Objective:** Build a gamified volunteering platform (Laravel/React).
- **Core Engine:** Points Transaction Ledger (Immutable).

## Milestone 1: Infrastructure & Tokenomics
- [x] Database Schema Design (Users, Events, Points, Rewards).
- [x] Implementation of the `points_transactions` migration.
- [x] Implementation of the `event_user` QR-check-in pivot.

## Milestone 2: Full Schema & Eloquent Layer
- **Date:** April 2026

### Migrations (13 tables)
- [x] `users` — role, grade, points_balance, total_hours, kyc_verified, establishment_id
- [x] `establishments` — school/university registry for inter-school leaderboard
- [x] `parteners` — KYC profile, rc_number, rc_document, is_certified
- [x] `events` — quota, duration_hours, points_reward, urgency_multiplier, qr_code_token
- [x] `event_user` — checked_in_at (QR lock), points_earned, partner_rating
- [x] `points_transactions` — immutable ledger, balance_after snapshot, morphable source
- [x] `rewards` — points_cost, stock, min_grade gate, is_premium
- [x] `reward_redemptions` — points_spent, status enum
- [x] `grades` — user academic level per establishment
- [x] `comments` — polymorphic (commentable)
- [x] `feedbacks` — polymorphic (feedbackable), rating 1-5
- [x] `cache`, `jobs` — Laravel defaults (untouched)

### Models (11 models)
- [x] `Establishment` — hasMany users, grades
- [x] `User` — full fillable, casts, role helpers (isAdmin/isPartner/isStudent), all relationships
- [x] `Partner` — $table='parteners', isApproved(), events via partner_id/user_id
- [x] `Grade` — belongsTo user + establishment
- [x] `Event` — effectivePoints(), isFull(), morphMany comments/feedbacks
- [x] `EventUser` (Pivot) — hasCheckedIn() QR guard, $incrementing=true
- [x] `PointsTransaction` — UPDATED_AT=null, boot() blocks update/delete (LogicException)
- [x] `Reward` — isAvailable(), isAccessibleBy(User) grade gate hierarchy
- [x] `RewardRedemption` — status helpers, morphMany pointsTransactions as source
- [x] `Comment` — polymorphic commentable
- [x] `Feedback` — polymorphic feedbackable, isValidRating()

### Known Risk (to address in Service layer)
- `users.points_balance` is mutable — must only be written by `PointsService` inside `DB::transaction()`.
  Never call `$user->update(['points_balance' => X])` directly anywhere in the codebase.

## Milestone 3: Authentication & Routing Layer
- **Date:** April 2026

### Breeze (Blade stack)
- [x] `laravel/breeze` v2.4.1 installed — Blade + Tailwind stack
- [x] `RegisteredUserController` — role field added to validation + User::create (student|partner only, admin never self-registered)
- [x] `register.blade.php` — role selector added (Student / Partner Organisation)
- [x] All 24 auth routes confirmed: login, register, logout, password reset, email verification, profile CRUD

### Schema Fix: Circular FK resolved
- [x] Removed inline FK `establishment_id` from `users` migration (caused errno 150 — establishments table didn't exist yet)
- [x] Created `2026_04_13_160006_add_establishment_foreign_to_users_table.php` — FK added after establishments table exists
- [x] `migrate:fresh` — all 14 tables created successfully

### Known Risk (still open)
- `users.points_balance` is mutable — must only be written by `PointsService` inside `DB::transaction()`.
  Never call `$user->update(['points_balance' => X])` directly anywhere in the codebase.

## Current Focus
- Spatie Laravel-Permission setup for 3 roles: student, partner, admin
- Role-based middleware and dashboard redirects post-login

## Milestone 4: Permissions & Action Classes
- **Date:** April 2026

### Spatie Laravel-Permission (permissions only — no roles tables)
- [x] `spatie/laravel-permission` v7.3.0 configured
- [x] Spatie migration stripped to `permissions` + `model_has_permissions` only — roles/model_has_roles/role_has_permissions tables removed
- [x] `User` model — `HasPermissions` trait added (no HasRoles)
- [x] `PermissionSeeder` — 20 granular permissions seeded grouped by role:
  - Student: event.browse/register/checkin, reward.browse/redeem, comment.create, feedback.create, certificate.download
  - Partner: event.browse/create/update/delete/generate-qr, checkin.validate, student.rate
  - Admin: partner.kyc-approve/reject, event.approve/reject, user.manage, analytics.view
- [x] `migrate:fresh --seed` — 15 tables + 20 permissions OK

### Action Classes (SOLID — one action, one responsibility)
- [x] `App\Actions\Auth\RegisterUserAction` — User::create + givePermissionTo in DB::transaction
- [x] `App\Actions\Points\CreditPointsAction` — ONLY place allowed to write points_transactions + increment points_balance
- [x] `App\Actions\Event\CheckInStudentAction` — QR token validation → hasCheckedIn() guard → updateExistingPivot → CreditPointsAction → increment total_hours
- [x] `RegisteredUserController` wired to `RegisterUserAction`

### Business Controllers (thin — delegate to Actions)
- [x] `EventController` — CRUD, auto-approve for certified partners, image upload
- [x] `EventRegistrationController` — student register/unregister with quota + duplicate guards
- [x] `CheckInController` — QR entry point, delegates to CheckInStudentAction
- [x] `Admin\KycController` — approve/reject partners, grants event.approve permission on approval
- [x] 37 routes registered and verified

### Known Risk (still open — closes when Policies are implemented)
- `authorize()` calls in controllers use `can()` permission checks but Laravel Policies are not yet defined.
  EventController uses `$this->authorize('create', Event::class)` which requires an EventPolicy.
- `users.points_balance` mutable risk — mitigated by CreditPointsAction but no DB-level constraint yet.

## Current Focus
- `RedeemRewardAction` — burn points on marketplace redemption
- Laravel Policies for Event, Partner, Admin authorization
- Role-based dashboard redirect post-login

## Milestone 5: RedeemRewardAction & Models Restoration
- **Date:** April 2026

### Models Restored (10 models wiped by Breeze, recreated)
- [x] `Event` — effectivePoints(), isFull(), morphMany comments/feedbacks
- [x] `EventUser` (Pivot) — hasCheckedIn() QR guard, $incrementing=true
- [x] `Partner` — $table='parteners', isApproved()
- [x] `Grade` — belongsTo user + establishment
- [x] `Establishment` — hasMany users, grades
- [x] `Reward` — isAvailable(), isAccessibleBy(User) grade gate
- [x] `RewardRedemption` — status helpers, morphMany pointsTransactions
- [x] `PointsTransaction` — UPDATED_AT=null, boot() immutability guard
- [x] `Comment` — polymorphic commentable
- [x] `Feedback` — polymorphic feedbackable, isValidRating()

### Action Classes
- [x] `App\Actions\Reward\RedeemRewardAction` — 4 sequential guards:
  1. `isAvailable()` — stock + expiration + active check
  2. `isAccessibleBy()` — grade gate (novice/pilier/ambassadeur)
  3. `points_balance >= points_cost` — balance check
  4. `CreditPointsAction(amount: -points_cost, type: 'burned')` — points burned atomically
  5. `stock decrement` if limited
  6. `RewardRedemption::create()` — redemption record created

### RewardController
- [x] `GET rewards` — marketplace browse with DB-level availability filter
- [x] `POST rewards/{reward}/redeem` — delegates to RedeemRewardAction
- [x] 39 routes registered and verified

### Known Risks (still open)
- `authorize()` in EventController requires EventPolicy — not yet defined
- `users.points_balance` mutable — mitigated by CreditPointsAction, no DB-level constraint

## Current Focus
- Laravel Policies (Event, Partner, Admin)
- Role-based dashboard redirect post-login

## Milestone 6: Policies & Role-Based Dashboards
- **Date:** April 2026

### Laravel Policies (3 policies — authorize() risk closed)
- [x] `EventPolicy` — viewAny, view (approved|owner|admin), create (partner+permission), update (owner+not cancelled), delete (owner), approve (admin)
- [x] `PartnerPolicy` — approve/reject (admin+permission), update (owner only)
- [x] `RewardPolicy` — viewAny (reward.browse), redeem (student+permission+isAvailable+isAccessibleBy)
- [x] All `abort_if(can())` replaced by `$this->authorize()` in EventController, RewardController, KycController
- [x] Known risk "authorize() without Policy" — CLOSED

### Role-Based Dashboard Redirect
- [x] `AuthenticatedSessionController` — match($role) redirects to dashboard.student / dashboard.partner / dashboard.admin
- [x] 3 dashboard routes added: GET /dashboard/student, /dashboard/partner, /dashboard/admin
- [x] `dashboard/student.blade.php` — points balance, certified hours, grade, links to events + marketplace
- [x] `dashboard/partner.blade.php` — KYC warning if unverified, total/active events count, create event link
- [x] `dashboard/admin.blade.php` — students/partners/pending events/KYC queue counters, admin links
- [x] 42 total routes registered and verified

### Known Risks (still open)
- (none — all closed)

## Milestone 7: Points Balance Sync Trigger
- [x] 2 triggers MySQL créés (insert + delete) sur points_transactions
- [x] Synchronisation automatique users.points_balance depuis le ledger
- [x] Risque "mutable points_balance sans contrainte DB" — CLOSED

## Milestone 8: QR Code Generation
- [x] Route GET events/{event}/qr ajoutée
- [x] EventController@qr() générate SVG si token absent
- [x] EventPolicy@generateQr() ajouté (partner + permission)
- [x] GenerateQrAction utilisé pour génération

## Milestone 9: Blade Views for Events
- [x] Controller base ajouté (AuthorizesRequests trait)
- [x] events/index.blade.php — liste paginée avec cards
- [x] events/show.blade.php — détail + QR check-in partner + register student
- [x] events/create.blade.php — formulaire création (partner)
- [x] events/edit.blade.php — formulaire édition (partner owner)
- [x] Risque "No Blade views for events" — CLOSED

## Milestone 10: Grade Upgrade Logic
- [x] UpgradeGradeAction créé (hierarchy: novice → pilier → ambassadeur)
- [x] Thresholds: novice=0, pilier=50h, ambassadeur=150h
- [x] Intégré dans CheckInStudentAction après increment total_hours
- [x] Méthodes utilitaires: getThreshold(), getNextThreshold()

## Milestone 11: Rewards Marketplace Views
- [x] rewards/index.blade.php — grid avec points balance, grade, access check
- [x] Conditions: isAccessibleBy, points_balance check, stock warning
- [x] Risque "No Blade views for rewards" — CLOSED

## Milestone 12: Tests & Fixtures
- [x] phpunit.xml configuré pour MySQL (pfaproject_test)
- [x] UserFactory: role, grade, points_balance, total_hours + states (student/partner/admin)
- [x] AuthenticationTest: redirect to role-based dashboard
- [x] Tests: 24 passed, 1 skipped (registration redirect)

## Current Focus
- UI: Landing Page + Navigation

## Milestone 13: Landing Page UI
- [x] welcome.blade.php redesign avec palette: #FFDCDC, #FFF2EB, #FFE8CD, #FFD6BA, #D4A574
- [x] Hero section: tagline + CTA buttons
- [x] Features: Check-in QR, Points Garantis, Marketplace
- [x] Stats section fake
- [x] Footer avec liens

## Milestone 14: Navigation UI
- [x] navigation.blade.php redesigné avec palette ActTogether
- [x] Menu dynamique par rôle (student/partner/admin)
- [x] Blade::role() directives ajoutées dans AppServiceProvider
- [x] Points balance affiché pour étudiants
- [x] Grade affiché dans dropdown utilisateur
