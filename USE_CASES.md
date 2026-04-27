# ActTogether v2.0 — Use Case Catalog

**Version:** 2.1 (Comprehensive)  
**Last Updated:** 2026-04-23  
**Status:** Production Ready (Core Features)

---

## 📋 Table of Contents

1. [Actors & Roles](#actors--roles)
2. [Use Cases Overview](#use-cases-overview)
3. [Detailed Use Cases](#detailed-use-cases)
4. [Permissions Matrix](#permissions-matrix)
5. [Feature Area Summary](#feature-area-summary)

---

## Actors & Roles

| Actor | Description |
|-------|-------------|
| **Guest** | Unauthenticated visitor to the platform |
| **Student** | End-user who volunteers for events, earns points, redeems rewards |
| **Partner** | Organization that creates/manages volunteering missions, validates check-ins |
| **Admin** | Platform operator who approves partners, events, moderates disputes, views analytics |
| **System** | Automated processes: email sending, points sync, grade upgrade, broadcasting |

---

## Use Cases Overview

### Summary by Actor

| Actor | Count | Primary Responsibilities |
|-------|------|-------------------------|
| Guest | 9 | Registration, login, password reset, email verification |
| Authenticated User | 5 | Profile management, dashboard access |
| Student | 40 | Browse/register events, check-in/out, wallet, rewards, leaderboard, messaging |
| Partner | 25 | Create/edit events, check-in validation, reward creation, messaging, KYC |
| Admin | 10 | Moderation (events, partners, users), analytics, dispute resolution |
| System | 14 | Automated notifications, point calculations, grade upgrades, broadcasts |

**Total Use Cases:** 83

---

## Detailed Use Cases

### A. Authentication & Account Management

#### UC-A01: View Welcome Page
- **Actor:** Guest
- **Goal:** See landing page
- **Description:** View public welcome page introducing the platform

#### UC-A02: Register as Student
- **Actor:** Guest
- **Goal:** Create student account
- **Preconditions:** Email not taken; user not logged in
- **Main Flow:** Registers with role='student', assigned student permissions

#### UC-A03: Register as Partner
- **Actor:** Guest
- **Goal:** Create partner account
- **Preconditions:** Email not taken; user not logged in
- **Main Flow:** Registers with role='partner', KYC status = pending, limited permissions

#### UC-A04: Login to System
- **Actor:** Guest
- **Goal:** Authenticate
- **Main Flow:** Email/password validation, redirect to role-based dashboard

#### UC-A05: Logout
- **Actor:** Authenticated User
- **Goal:** End session
- **Main Flow:** Session invalidation, redirect to login

#### UC-A06: Request Password Reset
- **Actor:** Guest
- **Goal:** Initiate password recovery
- **Main Flow:** Email with reset link sent

#### UC-A07: Reset Password
- **Actor:** Guest
- **Goal:** Set new password
- **Main Flow:** Token validation, password update

#### UC-A08: Verify Email Address
- **Actor:** Guest
- **Goal:** Confirm email ownership
- **Main Flow:** Click verification link from email

#### UC-A09: Resend Verification Email
- **Actor:** Authenticated User
- **Goal:** Get new verification link
- **Main Flow:** Resend if email not verified

#### UC-A10: Confirm Password
- **Actor:** Authenticated User
- **Goal:** Re-authenticate for sensitive operations
- **Main Flow:** Password confirmation dialog

#### UC-A11: View Dashboard
- **Actor:** Authenticated User
- **Goal:** Access role-specific dashboard
- **Variants:**
  - Student: points, hours, grade progress, upcoming events
  - Partner: KYC status, active events count, quick actions
  - Admin: system-wide stats (users, events, KYC queue)

#### UC-A12: Edit Profile Information
- **Actor:** Authenticated User
- **Goal:** Update personal data
- **Fields:** Name, email, city, phone, avatar, interests
- **Constraints:** Cannot change role or points balance

#### UC-A13: Update Password
- **Actor:** Authenticated User
- **Goal:** Change account password
- **Main Flow:** Current password verification, new password entry

#### UC-A14: Delete Account
- **Actor:** Authenticated User
- **Goal:** Permanently delete account
- **Constraints:** Soft delete forbidden; actual deletion blocked for audit

---

### B. Event Management — Student

#### UC-S01: Browse Approved Events
- **Actor:** Student
- **Goal:** Discover volunteering missions
- **Filter:** Only `status='approved'` events, paginated

#### UC-S02: Search Events
- **Actor:** Student
- **Goal:** Find events by keyword
- **Fields:** Title, description

#### UC-S03: Filter Events by City
- **Actor:** Student
- **Goal:** Find local events
- **Field:** City

#### UC-S04: Filter Events by Category
- **Actor:** Student
- **Goal:** Find events matching interests
- **Field:** Category (multi-select)

#### UC-S05: View Event Details
- **Actor:** Student
- **Goal:** See full event information
- **Data:** Description, address, quota, duration, points, participants count

#### UC-S06: Like Event
- **Actor:** Student
- **Goal:** Bookmark event
- **Action:** Create entry in `likes` table

#### UC-S07: Unlike Event
- **Actor:** Student
- **Goal:** Remove bookmark
- **Action:** Delete entry from `likes` table

#### UC-S08: View Liked Events
- **Actor:** Student
- **Goal:** See all bookmarked events
- **Page:** "/my-likes"

#### UC-S09: Add Event to Wishlist
- **Actor:** Student
- **Goal:** Save for later consideration
- **Note:** Same as Like (implementation may vary)

#### UC-S10: Remove from Wishlist
- **Actor:** Student
- **Goal:** Remove saved event

#### UC-S11: Register for Event
- **Actor:** Student
- **Goal:** Sign up to volunteer
- **Preconditions:** Event approved; not full; not already registered
- **Effect:** Creates pivot record, generates QR token

#### UC-S12: Cancel Registration
- **Actor:** Student
- **Goal:** Withdraw from event
- **Effect:** Pivot record deleted; no points affected

#### UC-S13: View My Registrations
- **Actor:** Student
- **Goal:** See all registered events
- **Data:** Status (registered/checked_in/checked_out/cancelled), QR code

#### UC-S14: View Registration QR Code
- **Actor:** Student
- **Goal:** Display QR for check-in
- **Use:** Self check-in or showing to partner

#### UC-S15: Send Registration Email Resend
- **Actor:** System (triggered by Student action)
- **Goal:** Resend QR code email
- **Trigger:** Student clicks "Resend Email"

---

### C. Event Management — Partner

#### UC-P01: Create Event
- **Actor:** Partner
- **Goal:** Publish new volunteering mission
- **Preconditions:** `can('event.create')`
- **Status:** Default `'pending'`, auto-approve if certified partner
- **Fields:** Title, description, category, city, address, dates, quota, duration, points_reward, urgency_multiplier, image

#### UC-P02: Edit Own Event
- **Actor:** Partner (owner)
- **Goal:** Modify event details
- **Preconditions:** User owns event; event not cancelled/rejected

#### UC-P03: Cancel Event
- **Actor:** Partner (owner)
- **Goal:** Soft-delete event
- **Action:** Set `status='cancelled'`
- **Constraint:** Hard delete forbidden

#### UC-P04: View My Events
- **Actor:** Partner
- **Goal:** See all events created by self
- **Scope:** All statuses

#### UC-P05: View All Events
- **Actor:** Partner
- **Goal:** Browse platform events + own
- **Filter:** Can filter to show only own events

#### UC-P06: View Event Analytics
- **Actor:** Partner
- **Goal:** See event statistics
- **Metrics:** Participant count, engagement stats

#### UC-P07: View Active Events for Check-in
- **Actor:** Partner
- **Goal:** See currently running events needing attendance
- **Filter:** Events in progress with registered students

#### UC-P08: Generate QR Code (Event Display)
- **Actor:** Partner
- **Goal:** Obtain event QR to display at venue
- **Note:** This is a shared QR for the partner's scanner device

---

### D. Check-in & Check-out Operations

#### UC-C01: Student Self Check-in
- **Actor:** Student
- **Goal:** Record presence at event start
- **Method:** Scan own registration QR or tap button
- **Effect:** Sets `checked_in_at = now()`

#### UC-C02: Partner Validate Student Check-in
- **Actor:** Partner
- **Goal:** Confirm student attendance
- **Method:** Scan student's personal QR token
- **Validation:** Token belongs to event; student registered; not already checked in
- **Effect:** Sets `checked_in_at = now()`; sends notification

#### UC-C03: Partner Validate Student Check-out
- **Actor:** Partner
- **Goal:** Confirm student departure
- **Method:** Scan student's QR again
- **Validation:** Student is checked in
- **Effect:** Sets `checked_out_at = now()`; triggers point calculation

#### UC-C04: Student Self Check-out
- **Actor:** Student
- **Goal:** Mark own departure
- **Method:** Button in app
- **Trigger:** Also triggers point calculation

#### UC-C05: Admin Override Check-in
- **Actor:** Admin
- **Goal:** Manually validate attendance
- **Authority:** Bypasses ownership; any event

#### UC-C06: Admin Override Check-out
- **Actor:** Admin
- **Goal:** Manually trigger point calculation
- **Effect:** Same as partner check-out

#### UC-C07: View Check-in Terminal
- **Actor:** Partner
- **Goal:** Dedicated QR scanning interface
- **UI:** Camera view, live feed of scan results

#### UC-C08: View Live Check-in Feed
- **Actor:** Partner
- **Goal:** Real-time feedback on scans
- **Display:** Student name, success/failure, timestamp

#### UC-C09: View Check-in Status (Student)
- **Actor:** Student
- **Goal:** See current check-in/out state
- **Page:** Event details page shows status badge

#### UC-C10: Calculate Pro-rated Hours
- **Actor:** System
- **Goal:** Compute actual hours
- **Rule:** Min(actual duration, planned hours); minimum 30 minutes

#### UC-C11: Calculate & Credit Points
- **Actor:** System
- **Goal:** Award points for participation
- **Formula:** `points = proRatedHours × event.points_reward × urgency_multiplier`
- **Actions:** Create PointsTransaction (type='earned'), update `users.points_balance`, increment `users.total_hours`

---

### E. Points & Wallet

#### UC-W01: View Wallet Balance
- **Actor:** Student
- **Goal:** See current points
- **Location:** Dashboard header, wallet page

#### UC-W02: View Wallet Summary
- **Actor:** Student
- **Goal:** See earned vs spent breakdown
- **Display:** Total earned, total burned, current balance

#### UC-W03: Filter Transaction History
- **Actor:** Student
- **Goal:** Filter by type
- **Options:** earned / spent / burned

#### UC-W04: View Transaction Details
- **Actor:** Student
- **Goal:** See context of each points change
- **Data:** Source event/reward/refund, description, timestamp

#### UC-W05: View Points Ledger
- **Actor:** Student
- **Goal:** See paginated transaction list
- **Page:** "/wallet"

#### UC-W06: Points Balance Sync (System)
- **Actor:** System
- **Goal:** Keep balance current
- **Method:** Database trigger sums ledger transactions

---

### F. Rewards Marketplace

#### UC-R01: Browse Rewards
- **Actor:** Student
- **Goal:** Discover redeemable items
- **Display:** Grid with cards showing image, title, points cost, stock, grade lock, premium badge
- **Filter:** Implicit by grade (locked items hidden or overlaid)

#### UC-R02: View Reward Details
- **Actor:** Student
- **Goal:** See full reward info
- **Data:** Description, terms, expiry, stock count, partner info

#### UC-R03: View Premium Rewards
- **Actor:** Student
- **Goal:** Identify special/high-value items
- **Indicator:** Premium badge

#### UC-R04: View Stock Availability
- **Actor:** Student
- **Goal:** Know if reward is in stock
- **Display:** "Only X left" or "Out of stock"

#### UC-R05: Redeem Reward
- **Actor:** Student
- **Goal:** Exchange points for reward
- **Preconditions:**
  - Sufficient balance
  - Meets `min_grade` requirement
  - Stock available (if limited)
- **Actions:** Deduct points, create redemption record, decrement stock

#### UC-R06: View Redemption Status
- **Actor:** Student
- **Goal:** Track pending/approved/redeemed rewards
- **Statuses:** pending → approved/ rejected

#### UC-R07: View Wallet in Marketplace
- **Actor:** Student
- **Goal:** See balance while browsing
- **Display:** Sticky header shows current points and grade

#### UC-R08: Create Reward (Partner)
- **Actor:** Partner
- **Goal:** Add new reward to marketplace
- **Fields:** Title, description, image, points_cost, stock, min_grade, is_premium, expires_at

#### UC-R09: Edit Own Reward
- **Actor:** Partner (owner)
- **Goal:** Modify reward details
- **Scope:** Only own rewards

#### UC-R10: Delete Own Reward
- **Actor:** Partner (owner)
- **Goal:** Remove reward
- **Constraint:** Soft delete if redemptions exist; hard delete not allowed

#### UC-R11: Set Grade Requirement
- **Actor:** Partner
- **Goal:** Specify minimum user grade
- **Options:** novice / pilier / ambassadeur

#### UC-R12: Set Premium Flag
- **Actor:** Partner
- **Goal:** Mark reward as premium
- **Effect:** Premium badge displayed

#### UC-R13: Fulfill Reward (Partner/Admin)
- **Actor:** Partner or Admin
- **Goal:** Mark redemption as shipped/delivered
- **Action:** Set `status='approved'`
- **Note:** Physical fulfillment happens outside system

#### UC-R14: Revoke/Reject Redemption
- **Actor:** Partner or Admin
- **Goal:** Cancel pending redemption and refund points
- **Actions:** Set status='rejected', refund points, restore stock if limited

---

### G. Leaderboard & Rankings

#### UC-L01: View National Leaderboard
- **Actor:** Student
- **Goal:** See top users nationwide
- **Metric:** All-time points
- **Size:** Top 100

#### UC-L02: View City Leaderboard
- **Actor:** Student
- **Goal:** See top users in same city
- **Metric:** All-time points filtered by city

#### UC-L03: View Establishment Leaderboard
- **Actor:** Student
- **Goal:** See top users in same school/establishment
- **Metric:** All-time points filtered by establishment

#### UC-L04: View Weekly Leaderboard
- **Actor:** Student
- **Goal:** See top performers this week
- **Metric:** Points earned this calendar week (Monday-Sunday)

#### UC-L05: See Rank Position
- **Actor:** Student
- **Goal:** Know personal ranking
- **Display:** Rank number shown next to each entry

---

### H. Social & Communication

#### UC-SC01: View User Directory
- **Actor:** Student / Partner
- **Goal:** Search and browse users
- **Exclusions:** Cannot view self; may not view users they're restricted from

#### UC-SC02: Search Users
- **Actor:** Student / Partner
- **Goal:** Find specific users
- **Fields:** Name, email

#### UC-SC03: Send Direct Message
- **Actor:** Student / Partner
- **Goal:** Start private conversation
- **Method:** Create conversation + first message

#### UC-SC04: View Conversations List
- **Actor:** Student / Partner
- **Goal:** See all message threads
- **Data:** Last message preview, unread count

#### UC-SC05: View Conversation
- **Actor:** Student / Partner
- **Goal:** Read message history
- **Display:** Chronological messages with sender avatar

#### UC-SC06: Send Message
- **Actor:** Student / Partner
- **Goal:** Send text in conversation
- **Broadcast:** Real-time via Pusher/Pushe

#### UC-SC07: View Unread Count
- **Actor:** Student / Partner
- **Goal:** See unread message badge
- **Location:** Navigation bar, conversations list

---

### I. Notifications

#### UC-N01: View Notifications List
- **Actor:** Student / Partner / Admin
- **Goal:** See system alerts
- **Limit:** Max 50 most recent

#### UC-N02: Mark Notification as Read
- **Actor:** Student / Partner / Admin
- **Goal:** Dismiss single notification
- **Action:** Click mark-read button

#### UC-N03: Mark All Notifications as Read
- **Actor:** Student / Partner / Admin
- **Goal:** Clear notification badge
- **Action:** Bulk operation

#### UC-N04: Get Unread Count (AJAX)
- **Actor:** Student / Partner / Admin
- **Goal:** Fetch count for badge
- **Method:** API endpoint (AJAX)

#### UC-N05: Send Event Created Email (System)
- **Actor:** System
- **Goal:** Notify event creator and admins
- **Triggers:** On `EventCreated` event

#### UC-N06: Send Check-in Notification (System)
- **Actor:** System
- **Goal:** Confirm check-in to student
- **Triggers:** On successful check-in

#### UC-N07: Send Check-out Notification (System)
- **Actor:** System
- **Goal:** Inform student of points earned
- **Content:** Points awarded, total hours

---

### J. Administration & Moderation

#### UC-AD01: View Admin Dashboard
- **Actor:** Admin
- **Goal:** System overview
- **Stats:** Total users (by role), pending events count, pending KYC, points metrics

#### UC-AD02: View KYC Verification Queue
- **Actor:** Admin
- **Goal:** Review partner submissions
- **Filter:** Partners with `kyc_status='pending'`

#### UC-AD03: Approve Partner KYC
- **Actor:** Admin
- **Goal:** Verify partner organization
- **Actions:** Set `is_certified_partner=true`, `kyc_verified=true`, grant `event.approve` permission
- **Effect:** Partner can create events (auto-approve applies)

#### UC-AD04: Reject Partner KYC
- **Actor:** Admin
- **Goal:** Deny partner verification
- **Actions:** Set `kyc_status='rejected'`

#### UC-AD05: View All Users
- **Actor:** Admin
- **Goal:** User management
- **Capabilities:** Filter by role, search, view details

#### UC-AD06: View All Events
- **Actor:** Admin
- **Goal:** Unrestricted event visibility
- **Scope:** All events regardless of status

#### UC-AD07: Approve Event
- **Actor:** Admin
- **Goal:** Publish pending event
- **Action:** Change `status='pending'` → `'approved'`

#### UC-AD08: Reject Event
- **Actor:** Admin
- **Goal:** Reject event submission
- **Action:** Change `status='pending'` → `'rejected'`

#### UC-AD09: View Pending Events Queue
- **Actor:** Admin
- **Goal:** See events awaiting moderation
- **Filter:** `status='pending'`

#### UC-AD10: Resolve Dispute
- **Actor:** Admin
- **Goal:** Handle student no-shows or partner misconduct
- **Triggers:** Low-rating feedback (≤ 2 stars)
- **Actions:** Adjust points, issue warnings, record resolution

---

### K. System Automation

#### UC-SYS01: Upgrade User Grade
- **Actor:** System
- **Goal:** Auto-promote based on hours
- **Thresholds:**
  - 50 hours: novice → pilier
  - 150 hours: pilier → ambassadeur

#### UC-SYS02: Broadcast EventCreated
- **Actor:** System
- **Goal:** Real-time notification
- **Recipients:** Event creator, all admins
- **Channel:** Private event-{id} channel

#### UC-SYS03: Broadcast MessageSent
- **Actor:** System
- **Goal:** Real-time chat delivery
- **Recipients:** Conversation participants
- **Channel:** Private conversation-{id} channel

#### UC-SYS04: Prevent Hard Delete of Users
- **Actor:** System
- **Goal:** Audit trail protection
- **Method:** Model observer blocks `forceDelete()`

#### UC-SYS05: Prevent Hard Delete of Events
- **Actor:** System
- **Goal:** Preserve event history
- **Method:** Cancel only; hard delete denied

#### UC-SYS06: Prevent Direct Balance Modification
- **Actor:** System
- **Goal:** Ensure ledger integrity
- **Method:** Model observer blocks direct `points_balance` updates

#### UC-SYS07: Sync Points Balance (Database Trigger)
- **Actor:** System
- **Goal:** Keep balance in sync with ledger
- **Method:** PostgreSQL trigger on `points_transactions`

#### UC-SYS08: Send Email Event Created Notification
- **Actor:** System (Mailable)
- **Goal:** Email event creator + admins
- **Trigger:** Event created (status pending)

#### UC-SYS09: Send Email Check-in Confirmation
- **Actor:** System (Mailable)
- **Goal:** Email student confirming check-in
- **Trigger:** Successful check-in

#### UC-SYS10: Send Email Check-out Completion
- **Actor:** System (Mailable)
- **Goal:** Email student with points earned & total hours
- **Trigger:** Successful check-out

---

## Permissions Matrix

| Permission Code | Description | Student | Partner | Admin |
|-----------------|-------------|---------|---------|-------|
| `event.browse` | View event listings | ✓ | ✓ | ✓ |
| `event.register` | Register for events | ✓ | ✗ | ✗ |
| `event.checkin` | Access check-in features | ✓ | ✓ | ✓ |
| `event.create` | Create new events | ✗ | ✓ | ✗ |
| `event.update` | Edit own events | ✗ | ✓ | ✗ |
| `event.delete` | Cancel own events | ✗ | ✓ | ✗ |
| `event.approve` | Approve/reject events | ✗ | ✗ | ✓ |
| `checkin.validate` | Scan QR & validate | ✗ | ✓ | ✓ |
| `reward.browse` | View rewards | ✓ | ✓ | ✓ |
| `reward.redeem` | Redeem rewards | ✓ | ✗ | ✗ |
| `reward.create` | Create rewards | ✗ | ✓ | ✗ |
| `reward.update` | Edit own rewards | ✗ | ✓ | ✗ |
| `reward.delete` | Delete own rewards | ✗ | ✓ | ✗ |
| `comment.create` | Post comments | ✓ | ✓ | ✓ |
| `feedback.create` | Submit feedback | ✓ | ✓ | ✓ |
| `certificate.download` | Download certificate | ✓ | ✗ | ✗ |
| `student.rate` | Rate student performance | ✗ | ✓ | ✗ |
| `partner.kyc-approve` | Approve partner KYC | ✗ | ✗ | ✓ |
| `partner.kyc-reject` | Reject partner KYC | ✗ | ✗ | ✓ |
| `user.manage` | Manage users | ✗ | ✗ | ✓ |
| `analytics.view` | View analytics | ✗ | ✗ | ✓ |

> **Note:** Permissions `comment.create`, `feedback.create`, `certificate.download`, `student.rate` exist but UI not fully implemented.

---

## Feature Area Summary

| Feature Area | Total Use Cases | Primary Actors |
|--------------|-----------------|----------------|
| Authentication & Profile | 14 | Guest, Authenticated |
| Event Management | 20 | Student, Partner, Admin |
| Check-in/Check-out | 11 | Student, Partner, Admin, System |
| Points & Wallet | 6 | Student, System |
| Rewards Marketplace | 14 | Student, Partner, Admin |
| Leaderboard | 5 | Student |
| Social & Communication | 7 | Student, Partner |
| Notifications | 7 | All, System |
| Admin & Moderation | 10 | Admin |
| System Automation | 10 | System |
| **GRAND TOTAL** | **104** | — |

---

## Notes for UML Diagramming

### Relationships to Highlight

1. **<<extend>>** relationships:
   - UC-A09 (Resend Verification) extends UC-A02/UC-A03 (Registration)
   - UC-S15 (Resend QR Email) extends UC-S13 (View Registrations)

2. **<<include>>** relationships:
   - UC-C11 (Calculate Points) includes UC-C10 (Calculate Hours)
   - UC-R05 (Redeem) includes UC-W01 (Balance Check) and UC-Validate

3. **Generalization (Inheritance):**
   - Student, Partner, Admin ← Authenticated User
   - Administrator functions extend Admin's capabilities

4. **System as boundary/actor:**
   - Draw System outside ellipse, <<system>> stereotype for automated processes

5. **Actor-to-Use-Case associations:**
   - One-to-many (Student → 40 use cases)
   - Partner → 25 use cases (mix of content creation and validation)
   - Admin → 10 use cases (all moderation/administration)

### Swimlane Recommendations

For Activity Diagram or Sequence Diagram, consider swimlanes:
- **Student Lane:** 40 actions
- **Partner Lane:** 25 actions
- **Admin Lane:** 10 actions
- **System Lane:** 14 actions

### Event Lifecycle Flow

The core event flow crosses multiple actors:
1. **Partner** creates event (UC-P01)
2. **Admin** approves event (UC-AD07)
3. **Student** registers (UC-S11)
4. **Partner** validates check-in (UC-C02)
5. **Partner** validates check-out → **System** credits points (UC-C03 + UC-C11)
6. **Student** views wallet (UC-W01) and redeems (UC-R05)

### Critical Path Use Cases (High Frequency / Criticality)

Rank by criticality from summary table:
- UC-A04 (Login) — Critical, daily
- UC-AD03 (Approve KYC) — Critical, daily
- UC-AD07 (Approve Event) — Critical, daily
- UC-S11 (Register Event) — Critical, frequent
- UC-C02/UC-C03 (Check-in/out) — Critical, per event
- UC-R05 (Redeem Reward) — High, monthly

---

## Legend for Categories

| Category | What It Covers |
|----------|----------------|
| **Authentication** | Login, registration, verification, password |
| **Profile** | Account data management, deletion |
| **Event** | Full lifecycle: CRUD, browse, register, cancel |
| **Check-in/Check-out** | Attendance tracking, point calculation |
| **Points** | Wallet operations, transaction history |
| **Rewards** | Marketplace creation & redemption |
| **Leaderboard** | Points/hour-based rankings |
| **Social** | User discovery, commenting, feedback |
| **Messaging** | Private conversations |
| **Notification** | System alerts, emails |
| **Admin** | Moderation, KYC, user management |
| **System** | Background jobs, triggers, broadcasts |

---

*Generated by automated codebase analysis. This document is the single source of truth for functional use cases in the current codebase.*
